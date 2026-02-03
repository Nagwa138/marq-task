<?php

namespace App\Http\Requests\Company;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
//        return $this->user()->can('create', Company::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('companies')->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                })
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الشركة مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'tax_number.unique' => 'الرقم الضريبي مستخدم بالفعل',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();

        // Add tenant_id automatically
        $validated['tenant_id'] = $this->user()->tenant_id;

        return $key ? ($validated[$key] ?? $default) : $validated;
    }
}
