<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'customer_id');
    }

    public function getFullNameAttribute(): string
{
    return trim("{$this->first_name} {$this->last_name}");
}
}
