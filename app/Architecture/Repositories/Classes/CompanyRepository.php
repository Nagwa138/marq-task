<?php

namespace App\Architecture\Repositories\Classes;

use App\Architecture\Repositories\AbstractRepository;
use App\Architecture\Repositories\Interfaces\ICompanyRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyRepository extends AbstractRepository implements ICompanyRepository
{
    public function all(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    public function paginate(array $conditions = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->where('tenant_id', $this->getTenantId());

        $this->applyFilters($query, $conditions);

        return $query->withCount(['invoices', 'customers'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->with(['invoices', 'customers'])
            ->findOrFail($id);
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['company_id'] = session('active_company_id');

        return $this->model->create($data);
    }

    public function update(array $conditions = [], array $data = [])
    {
        $company = $this->first($conditions);
        $company->update($data);

        return $company->fresh();
    }

    public function delete(int $id): void
    {
        $company = $this->find($id);

        $company->delete();
    }

    public function countActive(): int
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->count();
    }

    public function withStats(int $id)
    {
        return $this->model
            ->where('tenant_id', $this->getTenantId())
            ->withCount([
                'invoices',
                'invoices as total_invoice_amount' => function ($query) {
                    $query->select(DB::raw('SUM(total)'));
                },
                'customers'
            ])
            ->findOrFail($id);
    }

    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('tax_number', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }
    }

    protected function getTenantId(): int
    {
        return Auth::user()?->tenant_id;
    }
}
