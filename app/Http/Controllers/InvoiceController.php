<?php

namespace App\Http\Controllers;

use App\Architecture\Services\Classes\InvoiceService;
use App\Architecture\Services\Interfaces\ICompanyService;
use App\Architecture\Services\Interfaces\IInvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly IInvoiceService $invoiceService,
        private readonly ICompanyService $companyService,
    ) {}

    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        // Build filters from request
        $filters = $this->buildFilters($request);

        // Get all data needed for the index page
        $data = $this->invoiceService->getIndexData($filters);

        return view('invoices.index', [
            'invoices' => $data['invoices'],
            'stats' => $data['stats'],
            'filters' => $request->only(['search', 'status', 'date', 'sort']),
        ]);
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        // Get data needed for create form
        $data = $this->invoiceService->getCreateData();

        $companies = $this->companyService->all();

        $activeCompanyId = session('activeCompanyId') ?? null;
        return view('invoices.create', compact('data', 'companies', 'activeCompanyId'));
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'in:draft,sent,paid,overdue',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $invoice = $this->invoiceService->create($validated);

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * Build filters array from request.
     */
    private function buildFilters(Request $request): array
    {
        $filters = [];

        // Search filter
        if ($request->has('search') && $request->search) {
            $filters['search'] = $request->search;
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $filters['status'] = $request->status;
        }

        // Company filter (from query parameter)
        if ($request->has('company') && $request->company) {
            $filters['company_id'] = $request->company;
        }

        // Customer filter
        if ($request->has('customer') && $request->customer) {
            $filters['customer_id'] = $request->customer;
        }

        // Date range filter
        if ($request->has('date') && $request->date) {
            $filters['date'] = $request->date;
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        $filters['sort_by'] = $this->getSortColumn($sort);
        $filters['sort_direction'] = $this->getSortDirection($sort);

        // Pagination
        $filters['per_page'] = 15;

        return $filters;
    }

    /**
     * Get sort column based on sort option.
     */
    private function getSortColumn(string $sort): string
    {
        return match($sort) {
            'oldest' => 'created_at',
            'newest' => 'created_at',
            'due_date_asc' => 'due_date',
            'due_date_desc' => 'due_date',
            'amount_asc' => 'total',
            'amount_desc' => 'total',
            default => 'created_at',
        };
    }

    /**
     * Get sort direction based on sort option.
     */
    private function getSortDirection(string $sort): string
    {
        return match($sort) {
            'oldest' => 'asc',
            'newest' => 'desc',
            'due_date_asc' => 'asc',
            'due_date_desc' => 'desc',
            'amount_asc' => 'asc',
            'amount_desc' => 'desc',
            default => 'desc',
        };
    }
}
