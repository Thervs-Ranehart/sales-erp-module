<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomerSegmentationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $segment = $request->query('segment');
        $frequency = $request->query('frequency');

        $query = CustomerSegment::query()->with(['customer']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('segment_name', 'like', "%{$search}%")
                    ->orWhere('spending_category', 'like', "%{$search}%")
                    ->orWhere('purchase_frequency', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($segment && $segment !== 'All Segments') {
            $query->where('segment_name', $segment);
        }

        if ($frequency) {
            $query->where('purchase_frequency', $frequency);
        }

        $records = $query->orderByDesc('last_updated')->paginate(10)->withQueryString();

        return view('crm.customer-segmentation', [
            'segments' => $records,
            'search' => $search,
            'segment' => $segment,
            'frequency' => $frequency,
            'highValueCount' => CustomerSegment::where('segment_name', 'High-Value')->count(),
            'regularCount' => CustomerSegment::where('segment_name', 'Regular')->count(),
            'atRiskCount' => CustomerSegment::where('segment_name', 'At-Risk')->count(),
        ]);
    }

    /**
     * Recompute every customer's segment from their actual Sales Order
     * history (App\Models\SalesOrder) instead of relying on independently,
     * manually-maintained customer_segments rows.
     *
     * Rules:
     * - No sales orders at all           -> skip (not enough data to classify)
     * - No order in the last 90 days     -> At-Risk
     * - Total spent >= 50,000 and 3+ orders -> High-Value
     * - Everything else with any orders  -> Regular
     *
     * Spending category and purchase frequency are derived the same way,
     * from total spend and the average gap between orders.
     */
    public function recalculate(Request $request)
    {
        $customers = Customer::query()
            ->withCount('salesOrders as orders_count')
            ->withSum('salesOrders as total_spent', 'total_amount')
            ->withMax('salesOrders as last_order_date', 'order_date')
            ->withMin('salesOrders as first_order_date', 'order_date')
            ->get();

        $now = Carbon::now();

        foreach ($customers as $customer) {
            $ordersCount = (int) ($customer->orders_count ?? 0);

            if ($ordersCount === 0) {
                // Wala pang sales order ang customer na ito — wala pang
                // sapat na datos para i-classify, huwag galawin.
                continue;
            }

            $totalSpent = (float) ($customer->total_spent ?? 0);
            $lastOrderDate = $customer->last_order_date ? Carbon::parse($customer->last_order_date) : null;
            $firstOrderDate = $customer->first_order_date ? Carbon::parse($customer->first_order_date) : null;

            $daysSinceLastOrder = $lastOrderDate ? $lastOrderDate->diffInDays($now) : null;

            // --- Segment classification ---
            if ($daysSinceLastOrder !== null && $daysSinceLastOrder > 90) {
                $segmentName = 'At-Risk';
            } elseif ($totalSpent >= 50000 && $ordersCount >= 3) {
                $segmentName = 'High-Value';
            } else {
                $segmentName = 'Regular';
            }

            // --- Spending category ---
            $spendingCategory = match (true) {
                $totalSpent >= 50000 => 'High',
                $totalSpent >= 10000 => 'Medium',
                default => 'Low',
            };

            // --- Purchase frequency (average gap between orders) ---
            $purchaseFrequency = 'Occasional';

            if ($ordersCount > 1 && $firstOrderDate && $lastOrderDate) {
                $spanDays = max(1, $firstOrderDate->diffInDays($lastOrderDate));
                $averageGapDays = $spanDays / max(1, $ordersCount - 1);

                $purchaseFrequency = match (true) {
                    $averageGapDays <= 10 => 'Weekly',
                    $averageGapDays <= 45 => 'Monthly',
                    default => 'Occasional',
                };
            }

            CustomerSegment::updateOrCreate(
                ['customer_id' => $customer->customer_id],
                [
                    'segment_name' => $segmentName,
                    'spending_category' => $spendingCategory,
                    'purchase_frequency' => $purchaseFrequency,
                    'last_updated' => $now,
                ]
            );
        }

        return redirect()->route('crm.segmentation')
            ->with('success', 'Customer segments recalculated from sales order data.');
    }
}