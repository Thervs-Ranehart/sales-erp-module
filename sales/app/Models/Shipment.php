<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $primaryKey = 'shipment_id';

    protected $fillable = [
        'order_id', 'created_by', 'shipment_number', 'carrier', 'tracking_number',
        'shipment_status', 'shipped_at', 'delivered_at', 'proof_of_delivery', 'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id', 'shipment_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by', 'employee_id');
    }
}
