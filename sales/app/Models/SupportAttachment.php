<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportAttachment extends Model
{
    protected $primaryKey = 'attachment_id';

    public $timestamps = false;

    protected $fillable = ['ticket_id', 'uploaded_by', 'original_name', 'storage_path', 'mime_type', 'file_size', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id', 'ticket_id');
    }
}
