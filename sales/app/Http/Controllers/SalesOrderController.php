<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderStatusRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\PricingRule;
use App\Models\Product;
use App\Models\SalesApproval;
use App\Models\SalesAuditLog;
use App\Models\SalesOrder;
use App\Models\Shipment;
use App\Services\PricingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalesOrderController extends Controller
{
    public function __construct(private readonly PricingCalculator $pricingCalculator) {}

    public function index(): View
    {
        $orders = SalesOrder::query()
            ->with('customer')
            ->orderByDesc('order_date')
            ->orderByDesc('order_id')
            ->get();

        $statusCounts = [
            'all' => $orders->count(),
            'pending' => $orders->where('order_status', 'pending')->count(),
            'processed' => $orders->where('order_status', 'processed')->count(),
            'shipped' => $orders->where('order_status', 'shipped')->count(),
            'delivered' => $orders->where('order_status', 'delivered')->count(),
        ];

        return view('sales.index', compact('orders', 'statusCounts'));
    }

    public function create(): View
    {
        return view('sales.create-sales-order', $this->formData());
    }

    public function store(StoreSalesOrderRequest $request): RedirectResponse
    {
        $order = DB::transaction(function () use ($request) {
            $totals = $this->calculateTotals($request->validated());

            $order = SalesOrder::query()->create([
                'order_number' => null,
                'customer_id' => $request->integer('customer_id'),
                'employee_id' => $this->resolveEmployeeId(),
                'pricing_rule_id' => $request->input('pricing_rule_id') ?: null,
                'order_date' => $request->input('order_date'),
                'order_status' => $request->input('status'),
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'tax' => $totals['tax'],
                'shipping_fee' => 0,
                'total_amount' => $totals['total'],
            ]);
            $order->update([
                'order_number' => 'SO-'.str_pad((string) $order->order_id, 5, '0', STR_PAD_LEFT),
            ]);

            $this->syncOrderItems($order, $totals['items']);

            return $order;
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "Sales order {$order->order_number} created successfully.");
    }

    public function show(SalesOrder $salesOrder): View
    {
        $salesOrder->load([
            'customer', 'employee', 'pricingRule', 'items.product', 'invoices.items',
            'shipments.items.orderItem.product', 'shipments.creator',
        ]);

        return view('sales.profile', [
            'order' => $salesOrder,
            'auditLogs' => SalesAuditLog::query()
                ->whereIn('auditable_type', [SalesOrder::class, Shipment::class])
                ->where(function ($query) use ($salesOrder): void {
                    $query->where(fn ($orderQuery) => $orderQuery
                        ->where('auditable_type', SalesOrder::class)
                        ->where('auditable_id', $salesOrder->order_id))
                        ->orWhere(fn ($shipmentQuery) => $shipmentQuery
                            ->where('auditable_type', Shipment::class)
                            ->whereIn('auditable_id', $salesOrder->shipments->pluck('shipment_id')));
                })
                ->latest('created_at')
                ->get(),
            'approvals' => SalesApproval::query()
                ->where('approvable_type', Invoice::class)
                ->whereIn('approvable_id', $salesOrder->invoices->pluck('invoice_id'))
                ->latest()
                ->get(),
        ]);
    }

    public function edit(SalesOrder $salesOrder): View
    {
        $salesOrder->load(['items.product']);

        return view('sales.create-sales-order', array_merge(
            $this->formData(),
            ['salesOrder' => $salesOrder]
        ));
    }

    public function update(UpdateSalesOrderRequest $request, SalesOrder $salesOrder): RedirectResponse
    {
        $transitionError = $this->statusTransitionError($salesOrder, (string) $request->input('status'));
        if ($transitionError) {
            return back()->withErrors(['status' => $transitionError])->withInput();
        }

        DB::transaction(function () use ($request, $salesOrder): void {
            $totals = $this->calculateTotals($request->validated());

            $salesOrder->update([
                'customer_id' => $request->integer('customer_id'),
                'pricing_rule_id' => $request->input('pricing_rule_id') ?: null,
                'order_date' => $request->input('order_date'),
                'order_status' => $request->input('status'),
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'tax' => $totals['tax'],
                'total_amount' => $totals['total'],
            ]);

            $salesOrder->items()->delete();
            $this->syncOrderItems($salesOrder, $totals['items']);
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "Sales order {$salesOrder->order_number} updated successfully.");
    }

    public function destroy(SalesOrder $salesOrder): RedirectResponse
    {
        $orderNumber = $salesOrder->order_number;

        DB::transaction(function () use ($salesOrder): void {
            $salesOrder->delete();
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "Sales order {$orderNumber} deleted successfully.");
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_order_ids' => ['required', 'array', 'min:1'],
            'selected_order_ids.*' => ['required', 'integer', 'distinct', 'exists:sales_orders,order_id'],
        ]);

        $orders = SalesOrder::query()
            ->whereKey($validated['selected_order_ids'])
            ->get();

        DB::transaction(function () use ($orders): void {
            $orders->each->delete();
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "{$orders->count()} sales order records deleted successfully.");
    }

    public function updateStatus(UpdateSalesOrderStatusRequest $request, SalesOrder $salesOrder): RedirectResponse
    {
        $transitionError = $this->statusTransitionError($salesOrder, (string) $request->input('status'));
        if ($transitionError) {
            return back()->withErrors(['status' => $transitionError]);
        }

        $oldValues = ['order_status' => $salesOrder->order_status];
        $salesOrder->update([
            'order_status' => $request->input('status'),
        ]);
        SalesAuditLog::record(
            $salesOrder,
            'order_status_updated',
            $oldValues,
            ['order_status' => $salesOrder->order_status]
        );

        return redirect()
            ->route('sales.profile', $salesOrder)
            ->with('success', 'Order status updated successfully.');
    }

    private function formData(): array
    {
        return [
            'customers' => Customer::query()
                ->available()
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(),
            'products' => Product::query()
                ->orderBy('product_name')
                ->get(),
            'pricingRules' => PricingRule::query()
                ->orderBy('rule_name')
                ->get(),
        ];
    }

    private function resolveEmployeeId(): int
    {
        return (int) (request()->session()->get('employee_id') ?? Employee::query()->value('employee_id') ?? 1);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{subtotal: float, discount: float, tax: float, total: float}
     */
    private function calculateTotals(array $data): array
    {
        return $this->pricingCalculator->calculate(
            $data['product_id'],
            $data['qty'],
            $data['order_date'],
            ! empty($data['pricing_rule_id']) ? (int) $data['pricing_rule_id'] : null,
            isset($data['discount']) ? (float) $data['discount'] : null,
            isset($data['tax']) ? (float) $data['tax'] : null,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncOrderItems(SalesOrder $order, array $items): void
    {
        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => 0,
                'subtotal' => $item['subtotal'],
            ]);
        }
    }

    private function statusTransitionError(SalesOrder $salesOrder, string $requestedStatus): ?string
    {
        $allowedTransitions = [
            'pending' => ['processed', 'cancelled'],
            'processed' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ];
        $currentStatus = strtolower((string) $salesOrder->order_status);
        $newStatus = strtolower($requestedStatus);

        if ($newStatus === $currentStatus || in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true)) {
            return null;
        }

        return "An order cannot move from {$salesOrder->formattedStatus()} to ".ucfirst($newStatus).'.';
    }
}
