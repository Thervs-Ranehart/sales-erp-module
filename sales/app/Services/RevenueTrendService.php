<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class RevenueTrendService
{
    public function getMonthlyRevenue(int $year): array
    {
        $monthlyTotals = Invoice::query()
            ->selectRaw('MONTH(invoice_date) as month')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as total')
            ->whereYear('invoice_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        $labels = collect(range(1, 12))
            ->map(fn (int $month) => Carbon::createFromDate($year, $month, 1)->format('M'))
            ->all();

        $values = collect(range(1, 12))
            ->map(fn (int $month) => isset($monthlyTotals[$month]) ? (float) $monthlyTotals[$month] : 0)
            ->all();

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
