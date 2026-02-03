<?php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tax_number' => $request->tax_number,
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        auth()->user()->update(['company_id' => $company->id]);

        return redirect()->route('dashboard');
    }
}
