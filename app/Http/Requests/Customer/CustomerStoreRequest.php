<?php

namespace App\Http\Requests\Customer;

    use App\Models\Customer;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\Auth;

class CustomerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
//        return $this->user()->can('create', Customer::class);
    }

    public function rules(): array
    {
        $tenantId = Auth::user()->tenant_id;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                })
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:50',
            'type' => 'required|in:individual,company',
            'commercial_register' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'اسم العميل يجب ألا يتجاوز 255 حرف',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 رقماً',
            'address.max' => 'العنوان يجب ألا يتجاوز 500 حرف',
            'tax_number.max' => 'الرقم الضريبي يجب ألا يتجاوز 50 حرف',
            'type.required' => 'حقل نوع العميل مطلوب',
            'type.in' => 'نوع العميل غير صالح',
            'commercial_register.max' => 'رقم السجل التجاري يجب ألا يتجاوز 100 حرف',
            'contact_person.max' => 'اسم الشخص المسؤول يجب ألا يتجاوز 255 حرف',
            'contact_person_phone.max' => 'هاتف الشخص المسؤول يجب ألا يتجاوز 20 رقماً',
            'website.url' => 'الموقع الإلكتروني غير صالح',
            'website.max' => 'الموقع الإلكتروني يجب ألا يتجاوز 255 حرف',
            'payment_terms.max' => 'شروط الدفع يجب ألا تتجاوز 255 حرف',
            'credit_limit.numeric' => 'الحد الائتماني يجب أن يكون رقماً',
            'credit_limit.min' => 'الحد الائتماني يجب أن يكون موجباً',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم العميل',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'address' => 'العنوان',
            'tax_number' => 'الرقم الضريبي',
            'type' => 'نوع العميل',
            'commercial_register' => 'رقم السجل التجاري',
            'contact_person' => 'الشخص المسؤول',
            'contact_person_phone' => 'هاتف الشخص المسؤول',
            'website' => 'الموقع الإلكتروني',
            'payment_terms' => 'شروط الدفع',
            'credit_limit' => 'الحد الائتماني',
            'notes' => 'ملاحظات',
        ];
    }
}
