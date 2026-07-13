<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class SalesOrderItem extends Model
{
    protected $table = 'sales_order_items';

    protected $primaryKey = 'order_item_id';

    protected $keyType = 'int';

    public $incrementing = true;

=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderItem extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'order_item_id';

>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    protected $casts = [
<<<<<<< HEAD
        'quantity' => 'integer',
=======
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

<<<<<<< HEAD
    public function order()
=======
    public function order(): BelongsTo
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
    {
        return $this->belongsTo(SalesOrder::class, 'order_id', 'order_id');
    }

<<<<<<< HEAD
    public function product()
=======
    public function product(): BelongsTo
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
<<<<<<< HEAD

=======
>>>>>>> 99478f68ab3bb967d67ce05bf50f595c48e8f13b
