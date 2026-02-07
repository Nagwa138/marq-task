<?php

namespace App\Architecture\Repositories\Classes;

use App\Architecture\Repositories\AbstractRepository;
use App\Architecture\Services\Classes\InvoiceService;
use App\Models\Invoice;
use App\Architecture\Repositories\Interfaces\IInvoiceRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceRepository extends AbstractRepository implements IInvoiceRepository
{
    public function find(int $id): ?Invoice
    {
        return $this->first(['id' => $id]);
    }

    public function findWithRelations(int $id): ?Invoice
    {
        return $this->prepareQuery()->with([
            'company',
            'customer',
            'items',
            'payments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->find($id);
    }

    public function getInvoiceForPrint(int $id): ?Invoice
    {
        return $this->prepareQuery()->with([
            'company' => function ($query) {
                $query->select(['id', 'name', 'email', 'phone', 'address', 'logo', 'tax_number']);
            },
            'customer' => function ($query) {
                $query->select(['id', 'name', 'email', 'phone', 'address', 'tax_number']);
            },
            'items'
        ])->find($id);
    }

    public function markAsSent(int $id): bool
    {
        $invoice = $this->find($id);

        if (!$invoice) {
            return false;
        }

        return $invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsPaid(int $id): bool
    {
        $invoice = $this->find($id);

        if (!$invoice) {
            return false;
        }

        return DB::transaction(function () use ($invoice) {
            $result = $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // If there are outstanding payments, mark them as completed
            if ($result) {
                $invoice->payments()
                    ->where('status', 'pending')
                    ->update(['status' => 'completed']);
            }

            return $result;
        });
    }

    public function duplicate(int $id): ?Invoice
    {
        return DB::transaction(function () use ($id) {
            $originalInvoice = $this->findWithRelations($id);

            if (!$originalInvoice) {
                return null;
            }

            // Duplicate invoice
            $newInvoice = $originalInvoice->replicate();
            $newInvoice->invoice_number = self::generateInvoiceNumber();
            $newInvoice->status = 'draft';
            $newInvoice->issue_date = now();
            $newInvoice->due_date = now()->addDays($originalInvoice->customer->payment_terms_days ?? 30);
            $newInvoice->sent_at = null;
            $newInvoice->paid_at = null;
            $newInvoice->save();

            // Duplicate invoice items
            foreach ($originalInvoice->items as $item) {
                $newItem = $item->replicate();
                $newItem->invoice_id = $newInvoice->id;
                $newItem->save();
            }

            return $newInvoice->load(['customer', 'company', 'items']);
        });
    }

    public function getRelatedInvoices(int $customerId, int $excludeId = null)
    {
        $query = $this->model->where('customer_id', $customerId)
            ->with(['company'])
            ->orderBy('created_at', 'desc')
            ->limit(5);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }

    public function all(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->prepareQuery();

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

    public function withRelations(int $id): ?Invoice
    {
        return $this->prepareQuery()->with(['customer', 'company', 'items'])
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

    public static function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');

        // Get the last invoice for this year/month
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract the sequential number
            $pattern = '/^INV-' . $year . $month . '-(\d+)$/';
            if (preg_match($pattern, $lastInvoice->invoice_number, $matches)) {
                $lastNumber = (int)$matches[1];
                $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = '0001';
            }
        } else {
            $nextNumber = '0001';
        }

        return "INV-{$year}{$month}-{$nextNumber}";
    }
}
