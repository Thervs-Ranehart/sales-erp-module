<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_orders';

    protected $primaryKey = 'order_id';

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'order_number',
        'quotation_id',
        'customer_id',
        'employee_id',
        'pricing_rule_id',
        'order_date',
        'payment_method',
        'payment_status',
        'order_status',
        'warehouse',
        'subtotal',
        'discount',
        'tax',
        'shipping_fee',
        'total_amount',
    ];

    protected $casts = [
        'order_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class, 'order_id', 'order_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'order_id', 'order_id');
    }
}

