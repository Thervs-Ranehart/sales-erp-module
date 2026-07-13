<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'category',
        'description',
        'unit_price',
        'stock_quantity',
        'product_status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function salesOrderItems(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class, 'product_id', 'product_id');
    }
}
