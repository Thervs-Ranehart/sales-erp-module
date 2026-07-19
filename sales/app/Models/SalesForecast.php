<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesForecast extends Model
{
    protected $primaryKey = 'forecast_id';

    public $timestamps = false;

    protected $fillable = ['forecast_period_start', 'forecast_period_end', 'forecast_method', 'predicted_orders', 'predicted_revenue', 'predicted_growth', 'confidence_level', 'generated_by', 'generated_at'];

    protected function casts(): array
    {
        return ['forecast_period_start' => 'date', 'forecast_period_end' => 'date', 'predicted_orders' => 'integer', 'predicted_revenue' => 'decimal:2', 'predicted_growth' => 'decimal:2', 'confidence_level' => 'decimal:2', 'generated_at' => 'datetime'];
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(ForecastRecommendation::class, 'forecast_id', 'forecast_id');
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'generated_by', 'employee_id');
    }
}
