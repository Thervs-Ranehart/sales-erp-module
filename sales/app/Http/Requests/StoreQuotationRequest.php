<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'pricing_rule_id' => ['nullable', 'exists:pricing_rules,pricing_rule_id'],

            'quotation_date' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:quotation_date'],

            'status' => [
                'required',
                'in:draft,sent,accepted,rejected,expired',
            ],

            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],

            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:products,product_id'],

            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'integer', 'min:1'],

            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric', 'min:0'],
        ];
    }
}