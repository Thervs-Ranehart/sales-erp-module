<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastWorkflowEvent extends Model
{
    protected $primaryKey = 'event_id';

    public $timestamps = false;

    protected $fillable = ['subject_type', 'subject_id', 'employee_id', 'event_type', 'description', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];
}
