<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\PricingRule;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Services\PricingCalculator;
use Illuminate\Http\RedirectResponse;
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
        $quotation = DB::transaction(function () use ($request) {

            $totals = $this->calculateTotals($request->validated());

            $quotation = Quotation::query()->create([
                'quotation_number' => null,
                'customer_id' => $request->integer('customer_id'),
                'employee_id' => $this->resolveEmployeeId(),
                'pricing_rule_id' => $request->input('pricing_rule_id') ?: null,
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
            $existingOrder = SalesOrder::query()->where('quotation_id', $quotation->quotation_id)->first();

            if ($existingOrder) {
                return $existingOrder;
            }

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
            'expired' => [],
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

        DB::transaction(function () use ($request, $quotation): void {

            $totals = $this->calculateTotals($request->validated());

            $quotation->update([
                'customer_id' => $request->integer('customer_id'),
                'pricing_rule_id' => $request->input('pricing_rule_id') ?: null,
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
     * @param  array<string,mixed>  $data
     * @return array{subtotal:float,discount:float,tax:float,total:float}
     */
    private function calculateTotals(array $data): array
    {
        return $this->pricingCalculator->calculate(
            $data['product_id'],
            $data['qty'],
            $data['quotation_date'],
            ! empty($data['pricing_rule_id']) ? (int) $data['pricing_rule_id'] : null,
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
}
