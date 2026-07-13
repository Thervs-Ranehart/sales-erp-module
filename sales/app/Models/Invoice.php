<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $primaryKey = 'invoice_id';

    protected $keyType = 'int';

    public $incrementing = true;

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

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }
}

