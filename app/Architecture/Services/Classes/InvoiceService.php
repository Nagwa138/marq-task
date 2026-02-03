<?php

namespace App\Architecture\Services\Classes;

use App\Architecture\Repositories\Interfaces\IInvoiceRepository;
use App\Architecture\Services\Interfaces\IInvoiceService;
use App\Models\Invoice;
use App\Architecture\Services\Classes\CustomerService;
use Illuminate\Support\Facades\DB;

class InvoiceService implements IInvoiceService
{
    public function __construct(
        private IInvoiceRepository $repository,
        private CustomerService $customerService
    ) {}

    public function createInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            // Calculate totals
            $totals = $this->calculateTotals($data['items'], $data['discount'] ?? 0, $data['discount_type'] ?? 'fixed');

            // Create invoice
            $invoice = $this->repository->create(array_merge($data, $totals));

            // Create invoice items
            $this->createInvoiceItems($invoice, $data['items']);

            // Update customer balance
            $this->customerService->updateBalance($data['customer_id']);

            return $invoice->load(['items', 'customer', 'company']);
        });
    }

    public function sendInvoice(int $invoiceId): Invoice
    {
        return $this->repository->update($invoiceId, ['status' => 'sent']);
    }

    public function markAsPaid(int $invoiceId): Invoice
    {
        return DB::transaction(function () use ($invoiceId) {
            $invoice = $this->repository->update($invoiceId, ['status' => 'paid']);
            $this->customerService->updateBalance($invoice->customer_id);

            return $invoice;
        });
    }

    protected function calculateTotals(array $items, float $discount = 0, string $discountType = 'fixed'): array
    {
        $subtotal = collect($items)->sum(function ($item) {
            return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        });

        // Apply discount
        if ($discount > 0) {
            if ($discountType === 'percentage') {
                $subtotal -= $subtotal * ($discount / 100);
            } else {
                $subtotal -= $discount;
            }
        }

        // Calculate tax (15% default)
        $taxRate = 15;
        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
            'discount' => $discount,
            'discount_type' => $discountType,
            'tax_rate' => $taxRate,
        ];
    }

    protected function createInvoiceItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            $total = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            $taxAmount = $total * (($item['tax_rate'] ?? 0) / 100);

            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $total,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'tax_amount' => $taxAmount,
            ]);
        }
    }

    public function getInvoiceStatistics(): array
    {
        return [
            'total' => $this->repository->count(),
            'paid' => $this->repository->countByStatus('paid'),
            'pending' => $this->repository->countByStatus('sent'),
            'overdue' => $this->repository->getOverdueCount(),
            'revenue' => $this->repository->getTotalRevenue(),
        ];
    }
}
