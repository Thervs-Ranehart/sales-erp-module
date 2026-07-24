<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCaseEvent extends Model
{
    protected $primaryKey = 'event_id';

    public $timestamps = false;

    protected $fillable = ['ticket_id', 'employee_id', 'event_type', 'description', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
