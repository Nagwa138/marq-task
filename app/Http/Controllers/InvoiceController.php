<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        return view('invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // حساب المجموع
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $tax = $subtotal * 0.15; // ضريبة 15%
        $total = $subtotal + $tax;

        // إنشاء الفاتورة
        $invoice = Invoice::create([
            'tenant_id' => auth()->user()->tenant_id,
            'company_id' => auth()->user()->company_id,
            'customer_id' => $request->customer_id,
            'invoice_number' => 'INV-' . time(),
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'draft',
            'notes' => $request->notes,
        ]);

        // إضافة البنود
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice);
    }
}
