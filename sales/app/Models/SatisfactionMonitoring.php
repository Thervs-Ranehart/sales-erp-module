<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatisfactionMonitoring extends Model
{
    protected $table = 'satisfaction_monitoring';

    protected $primaryKey = 'feedback_id';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'rating',
        'satisfaction_level',
        'comments',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }
}

