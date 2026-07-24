<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerBehaviorAnalysis extends Model
{
    protected $table = 'customer_behavior_analysis';

    protected $primaryKey = 'analysis_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'analysis_period_start',
        'analysis_period_end',
        'total_orders',
        'total_spent',
        'average_order_value',
        'favorite_product_category',
        'customer_lifetime_value',
        'generated_at',
        'spending_trend', 'trend_percentage', 'predicted_next_purchase',
        'churn_risk_score', 'predicted_90_day_value',
        'recommended_product_category', 'retention_recommendation',
    ];

    protected $casts = [
        'analysis_period_start' => 'date',
        'analysis_period_end' => 'date',
        'total_orders' => 'integer',
        'total_spent' => 'decimal:2',
        'average_order_value' => 'decimal:2',
        'customer_lifetime_value' => 'decimal:2',
        'generated_at' => 'datetime',
        'predicted_next_purchase' => 'date',
        'churn_risk_score' => 'decimal:2',
        'predicted_90_day_value' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Build a Customer Behavior Analysis snapshot for a given customer,
     * using only the customer's already-known Sales Order history
     * (App\Models\SalesOrder / SalesOrderItem). No new tables are used —
     * this reuses the existing customer_behavior_analysis table.
     *
     * The returned array is display-ready for the CRM views, and a copy
     * of the summary is persisted (one row per customer) so the analysis
     * is also queryable/reportable later via the model itself.
     *
     * Expects $customer to have 'salesOrders.items.product' loaded for
     * best performance, but will lazy-load if not.
     */
    public static function generateFor(Customer $customer): array
    {
        $orders = $customer->relationLoaded('salesOrders')
            ? $customer->salesOrders
            : $customer->salesOrders()->with('items.product')->get();

        $totalOrders = $orders->count();
        $totalSpent = (float) $orders->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0.0;

        $sortedOrders = $orders->filter(fn ($order) => $order->order_date)->sortBy('order_date')->values();
        $firstOrderDate = $sortedOrders->first()?->order_date;
        $lastOrderDate = $sortedOrders->last()?->order_date;

        // --- Purchase frequency (how often they buy, based on average gap between orders) ---
        $purchaseFrequency = 'No Orders Yet';
        $averageGapDays = null;

        if ($totalOrders === 1) {
            $purchaseFrequency = 'One-Time Buyer';
        } elseif ($totalOrders > 1 && $firstOrderDate && $lastOrderDate) {
            $spanDays = max(1, Carbon::parse($firstOrderDate)->diffInDays(Carbon::parse($lastOrderDate)));
            $averageGapDays = $spanDays / max(1, $totalOrders - 1);

            $purchaseFrequency = match (true) {
                $averageGapDays <= 10 => 'Weekly',
                $averageGapDays <= 45 => 'Monthly',
                default => 'Occasional',
            };
        }

        // --- Activity status (how recently they last bought) ---
        $daysSinceLastOrder = $lastOrderDate ? Carbon::parse($lastOrderDate)->diffInDays(Carbon::now()) : null;

        $activityStatus = match (true) {
            $totalOrders === 0 => 'No Activity',
            $daysSinceLastOrder !== null && $daysSinceLastOrder <= 30 => 'Active',
            $daysSinceLastOrder !== null && $daysSinceLastOrder <= 90 => 'Slowing Down',
            default => 'At-Risk',
        };

        // --- Favorite product category (by amount spent per category) ---
        $categoryTotals = $orders
            ->flatMap(fn ($order) => $order->items)
            ->groupBy(fn ($item) => $item->product?->category ?: 'Uncategorized')
            ->map(fn ($items) => (float) $items->sum('subtotal'));

        $favoriteCategory = $categoryTotals->isNotEmpty()
            ? $categoryTotals->sortDesc()->keys()->first()
            : '—';

        // --- Buying trend: total spend per month, last 6 months (for the chart/cards) ---
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));

        $trend = $months->map(function ($month) use ($orders) {
            $total = $orders
                ->filter(fn ($order) => $order->order_date && Carbon::parse($order->order_date)->isSameMonth($month))
                ->sum('total_amount');

            return [
                'label' => $month->format('M Y'),
                'total' => round((float) $total, 2),
            ];
        })->values();
        $previousSpend = (float) $trend->take(3)->sum('total');
        $recentSpend = (float) $trend->take(-3)->sum('total');
        $trendPercentage = $previousSpend > 0 ? (($recentSpend - $previousSpend) / $previousSpend) * 100 : ($recentSpend > 0 ? 100 : 0);
        $spendingTrend = $trendPercentage > 10 ? 'Increasing' : ($trendPercentage < -10 ? 'Declining' : 'Stable');
        $predictedNextPurchase = $lastOrderDate && $averageGapDays
            ? Carbon::parse($lastOrderDate)->addDays((int) round($averageGapDays))
            : null;
        $recencyRisk = $daysSinceLastOrder === null ? 100 : min(100, ($daysSinceLastOrder / max(30, $averageGapDays ?? 90)) * 60);
        $trendRisk = $spendingTrend === 'Declining' ? 30 : ($spendingTrend === 'Stable' ? 10 : 0);
        $churnRiskScore = min(100, round($recencyRisk + $trendRisk, 2));
        $predicted90DayValue = $totalOrders > 0 ? round($averageOrderValue * (90 / max(30, $averageGapDays ?? 90)), 2) : 0;
        $retentionRecommendation = match (true) {
            $churnRiskScore >= 70 => 'Schedule a high-priority personal follow-up and targeted win-back offer.',
            $spendingTrend === 'Declining' => 'Send a category-specific incentive and review recent service issues.',
            $spendingTrend === 'Increasing' => 'Recommend complementary products and review loyalty-tier eligibility.',
            default => 'Maintain regular communication and personalized product recommendations.',
        };

        // --- Customer ranking: position by total lifetime spend vs. all customers ---
        $ranking = DB::table('sales_orders')
            ->select('customer_id', DB::raw('SUM(total_amount) as lifetime_spent'))
            ->groupBy('customer_id')
            ->orderByDesc('lifetime_spent')
            ->pluck('customer_id')
            ->values();

        $rankPosition = $ranking->search($customer->customer_id);
        $rank = $rankPosition === false ? null : $rankPosition + 1;
        $rankedCustomerCount = $ranking->count();

        $percentile = ($rank && $rankedCustomerCount > 0)
            ? max(1, 100 - (int) round((($rank - 1) / $rankedCustomerCount) * 100))
            : null;

        $lifetimeValue = $totalSpent;

        // Persist a single, always up-to-date snapshot per customer by
        // reusing this same table/model (no schema changes required).
        static::updateOrCreate(
            ['customer_id' => $customer->customer_id],
            [
                'analysis_period_start' => $firstOrderDate ? Carbon::parse($firstOrderDate)->toDateString() : null,
                'analysis_period_end' => $lastOrderDate ? Carbon::parse($lastOrderDate)->toDateString() : null,
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'average_order_value' => $averageOrderValue,
                'favorite_product_category' => $favoriteCategory,
                'customer_lifetime_value' => $lifetimeValue,
                'generated_at' => Carbon::now(),
                'spending_trend' => $spendingTrend,
                'trend_percentage' => $trendPercentage,
                'predicted_next_purchase' => $predictedNextPurchase?->toDateString(),
                'churn_risk_score' => $churnRiskScore,
                'predicted_90_day_value' => $predicted90DayValue,
                'recommended_product_category' => $favoriteCategory,
                'retention_recommendation' => $retentionRecommendation,
            ]
        );

        return [
            'total_orders' => $totalOrders,
            'total_spent' => $totalSpent,
            'average_order_value' => $averageOrderValue,
            'purchase_frequency' => $purchaseFrequency,
            'activity_status' => $activityStatus,
            'favorite_category' => $favoriteCategory,
            'lifetime_value' => $lifetimeValue,
            'rank' => $rank,
            'ranked_customer_count' => $rankedCustomerCount,
            'percentile' => $percentile,
            'trend' => $trend,
            'generated_at' => Carbon::now(),
            'spending_trend' => $spendingTrend,
            'trend_percentage' => $trendPercentage,
            'predicted_next_purchase' => $predictedNextPurchase,
            'churn_risk_score' => $churnRiskScore,
            'predicted_90_day_value' => $predicted90DayValue,
            'recommended_product_category' => $favoriteCategory,
            'retention_recommendation' => $retentionRecommendation,
        ];
    }
}
