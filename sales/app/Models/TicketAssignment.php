<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAssignment extends Model
{
    protected $table = 'ticket_assignments';

    protected $primaryKey = 'assignment_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'employee_id',
        'assigned_at',
        'assignment_status',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
