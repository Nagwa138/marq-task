<?php

namespace App\Http\Requests\Invoice;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Invoice::class);
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
            'payment_terms' => 'required|in:net_30,net_15,due_on_receipt,custom',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',

            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateItems($validator);
        });
    }

    protected function validateItems($validator): void
    {
        $items = $this->input('items', []);

        foreach ($items as $index => $item) {
            $quantity = $item['quantity'] ?? 0;
            $unitPrice = $item['unit_price'] ?? 0;

            if ($quantity <= 0) {
                $validator->errors()->add(
                    "items.{$index}.quantity",
                    "الكمية يجب أن تكون أكبر من الصفر"
                );
            }

            if ($unitPrice < 0) {
                $validator->errors()->add(
                    "items.{$index}.unit_price",
                    "سعر الوحدة يجب أن يكون صفر أو أكثر"
                );
            }
        }
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['tenant_id'] = $this->user()->tenant_id;

        return $key ? ($validated[$key] ?? $default) : $validated;
    }
}
