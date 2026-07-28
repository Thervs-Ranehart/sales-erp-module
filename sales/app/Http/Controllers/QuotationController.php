<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Services\PricingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(private readonly PricingCalculator $pricingCalculator) {}

    public function index(): View
    {
        Quotation::query()
            ->whereIn('quotation_status', ['draft', 'sent'])
            ->whereDate('valid_until', '<', now()->toDateString())
            ->update(['quotation_status' => 'expired']);

        $quotations = Quotation::query()
            ->with('customer')
            ->withCount('salesOrders')
            ->orderByDesc('quotation_date')
            ->orderByDesc('quotation_id')
            ->get();

        $statusCounts = [
            'all' => $quotations->count(),
            'draft' => $quotations->where('quotation_status', 'draft')->count(),
            'sent' => $quotations->where('quotation_status', 'sent')->count(),
            'accepted' => $quotations->where('quotation_status', 'accepted')->count(),
            'rejected' => $quotations->where('quotation_status', 'rejected')->count(),
            'expired' => $quotations->where('quotation_status', 'expired')->count(),
        ];

        return view('sales.quotations', compact('quotations', 'statusCounts'));
    }

    public function create(): View
    {
        return view(
            'sales.create-quotation',
            array_merge(
                $this->formData(),
                [
                    'quotation' => null,
                ]
            )
        );
    }

    public function store(StoreQuotationRequest $request): RedirectResponse
    {
        $quotation = DB::transaction(function () use ($request): Quotation {

            $totals = $this->calculateTotals($request->validated());

            $quotation = Quotation::query()->create([
                'quotation_number' => null,
                'customer_id' => $request->integer('customer_id'),
                'employee_id' => $this->resolveEmployeeId(),
                'pricing_rule_id' => null,
                'quotation_date' => $request->input('quotation_date'),
                'valid_until' => $request->input('valid_until'),
                'quotation_status' => $request->input('status'),
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'tax' => $totals['tax'],
                'shipping_fee' => 0,
                'total_amount' => $totals['total'],
            ]);
            $quotation->update([
                'quotation_number' => 'QT-'.str_pad((string) $quotation->quotation_id, 5, '0', STR_PAD_LEFT),
            ]);

            $this->syncQuotationItems($quotation, $totals);

            return $quotation;
        });

        return redirect()
            ->route('quotations.index')
            ->with(
                'success',
                "Quotation {$quotation->quotation_number} created successfully."
            );
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load([
            'customer',
            'items.product',
        ]);

        return view('sales.quotation-profile', [
            'quotation' => $quotation,
            'convertedOrder' => SalesOrder::query()->where('quotation_id', $quotation->quotation_id)->first(),
        ]);
    }

    public function convert(Quotation $quotation): RedirectResponse
    {
        if (strtolower((string) $quotation->quotation_status) !== 'accepted') {
            return back()->withErrors(['quotation' => 'Only accepted quotations can be converted to a sales order.']);
        }

        if ($quotation->valid_until?->isPast()) {
            return back()->withErrors(['quotation' => 'This quotation has expired and cannot be converted.']);
        }

        $order = DB::transaction(function () use ($quotation): SalesOrder {
            $quotation = Quotation::query()->with('items')->lockForUpdate()->findOrFail($quotation->quotation_id);

            return $this->createSalesOrderFromQuotation($quotation);
        });

        return redirect()->route('sales.profile', $order)->with('success', 'Quotation converted to a sales order successfully.');
    }

    public function edit(Quotation $quotation): View
    {
        if ($quotation->salesOrders()->exists()) {
            abort(409, 'Converted quotations are locked to preserve transaction history.');
        }

        $quotation->load([
            'items.product',
        ]);

        return view(
            'sales.create-quotation',
            array_merge(
                $this->formData(),
                [
                    'quotation' => $quotation,
                ]
            )
        );
    }

    public function update(
        UpdateQuotationRequest $request,
        Quotation $quotation
    ): RedirectResponse {
        if ($quotation->salesOrders()->exists()) {
            return back()->withErrors(['quotation' => 'Converted quotations cannot be changed.']);
        }

        $allowedTransitions = [
            'draft' => ['sent', 'rejected'],
            'sent' => ['accepted', 'rejected', 'expired'],
            'accepted' => [],
            'rejected' => [],
            'expired' => ['sent'],
        ];
        $currentStatus = strtolower((string) $quotation->quotation_status);
        $newStatus = strtolower((string) $request->input('status'));

        if ($newStatus !== $currentStatus && ! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true)) {
            return back()->withErrors([
                'status' => 'A quotation cannot move from '.ucfirst($currentStatus).' to '.ucfirst($newStatus).'.',
            ]);
        }

        if ($currentStatus === 'accepted') {
            return back()->withErrors(['quotation' => 'Accepted quotations are locked to preserve transaction history.']);
        }

        if ($newStatus === 'sent' && $request->date('valid_until')?->isBefore(today())) {
            return back()->withErrors([
                'valid_until' => 'Extend the valid-until date to today or later before reopening a quotation.',
            ]);
        }

        DB::transaction(function () use ($request, $quotation): void {
            $quotation = Quotation::query()
                ->lockForUpdate()
                ->findOrFail($quotation->quotation_id);

            $totals = $this->calculateTotals($request->validated());

            $quotation->update([
                'customer_id' => $request->integer('customer_id'),
                'pricing_rule_id' => null,
                'quotation_date' => $request->input('quotation_date'),
                'valid_until' => $request->input('valid_until'),
                'quotation_status' => $request->input('status'),
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'tax' => $totals['tax'],
                'shipping_fee' => 0,
                'total_amount' => $totals['total'],
            ]);

            $quotation->items()->delete();

            $this->syncQuotationItems($quotation, $totals);
        });

        return redirect()
            ->route('quotations.index')
            ->with(
                'success',
                "Quotation {$quotation->quotation_number} updated successfully."
            );
    }

    public function updateStatus(Request $request, Quotation $quotation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:sent,accepted,rejected,expired'],
            'convert_to_order' => ['nullable', 'boolean'],
        ]);
        $newStatus = strtolower((string) $validated['status']);
        $convertToOrder = $request->boolean('convert_to_order');

        $order = DB::transaction(function () use ($quotation, $newStatus, $convertToOrder): ?SalesOrder {
            $quotation = Quotation::query()
                ->lockForUpdate()
                ->findOrFail($quotation->quotation_id);

            if ($quotation->salesOrders()->exists()) {
                abort(409, 'Converted quotations cannot be changed.');
            }

            $allowedTransitions = [
                'draft' => ['sent', 'rejected'],
                'sent' => ['accepted', 'rejected', 'expired'],
            ];
            $currentStatus = strtolower((string) $quotation->quotation_status);

            if (! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true)) {
                abort(422, 'This quotation cannot move from '.ucfirst($currentStatus).' to '.ucfirst($newStatus).'.');
            }

            $quotation->update(['quotation_status' => $newStatus]);

            return $newStatus === 'accepted' && $convertToOrder
                ? $this->createSalesOrderFromQuotation($quotation)
                : null;
        });

        $message = $order
            ? "Quotation {$quotation->quotation_number} accepted and sales order {$order->order_number} created successfully."
            : ($newStatus === 'accepted'
                ? "Quotation {$quotation->quotation_number} accepted. You can convert it to a sales order when ready."
                : "Quotation {$quotation->quotation_number} marked as ".ucfirst($newStatus).'.');

        if ($order) {
            return redirect()->route('sales.profile', $order)->with('success', $message);
        }

        return redirect()->route('quotations.index')->with('success', $message);
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        if ($quotation->salesOrders()->exists() || strtolower((string) $quotation->quotation_status) === 'accepted') {
            return back()->withErrors(['quotation' => 'Accepted or converted quotations must be retained for transaction history.']);
        }

        $quotationNumber = $quotation->quotation_number;

        DB::transaction(function () use ($quotation): void {
            $quotation->delete();
        });

        return redirect()
            ->route('quotations.index')
            ->with(
                'success',
                "Quotation {$quotationNumber} deleted successfully."
            );
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

            'productCategories' => Product::query()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),

        ];
    }

    private function resolveEmployeeId(): int
    {
        return (int) (request()->session()->get('employee_id') ?? Employee::query()->value('employee_id') ?? 1);
    }

    /**
     * @param  array<string,mixed>  $data
     * @return array{subtotal:float,discount:float,tax:float,total:float}
     */
    private function calculateTotals(array $data): array
    {
        return $this->pricingCalculator->calculate(
            $data['product_id'],
            $data['qty'],
            $data['quotation_date'],
            null,
            isset($data['discount']) ? (float) $data['discount'] : null,
            isset($data['tax']) ? (float) $data['tax'] : null,
        );
    }

    /**
     * @param  array<string,mixed>  $data
     */
    private function syncQuotationItems(
        Quotation $quotation,
        array $data
    ): void {

        foreach ($data['items'] as $item) {
            $quotation->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => 0,
                'subtotal' => $item['subtotal'],
            ]);
        }
    }

    private function createSalesOrderFromQuotation(Quotation $quotation): SalesOrder
    {
        $existingOrder = SalesOrder::query()
            ->where('quotation_id', $quotation->quotation_id)
            ->first();

        if ($existingOrder) {
            return $existingOrder;
        }

        $quotation->loadMissing('items');

        $order = SalesOrder::query()->create([
            'order_number' => null,
            'quotation_id' => $quotation->quotation_id,
            'customer_id' => $quotation->customer_id,
            'employee_id' => $quotation->employee_id,
            'pricing_rule_id' => $quotation->pricing_rule_id,
            'order_date' => now()->toDateString(),
            'order_status' => 'pending',
            'subtotal' => $quotation->subtotal,
            'discount' => $quotation->discount,
            'tax' => $quotation->tax,
            'shipping_fee' => $quotation->shipping_fee ?? 0,
            'total_amount' => $quotation->total_amount,
        ]);
        $order->update([
            'order_number' => 'SO-'.str_pad((string) $order->order_id, 5, '0', STR_PAD_LEFT),
        ]);

        foreach ($quotation->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount ?? 0,
                'subtotal' => $item->subtotal,
            ]);
        }

        return $order;
    }
}
