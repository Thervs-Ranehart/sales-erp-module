<?php

namespace App\Http\Controllers;

use App\Services\RevenueTrendService;
use Illuminate\Http\Request;

class ForecastingController extends Controller
{
    public function index()
    {
        return view('forecasting.index');
    }

    public function reports(Request $request, RevenueTrendService $revenueTrendService)
    {
        $year = (int) $request->query('year', now()->year);

        $monthlyRevenue = $revenueTrendService->getMonthlyRevenue($year);

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
