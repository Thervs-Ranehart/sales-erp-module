<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingRule extends Model
{
    protected $table = 'pricing_rules';

    protected $primaryKey = 'pricing_rule_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'rule_name',
        'discount_type',
        'discount_value',
        'tax_rate',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'pricing_rule_id', 'pricing_rule_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'pricing_rule_id', 'pricing_rule_id');
    }

    /**
     * Determine whether the pricing rule is currently active
     * based on today's date.
     */
    public function isActive(): bool
    {
        $today = Carbon::today();

        return $today->between(
            $this->start_date,
            $this->end_date
        );
    }
}