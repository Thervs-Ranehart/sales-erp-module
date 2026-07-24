<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesRegion;
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
            ->with(['items.product', 'employee', 'customer.region'])
            ->whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
            ->where(fn (Builder $query): Builder => $query
                ->whereNull('order_status')
                ->orWhereRaw('LOWER(order_status) != ?', ['cancelled']))
            ->when($this->selectedId($filters, 'representative'), fn (Builder $query, int $id): Builder => $query->where('employee_id', $id))
            ->when($this->selectedId($filters, 'region'), fn (Builder $query, int $regionId): Builder => $query->whereHas('customer', fn (Builder $customerQuery): Builder => $customerQuery->where('region_id', $regionId)))
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
            ->groupBy(fn (SalesOrder $order): string => $order->customer?->region?->region_name ?: 'Unassigned Region')
            ->map(fn (Collection $group): float => (float) $group->sum('total_amount'))
            ->sortDesc();
        $warehouseSales = $orders
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
            'warehouseSales' => $warehouseSales,
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
            'regions' => Schema::hasTable('sales_regions')
                ? SalesRegion::query()->where('status', 'Active')->orderBy('region_name')->get(['region_id', 'region_name'])
                : collect(),
        ];
    }

    /** @return array{nextMonth: float, growthRate: float, quarter: float, confidence: int, predictionLower: float, predictionUpper: float, mae: float, mape: float, rmse: float, sampleSize: int, method: string} */
    public function forecast(array $monthlyRevenue): array
    {
        $observed = collect($monthlyRevenue)->map(fn ($value): float => (float) $value)->values();
        while ($observed->isNotEmpty() && $observed->first() === 0.0) {
            $observed->shift();
        }
        if ($observed->isEmpty()) {
            return [
                'nextMonth' => 0, 'growthRate' => 0, 'quarter' => 0, 'confidence' => 0,
                'predictionLower' => 0, 'predictionUpper' => 0, 'mae' => 0, 'mape' => 0,
                'rmse' => 0, 'sampleSize' => 0, 'method' => 'insufficient-history',
            ];
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
        $movingForecast = max(0, (float) $observed->last() * (1 + $growth));

        $count = $observed->count();
        $meanX = ($count - 1) / 2;
        $meanY = (float) $observed->average();
        $denominator = collect(range(0, $count - 1))->sum(fn (int $x): float => ($x - $meanX) ** 2);
        $slope = $denominator > 0
            ? collect(range(0, $count - 1))->sum(fn (int $x): float => ($x - $meanX) * ((float) $observed[$x] - $meanY)) / $denominator
            : 0;
        $intercept = $meanY - ($slope * $meanX);
        $trendForecast = max(0, $intercept + ($slope * $count));

        $seasonalFactors = collect(range(0, $count - 1))
            ->groupBy(fn (int $index): int => $index % 12)
            ->map(fn (Collection $indices): float => $meanY > 0
                ? (float) $indices->average(fn (int $index): float => (float) $observed[$index]) / $meanY
                : 1.0);
        $seasonalFactor = (float) ($seasonalFactors[$count % 12] ?? 1.0);
        $seasonalForecast = max(0, $trendForecast * $seasonalFactor);
        $nextMonth = $count >= 6
            ? (($movingForecast * .35) + ($trendForecast * .35) + ($seasonalForecast * .30))
            : (($movingForecast + $trendForecast) / 2);

        $fitted = collect(range(0, $count - 1))->map(fn (int $x): float => max(0, ($intercept + ($slope * $x)) * (float) ($seasonalFactors[$x % 12] ?? 1)));
        $errors = $observed->map(fn (float $actual, int $index): float => $actual - (float) $fitted[$index]);
        $mae = (float) $errors->map(fn (float $error): float => abs($error))->average();
        $mapeValues = $errors->map(fn (float $error, int $index): ?float => $observed[$index] > 0 ? abs($error / $observed[$index]) * 100 : null)->filter(fn ($value): bool => $value !== null);
        $mape = (float) ($mapeValues->average() ?? 0);
        $rmse = sqrt((float) $errors->map(fn (float $error): float => $error ** 2)->average());
        $standardError = $count > 2 ? sqrt((float) $errors->map(fn (float $error): float => $error ** 2)->sum() / ($count - 2)) : $rmse;
        $margin = 1.96 * $standardError;

        $quarter = $nextMonth + ($nextMonth * (1 + $growth)) + ($nextMonth * ((1 + $growth) ** 2));

        return [
            'nextMonth' => round($nextMonth, 2),
            'growthRate' => round($growth * 100, 1),
            'quarter' => round($quarter, 2),
            'confidence' => $count >= 6 ? 95 : max(50, 60 + ($count * 5)),
            'predictionLower' => round(max(0, $nextMonth - $margin), 2),
            'predictionUpper' => round($nextMonth + $margin, 2),
            'mae' => round($mae, 2),
            'mape' => round($mape, 2),
            'rmse' => round($rmse, 2),
            'sampleSize' => $count,
            'method' => $count >= 6 ? 'ensemble-trend-seasonal-moving-growth' : 'trend-moving-growth',
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
            'representativeOrders' => collect(), 'regionalSales' => collect(), 'warehouseSales' => collect(), 'targets' => collect(),
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
