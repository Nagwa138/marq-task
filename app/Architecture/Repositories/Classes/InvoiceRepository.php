<?php

namespace App\Architecture\Repositories\Classes;

use App\Architecture\Repositories\AbstractRepository;
use App\Models\Invoice;
use App\Architecture\Repositories\Interfaces\IInvoiceRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceRepository extends AbstractRepository implements IInvoiceRepository
{
    public function all(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Invoice::query();

        return $this->applyFilters($query, $filters)->get();
    }

    public function paginate(array $conditions = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Invoice::with(['customer', 'company', 'items']);

        return $this->applyFilters($query, $conditions)
            ->orderBy($conditions['sort_by'] ?? 'created_at', $conditions['sort_direction'] ?? 'desc')
            ->paginate($conditions['per_page'] ?? 15)
            ->withQueryString();
    }

    public function find(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $invoice = $this->find($id);
        if (!$invoice) {
            return false;
        }

        return $invoice->update($data);
    }

    public function delete(int $id): bool
    {
        $invoice = $this->find($id);
        if (!$invoice) {
            return false;
        }

        return $invoice->delete();
    }

    public function withRelations(int $id): ?Invoice
    {
        return Invoice::with(['customer', 'company', 'items'])
            ->where('id', $id)
            ->first();
    }

    public function getStats(): array
    {
        $currentMonth = now()->format('Y-m');

        return [
            'total' => Invoice::count(),
            'this_month' => Invoice::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total'),
            'outstanding' => Invoice::whereIn('status', ['sent', 'overdue'])->count(),
            'outstanding_amount' => Invoice::whereIn('status', ['sent', 'overdue'])->sum('total'),
            'overdue' => Invoice::where('status', 'overdue')->count(),
            'overdue_amount' => Invoice::where('status', 'overdue')->sum('total'),
            'total_tax' => Invoice::sum('tax'),
            'average_invoice' => Invoice::avg('total') ?? 0,
        ];
    }

    public function getDashboardStats(): array
    {
        $stats = $this->getStats();

        // Calculate collection rate
        $collectionRate = 0;
        if ($stats['total'] > 0) {
            $collectionRate = round(($stats['paid'] / $stats['total']) * 100, 2);
        }

        return array_merge($stats, [
            'collection_rate' => $collectionRate,
        ]);
    }

    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');

        // Get last invoice number for this year/month
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return "INV-{$year}{$month}-{$nextNumber}";
    }

    private function applyFilters($query, array $filters)
    {
        // Search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Status filter
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Company filter
        if (isset($filters['company_id']) && !empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        // Customer filter
        if (isset($filters['customer_id']) && !empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Date range filter
        if (isset($filters['date']) && !empty($filters['date'])) {
            $query->whereBetween('issue_date', $this->getDateRange($filters['date']));
        }

        return $query;
    }

    private function getDateRange(string $range): array
    {
        return match($range) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->subYear(), now()],
        };
    }
}
