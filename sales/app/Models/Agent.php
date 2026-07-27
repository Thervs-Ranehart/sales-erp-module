<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    protected $table = 'agents';

    protected $primaryKey = 'agent_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'department',
        'status',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function communicationLogs(): HasMany
    {
        return $this->hasMany(
            CommunicationLog::class,
            'agent_id',
            'agent_id'
        );
    }
}

