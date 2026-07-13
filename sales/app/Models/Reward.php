<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $primaryKey = 'reward_id';

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'icon',
        'status',
    ];

    protected $casts = [
        'points_required' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'reward_id';
    }

    /**
     * Badge label used by the Blade view.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'available' => 'Available',
            'limited' => 'Limited',
            'unavailable' => 'Unavailable',
            default => ucfirst($this->status),
        };
    }

    /**
     * Badge CSS class used by the Blade view.
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'available' => 'bg-success',
            'limited' => 'bg-warning text-dark',
            'unavailable' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}