<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';

    protected $primaryKey = 'request_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'request_number',
        'ticket_id',
        'technician_id',
        'request_type',
        'requested_at',
        'scheduled_date',
        'scheduled_end',
        'schedule_notes',
        'completion_date',
        'service_status',
    ];

    protected $casts = [
        'ticket_id' => 'integer',
        'technician_id' => 'integer',
        'requested_at' => 'datetime',
        'scheduled_date' => 'datetime',
        'scheduled_end' => 'datetime',
        'completion_date' => 'datetime',
    ];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }

    public function technician()
    {
        return $this->belongsTo(Employee::class, 'technician_id', 'employee_id');
    }

    public function serviceContract()
    {
        return $this->hasOneThrough(
            ServiceContract::class,
            SupportTicket::class,
            'ticket_id',
            'contract_id',
            'ticket_id',
            'service_contract_id',
        );
    }

    public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,
            SupportTicket::class,
            'ticket_id',
            'customer_id',
            'ticket_id',
            'customer_id',
        );
    }

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            SupportTicket::class,
            'ticket_id',
            'product_id',
            'ticket_id',
            'product_id',
        );
    }
}
