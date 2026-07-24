<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'employee_id' => $this->session()->get('employee_id')
                ?? $this->input('employee_id')
                ?? Employee::query()->value('employee_id'),
        ]);
    }

    public function rules(): array
    {
        return [

            'order_id' => [
                'required',
                'exists:sales_orders,order_id',
            ],

            'employee_id' => [
                'required',
                'exists:employees,employee_id',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:255',
            ],

            'payment_status' => [
                'required',
                'in:Pending,Paid,Cancelled',
            ],

            'subtotal' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'shipping_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'quantities' => ['nullable', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0'],

        ];
    }
}
