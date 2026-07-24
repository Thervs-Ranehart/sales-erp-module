<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';

    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'notification_type', 'title', 'message',
        'related_module', 'related_record_id', 'is_read', 'created_at',
    ];
}
