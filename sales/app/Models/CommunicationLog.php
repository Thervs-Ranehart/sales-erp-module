<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    protected $table = 'communication_logs';

    protected $primaryKey = 'communication_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * The communication_logs table intentionally has no created_at or updated_at columns.
     */
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'employee_id',
        'communication_date',
        'communication_channel',
        'subject',
        'notes',
        'follow_up_date',
        'communication_status',
    ];

    protected $casts = [
        'communication_date' => 'datetime',
        'follow_up_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function getRouteKeyName(): string
    {
        return 'communication_id';
    }
}

s