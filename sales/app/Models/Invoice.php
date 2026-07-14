<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $primaryKey = 'invoice_id';

    public $incrementing = true;

    protected $keyType = 'int';

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

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(
            SalesOrder::class,
            'order_id',
            'order_id'
        );
    }

    /**
     * Alias for salesOrder(), since some controllers/views reference
     * 'order' instead of 'salesOrder'. Keeping both avoids breaking
     * either naming convention.
     */
    public function order(): BelongsTo
    {
        return $this->salesOrder();
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            InvoiceItem::class,
            'invoice_id',
            'invoice_id'
        );
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'employee_id'
        );
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(
            InventoryTransaction::class,
            'invoice_id',
            'invoice_id'
        );
    }

    public function financeTransactions(): HasMany
    {
        return $this->hasMany(
            FinanceTransaction::class,
            'invoice_id',
            'invoice_id'
        );
    }

    public function getRouteKeyName(): string
    {
        return 'invoice_id';
    }
}