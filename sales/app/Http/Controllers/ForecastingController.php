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

        return view('forecasting.reports', [
            'monthlyRevenue' => $monthlyRevenue,
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
