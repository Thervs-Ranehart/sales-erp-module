<?php

namespace App\Services;

use App\Models\ForecastRecommendation;
use App\Models\SalesForecast;
use App\Models\SalesPerformance;
use App\Models\SalesReport;
use Illuminate\Support\Facades\DB;

class ForecastingPersistenceService
{
    /** @param array<string, mixed> $snapshot @param array<string, mixed> $kpis */
    public function saveReport(array $snapshot, array $kpis, int $employeeId): SalesReport
    {
        return DB::transaction(function () use ($snapshot, $kpis, $employeeId): SalesReport {
            $report = SalesReport::query()->firstOrCreate([
                'report_name' => 'Sales Report '.$snapshot['start']->format('M Y').' - '.$snapshot['end']->format('M Y'),
                'report_type' => 'filtered-sales-summary',
                'report_period_start' => $snapshot['start'],
                'report_period_end' => $snapshot['end'],
                'generated_by' => $employeeId,
            ], [
                'generated_at' => now(),
            ]);

            $report->metrics()->delete();

            foreach ($kpis as $name => $value) {
                if (is_numeric($value)) {
                    $report->metrics()->create(['metric_name' => $name, 'metric_value' => $value]);
                }
            }

            return $report;
        });
    }

    /** @param array<string, mixed> $snapshot */
    public function savePerformance(array $snapshot): void
    {
        foreach ($snapshot['targets']->groupBy(fn ($target): string => $target->employee_id.'-'.$target->target_year.'-'.$target->target_month) as $targets) {
            $target = $targets->first();
            $employeeOrders = $snapshot['orders']->filter(fn ($order): bool => (int) $order->employee_id === (int) $target->employee_id
                && (int) $order->order_date?->year === (int) $target->target_year
                && (int) $order->order_date?->month === (int) $target->target_month);
            $actualRevenue = (float) $employeeOrders->sum('total_amount');
            $targetRevenue = (float) $targets->sum('revenue_target');
            $achievement = $targetRevenue > 0 ? ($actualRevenue / $targetRevenue) * 100 : 0;

            SalesPerformance::query()->updateOrCreate(
                ['employee_id' => $target->employee_id, 'evaluation_month' => $target->target_month, 'evaluation_year' => $target->target_year],
                [
                    'actual_orders' => $employeeOrders->count(),
                    'actual_revenue' => $actualRevenue,
                    'target_achievement' => $achievement,
                    'performance_status' => $achievement >= 100 ? 'Exceeded' : ($achievement >= 95 ? 'On Target' : 'Below'),
                    'evaluated_at' => now(),
                ],
            );
        }
    }

    /** @param array<string, mixed> $snapshot @param array{nextMonth: float, growthRate: float, quarter: float, confidence: int} $calculation */
    public function saveForecast(array $snapshot, array $calculation, int $employeeId, float $scenarioMultiplier = 1): SalesForecast
    {
        return SalesForecast::query()->updateOrCreate([
            'forecast_period_start' => $snapshot['end']->addDay(),
            'forecast_period_end' => $snapshot['end']->addMonths(3),
            'forecast_method' => 'three-month-moving-growth',
            'generated_by' => $employeeId,
        ], [
            'predicted_orders' => (int) round(($snapshot['totalOrders'] / max(1, count($snapshot['labels']))) * 3 * $scenarioMultiplier),
            'predicted_revenue' => $calculation['quarter'] * $scenarioMultiplier,
            'predicted_growth' => $calculation['growthRate'],
            'confidence_level' => $calculation['confidence'],
            'generated_at' => now(),
        ]);
    }

    /** @param array<int, array<string, string>> $recommendations */
    public function saveRecommendations(SalesForecast $forecast, array $recommendations, int $employeeId): void
    {
        $forecast->recommendations()->delete();
        foreach ($recommendations as $recommendation) {
            ForecastRecommendation::query()->create([
                'forecast_id' => $forecast->forecast_id,
                'recommendation_type' => $recommendation['category'],
                'title' => $recommendation['title'],
                'description' => $recommendation['insight'].' Action: '.$recommendation['action'],
                'priority' => $recommendation['priority'],
                'implementation_status' => $recommendation['status'] ?? 'New',
                'created_by' => $employeeId,
                'created_at' => now(),
            ]);
        }
    }
}
