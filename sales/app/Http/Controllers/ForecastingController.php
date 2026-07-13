<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ForecastingController extends Controller
{
    public function index()
    {
        return view('forecasting.index');
    }

    public function reports(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        // UI-only fallback dataset (no DB calls).
        $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $values = [400000, 360000, 420000, 390000, 450000, 480000, 520000, 500000, 470000, 530000, 600000, 680000];

        $monthlyRevenue = [
            'labels' => $labels,
            'values' => array_map(fn ($v) => (float) $v, $values),
        ];

        // UI-only fallback dataset for Top 5 products (labels sorted highest to lowest in the desired UI)
        // Values represent sales amount (PHP) for each product.
        $topProducts = [
            'labels' => ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
            'values' => [680000, 520000, 460000, 310000, 240000],
        ];

        return view('forecasting.reports', [
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts,
            'selectedYear' => $year,
        ]);
    }


    public function performance()
    {
        return view('forecasting.performance');
    }

    public function forecast()
    {
        return view('forecasting.forecast');
    }

    public function recommendations()
    {
        return view('forecasting.recommendations');
    }
}
