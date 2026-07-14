<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    protected $table = 'warranty_claims';

    protected $primaryKey = 'claim_id';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'warranty_id',
        'ticket_id',
        'claim_reason',
        'claim_status',
        'claim_date',
        'approved_date',
    ];

    protected $casts = [
        'claim_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    public function warrantyRecord()
    {
        return $this->belongsTo(WarrantyRecord::class, 'warranty_id', 'warranty_id');
    }

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }
}

