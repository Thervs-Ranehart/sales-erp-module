<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $keyType = 'int';

    public $incrementing = true;

=======
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $primaryKey = 'product_id';

>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
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
<<<<<<< HEAD
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

=======
    ];

    public function salesOrderItems(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class, 'product_id', 'product_id');
    }
}
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
