<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $activeCompanyId = session('active_company_id');

        // Get user's companies
        $companies = Company::where('tenant_id', $user->tenant_id)
            ->withCount(['customers', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Get recent data based on active company
        $recentInvoices = collect();
        $recentCustomers = collect();

        if ($activeCompanyId) {
            $recentInvoices = Invoice::where('company_id', $activeCompanyId)
                ->with('customer')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $recentCustomers = Customer::where('company_id', $activeCompanyId)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard.index', [
            'companies' => $companies,
            'recentInvoices' => $recentInvoices,
            'recentCustomers' => $recentCustomers,
            'activeCompanyId' => $activeCompanyId,
        ]);
    }

    public function switchCompany(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $company = Company::findOrFail($request->company_id);

        // Check if user has access to this company
        if ($company->tenant_id !== Auth::user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالوصول لهذه الشركة'
            ], 403);
        }

        // Store active company in session
        session(['active_company_id' => $company->id]);
        session(['active_company_name' => $company->name]);

        // Update user's last active company
        Auth::user()->update([
            'last_active_company_id' => $company->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تبديل الشركة النشطة بنجاح',
            'company' => [
                'id' => $company->id,
                'name' => $company->name
            ]
        ]);
    }

    public function getCompanyStats($companyId)
    {
        $company = Company::findOrFail($companyId);

        // Verify access
        if ($company->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $stats = [
            'customers' => $company->customers()->count(),
            'invoices' => $company->invoices()->count(),
            'revenue' => $company->invoices()->where('status', 'paid')->sum('total'),
            'overdue' => $company->invoices()->where('status', 'overdue')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
