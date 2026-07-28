<?php

namespace App\Http\Requests;

use App\Models\PricingRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'payment_method' => $this->input('payment_method', 'Cash'),
            'payment_status' => $this->input('payment_status', 'Pending'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,customer_id'],
            'order_date' => ['required', 'date'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'integer', 'exists:products,product_id'],
            'qty' => ['required', 'array', 'min:1'],
            'qty.*' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'array', 'min:1'],
            'price.*' => ['required', 'numeric', 'min:0'],
            'pricing_rule_id' => ['nullable', 'integer', 'exists:pricing_rules,pricing_rule_id'],
            'reward_id' => ['nullable', 'integer', 'exists:rewards,reward_id'],
            'discount' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $pricingRule = PricingRule::find($this->pricing_rule_id);

                    if (
                        $pricingRule &&
                        strtolower($pricingRule->discount_type) === 'percentage' &&
                        $value > 100
                    ) {
                        $fail('Percentage discount cannot exceed 100%.');
                    }
                },
            ],
            'tax' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:pending,processed,shipped,delivered,cancelled'],
            'payment_method' => ['required', 'in:Cash,Card,Bank Transfer,E-Wallet'],
            'payment_status' => ['required', 'in:Pending,Paid'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'product_id.required' => 'Please add at least one product.',
            'product_id.min' => 'Please add at least one product.',
            'status.in' => 'Please select a valid order status.',
        ];
    }
}
