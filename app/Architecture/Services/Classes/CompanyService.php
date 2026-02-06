<?php

namespace App\Architecture\Services\Classes;

use App\Architecture\Repositories\Interfaces\ICompanyRepository;
use App\Architecture\Services\Interfaces\ICompanyService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyService implements ICompanyService
{
    public function __construct(
        private readonly ICompanyRepository $companyRepository
    ) {}

    public function all(array $filters = [])
    {
        return $this->companyRepository->all($filters);
    }

    public function paginate(array $filters = [])
    {
        return $this->companyRepository->paginate($filters);
    }

    public function show(int $id)
    {
        return $this->companyRepository->withStats($id);
    }

    public function create(array $data)
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo'] = $this->uploadLogo($data['logo']);
        }

        return $this->companyRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $data['logo'] = $this->uploadLogo($data['logo']);
        }

        return $this->companyRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->companyRepository->delete($id);
    }

    public function getStats(): array
    {
        return [
            'total' => $this->companyRepository->countActive(),
            'recent' => $this->companyRepository->paginate([], 5),
        ];
    }

    protected function uploadLogo(UploadedFile $file): string
    {
        $path = $file->store('companies/logos', 'public');

        return str_replace('public/', '', $path);
    }

    public function deleteLogo(int $id): bool
    {
        $company = $this->companyRepository->first(['id' => $id]);

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
            $company->update(['logo' => null]);

            return true;
        }

        return false;
    }

    public function getIndexData(array $filters = []): array
    {
        // Get paginated companies
        $paginatedCompanies = $this->paginate($filters);

        // Calculate statistics
        $stats = $this->getDashboardStats();

        return [
            'paginatedCompanies' => $paginatedCompanies,
            'stats' => $stats,
        ];
    }

    public function getDashboardStats(): array
    {
        return Cache::remember('company_dashboard_stats', 3600, function () {
            // Get company stats
            $totalCompanies = $this->companyRepository->countActive();
            $activeCompanies = $this->companyRepository->countActive();

            // Get invoice statistics
            $invoiceStats = $this->getInvoiceStatistics();

            // Get customer statistics
            $customerStats = $this->getCustomerStatistics();

            // Calculate revenue growth
            $revenueGrowth = $this->calculateRevenueGrowth();

            return [
                'recent' => $activeCompanies,
                'monthly_invoices' => $invoiceStats['monthly_invoices'] ?? 0,
                'paid_invoices' => $invoiceStats['paid_invoices'] ?? 0,
                'overdue_invoices' => $invoiceStats['overdue_invoices'] ?? 0,
                'total_invoices' => $invoiceStats['total_invoices'] ?? 0,
                'total_paid' => $invoiceStats['total_paid'] ?? 0,
                'outstanding_balance' => $invoiceStats['outstanding_balance'] ?? 0,
                'formatted_revenue' => number_format($invoiceStats['current_month_revenue'] ?? 0, 0),
                'revenue_growth' => $revenueGrowth,
                'new_customers' => $customerStats['new_customers'] ?? 0,
                'total_customers' => $customerStats['total_customers'] ?? 0,
            ];
        });
    }

    private function getInvoiceStatistics(): array
    {
        $currentMonth = now()->format('Y-m');

        $monthlyInvoices = DB::table('invoices')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $paidInvoices = DB::table('invoices')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->count();

        $overdueInvoices = DB::table('invoices')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->count();

        $totalInvoices = DB::table('invoices')->count();

        $totalPaid = DB::table('invoices')
            ->where('status', 'paid')
            ->sum('total') ?? 0;

        $outstandingBalance = DB::table('invoices')
            ->where('status', '!=', 'paid')
            ->sum('total') ?? 0;

        $currentMonthRevenue = DB::table('invoices')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->sum('total') ?? 0;

        return [
            'monthly_invoices' => $monthlyInvoices,
            'paid_invoices' => $paidInvoices,
            'overdue_invoices' => $overdueInvoices,
            'total_invoices' => $totalInvoices,
            'total_paid' => $totalPaid,
            'outstanding_balance' => $outstandingBalance,
            'current_month_revenue' => $currentMonthRevenue,
        ];
    }

    private function getCustomerStatistics(): array
    {
        $totalCustomers = DB::table('customers')->count();

        $newCustomers = DB::table('customers')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return [
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
        ];
    }

    private function calculateRevenueGrowth(): float
    {
        $currentMonthRevenue = DB::table('invoices')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->sum('total') ?? 0;

        $lastMonthRevenue = DB::table('invoices')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('status', 'paid')
            ->sum('total') ?? 0;

        if ($lastMonthRevenue > 0) {
            return round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2);
        } elseif ($currentMonthRevenue > 0) {
            return 100;
        }

        return 0;
    }
}
