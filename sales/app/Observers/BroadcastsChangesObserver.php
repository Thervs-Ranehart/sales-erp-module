<?php

namespace App\Observers;

use App\Events\RecordChanged;
use Illuminate\Database\Eloquent\Model;

/**
 * Attach to any model (see AppServiceProvider::boot()) to instantly notify
 * every connected device whenever a row is created, updated, or deleted.
 */
class BroadcastsChangesObserver
{
    public function created(Model $model): void
    {
        $this->broadcast($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->broadcast($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->broadcast($model, 'deleted');
    }

    protected function broadcast(Model $model, string $action): void
    {
        event(new RecordChanged(
            model: class_basename($model),
            action: $action,
            id: $model->getKey(),
            label: $this->labelFor($model),
        ));
    }

    protected function labelFor(Model $model): ?string
    {
        foreach (['order_number', 'invoice_number', 'quotation_number', 'name', 'title', 'product_name'] as $field) {
            if (isset($model->{$field})) {
                return (string) $model->{$field};
            }
        }

        return null;
    }
}
