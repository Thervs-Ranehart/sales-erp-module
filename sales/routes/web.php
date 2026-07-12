<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', function () {
    return redirect('/dashboard');
})->name('login');

Route::get('/logout', function () {
    return redirect('/');
})->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/sales-order-management', [SalesOrderController::class, 'index'])->name('sales.index');

Route::get('/crm', [CustomerController::class, 'index'])->name('crm.index');

Route::get('/after-sales-support', [SupportTicketController::class, 'index'])->name('support.index');

Route::get('/forecasting', [ForecastingController::class, 'index'])->name('forecasting.index');
Route::get('/forecasting/reports', [ForecastingController::class, 'reports'])->name('forecasting.reports');
Route::get('/forecasting/performance', [ForecastingController::class, 'performance'])->name('forecasting.performance');
Route::get('/forecasting/forecast', [ForecastingController::class, 'forecast'])->name('forecasting.forecast');
Route::get('/forecasting/recommendations', [ForecastingController::class, 'recommendations'])->name('forecasting.recommendations');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');


