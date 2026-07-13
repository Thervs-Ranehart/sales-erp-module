<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingRule extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'pricing_rule_id';

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

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'pricing_rule_id', 'pricing_rule_id');
    }
}
