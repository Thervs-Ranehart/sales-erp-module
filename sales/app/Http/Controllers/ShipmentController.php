<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalesAuditLog;
use App\Models\SalesOrder;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShipmentController extends Controller
{
    public function store(Request $request, SalesOrder $salesOrder): RedirectResponse
    {
        $data = $request->validate([
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'shipment_status' => ['required', 'in:Packed,Shipped,Delivered'],
            'proof_of_delivery' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $shipment = DB::transaction(function () use ($data, $salesOrder): Shipment {
            $salesOrder = SalesOrder::query()->with('items')->lockForUpdate()->findOrFail($salesOrder->order_id);
            $selected = collect($data['quantities'])->filter(fn ($quantity) => (int) $quantity > 0);

            if ($selected->isEmpty()) {
                throw ValidationException::withMessages(['quantities' => 'Enter a quantity for at least one product.']);
            }

            foreach ($selected as $orderItemId => $quantity) {
                $item = $salesOrder->items->firstWhere('order_item_id', (int) $orderItemId);
                $remaining = $item ? (int) $item->quantity - $salesOrder->shippedQuantityFor((int) $orderItemId) : -1;
                if (! $item || (int) $quantity > $remaining) {
                    throw ValidationException::withMessages(["quantities.$orderItemId" => 'Quantity exceeds the remaining unfulfilled amount.']);
                }
            }

            $employeeId = (int) (request()->session()->get('employee_id') ?? Employee::query()->value('employee_id'));
            $shipment = Shipment::query()->create([
                'order_id' => $salesOrder->order_id,
                'created_by' => $employeeId,
                'shipment_number' => null,
                'carrier' => $data['carrier'] ?? null,
                'tracking_number' => $data['tracking_number'] ?? null,
                'shipment_status' => $data['shipment_status'],
                'shipped_at' => in_array($data['shipment_status'], ['Shipped', 'Delivered'], true) ? now() : null,
                'delivered_at' => $data['shipment_status'] === 'Delivered' ? now() : null,
                'proof_of_delivery' => $data['proof_of_delivery'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
            $shipment->update(['shipment_number' => 'SHP-'.str_pad((string) $shipment->shipment_id, 5, '0', STR_PAD_LEFT)]);

            foreach ($selected as $orderItemId => $quantity) {
                $shipment->items()->create(['order_item_id' => $orderItemId, 'quantity' => $quantity]);
            }

            $this->synchronizeOrderStatus($salesOrder);
            SalesAuditLog::record($shipment, 'shipment_created', null, $shipment->fresh()->toArray(), $data['notes'] ?? null);

            return $shipment;
        });

        return back()->with('success', "{$shipment->shipment_number} created successfully.");
    }

    public function update(Request $request, Shipment $shipment): RedirectResponse
    {
        $data = $request->validate([
            'shipment_status' => ['required', 'in:Packed,Shipped,Delivered,Cancelled'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'proof_of_delivery' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data, $shipment): void {
            $oldValues = $shipment->toArray();
            $shipment->update([
                ...$data,
                'shipped_at' => in_array($data['shipment_status'], ['Shipped', 'Delivered'], true) ? ($shipment->shipped_at ?? now()) : null,
                'delivered_at' => $data['shipment_status'] === 'Delivered' ? ($shipment->delivered_at ?? now()) : null,
            ]);
            $this->synchronizeOrderStatus($shipment->order);
            SalesAuditLog::record($shipment, 'shipment_updated', $oldValues, $shipment->fresh()->toArray(), $data['notes'] ?? null);
        });

        return back()->with('success', 'Shipment updated successfully.');
    }

    private function synchronizeOrderStatus(SalesOrder $order): void
    {
        $order->load('items');
        $ordered = (int) $order->items->sum('quantity');
        $fulfilled = (int) Shipment::query()
            ->where('order_id', $order->order_id)
            ->where('shipment_status', '!=', 'Cancelled')
            ->with('items')
            ->get()
            ->sum(fn (Shipment $shipment) => $shipment->items->sum('quantity'));
        $allDelivered = $fulfilled >= $ordered && ! Shipment::query()
            ->where('order_id', $order->order_id)
            ->whereNotIn('shipment_status', ['Delivered', 'Cancelled'])
            ->exists();

        if ($allDelivered && $ordered > 0) {
            $order->update(['order_status' => 'delivered']);
        } elseif ($fulfilled > 0) {
            $order->update(['order_status' => 'shipped']);
        }
    }
}
