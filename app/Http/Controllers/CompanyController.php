<?php

namespace App\Http\Controllers;

use App\Architecture\Services\Interfaces\ICompanyService;
use App\Http\Requests\Company\CompanyStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(
        private readonly ICompanyService $companyService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $companies = $this->companyService->all($filters);

        // Get all data needed for the index page
        $data = $this->companyService->getIndexData($filters);

        return view('companies.index', [
            'paginatedCompanies' => $data['paginatedCompanies'],
            'stats' => $data['stats'],
            'companies' => $companies
            ]
        );
    }

    public function create(): View
    {
        return view('companies.create');
    }

    public function store(CompanyStoreRequest $request): RedirectResponse
    {
        $company = $this->companyService->create($request->safe()->toArray());

        return redirect()
            ->route('companies.show', $company)
            ->with('success', 'تم إنشاء الشركة بنجاح');
    }

    public function show(int $id): View
    {
        $company = $this->companyService->show($id);
        $companies = $this->companyService->all();

        return view('companies.show', compact('company', 'companies'));
    }

    public function edit(int $id): View
    {
        $company = $this->companyService->show($id);

        return view('companies.edit', compact('company'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->companyService->delete($id);

        return redirect()
            ->route('companies.index')
            ->with('success', 'تم حذف الشركة بنجاح');
    }

    public function deleteLogo(int $id): RedirectResponse
    {
        $this->companyService->deleteLogo($id);

        return back()->with('success', 'تم حذف الشعار بنجاح');
    }
}
