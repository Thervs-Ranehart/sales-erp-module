<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
<<<<<<< HEAD
    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    public $incrementing = true;

    protected $keyType = 'int';

=======
    protected $primaryKey = 'customer_id';

>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_no',
        'address',
        'preferences',
    ];

<<<<<<< HEAD
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

    public function salesOrders()
=======
    public function salesOrders(): HasMany
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'customer_id');
    }

<<<<<<< HEAD
    public function getDisplayNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getRouteKeyName(): string
    {
        return 'customer_id';
    }
=======
    public function getFullNameAttribute(): string
{
    return trim("{$this->first_name} {$this->last_name}");
}
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
}

