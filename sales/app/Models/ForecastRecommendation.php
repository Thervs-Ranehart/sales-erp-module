<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForecastRecommendation extends Model
{
    protected $primaryKey = 'recommendation_id';

    public $timestamps = false;

    protected $fillable = [
        'forecast_id', 'recommendation_type', 'title', 'description', 'priority',
        'implementation_status', 'created_by', 'created_at', 'assigned_to', 'assigned_department',
        'reviewed_by', 'reviewed_at', 'due_date', 'evidence', 'decision_notes', 'outcome', 'completed_at',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime', 'reviewed_at' => 'datetime', 'due_date' => 'date', 'completed_at' => 'datetime'];
    }

    public function forecast(): BelongsTo
    {
        return $this->belongsTo(SalesForecast::class, 'forecast_id', 'forecast_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }

    public function planningActions()
    {
        return $this->hasMany(PlanningAction::class, 'recommendation_id', 'recommendation_id');
    }
}
