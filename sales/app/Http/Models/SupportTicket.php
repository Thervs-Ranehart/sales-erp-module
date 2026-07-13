<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['ticket_number', 'customer_id', 'status', 'issue'];
}
