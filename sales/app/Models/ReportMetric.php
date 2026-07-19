<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportMetric extends Model
{
    protected $primaryKey = 'metric_id';

    public $timestamps = false;

    protected $fillable = ['report_id', 'metric_name', 'metric_value', 'remarks'];

    protected function casts(): array
    {
        return ['metric_value' => 'decimal:2'];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(SalesReport::class, 'report_id', 'report_id');
    }
}
