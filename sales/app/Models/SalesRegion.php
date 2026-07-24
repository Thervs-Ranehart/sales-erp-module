<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRegion extends Model
{
    protected $primaryKey = 'region_id';

    protected $fillable = ['region_code', 'region_name', 'country', 'status'];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'region_id', 'region_id');
    }
}
