<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesReport extends Model
{
    protected $primaryKey = 'report_id';

    public $timestamps = false;

    protected $fillable = ['report_name', 'report_type', 'report_period_start', 'report_period_end', 'generated_by', 'generated_at'];

    protected function casts(): array
    {
        return ['report_period_start' => 'date', 'report_period_end' => 'date', 'generated_at' => 'datetime'];
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ReportMetric::class, 'report_id', 'report_id');
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'generated_by', 'employee_id');
    }
}
