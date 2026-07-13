<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBehaviorAnalysis extends Model
{
    protected $table = 'customer_behavior_analysis';

    protected $primaryKey = 'analysis_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'analysis_period_start',
        'analysis_period_end',
        'total_orders',
        'total_spent',
        'average_order_value',
        'favorite_product_category',
        'customer_lifetime_value',
        'generated_at',
    ];

    protected $casts = [
        'analysis_period_start' => 'date',
        'analysis_period_end' => 'date',
        'total_orders' => 'integer',
        'total_spent' => 'decimal:2',
        'average_order_value' => 'decimal:2',
        'customer_lifetime_value' => 'decimal:2',
        'generated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}

