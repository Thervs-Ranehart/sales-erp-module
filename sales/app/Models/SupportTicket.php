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
        'service_contract_id',
        'ticket_type',
        'subject',
        'description',
        'priority',
        'status',
        'created_at',
        'due_date',
        'resolved_at',
        'closed_at',
        'department',
        'first_response_due_at',
        'resolution_due_at',
        'escalation_level',
        'last_escalated_at',
        'archived_at',
        'archive_reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'first_response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
        'last_escalated_at' => 'datetime',
        'archived_at' => 'datetime',
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

    public function serviceContract()
    {
        return $this->belongsTo(ServiceContract::class, 'service_contract_id', 'contract_id');
    }

    public function ticketAssignments()
    {
        return $this->hasMany(TicketAssignment::class, 'ticket_id', 'ticket_id')
            ->orderByDesc('assigned_at')
            ->orderByDesc('assignment_id');
    }

    public function latestAssignment()
    {
        return $this->hasOne(TicketAssignment::class, 'ticket_id', 'ticket_id')
            ->whereRaw('LOWER(assignment_status) IN (?, ?)', ['assigned', 'active'])
            ->orderByDesc('assigned_at')
            ->orderByDesc('assignment_id');
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

    public function caseEvents()
    {
        return $this->hasMany(SupportCaseEvent::class, 'ticket_id', 'ticket_id')->orderByDesc('created_at');
    }

    public function attachments()
    {
        return $this->hasMany(SupportAttachment::class, 'ticket_id', 'ticket_id')->orderByDesc('created_at');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function isSlaBreached(): bool
    {
        return ! in_array($this->status, ['Resolved', 'Closed'], true)
            && $this->resolution_due_at?->isPast() === true;
    }
}
