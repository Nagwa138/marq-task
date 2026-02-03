<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * عرض قائمة المدفوعات
     */
    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'customer'])
            ->latest();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('customer', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // الفلترة حسب التاريخ
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        // الفلترة حسب طريقة الدفع
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->paginate(20);

        // إحصائيات
        $stats = [
            'total_payments' => Payment::count(),
            'total_amount' => Payment::sum('amount'),
            'today_payments' => Payment::whereDate('payment_date', today())->sum('amount'),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    /**
     * عرض نموذج إنشاء دفعة جديدة
     */
    public function create(Request $request)
    {
        $invoices = Invoice::where('status', '!=', 'paid')
            ->with('customer')
            ->get();

        $customers = Customer::all();

        // إذا كان هناك invoice_id في الـ request
        $invoice = null;
        if ($request->filled('invoice_id')) {
            $invoice = Invoice::findOrFail($request->invoice_id);
        }

        return view('payments.create', compact('invoices', 'customers', 'invoice'));
    }

    /**
     * حفظ دفعة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'bank_name' => 'required_if:payment_method,bank_transfer,check',
            'check_number' => 'required_if:payment_method,check',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        // التحقق من أن المبلغ لا يتجاوز المتبقي في الفاتورة
        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $remaining = $invoice->remaining_amount;

        if ($validated['amount'] > $remaining) {
            return back()->withInput()
                ->with('error', 'المبلغ يتجاوز المتبقي في الفاتورة. المتبقي: ' . number_format($remaining, 2));
        }

        DB::beginTransaction();

        try {
            // إنشاء الدفعة
            $payment = Payment::create([
                'tenant_id' => auth()->user()->tenant_id,
                'invoice_id' => $validated['invoice_id'],
                'customer_id' => $validated['customer_id'],
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference' => $validated['reference'] ?? 'PAY-' . strtoupper(uniqid()),
                'notes' => $validated['notes'],
                'bank_name' => $validated['bank_name'] ?? null,
                'check_number' => $validated['check_number'] ?? null,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'received_by' => auth()->id(),
                'status' => 'completed',
            ]);

            // تحديث حالة الفاتورة إذا تم دفعها بالكامل
            $invoice->refresh();
            if ($invoice->isFullyPaid()) {
                $invoice->markAsPaid();
            } elseif ($invoice->status === 'draft') {
                $invoice->status = 'sent';
                $invoice->save();
            }

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'تم تسجيل الدفعة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تسجيل الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل دفعة
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice', 'customer', 'receiver']);

        return view('payments.show', compact('payment'));
    }

    /**
     * عرض نموذج تعديل دفعة
     */
    public function edit(Payment $payment)
    {
        $invoices = Invoice::where('status', '!=', 'paid')->get();
        $customers = Customer::all();

        return view('payments.edit', compact('payment', 'invoices', 'customers'));
    }

    /**
     * تحديث دفعة
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:completed,pending,failed,cancelled',
        ]);

        DB::beginTransaction();

        try {
            // حفظ المبلغ القديم
            $oldAmount = $payment->amount;

            // تحديث الدفعة
            $payment->update($validated);

            // إذا تغير المبلغ، تحديث رصيد العميل
            if ($oldAmount != $payment->amount) {
                $payment->customer?->updateBalance();
            }

            // تحديث حالة الفاتورة
            $invoice = $payment->invoice;
            if ($invoice) {
                if ($invoice->isFullyPaid()) {
                    $invoice->markAsPaid();
                }
                $invoice->customer?->updateBalance();
            }

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', 'تم تحديث الدفعة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * حذف دفعة
     */
    public function destroy(Payment $payment)
    {
        DB::beginTransaction();

        try {
            $customer = $payment->customer;
            $invoice = $payment->invoice;

            $payment->delete();

            // تحديث رصيد العميل
            if ($customer) {
                $customer->updateBalance();
            }

            // تحديث حالة الفاتورة
            if ($invoice && !$invoice->isFullyPaid()) {
                $invoice->status = 'sent';
                $invoice->save();
            }

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'تم حذف الدفعة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * تسجيل دفعة سريعة (بدون فاتورة)
     */
    public function createQuickPayment()
    {
        $customers = Customer::all();

        return view('payments.quick-create', compact('customers'));
    }

    /**
     * حفظ دفعة سريعة
     */
    public function storeQuickPayment(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check,online',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::create([
            'tenant_id' => auth()->user()->tenant_id,
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['reference'] ?? 'QP-' . strtoupper(uniqid()),
            'notes' => $validated['notes'],
            'received_by' => auth()->id(),
            'status' => 'completed',
        ]);

        // تحديث رصيد العميل
        $payment->customer->updateBalance();

        return redirect()->route('payments.show', $payment)
            ->with('success', 'تم تسجيل الدفعة السريعة بنجاح.');
    }

    /**
     * تصدير المدفوعات إلى Excel
     */
    public function export(Request $request)
    {
        $payments = Payment::with(['customer', 'invoice'])
            ->when($request->filled('from_date'), function($q) use ($request) {
                $q->whereDate('payment_date', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function($q) use ($request) {
                $q->whereDate('payment_date', '<=', $request->to_date);
            })
            ->get();

        // هنا يمكنك استخدام Laravel Excel أو إرجاع CSV
        // هذا مثال مبسط لإرجاع CSV

        $filename = 'payments_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'المرجع',
                'العميل',
                'رقم الفاتورة',
                'المبلغ',
                'تاريخ الدفع',
                'طريقة الدفع',
                'الحالة',
                'ملاحظات'
            ]);

            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->reference,
                    $payment->customer->name,
                    $payment->invoice ? $payment->invoice->invoice_number : 'N/A',
                    number_format($payment->amount, 2),
                    $payment->payment_date->format('Y-m-d'),
                    $payment->payment_method_name,
                    $payment->status,
                    $payment->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * API: الحصول على المدفوعات لفاتورة معينة
     */
    public function getInvoicePayments(Invoice $invoice)
    {
        $payments = $invoice->payments()
            ->with(['customer'])
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'reference' => $payment->reference,
                    'amount' => number_format($payment->amount, 2),
                    'payment_date' => $payment->payment_date->format('Y-m-d'),
                    'payment_method' => $payment->payment_method_name,
                    'notes' => $payment->notes,
                ];
            });

        return response()->json([
            'success' => true,
            'payments' => $payments,
            'total_paid' => number_format($invoice->paid_amount, 2),
            'remaining' => number_format($invoice->remaining_amount, 2),
        ]);
    }
}
