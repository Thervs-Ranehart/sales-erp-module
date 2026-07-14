<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResolutionTracking extends Model
{
    protected $table = 'resolution_tracking';

    protected $primaryKey = 'resolution_id';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'resolved_by',
        'resolution_summary',
        'root_cause',
        'corrective_action',
        'resolution_time_hours',
        'resolved_at',
    ];

    protected $casts = [
        'resolution_time_hours' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'resolved_by', 'employee_id');
    }
}

