<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $primaryKey = 'ticket_id';
    public $incrementing = true;

    // Migration defines only created_at (no updated_at)
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'customer_id',
        'product_id',
        'ticket_type',
        'subject',
        'description',
        'priority',
        'status',
        'created_at',
        'due_date',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function ticketAssignments()
    {
        return $this->hasMany(TicketAssignment::class, 'ticket_id', 'ticket_id');
    }

    public function warrantyClaims()
    {
        return $this->hasMany(WarrantyClaim::class, 'ticket_id', 'ticket_id');
    }

    public function resolutionTrackings()
    {
        return $this->hasMany(ResolutionTracking::class, 'ticket_id', 'ticket_id');
    }

    public function satisfactionMonitorings()
    {
        return $this->hasMany(SatisfactionMonitoring::class, 'ticket_id', 'ticket_id');
    }
}

