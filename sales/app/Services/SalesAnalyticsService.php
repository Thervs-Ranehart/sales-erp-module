<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Product;
use App\Models\SalesOrder;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SalesAnalyticsService
{
    /**
     * Build a filtered reporting snapshot from sales-order records.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function snapshot(int $year, array $filters = []): array
    {
        [$start, $end] = $this->dateRange($year, $filters);

        if (! Schema::hasTable('sales_orders')) {
            return $this->emptySnapshot($start, $end);
        }

        $orders = SalesOrder::query()
            ->with(['items.product', 'employee', 'customer'])
            ->whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
            ->where(fn (Builder $query): Builder => $query
                ->whereNull('order_status')
                ->orWhereRaw('LOWER(order_status) != ?', ['cancelled']))
            ->when($this->selectedId($filters, 'representative'), fn (Builder $query, int $id): Builder => $query->where('employee_id', $id))
            ->when($this->selectedValue($filters, 'region'), fn (Builder $query, string $warehouse): Builder => $query->where('warehouse', $warehouse))
            ->when($this->selectedId($filters, 'product'), fn (Builder $query, int $id): Builder => $query->whereHas('items', fn (Builder $itemQuery): Builder => $itemQuery->where('product_id', $id)))
            ->orderBy('order_date')
            ->get();

        $period = collect(CarbonPeriod::create($start->startOfMonth(), '1 month', $end->startOfMonth()));
        $labels = $period->map(fn ($month): string => $month->format('M Y'))->all();
        $monthlyRevenue = $period->map(fn ($month): float => (float) $orders
            ->filter(fn (SalesOrder $order): bool => $order->order_date?->isSameMonth($month))
            ->sum('total_amount'));
        $monthlyOrders = $period->map(fn ($month): int => $orders
            ->filter(fn (SalesOrder $order): bool => $order->order_date?->isSameMonth($month))
            ->count());

        $items = $orders->flatMap->items;
        $productSales = $items
            ->groupBy(fn ($item): string => $item->product?->product_name ?? 'Unassigned Product')
            ->map(fn (Collection $group): float => (float) $group->sum('subtotal'))
            ->sortDesc();
        $productUnits = $items
            ->groupBy(fn ($item): string => $item->product?->product_name ?? 'Unassigned Product')
            ->map(fn (Collection $group): int => (int) $group->sum('quantity'))
            ->sortDesc();
        $representativeSales = $orders
            ->groupBy(fn (SalesOrder $order): string => $order->employee?->full_name ?: 'Unassigned Representative')
            ->map(fn (Collection $group): float => (float) $group->sum('total_amount'))
            ->sortDesc();
        $representativeOrders = $orders
            ->groupBy(fn (SalesOrder $order): string => $order->employee?->full_name ?: 'Unassigned Representative')
            ->map(fn (Collection $group): int => $group->count());
        $regionalSales = $orders
            ->groupBy(fn (SalesOrder $order): string => $order->warehouse ?: 'Unassigned Warehouse')
            ->map(fn (Collection $group): float => (float) $group->sum('total_amount'))
            ->sortDesc();

        $targets = collect();
        if (Schema::hasTable('sales_targets')) {
            $targets = DB::table('sales_targets')
                ->leftJoin('employees', 'employees.employee_id', '=', 'sales_targets.employee_id')
                ->where(function ($query) use ($start): void {
                    $query->where('target_year', '>', $start->year)
                        ->orWhere(fn ($nested) => $nested->where('target_year', $start->year)->where('target_month', '>=', $start->month));
                })
                ->where(function ($query) use ($end): void {
                    $query->where('target_year', '<', $end->year)
                        ->orWhere(fn ($nested) => $nested->where('target_year', $end->year)->where('target_month', '<=', $end->month));
                })
                ->when($this->selectedId($filters, 'representative'), fn ($query, int $id) => $query->where('sales_targets.employee_id', $id))
                ->select('sales_targets.*', 'employees.first_name', 'employees.last_name')
                ->get();
        }

        $monthlyTargets = $period->map(fn ($month): float => (float) $targets
            ->where('target_year', $month->year)
            ->where('target_month', $month->month)
            ->sum('revenue_target'));

        return [
            'year' => $year,
            'start' => $start,
            'end' => $end,
            'orders' => $orders,
            'labels' => $labels,
            'monthlyRevenue' => $monthlyRevenue->all(),
            'monthlyOrders' => $monthlyOrders->all(),
            'monthlyTargets' => $monthlyTargets->all(),
            'productSales' => $productSales,
            'productUnits' => $productUnits,
            'representativeSales' => $representativeSales,
            'representativeOrders' => $representativeOrders,
            'regionalSales' => $regionalSales,
            'targets' => $targets,
            'totalRevenue' => (float) $orders->sum('total_amount'),
            'totalOrders' => $orders->count(),
            'activeCustomers' => $orders->pluck('customer_id')->filter()->unique()->count(),
        ];
    }

    /** @return array<string, Collection<int, mixed>> */
    public function filterOptions(): array
    {
        if (! Schema::hasTable('sales_orders') || ! Schema::hasTable('products') || ! Schema::hasTable('employees')) {
            return ['products' => collect(), 'representatives' => collect(), 'regions' => collect()];
        }

        return [
            'products' => Product::query()->orderBy('product_name')->get(['product_id', 'product_name']),
            'representatives' => Employee::query()->whereHas('salesOrders')->orderBy('first_name')->get(['employee_id', 'first_name', 'last_name']),
            'regions' => SalesOrder::query()->whereNotNull('warehouse')->distinct()->orderBy('warehouse')->pluck('warehouse'),
        ];
    }

    /** @return array{nextMonth: float, growthRate: float, quarter: float, confidence: int} */
    public function forecast(array $monthlyRevenue): array
    {
        $observed = collect($monthlyRevenue)->filter(fn ($value): bool => (float) $value > 0)->values();
        if ($observed->isEmpty()) {
            return ['nextMonth' => 0, 'growthRate' => 0, 'quarter' => 0, 'confidence' => 0];
        }

        $recent = $observed->take(-3)->values();
        $growthRates = $recent->map(function ($value, int $index) use ($recent): ?float {
            if ($index === 0) {
                return null;
            }

            $previous = (float) $recent[$index - 1];

            return $previous > 0 ? (((float) $value - $previous) / $previous) : 0;
        })->filter(fn ($value): bool => $value !== null);
        $growth = (float) ($growthRates->average() ?? 0);
        $nextMonth = max(0, (float) $observed->last() * (1 + $growth));
        $quarter = $nextMonth + ($nextMonth * (1 + $growth)) + ($nextMonth * ((1 + $growth) ** 2));

        return [
            'nextMonth' => round($nextMonth, 2),
            'growthRate' => round($growth * 100, 1),
            'quarter' => round($quarter, 2),
            'confidence' => min(90, 60 + ($observed->count() * 3)),
        ];
    }

    /** @param array<string, mixed> $filters */
    private function dateRange(int $year, array $filters): array
    {
        $now = CarbonImmutable::now();

        return match ($filters['period'] ?? 'year') {
            'this-month' => [$now->startOfMonth(), $now->endOfMonth()],
            'last-month' => [$now->subMonth()->startOfMonth(), $now->subMonth()->endOfMonth()],
            'quarter', 'current-quarter' => [$now->startOfQuarter(), $now->endOfQuarter()],
            'last-6-months' => [$now->startOfMonth()->subMonths(5), $now->endOfMonth()],
            'last-12-months' => [$now->startOfMonth()->subMonths(11), $now->endOfMonth()],
            'last-24-months' => [$now->startOfMonth()->subMonths(23), $now->endOfMonth()],
            'previous-year' => [$now->subYear()->startOfYear(), $now->subYear()->endOfYear()],
            'custom', 'custom-range' => [
                CarbonImmutable::parse($filters['start_date'] ?? "$year-01-01")->startOfDay(),
                CarbonImmutable::parse($filters['end_date'] ?? "$year-12-31")->endOfDay(),
            ],
            default => [CarbonImmutable::create($year)->startOfYear(), CarbonImmutable::create($year)->endOfYear()],
        };
    }

    /** @return array<string, mixed> */
    private function emptySnapshot(CarbonImmutable $start, CarbonImmutable $end): array
    {
        return [
            'year' => $start->year, 'start' => $start, 'end' => $end, 'orders' => collect(),
            'labels' => [], 'monthlyRevenue' => [], 'monthlyOrders' => [], 'monthlyTargets' => [],
            'productSales' => collect(), 'productUnits' => collect(), 'representativeSales' => collect(),
            'representativeOrders' => collect(), 'regionalSales' => collect(), 'targets' => collect(),
            'totalRevenue' => 0.0, 'totalOrders' => 0, 'activeCustomers' => 0,
        ];
    }

    /** @param array<string, mixed> $filters */
    private function selectedId(array $filters, string $key): ?int
    {
        $value = $filters[$key] ?? null;

        return is_numeric($value) && (int) $value > 0 ? (int) $value : null;
    }

    /** @param array<string, mixed> $filters */
    private function selectedValue(array $filters, string $key): ?string
    {
        $value = $filters[$key] ?? null;

        return is_string($value) && ! in_array($value, ['', 'all', 'all-regions'], true) ? $value : null;
    }
}
