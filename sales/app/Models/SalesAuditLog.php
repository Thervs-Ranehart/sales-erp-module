<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesAuditLog extends Model
{
    protected $primaryKey = 'audit_id';

    public $timestamps = false;

    protected $fillable = [
        'employee_id', 'auditable_type', 'auditable_id', 'action',
        'old_values', 'new_values', 'reason', 'created_at',
    ];

    protected $casts = ['old_values' => 'array', 'new_values' => 'array', 'created_at' => 'datetime'];

    public static function record(Model $model, string $action, ?array $oldValues = null, ?array $newValues = null, ?string $reason = null): self
    {
        return static::query()->create([
            'employee_id' => request()->session()->get('employee_id'),
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'reason' => $reason,
            'created_at' => now(),
        ]);
    }
}
