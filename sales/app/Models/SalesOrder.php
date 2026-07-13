<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    protected $primaryKey = 'order_id';

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

    protected static function booted(): void
    {
        static::deleting(function (SalesOrder $order): void {
            $order->items()->delete();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function pricingRule(): BelongsTo
    {
        return $this->belongsTo(PricingRule::class, 'pricing_rule_id', 'pricing_rule_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class, 'order_id', 'order_id');
    }

    public function statusCssClass(): string
    {
        return match (strtolower((string) $this->order_status)) {
            'pending' => 'status-pending',
            'processed' => 'status-processed',
            'shipped' => 'status-shipped',
            'delivered' => 'status-delivered',
            'cancelled' => 'status-draft',
            default => 'status-pending',
        };
    }

    public function formattedStatus(): string
    {
        return ucfirst(strtolower((string) $this->order_status));
    }

    public function discountPercent(): float
    {
        if (! $this->subtotal || $this->subtotal <= 0) {
            return 0;
        }

        return round(((float) $this->discount / (float) $this->subtotal) * 100, 1);
    }

    public function taxPercent(): float
    {
        $taxable = (float) $this->subtotal - (float) $this->discount;

        if ($taxable <= 0) {
            return 0;
        }

        return round(((float) $this->tax / $taxable) * 100, 1);
    }
}
