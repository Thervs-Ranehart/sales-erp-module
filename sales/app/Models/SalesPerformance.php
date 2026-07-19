<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPerformance extends Model
{
    protected $table = 'sales_performance';

    protected $primaryKey = 'performance_id';

    public $timestamps = false;

    protected $fillable = ['employee_id', 'evaluation_month', 'evaluation_year', 'actual_orders', 'actual_revenue', 'target_achievement', 'performance_status', 'evaluated_at'];

    protected function casts(): array
    {
        return ['actual_orders' => 'integer', 'actual_revenue' => 'decimal:2', 'target_achievement' => 'decimal:2', 'evaluated_at' => 'datetime'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
