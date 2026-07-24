<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    protected $primaryKey = 'redemption_id';

    protected $fillable = ['redemption_number', 'loyalty_id', 'reward_id', 'processed_by', 'points_used', 'quantity', 'status', 'redeemed_at', 'cancelled_at', 'notes'];

    protected $casts = ['redeemed_at' => 'datetime', 'cancelled_at' => 'datetime'];

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id', 'reward_id');
    }

    public function loyalty()
    {
        return $this->belongsTo(LoyaltyProgram::class, 'loyalty_id', 'loyalty_id');
    }
}
