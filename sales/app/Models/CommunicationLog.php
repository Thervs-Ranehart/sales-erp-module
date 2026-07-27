<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    protected $table = 'communication_logs';

    protected $primaryKey = 'communication_id';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'employee_id',
<<<<<<< HEAD
        'agent_id',
        'communication_date',
=======
>>>>>>> 967f71e2833320541d2f7a12bceccdcfdaac7ba2
        'communication_channel',
        'subject',
        'notes',
        'communication_status',
        'priority',
        'communication_date',
        'follow_up_date',
        'automation_key',
        'recurrence',
        'retention_outcome',
    ];

    protected $casts = [
        'communication_date' => 'datetime',
        'follow_up_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id',
            'customer_id'
        );
    }

    public function agent()
    {
        return $this->belongsTo(
            Agent::class,
            'agent_id',
            'agent_id'
        );
    }


    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'employee_id'
        );
    }
}