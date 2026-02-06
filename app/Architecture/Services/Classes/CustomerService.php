<?php

namespace App\Architecture\Services\Classes;

use App\Architecture\Repositories\Interfaces\ICustomerRepository;
use App\Architecture\Services\Interfaces\ICustomerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerService implements ICustomerService
{
    public function __construct(
        private readonly ICustomerRepository $customerRepository,
    ) {}

    public function all(array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        try {
            // Add active company filter if set
            if (session('active_company_id')) {
                $filters['company_id'] = session('active_company_id');
            }

            return $this->customerRepository->paginateWithStats($filters);

        } catch (Exception $e) {
            dd($e->getMessage());
            throw new Exception('فشل في جلب العملاء: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $customer = $this->customerRepository->findWithRelations($id, [
                'company',
                'invoices' => function ($query) {
                    $query->with(['items', 'payments'])
                        ->orderBy('created_at', 'desc');
                },
                'payments' => function ($query) {
                    $query->with('invoice')
                        ->orderBy('created_at', 'desc');
                }
            ]);

            // Check authorization
            if ($customer->tenant_id !== Auth::user()->tenant_id) {
                throw new Exception('غير مصرح لك بالوصول إلى هذا العميل', 403);
            }

            // Load counts and sums
            $customer->loadCount([
                'invoices',
                'invoices as paid_invoices_count' => function ($query) {
                    $query->where('status', 'paid');
                },
                'invoices as overdue_invoices_count' => function ($query) {
                    $query->where('status', 'overdue');
                },
                'invoices as draft_invoices_count' => function ($query) {
                    $query->where('status', 'draft');
                },
                'invoices as sent_invoices_count' => function ($query) {
                    $query->where('status', 'sent');
                }
            ]);

            $customer->loadSum('invoices', 'total');
            $customer->loadSum('payments', 'amount');

            return $customer;

        } catch (Exception $e) {
            if ($e->getCode() === 403) {
                throw $e;
            }
            throw new Exception('فشل في جلب بيانات العميل: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            // Add tenant_id automatically
            $data['tenant_id'] = Auth::user()->tenant_id;

            // Validate company access
            if (isset($data['company_id'])) {
                $this->validateCompanyAccess($data['company_id']);
            }

            $customer = $this->customerRepository->create($data);

            DB::commit();
            return $customer;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('فشل في إنشاء العميل: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $customer = $this->customerRepository->find($id);

            // Check authorization
            if ($customer->tenant_id !== Auth::user()->tenant_id) {
                throw new Exception('غير مصرح لك بتعديل هذا العميل', 403);
            }

            // Validate company access if changing company
            if (isset($data['company_id']) && $data['company_id'] != $customer->company_id) {
                $this->validateCompanyAccess($data['company_id']);
            }

            $customer = $this->customerRepository->update($id, $data);

            // Update balance if needed
            if (isset($data['balance'])) {
                $this->updateBalance($id);
            }

            DB::commit();
            return $customer;

        } catch (Exception $e) {
            DB::rollBack();
            if ($e->getCode() === 403) {
                throw $e;
            }
            throw new Exception('فشل في تحديث العميل: ' . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $customer = $this->customerRepository->find($id);

            // Check authorization
            if ($customer->tenant_id !== Auth::user()->tenant_id) {
                throw new Exception('غير مصرح لك بحذف هذا العميل', 403);
            }

            // Check if customer has invoices
            if ($customer->invoices()->exists()) {
                throw new Exception('لا يمكن حذف العميل لأنه يحتوي على فواتير مرتبطة');
            }

            $result = $this->customerRepository->delete($id);

            DB::commit();
            return $result;

        } catch (Exception $e) {
            DB::rollBack();
            if ($e->getCode() === 403) {
                throw $e;
            }
            throw new Exception('فشل في حذف العميل: ' . $e->getMessage());
        }
    }

    public function getStats(): array
    {
        try {
            $companyId = request('company');

            $query = $this->customerRepository->prepareQuery();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $totalCustomers = $query->count();
            $customersWithInvoices = $query->has('invoices')->count();

            $totalBalance = $query->sum('balance');
            $positiveBalance = $query->where('balance', '>', 0)->sum('balance');
            $negativeBalance = $query->where('balance', '<', 0)->sum('balance');

            // Get top 5 customers by balance
            $topCustomers = $query->orderBy('balance', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'balance']);

            // Get recent customers
            $recentCustomers = $query->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'created_at']);

            return [
                'total_customers' => $totalCustomers,
                'customers_with_invoices' => $customersWithInvoices,
                'total_balance' => (float) $totalBalance,
                'positive_balance' => (float) abs($positiveBalance),
                'negative_balance' => (float) abs($negativeBalance),
                'average_balance' => $totalCustomers > 0 ? (float) ($totalBalance / $totalCustomers) : 0,
                'top_customers' => $topCustomers,
                'recent_customers' => $recentCustomers,
            ];

        } catch (Exception $e) {
            dd($e);
            throw new Exception('فشل في حساب إحصائيات العملاء: ' . $e->getMessage());
        }
    }

    public function getInvoices(int $customerId, int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $customer = $this->customerRepository->find($customerId);

            // Check authorization
            if ($customer->tenant_id !== Auth::user()->tenant_id) {
                throw new Exception('غير مصرح لك بالوصول إلى فواتير هذا العميل', 403);
            }

            $query = $customer->invoices()
                ->with(['items', 'payments'])
                ->orderBy('created_at', 'desc');

            if ($limit) {
                $query->limit($limit);
            }

            return $query->get();

        } catch (Exception $e) {
            if ($e->getCode() === 403) {
                throw $e;
            }
            throw new Exception('فشل في جلب فواتير العميل: ' . $e->getMessage());
        }
    }

    public function getPayments(int $customerId, int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $customer = $this->customerRepository->find($customerId);

            // Check authorization
            if ($customer->tenant_id !== Auth::user()->tenant_id) {
                throw new Exception('غير مصرح لك بالوصول إلى مدفوعات هذا العميل', 403);
            }

            $query = $customer->payments()
                ->with('invoice')
                ->orderBy('created_at', 'desc');

            if ($limit) {
                $query->limit($limit);
            }

            return $query->get();

        } catch (Exception $e) {
            if ($e->getCode() === 403) {
                throw $e;
            }
            throw new Exception('فشل في جلب مدفوعات العميل: ' . $e->getMessage());
        }
    }

    public function updateBalance(int $customerId): float
    {
        DB::beginTransaction();

        try {
            $customer = $this->customerRepository->find($customerId);

            // Check authorization
            if ($customer->tenant_id !== Auth::user()->tenant_id) {
                throw new Exception('غير مصرح لك بتحديث رصيد هذا العميل', 403);
            }

            $totalInvoices = $customer->invoices()->sum('total');
            $totalPayments = $customer->payments()->sum('amount');
            $balance = $totalInvoices - $totalPayments;

            $customer->update(['balance' => $balance]);

            DB::commit();
            return $balance;

        } catch (Exception $e) {
            DB::rollBack();
            if ($e->getCode() === 403) {
                throw $e;
            }
            throw new Exception('فشل في تحديث رصيد العميل: ' . $e->getMessage());
        }
    }

    public function search(string $query, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        try {
            $activeCompanyId = session('active_company_id');

            $searchQuery = $this->customerRepository->query()
                ->where('tenant_id', Auth::user()->tenant_id)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                });

            if ($activeCompanyId) {
                $searchQuery->where('company_id', $activeCompanyId);
            }

            return $searchQuery->limit($limit)
                ->get(['id', 'name', 'email', 'phone', 'company_id']);

        } catch (Exception $e) {
            throw new Exception('فشل في البحث عن العملاء: ' . $e->getMessage());
        }
    }

    /**
     * Validate that user has access to the company
     */
    private function validateCompanyAccess(int $companyId): void
    {
        $company = \App\Models\Company::find($companyId);

        if (!$company || $company->tenant_id !== Auth::user()->tenant_id) {
            throw new Exception('غير مصرح لك بإضافة عملاء لهذه الشركة', 403);
        }
    }
}
