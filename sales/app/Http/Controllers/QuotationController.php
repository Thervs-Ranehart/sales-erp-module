<?php




namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PricingRule;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class QuotationController extends Controller
{
    public function index(): View
    {
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
                'quotation_number' => $this->generateQuotationNumber(),
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

            $this->syncQuotationItems(
                $quotation,
                $request->validated()
            );

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
]);
    }

    public function edit(Quotation $quotation): View
    {
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

            $this->syncQuotationItems(
                $quotation,
                $request->validated()
            );
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
        private function generateQuotationNumber(): string
    {
        $latestId = Quotation::query()->max('quotation_id') ?? 0;

        return 'QT-' . str_pad((string) ($latestId + 1), 3, '0', STR_PAD_LEFT);
    }

    private function resolveEmployeeId(): int
    {
        return (int) (Employee::query()->value('employee_id') ?? 1);
    }

    /**
     * @param array<string,mixed> $data
     * @return array{subtotal:float,discount:float,tax:float,total:float}
     */
    private function calculateTotals(array $data): array
    {
        $subtotal = 0;

        foreach ($data['product_id'] as $index => $productId) {

            $qty = (float) ($data['qty'][$index] ?? 0);
            $price = (float) ($data['price'][$index] ?? 0);

            $subtotal += $qty * $price;
        }

        $discountPercent = (float) ($data['discount'] ?? 0);
        $taxPercent = (float) ($data['tax'] ?? 12);

        if (!empty($data['pricing_rule_id'])) {

            $pricingRule = PricingRule::find($data['pricing_rule_id']);

            if ($pricingRule) {

                if ($pricingRule->discount_value !== null && $discountPercent == 0) {
                    $discountPercent = (float) $pricingRule->discount_value;
                }

                if ($pricingRule->tax_rate !== null && ($data['tax'] ?? null) === null) {
                    $taxPercent = (float) $pricingRule->tax_rate;
                }
            }
        }

        $discountAmount = round($subtotal * ($discountPercent / 100), 2);

        $taxable = max($subtotal - $discountAmount, 0);

        $taxAmount = round($taxable * ($taxPercent / 100), 2);

        $total = round(
            $subtotal -
            $discountAmount +
            $taxAmount,
            2
        );

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => $discountAmount,
            'tax' => $taxAmount,
            'total' => $total,
        ];
    }

    /**
     * @param array<string,mixed> $data
     */
    private function syncQuotationItems(
        Quotation $quotation,
        array $data
    ): void {

        foreach ($data['product_id'] as $index => $productId) {

            $qty = (int) ($data['qty'][$index] ?? 0);
            $price = (float) ($data['price'][$index] ?? 0);

            $subtotal = round($qty * $price, 2);

            $quotation->items()->create([
                'product_id' => $productId,
                'quantity' => $qty,
                'unit_price' => $price,
                'discount' => 0,
                'subtotal' => $subtotal,
            ]);
        }
    }
}