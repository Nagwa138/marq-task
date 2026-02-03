<?php

namespace App\Architecture\Repositories\Classes;

use App\Architecture\Repositories\AbstractRepository;
use App\Architecture\Repositories\Interfaces\ICustomerRepository;
use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class CustomerRepository extends AbstractRepository implements ICustomerRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all customers with pagination
     */
    public function paginate(array $conditions = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply tenant filter
        $query->where('tenant_id', $this->getTenantId());

        // Apply other filters
        $this->applyFilters($query, $conditions);

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get customers with statistics
     */
    public function paginateWithStats(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->prepareQuery();

        // Apply tenant filter
        $query->where('company_id', session('active_company_id'));

        // Apply other filters
        $this->applyFilters($query, $filters);

        return $query->with(['company'])
            ->withCount([
                'invoices',
                'invoices as paid_invoices_count' => function ($query) {
                    $query->where('status', 'paid');
                },
                'invoices as overdue_invoices_count' => function ($query) {
                    $query->where('status', 'overdue');
                }
            ])
            ->withSum('invoices', 'total')
            ->withSum('payments', 'amount')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find customer by ID with relations
     */
    public function findWithRelations(int $id, array $relations = [])
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->with($relations)
            ->findOrFail($id);
    }

    /**
     * Get customers by company
     */
    public function getByCompany(int $companyId, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('tenant_id', $this->getTenantId())
            ->where('company_id', $companyId);

        $this->applyFilters($query, $filters);

        return $query->orderBy('name')
            ->get();
    }

    /**
     * Get customers with overdue invoices
     */
    public function getWithOverdueInvoices(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('tenant_id', $this->getTenantId())
            ->whereHas('invoices', function ($q) {
                $q->where('status', 'overdue');
            });

        $this->applyFilters($query, $filters);

        return $query->withCount(['invoices' => function ($q) {
            $q->where('status', 'overdue');
        }])
            ->withSum(['invoices' => function ($q) {
                $q->where('status', 'overdue');
            }], 'total')
            ->orderBy('name')
            ->get();
    }

    /**
     * Search customers for autocomplete
     */
    public function search(string $queryString, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->where(function ($query) use ($queryString) {
                $query->where('name', 'like', "%{$queryString}%")
                    ->orWhere('email', 'like', "%{$queryString}%")
                    ->orWhere('phone', 'like', "%{$queryString}%");
            })
            ->limit($limit)
            ->get(['id', 'name', 'email', 'phone', 'company_id']);
    }

    /**
     * Get customers with positive balance
     */
    public function getWithPositiveBalance(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('tenant_id', $this->getTenantId())
            ->where('balance', '>', 0);

        $this->applyFilters($query, $filters);

        return $query->orderBy('balance', 'desc')
            ->get();
    }

    /**
     * Get customers with negative balance
     */
    public function getWithNegativeBalance(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model
            ->where('tenant_id', $this->getTenantId())
            ->where('balance', '<', 0);

        $this->applyFilters($query, $filters);

        return $query->orderBy('balance', 'asc')
            ->get();
    }

    /**
     * Get recent customers
     */
    public function getRecent(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->with('company')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query, array $filters): void
    {
        // Apply active company filter if exists in session
        $activeCompanyId = session('active_company_id');
        if ($activeCompanyId && !isset($filters['company_id'])) {
            $query->where('company_id', $activeCompanyId);
        }

        foreach ($filters as $key => $value) {
            if (empty($value)) {
                continue;
            }

            switch ($key) {
                case 'search':
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                    });
                    break;

                case 'company_id':
                    $query->where('company_id', $value);
                    break;

                case 'balance':
                    if ($value === 'positive') {
                        $query->where('balance', '>', 0);
                    } elseif ($value === 'negative') {
                        $query->where('balance', '<', 0);
                    } elseif ($value === 'zero') {
                        $query->where('balance', 0);
                    }
                    break;

                case 'has_invoices':
                    if ($value === 'true') {
                        $query->has('invoices');
                    } elseif ($value === 'false') {
                        $query->doesntHave('invoices');
                    }
                    break;

                case 'has_overdue':
                    if ($value === 'true') {
                        $query->whereHas('invoices', function ($q) {
                            $q->where('status', 'overdue');
                        });
                    }
                    break;

                default:
                    $query->where($key, $value);
                    break;
            }
        }
    }

    /**
     * Override parent methods to add tenant filtering
     */
    public function all(array $conditions = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());
        $this->applyFilters($query, $conditions);
        return $query->get();
    }

    public function find(int $id)
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->find($id);
    }

    public function findOrFail(int $id)
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->findOrFail($id);
    }

    public function first(array $conditions = [])
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());
        $this->applyFilters($query, $conditions);
        return $query->first();
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Add tenant_id automatically
        $data['tenant_id'] = $this->getTenantId();

        // Add active company if not specified
        if (!isset($data['company_id'])) {
            $activeCompanyId = session('active_company_id');
            if ($activeCompanyId) {
                $data['company_id'] = $activeCompanyId;
            }
        }

        return parent::create($data);
    }

    public function count(array $conditions = []): int
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());
        $this->applyFilters($query, $conditions);
        return $query->count();
    }

    public function exists(array $conditions = []): bool
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());
        $this->applyFilters($query, $conditions);
        return $query->exists();
    }

    /**
     * Get customer statistics
     */
    public function getStats(): array
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());

        // Apply active company filter if exists
        $activeCompanyId = session('active_company_id');
        if ($activeCompanyId) {
            $query->where('company_id', $activeCompanyId);
        }

        $totalCustomers = $query->count();
        $totalBalance = $query->sum('balance');
        $customersWithInvoices = $query->has('invoices')->count();
        $customersWithOverdue = $query->whereHas('invoices', function ($q) {
            $q->where('status', 'overdue');
        })->count();

        return [
            'total_customers' => $totalCustomers,
            'total_balance' => (float) $totalBalance,
            'customers_with_invoices' => $customersWithInvoices,
            'customers_with_overdue' => $customersWithOverdue,
            'average_balance' => $totalCustomers > 0 ? (float) ($totalBalance / $totalCustomers) : 0,
        ];
    }

    /**
     * Update customer balance
     */
    public function updateBalance(int $customerId): float
    {
        $customer = $this->findOrFail($customerId);

        $totalInvoices = $customer->invoices()->sum('total');
        $totalPayments = $customer->payments()->sum('amount');
        $balance = $totalInvoices - $totalPayments;

        $customer->update(['balance' => $balance]);

        return $balance;
    }

    /**
     * Get top customers by balance
     */
    public function getTopCustomersByBalance(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->where('balance', '!=', 0)
            ->orderByRaw('ABS(balance) DESC')
            ->limit($limit)
            ->get(['id', 'name', 'balance', 'company_id']);
    }

    private function getTenantId()
    {
        return auth()->user()->tenant_id;
    }
}
