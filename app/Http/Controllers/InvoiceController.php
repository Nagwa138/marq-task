<?php

namespace App\Http\Controllers;

use App\Architecture\Services\Interfaces\ICompanyService;
use App\Architecture\Services\Interfaces\ICustomerService;
use App\Architecture\Services\Interfaces\IInvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private IInvoiceService $invoiceService,
        private ICompanyService $companyService,
        private ICustomerService $customerService
    ) {
        $this->middleware('can:view,invoice')->only('show', 'edit');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'customer_id', 'from_date', 'to_date']);
        $invoices = $this->invoiceService->getAllInvoices($filters);
        $stats = $this->invoiceService->getInvoiceStatistics();

        return view('invoices.index', compact('invoices', 'stats'));
    }

    public function create(Request $request): View
    {
        $companies = $this->companyService->getAllCompanies();
        $customers = $this->customerService->getAllCustomers();

        $preSelectedCustomer = $request->query('customer_id');

        return view('invoices.create', compact('companies', 'customers', 'preSelectedCustomer'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = $this->invoiceService->createInvoice($request->validated());

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show(int $id): View
    {
        $invoice = $this->invoiceService->getInvoice($id);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(int $id): View
    {
        $invoice = $this->invoiceService->getInvoice($id);
        $companies = $this->companyService->getAllCompanies();
        $customers = $this->customerService->getAllCustomers();

        return view('invoices.edit', compact('invoice', 'companies', 'customers'));
    }

    public function update(UpdateInvoiceRequest $request, int $id): RedirectResponse
    {
        $invoice = $this->invoiceService->updateInvoice($id, $request->validated());

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->invoiceService->deleteInvoice($id);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    public function send(int $id): RedirectResponse
    {
        $invoice = $this->invoiceService->sendInvoice($id);

        return back()->with('success', 'تم إرسال الفاتورة بنجاح');
    }

    public function markAsPaid(int $id): RedirectResponse
    {
        $invoice = $this->invoiceService->markAsPaid($id);

        return back()->with('success', 'تم تعيين الفاتورة كمدفوعة');
    }

    public function duplicate(int $id): RedirectResponse
    {
        $newInvoice = $this->invoiceService->duplicateInvoice($id);

        return redirect()
            ->route('invoices.show', $newInvoice)
            ->with('success', 'تم نسخ الفاتورة بنجاح');
    }

    public function print(int $id): View
    {
        $invoice = $this->invoiceService->getInvoice($id);

        return view('invoices.print', compact('invoice'));
    }

    public function pdf(int $id)
    {
        $invoice = $this->invoiceService->getInvoice($id);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function export(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'status']);
        $invoices = $this->invoiceService->getInvoicesForExport($filters);

        // Return CSV or Excel file
        return $this->invoiceService->exportToCsv($invoices);
    }
}
