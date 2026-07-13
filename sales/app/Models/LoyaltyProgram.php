<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    protected $table = 'loyalty_programs';

    protected $primaryKey = 'loyalty_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'membership_level',
        'points_earned',
        'points_redeemed',
        'available_points',
        'enrollment_date',
    ];

    protected $casts = [
        'points_earned' => 'integer',
        'points_redeemed' => 'integer',
        'available_points' => 'integer',
        'enrollment_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function getRouteKeyName(): string
    {
        return 'loyalty_id';
    }
}

