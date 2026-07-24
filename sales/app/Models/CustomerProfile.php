<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $table = 'customer_profiles';

    protected $primaryKey = 'profile_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'gender',
        'birth_date',
        'preferred_contact',
        'preferred_product_category',
        'marketing_consent',
        'preferences',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'marketing_consent' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
