<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    protected $primaryKey = 'campaign_id';

    protected $fillable = ['created_by', 'campaign_name', 'objective', 'channel', 'target_segment', 'target_loyalty_tier', 'status', 'scheduled_at', 'message'];

    protected $casts = ['scheduled_at' => 'datetime'];

    public function recipients()
    {
        return $this->hasMany(CampaignRecipient::class, 'campaign_id', 'campaign_id');
    }
}
