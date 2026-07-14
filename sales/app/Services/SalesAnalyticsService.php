<?php

namespace App\Services;

use App\Models\SalesOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SalesAnalyticsService
{
    /**
     * Build a read-only reporting snapshot from the existing sales database.
     *
     * @return array<string, mixed>|null
     */
    public function snapshot(int $year): ?array
    {
        if (! Schema::hasTable('sales_orders')) {
            return null;
        }

        $orders = SalesOrder::query()
            ->with(['items.product', 'employee', 'customer'])
            ->whereYear('order_date', $year)
            ->where(function ($query): void {
                $query->whereNull('order_status')->orWhereRaw('LOWER(order_status) != ?', ['cancelled']);
            })
            ->orderBy('order_date')
            ->get();

        if ($orders->isEmpty()) {
            return null;
        }

        $months = collect(range(1, 12));
        $monthlyRevenue = $months->map(fn (int $month): float => (float) $orders
            ->filter(fn (SalesOrder $order): bool => (int) $order->order_date?->month === $month)
            ->sum('total_amount'));
        $monthlyOrders = $months->map(fn (int $month): int => $orders
            ->filter(fn (SalesOrder $order): bool => (int) $order->order_date?->month === $month)
            ->count());

        $productSales = $orders->flatMap->items
            ->groupBy(fn ($item): string => $item->product?->product_name ?? 'Unassigned Product')
            ->map(fn (Collection $items): float => (float) $items->sum('subtotal'))
            ->sortDesc();

        $representativeSales = $orders
            ->groupBy(fn (SalesOrder $order): string => $order->employee?->full_name ?: 'Unassigned Representative')
            ->map(fn (Collection $employeeOrders): float => (float) $employeeOrders->sum('total_amount'))
            ->sortDesc();

        $regionalSales = $orders
            ->groupBy(fn (SalesOrder $order): string => $order->warehouse ?: 'Unassigned Warehouse')
            ->map(fn (Collection $warehouseOrders): float => (float) $warehouseOrders->sum('total_amount'))
            ->sortDesc();

        $targets = collect();
        if (Schema::hasTable('sales_targets')) {
            $targets = DB::table('sales_targets')
                ->leftJoin('employees', 'employees.employee_id', '=', 'sales_targets.employee_id')
                ->where('target_year', $year)
                ->select('sales_targets.*', 'employees.first_name', 'employees.last_name')
                ->get();
        }

        return [
            'year' => $year,
            'orders' => $orders,
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'monthlyRevenue' => $monthlyRevenue->all(),
            'monthlyOrders' => $monthlyOrders->all(),
            'productSales' => $productSales,
            'representativeSales' => $representativeSales,
            'regionalSales' => $regionalSales,
            'targets' => $targets,
            'totalRevenue' => (float) $orders->sum('total_amount'),
            'totalOrders' => $orders->count(),
            'activeCustomers' => $orders->pluck('customer_id')->filter()->unique()->count(),
        ];
    }

    /** @return array{nextMonth: float, growthRate: float, quarter: float, confidence: int} */
    public function forecast(array $monthlyRevenue): array
    {
        $observed = collect($monthlyRevenue)->filter(fn ($value): bool => (float) $value > 0)->values();
        if ($observed->isEmpty()) {
            return ['nextMonth' => 0, 'growthRate' => 0, 'quarter' => 0, 'confidence' => 0];
        }

        $recent = $observed->take(-3);
        $growthRates = $recent->values()->map(function ($value, int $index) use ($recent): ?float {
            if ($index === 0) {
                return null;
            }
            $previous = (float) $recent->values()[$index - 1];

            return $previous > 0 ? (((float) $value - $previous) / $previous) : 0;
        })->filter(fn ($value): bool => $value !== null);

        $growth = (float) ($growthRates->average() ?? 0);
        $latest = (float) $observed->last();
        $nextMonth = max(0, $latest * (1 + $growth));
        $quarter = $nextMonth + ($nextMonth * (1 + $growth)) + ($nextMonth * ((1 + $growth) ** 2));

        return [
            'nextMonth' => round($nextMonth, 2),
            'growthRate' => round($growth * 100, 1),
            'quarter' => round($quarter, 2),
            'confidence' => min(90, 60 + ($observed->count() * 3)),
        ];
    }
}
