<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $primaryKey = 'quotation_id';

    protected $fillable = [
        'quotation_number',
        'customer_id',
        'employee_id',
        'pricing_rule_id',
        'quotation_date',
        'valid_until',
        'subtotal',
        'discount',
        'tax',
        'shipping_fee',
        'total_amount',
        'quotation_status',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Quotation $quotation): void {
            $quotation->items()->delete();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'quotation_number';
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
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'quotation_id');
    }

    public function statusCssClass(): string
    {
        return match (strtolower((string) $this->quotation_status)) {
            'draft' => 'status-draft',
            'sent' => 'status-pending',
            'accepted' => 'status-delivered',
            'rejected' => 'status-cancelled',
            'expired' => 'status-processed',
            default => 'status-pending',
        };
    }

    public function formattedStatus(): string
    {
        return ucfirst((string) $this->quotation_status);
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