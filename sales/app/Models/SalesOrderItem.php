<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderItem extends Model
{
    protected $table = 'sales_order_items';

    protected $primaryKey = 'order_item_id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'discount'   => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}