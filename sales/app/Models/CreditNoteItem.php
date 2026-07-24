<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNoteItem extends Model
{
    protected $primaryKey = 'credit_note_item_id';

    public $timestamps = false;

    protected $fillable = ['credit_note_id', 'invoice_item_id', 'quantity', 'amount'];

    protected $casts = ['quantity' => 'integer', 'amount' => 'decimal:2'];

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id', 'invoice_item_id');
    }
}
