<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePricingRuleRequest;
use App\Http\Requests\UpdatePricingRuleRequest;
use App\Models\PricingRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PricingRuleController extends Controller
{
    public function index(): View
    {
        $pricingRules = PricingRule::query()
            ->orderByDesc('pricing_rule_id')
            ->get();

        $statusCounts = [
            'all' => $pricingRules->count(),
            'active' => $pricingRules->where('status', 'Active')->count(),
            'inactive' => $pricingRules->where('status', 'Inactive')->count(),
        ];

        return view('sales.pricing-rules', compact(
            'pricingRules',
            'statusCounts'
        ));
    }

    public function create(): View
    {
        $pricingRule = new PricingRule;

        return view(
            'sales.create-pricing',
            compact('pricingRule')
        );
    }

    public function store(StorePricingRuleRequest $request): RedirectResponse
    {
        PricingRule::create($request->validated());

        return redirect()
            ->route('pricing-rules.index')
            ->with('success', 'Pricing Rule created successfully.');
    }

    public function show(PricingRule $pricingRule): View
    {
        return view('sales.create-pricing', [
            'pricingRule' => $pricingRule,
        ]);
    }

    public function edit(PricingRule $pricingRule): View
    {
        return view(
            'sales.create-pricing',
            compact('pricingRule')
        );
    }

    public function update(
        UpdatePricingRuleRequest $request,
        PricingRule $pricingRule
    ): RedirectResponse {

        $pricingRule->update($request->validated());

        return redirect()
            ->route('pricing-rules.index')
            ->with('success', 'Pricing Rule updated successfully.');
    }

    public function destroy(
        PricingRule $pricingRule
    ): RedirectResponse {
        if ($pricingRule->quotations()->exists() || $pricingRule->salesOrders()->exists()) {
            $pricingRule->update(['status' => 'Inactive']);

            return redirect()
                ->route('pricing-rules.index')
                ->with('success', 'Pricing Rule was deactivated because it is used by existing transactions.');
        }

        $pricingRule->delete();

        return redirect()
            ->route('pricing-rules.index')
            ->with('success', 'Pricing Rule deleted successfully.');
    }
}
