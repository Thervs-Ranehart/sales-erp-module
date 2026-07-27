<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';


    protected $fillable = [
        'username',
        'password_hash',
        'first_name',
        'last_name',
        'department',
        'role',
        'hierarchy_level',
        'employee_status',
        'failed_login_attempts',
        'locked_until',
    ];


    protected $casts = [
        'locked_until' => 'datetime',
    ];



    public function salesOrders(): HasMany
    {
        return $this->hasMany(
            SalesOrder::class,
            'employee_id',
            'employee_id'
        );
    }



    public function ticketAssignments(): HasMany
    {
        return $this->hasMany(
            TicketAssignment::class,
            'employee_id',
            'employee_id'
        );
    }



    /**
     * Follow-ups / communication logs assigned to this employee
     */
    public function communicationLogs(): HasMany
    {
        return $this->hasMany(
            CommunicationLog::class,
            'employee_id',
            'employee_id'
        );
    }



    public function getFullNameAttribute(): string
    {
        return trim(
            "{$this->first_name} {$this->last_name}"
        );
    }



    public function isLocked(): bool
    {
        return $this->locked_until !== null 
            && $this->locked_until->isFuture();
    }
}