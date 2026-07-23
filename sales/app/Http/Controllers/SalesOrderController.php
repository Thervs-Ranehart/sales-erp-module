<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderStatusRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\PricingRule;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalesOrderController extends Controller
{
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
                'order_number' => $this->generateOrderNumber(),
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

            $this->syncOrderItems($order, $request->validated());

            return $order;
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "Sales order {$order->order_number} created successfully.");
    }

    public function show(SalesOrder $salesOrder): View
    {
        $salesOrder->load(['customer', 'employee', 'pricingRule', 'items.product', 'invoices']);

        return view('sales.profile', [
            'order' => $salesOrder,
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
            $this->syncOrderItems($salesOrder, $request->validated());
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
        $salesOrder->update([
            'order_status' => $request->input('status'),
        ]);

        return redirect()
            ->route('sales.profile', $salesOrder)
            ->with('success', 'Order status updated successfully.');
    }

    private function formData(): array
    {
        return [
            'customers' => Customer::query()
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

    private function generateOrderNumber(): string
    {
        $latestId = SalesOrder::query()->max('order_id') ?? 0;

        return 'SO-'.str_pad((string) ($latestId + 1), 3, '0', STR_PAD_LEFT);
    }

    private function resolveEmployeeId(): int
    {
        return (int) (Employee::query()->value('employee_id') ?? 1);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{subtotal: float, discount: float, tax: float, total: float}
     */
    private function calculateTotals(array $data): array
    {
        $subtotal = 0.0;

        foreach ($data['product_id'] as $index => $productId) {
            $quantity = (float) ($data['qty'][$index] ?? 0);
            $unitPrice = (float) ($data['price'][$index] ?? 0);
            $subtotal += $quantity * $unitPrice;
        }

        $discountPercent = (float) ($data['discount'] ?? 0);
        $taxPercent = (float) ($data['tax'] ?? 12);

        if (! empty($data['pricing_rule_id'])) {
            $pricingRule = PricingRule::query()->find($data['pricing_rule_id']);

            if ($pricingRule) {
                if ($pricingRule->discount_value !== null && $discountPercent === 0.0) {
                    $discountPercent = (float) $pricingRule->discount_value;
                }

                if ($pricingRule->tax_rate !== null && ($data['tax'] ?? null) === null) {
                    $taxPercent = (float) $pricingRule->tax_rate;
                }
            }
        }

        $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        $taxableAmount = max($subtotal - $discountAmount, 0);
        $taxAmount = round($taxableAmount * ($taxPercent / 100), 2);
        $total = round($subtotal - $discountAmount + $taxAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => $discountAmount,
            'tax' => $taxAmount,
            'total' => $total,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncOrderItems(SalesOrder $order, array $data): void
    {
        foreach ($data['product_id'] as $index => $productId) {
            $quantity = (int) ($data['qty'][$index] ?? 0);
            $unitPrice = (float) ($data['price'][$index] ?? 0);
            $lineSubtotal = round($quantity * $unitPrice, 2);

            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => 0,
                'subtotal' => $lineSubtotal,
            ]);
        }
    }
}
