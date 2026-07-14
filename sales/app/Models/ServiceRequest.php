<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';

    protected $primaryKey = 'request_id';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'request_type',
        'scheduled_date',
        'completion_date',
        'service_status',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }
}

