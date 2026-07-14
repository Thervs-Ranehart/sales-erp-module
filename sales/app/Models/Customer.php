<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_no',
        'address',
        'preferences',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(CustomerProfile::class, 'customer_id', 'customer_id');
    }

    public function communicationLogs()
    {
        return $this->hasMany(CommunicationLog::class, 'customer_id', 'customer_id');
    }

    public function loyaltyProgram()
    {
        return $this->hasOne(LoyaltyProgram::class, 'customer_id', 'customer_id');
    }

    public function segments()
    {
        return $this->hasMany(CustomerSegment::class, 'customer_id', 'customer_id');
    }

    public function behaviorAnalyses()
    {
        return $this->hasMany(CustomerBehaviorAnalysis::class, 'customer_id', 'customer_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'customer_id');
    }

    /**
     * Used by quotations and sales orders.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Optional alias so existing CRM code using display_name still works.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name;
    }

    public function getRouteKeyName(): string
    {
        return 'customer_id';
    }
}