<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditNote extends Model
{
    protected $primaryKey = 'credit_note_id';

    protected $fillable = [
        'invoice_id', 'created_by', 'approved_by', 'credit_note_number',
        'status', 'reason', 'amount', 'issued_at',
    ];

    protected $casts = ['amount' => 'decimal:2', 'issued_at' => 'datetime'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class, 'credit_note_id', 'credit_note_id');
    }
}
