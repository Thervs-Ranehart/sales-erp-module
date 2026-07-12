<?php

namespace App\Http\Controllers;

class SalesOrderController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }
}
