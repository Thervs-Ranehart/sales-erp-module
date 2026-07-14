<?php

namespace App\Models;

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

    public function isActive(): bool
    {
        return strtolower((string) $this->status) === 'active';
    }
}