<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired whenever a "trackable" model is created, updated, or deleted.
 * Every open browser/device listens on the public "erp-updates" channel
 * and refreshes itself when it receives an event relevant to what it's showing.
 *
 * Uses ShouldBroadcastNow (not ShouldBroadcast) so it pushes out
 * immediately without needing a queue worker running.
 */
class RecordChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public string $model,   // e.g. "SalesOrder", "Invoice", "Product"
        public string $action,  // "created" | "updated" | "deleted"
        public int|string|null $id = null,
        public ?string $label = null, // human readable, e.g. "Order #SO-1029"
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('erp-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'record.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'model' => $this->model,
            'action' => $this->action,
            'id' => $this->id,
            'label' => $this->label,
        ];
    }
}
