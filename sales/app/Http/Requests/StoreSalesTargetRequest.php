<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesTargetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->session()->has('employee_id');
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,employee_id'],
            'target_month' => ['required', 'integer', 'between:1,12'],
            'target_year' => ['required', 'integer', 'between:2020,2100'],
            'sales_target' => ['required', 'integer', 'min:0'],
            'revenue_target' => ['required', 'numeric', 'min:0'],
        ];
    }
}
