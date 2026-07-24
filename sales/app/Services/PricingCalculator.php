<?php

namespace App\Services;

use App\Models\PricingRule;
use App\Models\Product;
use Carbon\CarbonInterface;
use Illuminate\Validation\ValidationException;

class PricingCalculator
{
    /**
     * @param  array<int, mixed>  $productIds
     * @param  array<int, mixed>  $quantities
     * @return array{subtotal: float, discount: float, tax: float, total: float, discount_percent: float, tax_percent: float, items: array<int, array{product_id: int, quantity: int, unit_price: float, subtotal: float}>}
     */
    public function calculate(
        array $productIds,
        array $quantities,
        CarbonInterface|string $transactionDate,
        ?int $pricingRuleId = null,
        ?float $manualDiscountPercent = null,
        ?float $manualTaxPercent = null,
    ): array {
        if (count($productIds) !== count($quantities)) {
            throw ValidationException::withMessages([
                'product_id' => 'Every product must have a matching quantity.',
            ]);
        }

        $products = Product::query()
            ->whereIn('product_id', array_map('intval', $productIds))
            ->get()
            ->keyBy('product_id');

        $items = [];
        $subtotal = 0.0;

        foreach ($productIds as $index => $productId) {
            $product = $products->get((int) $productId);
            $quantity = (int) ($quantities[$index] ?? 0);

            if (! $product || $quantity < 1 || $product->unit_price === null) {
                throw ValidationException::withMessages([
                    "product_id.$index" => 'The selected product is unavailable or has no configured price.',
                ]);
            }

            $unitPrice = round((float) $product->unit_price, 2);
            $lineSubtotal = round($unitPrice * $quantity, 2);
            $subtotal += $lineSubtotal;
            $items[] = [
                'product_id' => (int) $product->product_id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $lineSubtotal,
            ];
        }

        $rule = $this->resolveRule($pricingRuleId, $transactionDate);
        $discountAmount = 0.0;
        $discountPercent = max(0.0, min(100.0, $manualDiscountPercent ?? 0.0));

        if ($rule) {
            if (strcasecmp((string) $rule->discount_type, 'Fixed') === 0) {
                $discountAmount = min($subtotal, round((float) $rule->discount_value, 2));
                $discountPercent = $subtotal > 0 ? ($discountAmount / $subtotal) * 100 : 0.0;
            } else {
                $discountPercent = max(0.0, min(100.0, (float) $rule->discount_value));
            }
        }

        if ($discountAmount === 0.0) {
            $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        }

        $taxPercent = max(0.0, min(100.0, $manualTaxPercent ?? 12.0));
        if ($rule && $rule->tax_rate !== null) {
            $taxPercent = max(0.0, min(100.0, (float) $rule->tax_rate));
        }

        $taxableAmount = max(0.0, $subtotal - $discountAmount);
        $taxAmount = round($taxableAmount * ($taxPercent / 100), 2);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => $discountAmount,
            'tax' => $taxAmount,
            'total' => round($taxableAmount + $taxAmount, 2),
            'discount_percent' => round($discountPercent, 4),
            'tax_percent' => round($taxPercent, 4),
            'items' => $items,
        ];
    }

    private function resolveRule(?int $pricingRuleId, CarbonInterface|string $transactionDate): ?PricingRule
    {
        if (! $pricingRuleId) {
            return null;
        }

        $rule = PricingRule::query()->find($pricingRuleId);
        $date = is_string($transactionDate) ? date('Y-m-d', strtotime($transactionDate)) : $transactionDate->format('Y-m-d');

        if (
            ! $rule
            || ! $rule->isActive()
            || ($rule->start_date && $date < $rule->start_date->format('Y-m-d'))
            || ($rule->end_date && $date > $rule->end_date->format('Y-m-d'))
        ) {
            throw ValidationException::withMessages([
                'pricing_rule_id' => 'The selected pricing rule is inactive or outside its effective dates.',
            ]);
        }

        return $rule;
    }
}
