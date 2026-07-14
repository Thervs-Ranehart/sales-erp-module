<?php

use App\Http\Controllers\AfterSalesSupportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Crm\CustomerDirectoryController;
use App\Http\Controllers\Crm\CustomerFollowUpsController;
use App\Http\Controllers\Crm\CustomerLogsController;
use App\Http\Controllers\Crm\CustomerLoyaltyController;
use App\Http\Controllers\Crm\CustomerProfilesController;
use App\Http\Controllers\Crm\CustomerSegmentationController;
use App\Http\Controllers\Crm\PurchaseHistoryController;
use App\Http\Controllers\Crm\RewardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PricingRuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'show']);

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/sales-order-management', [SalesOrderController::class, 'index'])->name('sales.order-management');
Route::redirect('/sales-order-management/index', '/sales-orders');

// CRM Dashboard
Route::redirect('/crm', '/customer-directory')->name('crm.index');

// CRM Modules
Route::get('/customer-directory', [CustomerDirectoryController::class, 'index'])->name('crm.directory');
Route::get('/customer-directory/create', [CustomerDirectoryController::class, 'create'])->name('crm.directory.create');
Route::post('/customer-directory', [CustomerDirectoryController::class, 'store'])->name('crm.directory.store');
Route::get('/customer-directory/{customer}/edit', [CustomerDirectoryController::class, 'edit'])->name('crm.directory.edit');
Route::put('/customer-directory/{customer}', [CustomerDirectoryController::class, 'update'])->name('crm.directory.update');
Route::delete('/customer-directory/{customer}', [CustomerDirectoryController::class, 'destroy'])->name('crm.directory.destroy');

Route::get('/customer-profiles', [CustomerProfilesController::class, 'index'])->name('crm.profiles');
Route::get('/customer-profiles/{customer}', [CustomerDirectoryController::class, 'show'])->name('crm.directory.show');
Route::get('/customer-profiles/{customer}/edit', [CustomerProfilesController::class, 'edit'])->name('crm.profiles.edit');
Route::post('/customer-profiles', [CustomerProfilesController::class, 'store'])->name('crm.profiles.store');
Route::put('/customer-profiles/{customer}', [CustomerProfilesController::class, 'update'])->name('crm.profiles.update');

Route::get('/purchase-history', [PurchaseHistoryController::class, 'index'])->name('crm.purchase');
Route::get('/purchase-history/export', [PurchaseHistoryController::class, 'export'])->name('crm.purchase.export');
Route::get('/purchase-history/receipt/{invoice}', [PurchaseHistoryController::class, 'receipt'])->name('crm.purchase.receipt');
Route::get('/purchase-history/{invoice}', [PurchaseHistoryController::class, 'show'])->name('crm.purchase.show');

Route::get('/customer-logs', [CustomerLogsController::class, 'index'])->name('crm.logs');
Route::post('/customer-logs', [CustomerLogsController::class, 'store'])->name('crm.logs.store');
Route::post('/customer-logs/{log}/status', [CustomerLogsController::class, 'updateStatus'])->name('crm.logs.status.update');
Route::delete('/customer-logs/{log}', [CustomerLogsController::class, 'destroy'])->name('crm.logs.destroy');

Route::get('/customer-follow-ups', [CustomerFollowUpsController::class, 'index'])->name('crm.followups');
Route::post('/customer-follow-ups', [CustomerFollowUpsController::class, 'store'])->name('crm.followups.store');
Route::post('/customer-follow-ups/{log}/status', [CustomerFollowUpsController::class, 'updateStatus'])->name('crm.followups.status.update');
Route::delete('/customer-follow-ups/{log}', [CustomerFollowUpsController::class, 'destroy'])->name('crm.followups.destroy');

Route::get('/customer-segmentation', [CustomerSegmentationController::class, 'index'])->name('crm.segmentation');
Route::post('/customer-segmentation/recalculate', [CustomerSegmentationController::class, 'recalculate'])->name('crm.segmentation.recalculate');

Route::get('/customer-loyalty', [CustomerLoyaltyController::class, 'index'])->name('crm.loyalty');
Route::post('/customer-loyalty', [CustomerLoyaltyController::class, 'store'])->name('crm.loyalty.store');
Route::get('/customer-loyalty/{loyalty}', [CustomerLoyaltyController::class, 'show'])->name('crm.loyalty.show');
Route::put('/customer-loyalty/{loyalty}', [CustomerLoyaltyController::class, 'update'])->name('crm.loyalty.update');

// Loyalty Program Rewards
Route::post('/crm/rewards', [RewardController::class, 'store'])->name('crm.rewards.store');
Route::put('/crm/rewards/{reward}', [RewardController::class, 'update'])->name('crm.rewards.update');
Route::delete('/crm/rewards/{reward}', [RewardController::class, 'destroy'])->name('crm.rewards.destroy');

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
Route::get('/forecasting/sales-analysis', [ForecastingController::class, 'salesAnalysis'])->name('forecasting.sales-analysis');
Route::get('/forecasting/performance', [ForecastingController::class, 'performance'])->name('forecasting.performance');
Route::get('/forecasting/forecast', [ForecastingController::class, 'forecast'])->name('forecasting.forecast');
Route::get('/forecasting/recommendations', [ForecastingController::class, 'recommendations'])->name('forecasting.recommendations');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

// =========================
// Sales Order Management
// =========================

Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales.index');
Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales.create');
Route::post('/sales-orders', [SalesOrderController::class, 'store'])->name('sales.store');
Route::get('/sales-orders/{salesOrder}/edit', [SalesOrderController::class, 'edit'])->name('sales.edit');
Route::put('/sales-orders/{salesOrder}', [SalesOrderController::class, 'update'])->name('sales.update');
Route::delete('/sales-orders/{salesOrder}', [SalesOrderController::class, 'destroy'])->name('sales.destroy');
Route::get('/sales-order-management/profile/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales.profile');
Route::patch('/sales-order-management/profile/{salesOrder}/status', [SalesOrderController::class, 'updateStatus'])->name('sales.update-status');

Route::resource('pricing-rules', PricingRuleController::class);
Route::resource('invoices', InvoiceController::class);

Route::resource('quotations', QuotationController::class);
