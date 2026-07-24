<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesApproval extends Model
{
    protected $primaryKey = 'approval_id';

    protected $fillable = [
        'requested_by', 'reviewed_by', 'approvable_type', 'approvable_id',
        'action', 'status', 'reason', 'review_notes', 'reviewed_at',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];
}
