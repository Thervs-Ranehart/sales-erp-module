<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRecipient extends Model
{
    protected $primaryKey = 'recipient_id';

    protected $fillable = ['campaign_id', 'customer_id', 'delivery_status', 'sent_at', 'responded_at', 'converted_at'];

    protected $casts = ['sent_at' => 'datetime', 'responded_at' => 'datetime', 'converted_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
