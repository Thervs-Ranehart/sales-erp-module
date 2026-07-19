<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTarget extends Model
{
    protected $primaryKey = 'target_id';

    public $timestamps = false;

    protected $fillable = ['employee_id', 'target_month', 'target_year', 'sales_target', 'revenue_target', 'created_by', 'created_at'];

    protected function casts(): array
    {
        return ['target_month' => 'integer', 'target_year' => 'integer', 'sales_target' => 'decimal:2', 'revenue_target' => 'decimal:2', 'created_at' => 'datetime'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
