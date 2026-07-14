<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_no',
        'address',
        'preferences',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class, 'customer_id', 'customer_id');
    }

    public function loyaltyProgram(): HasOne
    {
        return $this->hasOne(LoyaltyProgram::class, 'customer_id', 'customer_id');
    }

    public function segments(): HasMany
    {
        return $this->hasMany(CustomerSegment::class, 'customer_id', 'customer_id');
    }

    public function communicationLogs(): HasMany
    {
        return $this->hasMany(CommunicationLog::class, 'customer_id', 'customer_id');
    }

    public function behaviorAnalyses(): HasMany
    {
        return $this->hasMany(CustomerBehaviorAnalysis::class, 'customer_id', 'customer_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'customer_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name;
    }
}