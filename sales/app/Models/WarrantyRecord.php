<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantyRecord extends Model
{
    protected $table = 'warranty_records';

    protected $primaryKey = 'warranty_id';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'warranty_number',
        'warranty_start',
        'warranty_end',
        'warranty_status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'warranty_start' => 'date',
        'warranty_end' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,
            SalesOrder::class,
            'order_id',
            'customer_id',
            'order_id',
            'customer_id',
        );
    }

    public function warrantyClaims()
    {
        return $this->hasMany(WarrantyClaim::class, 'warranty_id', 'warranty_id');
    }

    public function currentStatus(): string
    {
        if ($this->warranty_status !== null && $this->warranty_status !== '') {
            return $this->warranty_status;
        }

        if ($this->warranty_end === null) {
            return '—';
        }

        if ($this->warranty_end->isPast()) {
            return 'Expired';
        }

        if ($this->warranty_end->lte(today()->addDays(30))) {
            return 'Expiring Soon';
        }

        return 'Active';
    }
}
