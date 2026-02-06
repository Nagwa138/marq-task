<?php

namespace App\Architecture\Services\Classes;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Architecture\Repositories\Interfaces\IInvoiceRepository;
use App\Architecture\Services\Interfaces\IInvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class InvoiceService implements IInvoiceService
{
    public function __construct(
        private readonly IInvoiceRepository $invoiceRepository
    ) {}

    public function getIndexData(array $filters = []): array
    {
        // Get paginated invoices
        $invoices = $this->paginate($filters);

        // Get statistics
        $stats = $this->getDashboardStats();

        return [
            'invoices' => $invoices,
            'stats' => $stats,
        ];
    }

    public function getCreateData(): array
    {
        // Get active companies for the current tenant/user
        $customers = auth()->user()->tenant->customers;

        // Generate invoice number
        $invoiceNumber = $this->invoiceRepository->generateInvoiceNumber();

        return [
            'companies' => $companies,
            'customers' => $customers,
            'invoice_number' => $invoiceNumber,
            'default_tax_rate' => config('app.default_tax_rate', 15),
        ];
    }

    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            // Calculate totals
            $subtotal = 0;
            $items = [];

            // Process invoice items if provided
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    $subtotal += $itemTotal;

                    $items[] = [
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total' => $itemTotal,
                    ];
                }
            }

            // Calculate tax
            $taxRate = $data['tax_rate'] ?? config('app.default_tax_rate', 15);
            $tax = ($subtotal * $taxRate) / 100;

            // Calculate total
            $total = $subtotal + $tax;

            // Prepare invoice data
            $invoiceData = [
                'company_id' => $data['company_id'],
                'customer_id' => $data['customer_id'],
                'invoice_number' => $data['invoice_number'] ?? $this->invoiceRepository->generateInvoiceNumber(),
                'issue_date' => $data['issue_date'] ?? now(),
                'due_date' => $data['due_date'] ?? now()->addDays(30),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'tax_rate' => $taxRate,
                'total' => $total,
                'status' => $data['status'] ?? 'draft',
                'notes' => $data['notes'] ?? null,
            ];

            // Create invoice
            $invoice = $this->invoiceRepository->create($invoiceData);

            // Create invoice items if any
            if (!empty($items)) {
                foreach ($items as $item) {
                    $invoice->items()->create($item);
                }
            }

            return $invoice->load(['customer', 'company', 'items']);
        });
    }

    public function getDashboardStats(): array
    {
        return Cache::remember('invoice_dashboard_stats', 3600, function () {
            $stats = $this->invoiceRepository->getDashboardStats();

            // Calculate collection rate
            $collectionRate = 0;
            if ($stats['total'] > 0) {
                $collectionRate = round(($stats['paid'] / $stats['total']) * 100, 2);
            }

            return array_merge($stats, [
                'collection_rate' => $collectionRate,
            ]);
        });
    }

    public function paginate(array $filters = [])
    {
        return $this->invoiceRepository->paginate($filters);
    }
}
