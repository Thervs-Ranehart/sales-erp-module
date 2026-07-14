<?php

namespace App\Http\Requests;

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
            // NOTE: auth() authenticates against the `users` table, which has
            // no relationship to `employees`. Hardcoding until employee-based
            // auth (or a users.employee_id link) is wired up.
            'employee_id' => 1, // TODO: replace with real employee lookup
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
                'required',
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
                'required',
                'numeric',
                'min:0',
            ],

        ];
    }
}