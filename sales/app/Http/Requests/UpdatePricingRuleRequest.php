<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePricingRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rule_name' => [
                'required',
                'string',
                'max:255',
            ],

            'discount_type' => [
                'required',
                'in:Percentage,Fixed',
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'tax_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'rule_name.required' => 'Rule name is required.',
            'discount_type.required' => 'Please select a discount type.',
            'discount_value.required' => 'Discount value is required.',
            'status.required' => 'Please select a status.',
            'end_date.after_or_equal' => 'End date must be after or equal to the start date.',
        ];
    }
}