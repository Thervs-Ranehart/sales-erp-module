<?php

namespace App\Http\Controllers;

class ForecastingController extends Controller
{
    public function index()
    {
        return view('forecasting.index');
    }

    public function reports()
    {
        return view('forecasting.reports');
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
