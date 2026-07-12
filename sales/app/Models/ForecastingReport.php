<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastingReport extends Model
{
    protected $fillable = ['title', 'period', 'forecast_value'];
}
