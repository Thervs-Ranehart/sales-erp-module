<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ServiceContract extends Model
{
    protected $table = 'service_contracts';

    protected $primaryKey = 'contract_id';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'customer_id',
        'product_id',
        'contract_number',
        'service_type',
        'service_start',
        'service_end',
        'contract_status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'service_start' => 'date',
        'service_end' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'service_contract_id', 'contract_id');
    }

    public function serviceRequests()
    {
        return $this->hasManyThrough(
            ServiceRequest::class,
            SupportTicket::class,
            'service_contract_id',
            'ticket_id',
            'contract_id',
            'ticket_id',
        );
    }

    public function currentStatus(): string
    {
        $status = strtolower((string) $this->contract_status);

        if ($status === 'terminated') {
            return 'Terminated';
        }

        if ($status === 'expired' || $this->service_end?->lt(today())) {
            return 'Expired';
        }

        if ($this->service_end?->betweenIncluded(today(), today()->addDays(30))) {
            return 'Expiring Soon';
        }

        if ($status === 'active') {
            return 'Active';
        }

        if ($status === 'expiring') {
            return 'Expiring Soon';
        }

        return $this->contract_status ?: '—';
    }

    public function isCovered(): bool
    {
        return in_array($this->currentStatus(), ['Active', 'Expiring Soon'], true);
    }

    public function scopeExpiringSoon(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $statusQuery): void {
                $statusQuery->whereNull('contract_status')
                    ->orWhereRaw('LOWER(contract_status) IN (?, ?)', ['active', 'expiring']);
            })
            ->whereBetween('service_end', [today(), today()->addDays(30)]);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $statusQuery): void {
                $statusQuery->whereNull('contract_status')
                    ->orWhereRaw('LOWER(contract_status) <> ?', ['terminated']);
            })
            ->where(function (Builder $expiryQuery): void {
                $expiryQuery->whereRaw('LOWER(contract_status) = ?', ['expired'])
                    ->orWhereDate('service_end', '<', today());
            });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $statusQuery): void {
                $statusQuery->whereNull('contract_status')
                    ->orWhereRaw('LOWER(contract_status) NOT IN (?, ?)', ['expired', 'terminated']);
            })
            ->where(function (Builder $startQuery): void {
                $startQuery->whereNull('service_start')
                    ->orWhereDate('service_start', '<=', today());
            })
            ->where(function (Builder $endQuery): void {
                $endQuery->whereNull('service_end')
                    ->orWhereDate('service_end', '>=', today());
            });
    }
}
