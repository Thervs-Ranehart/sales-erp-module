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
        'qc_status',
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

    public function resolveQcStatus(): string
    {
        $explicitStatus = strtolower((string) ($this->qc_status ?? ''));
        if (in_array($explicitStatus, ['pending', 'passed', 'failed'], true)) {
            return $explicitStatus;
        }

        $correctiveAction = strtolower((string) ($this->corrective_action ?? ''));
        if (str_contains($correctiveAction, 'fail')) {
            return 'failed';
        }

        if ($correctiveAction === '' || str_contains($correctiveAction, 'pass')) {
            return 'passed';
        }

        if (str_contains($correctiveAction, 'pending')) {
            return 'pending';
        }

        return 'pending';
    }

    public function outcome(): string
    {
        return $this->resolved_at === null ? 'In Review' : 'Resolved';
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'resolved_by', 'employee_id');
    }
}
