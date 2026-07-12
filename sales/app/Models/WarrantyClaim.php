<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    protected $fillable = ['claim_number', 'customer_id', 'status', 'details'];
}
