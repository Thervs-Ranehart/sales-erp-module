<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanningAction extends Model
{
    protected $primaryKey = 'planning_action_id';

    protected $fillable = ['recommendation_id', 'action_type', 'title', 'assigned_to', 'assigned_department', 'due_date', 'status'];

    protected $casts = ['due_date' => 'date'];

    public function recommendation()
    {
        return $this->belongsTo(ForecastRecommendation::class, 'recommendation_id', 'recommendation_id');
    }
}
