<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPointTransaction extends Model
{
    protected $primaryKey = 'point_transaction_id';

    protected $fillable = ['loyalty_id', 'employee_id', 'transaction_type', 'points', 'balance_after', 'source_type', 'source_id', 'description'];

    protected $casts = ['points' => 'integer', 'balance_after' => 'integer'];
}
