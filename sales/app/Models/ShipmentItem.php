<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentItem extends Model
{
    protected $primaryKey = 'shipment_item_id';

    public $timestamps = false;

    protected $fillable = ['shipment_id', 'order_item_id', 'quantity'];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class, 'shipment_id', 'shipment_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class, 'order_item_id', 'order_item_id');
    }
}
