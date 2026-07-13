<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $keyType = 'int';

    public $incrementing = true;

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
        'stock_quantity' => 'integer',
    ];

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class, 'product_id', 'product_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'product_id', 'product_id');
    }
}

