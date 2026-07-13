<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\AfterSalesSupportController;
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

Route::get('/sales-order-management', [SalesOrderController::class, 'index'])->name('sales.order-management');

// CRM Dashboard
Route::view('/crm', 'crm.index')->name('crm.index');

// CRM Modules
Route::view('/customer-directory', 'crm.customer-directory')->name('crm.directory');
 
Route::view('/customer-profiles', 'crm.customer-profiles')->name('crm.profiles');
 
Route::view('/purchase-history', 'crm.purchase-history')->name('crm.purchase');
 
Route::view('/customer-logs', 'crm.customer-logs')->name('crm.logs');

Route::view('/customer-follow-ups', 'crm.customer-followups')->name('crm.followups');

Route::view('/customer-loyalty', 'crm.customer-loyalty')->name('crm.loyalty');

Route::view('/customer-segmentation', 'crm.customer-segmentation')->name('crm.segmentation');

// After-Sales Support (case management)
Route::get('/after-sales-support', [SupportTicketController::class, 'index'])->name('support.index');
Route::get('/support/tickets', [AfterSalesSupportController::class, 'ticketsIndex'])->name('support.tickets');
Route::get('/support/warranty-records', [AfterSalesSupportController::class, 'warrantyRecordsIndex'])->name('support.warranty-records');
Route::get('/support/warranty-claims', [AfterSalesSupportController::class, 'warrantyClaimsIndex'])->name('support.warranty-claims');
Route::get('/support/service-contracts', [AfterSalesSupportController::class, 'serviceContractsIndex'])->name('support.service-contracts');
Route::get('/support/service-requests', [AfterSalesSupportController::class, 'serviceRequestsIndex'])->name('support.service-requests');
Route::get('/support/resolution-tracking', [AfterSalesSupportController::class, 'resolutionTrackingIndex'])->name('support.resolution-tracking');
Route::get('/support/customer-satisfaction', [AfterSalesSupportController::class, 'customerSatisfactionIndex'])->name('support.customer-satisfaction');

Route::get('/forecasting', [ForecastingController::class, 'index'])->name('forecasting.index');
Route::get('/forecasting/reports', [ForecastingController::class, 'reports'])->name('forecasting.reports');
Route::get('/forecasting/performance', [ForecastingController::class, 'performance'])->name('forecasting.performance');
Route::get('/forecasting/forecast', [ForecastingController::class, 'forecast'])->name('forecasting.forecast');
Route::get('/forecasting/recommendations', [ForecastingController::class, 'recommendations'])->name('forecasting.recommendations');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

// Sales Order Management 
Route::view('/sales-orders', 'sales.index')
    ->name('sales.index');

Route::view('/quotations', 'sales.quotations')
    ->name('quotations.index');

Route::view('/order-tracking', 'sales.tracking')
    ->name('tracking.index');

Route::view('/pricing-rules', 'sales.pricing')
    ->name('pricing.index');

Route::view('/discounts', 'sales.discounts')
    ->name('discounts.index');

Route::view('/taxes', 'sales.taxes')
    ->name('taxes.index');

Route::view('/invoice-generation', 'sales.invoice')
    ->name('invoice.index');

Route::get('/sales-order-management/profile/{id}', function ($id) {
    return view('sales.profile', compact('id'));
    })->name('sales.profile');
    
Route::view('/quotations', 'sales.quotations')
    ->name('quotations.index');

Route::view('/quotations/create', 'sales.create-quotation')
    ->name('quotations.create');
    
Route::view('/invoices', 'sales.invoices')
    ->name('invoices.index');

Route::view('/invoices/create', 'sales.create-invoice')
    ->name('invoices.create');

Route::view('/pricing-rules', 'sales.pricing-rules')
    ->name('pricing.index');

Route::view('/pricing-rules/create', 'sales.create-pricing')
    ->name('pricing.create');