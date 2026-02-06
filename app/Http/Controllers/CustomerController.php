<?php

namespace App\Http\Controllers;

use App\Architecture\Services\Interfaces\ICompanyService;
use App\Architecture\Services\Interfaces\ICustomerService;
use App\Http\Requests\Customer\CustomerStoreRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private readonly ICustomerService $customerService,
    ) {
        $this->middleware('can:view,customer')->only('show', 'edit');
    }

    public function index(Request $request)
    {
        if (!session('active_company_id')) {
            return back()->withInput()
                ->with('error', 'برجاء تفعيل احد الشركات حتي تتمكن من عرض العملاء المنتميين للشركه.');
        }

        $filters = $request->only(['search', 'type', 'balance']);
        $customers = $this->customerService->all($filters);
        $stats = $this->customerService->getStats();

        $companies = Company::where('tenant_id', auth()->user()->tenant_id)
            ->withCount(['customers', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        $activeCompanyId = session('active_company_id');

        return view('customers.index', compact('customers', 'stats', 'companies', 'activeCompanyId'));
    }

    public function create()
    {
        if (!session('active_company_id')) {

            return back()->withInput()
                ->with('error', 'برجاء تفعيل احد الشركات حتي تتمكن من إضافة العملاء للشركه.');

        }

        $companies = Company::where('tenant_id', auth()->user()->tenant_id)
                    ->withCount(['customers', 'invoices'])
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();

        return view('customers.create', ['activeCompanyId' => session('active_company_id'), 'companies' => $companies]);
    }

    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        dd($request->all());
        $customer = $this->customerService->create($request->safe()->toArray());

        return redirect()
            ->route('customers.index', $customer)
            ->with('success', 'تم إنشاء العميل بنجاح');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->customerService->delete($id);

        return redirect()
            ->route('customers.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }

    public function invoices(int $id): View
    {
        $customer = $this->customerService->show($id);
        $invoices = $this->customerService->getInvoices($id);

        return view('customers.invoices', compact('customer', 'invoices'));
    }

    public function updateBalance(int $id): RedirectResponse
    {
        $balance = $this->customerService->updateBalance($id);

        return back()->with('success', "تم تحديث الرصيد إلى: {$balance} ر.س");
    }
}
