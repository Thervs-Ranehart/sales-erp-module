<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'invoice_number',
        'order_id',
        'employee_id',
        'invoice_date',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount',
        'tax',
        'shipping_fee',
        'total_amount',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];
}
