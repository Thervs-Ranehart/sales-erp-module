<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceContract extends Model
{
    protected $table = 'service_contracts';

    protected $primaryKey = 'contract_id';
    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'customer_id',
        'product_id',
        'contract_number',
        'service_type',
        'service_start',
        'service_end',
        'contract_status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'service_start' => 'date',
        'service_end' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}

