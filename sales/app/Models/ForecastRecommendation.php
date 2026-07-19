<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForecastRecommendation extends Model
{
    protected $primaryKey = 'recommendation_id';

    public $timestamps = false;

    protected $fillable = ['forecast_id', 'recommendation_type', 'title', 'description', 'priority', 'implementation_status', 'created_by', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function forecast(): BelongsTo
    {
        return $this->belongsTo(SalesForecast::class, 'forecast_id', 'forecast_id');
    }
}
