<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AfterSalesSupportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\Crm\CustomerArchiveController;
use App\Http\Controllers\Crm\CustomerDirectoryController;
use App\Http\Controllers\Crm\CustomerFollowUpsController;
use App\Http\Controllers\Crm\CustomerLogsController;
use App\Http\Controllers\Crm\CustomerLoyaltyController;
use App\Http\Controllers\Crm\CustomerProfilesController;
use App\Http\Controllers\Crm\CustomerSegmentationController;
use App\Http\Controllers\Crm\MarketingCampaignController;
use App\Http\Controllers\Crm\PurchaseHistoryController;
use App\Http\Controllers\Crm\RewardController;
use App\Http\Controllers\Crm\RewardRedemptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\ForecastOperationsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PricingRuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalesApprovalController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesTargetController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SupportAttachmentController;
use App\Http\Controllers\SupportOperationsController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\WarrantyClaimController;
use App\Http\Controllers\WarrantyRecordController;
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
Route::patch('/customer-directory/{customer}/archive', [CustomerArchiveController::class, 'archive'])->name('crm.directory.archive');
Route::patch('/customer-directory/{customer}/restore', [CustomerArchiveController::class, 'restore'])->name('crm.directory.restore');

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
Route::post('/crm/reward-redemptions', [RewardRedemptionController::class, 'store'])->name('crm.redemptions.store');
Route::patch('/crm/reward-redemptions/{redemption}/cancel', [RewardRedemptionController::class, 'cancel'])->name('crm.redemptions.cancel');
Route::get('/crm/marketing-campaigns', [MarketingCampaignController::class, 'index'])->name('crm.campaigns');
Route::post('/crm/marketing-campaigns', [MarketingCampaignController::class, 'store'])->name('crm.campaigns.store');

// After-Sales Support (case management)
// Redirect module entrypoint to Support Tickets (default)
Route::redirect('/after-sales-support', '/support/tickets');

Route::get('/support/tickets', [AfterSalesSupportController::class, 'ticketsIndex'])->name('support.tickets');

Route::get('/support/tickets/{ticket}/show', [SupportTicketController::class, 'show'])->name('support.tickets.show');
Route::get('/support/tickets/{ticket}/assign', [SupportTicketController::class, 'assignForm'])->name('support.tickets.assign.form');
Route::post('/support/tickets/{ticket}/assign', [SupportTicketController::class, 'assign'])->name('support.tickets.assign');
Route::post('/support/tickets/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('support.tickets.status');
Route::post('/support/tickets', [SupportOperationsController::class, 'storeTicket'])->name('support.tickets.store');
Route::put('/support/tickets/{ticket}', [SupportOperationsController::class, 'updateTicket'])->name('support.tickets.update');
Route::patch('/support/tickets/{ticket}/archive', [SupportOperationsController::class, 'archiveTicket'])->name('support.tickets.archive');
Route::patch('/support/tickets/{ticket}/restore', [SupportOperationsController::class, 'restoreTicket'])->name('support.tickets.restore');
Route::post('/support/tickets/{ticket}/attachments', [SupportAttachmentController::class, 'store'])->name('support.tickets.attachments.store');
Route::delete('/support/attachments/{attachment}', [SupportAttachmentController::class, 'destroy'])->name('support.attachments.destroy');

// Warranty Records (AJAX View)
Route::get('/support/warranty-records/{warranty}/show', [WarrantyRecordController::class, 'show'])->name('support.warranty-records.show');
Route::post('/support/warranty-records', [SupportOperationsController::class, 'storeWarranty'])->name('support.warranty-records.store');
Route::put('/support/warranty-records/{warranty}', [SupportOperationsController::class, 'updateWarranty'])->name('support.warranty-records.update');
Route::patch('/support/warranty-records/{warranty}/archive', [SupportOperationsController::class, 'archiveWarranty'])->name('support.warranty-records.archive');

// Warranty Claims (AJAX modal workflow)
Route::get('/support/warranty-claims/{claim}/show', [WarrantyClaimController::class, 'show'])->name('support.warranty-claims.show');
Route::post('/support/warranty-claims/{claim}/status', [WarrantyClaimController::class, 'updateStatus'])->name('support.warranty-claims.status');
Route::post('/support/warranty-claims', [SupportOperationsController::class, 'storeClaim'])->name('support.warranty-claims.store');
Route::patch('/support/warranty-claims/{claim}/cancel', [SupportOperationsController::class, 'cancelClaim'])->name('support.warranty-claims.cancel');

Route::get('/support/warranty-records', [AfterSalesSupportController::class, 'warrantyRecordsIndex'])->name('support.warranty-records');
Route::get('/support/warranty-claims', [AfterSalesSupportController::class, 'warrantyClaimsIndex'])->name('support.warranty-claims');

Route::get('/support/service-contracts', [AfterSalesSupportController::class, 'serviceContractsIndex'])->name('support.service-contracts');
Route::get('/support/service-contracts/{contract}/show', [AfterSalesSupportController::class, 'serviceContractShow'])->name('support.service-contracts.show');
Route::post('/support/service-contracts', [SupportOperationsController::class, 'storeContract'])->name('support.service-contracts.store');
Route::put('/support/service-contracts/{contract}', [SupportOperationsController::class, 'updateContract'])->name('support.service-contracts.update');
Route::patch('/support/service-contracts/{contract}/archive', [SupportOperationsController::class, 'archiveContract'])->name('support.service-contracts.archive');

Route::get('/support/service-requests', [AfterSalesSupportController::class, 'serviceRequestsIndex'])->name('support.service-requests');
Route::get('/support/service-requests/{request}/show', [AfterSalesSupportController::class, 'serviceRequestShow'])->name('support.service-requests.show');
Route::patch('/support/service-requests/{request}/schedule', [AfterSalesSupportController::class, 'scheduleServiceRequest'])->name('support.service-requests.schedule');
Route::post('/support/service-requests', [SupportOperationsController::class, 'storeServiceRequest'])->name('support.service-requests.store');
Route::put('/support/service-requests/{serviceRequest}', [SupportOperationsController::class, 'updateServiceRequest'])->name('support.service-requests.update');
Route::patch('/support/service-requests/{serviceRequest}/cancel', [SupportOperationsController::class, 'cancelServiceRequest'])->name('support.service-requests.cancel');

Route::get('/support/resolution-tracking', [AfterSalesSupportController::class, 'resolutionTrackingIndex'])->name('support.resolution-tracking');
Route::get('/support/resolution-tracking/{resolution}/show', [AfterSalesSupportController::class, 'resolutionShow'])->name('support.resolution-tracking.show');
Route::post('/support/resolution-tracking', [SupportOperationsController::class, 'storeResolution'])->name('support.resolution-tracking.store');
Route::put('/support/resolution-tracking/{resolution}', [SupportOperationsController::class, 'updateResolution'])->name('support.resolution-tracking.update');
Route::patch('/support/resolution-tracking/{resolution}/approve', [SupportOperationsController::class, 'approveResolution'])->name('support.resolution-tracking.approve');

Route::get('/support/customer-satisfaction', [AfterSalesSupportController::class, 'customerSatisfactionIndex'])->name('support.customer-satisfaction');
Route::patch('/support/customer-satisfaction/{feedback}/submit', [SupportOperationsController::class, 'submitFeedback'])->name('support.customer-satisfaction.submit');

Route::get('/forecasting', [ForecastingController::class, 'index'])->name('forecasting.index');
Route::get('/forecasting/reports', [ForecastingController::class, 'reports'])->name('forecasting.reports');
Route::get('/forecasting/sales-analysis', [ForecastingController::class, 'salesAnalysis'])->name('forecasting.sales-analysis');
Route::get('/forecasting/performance', [ForecastingController::class, 'performance'])->name('forecasting.performance');
Route::get('/forecasting/forecast', [ForecastingController::class, 'forecast'])->name('forecasting.forecast');
Route::get('/forecasting/recommendations', [ForecastingController::class, 'recommendations'])->name('forecasting.recommendations');
Route::get('/forecasting/export/{type}', [ForecastOperationsController::class, 'export'])->name('forecasting.export');
Route::post('/forecasting/regions', [ForecastOperationsController::class, 'storeRegion'])->name('forecasting.regions.store');
Route::patch('/forecasting/customers/{customer}/region', [ForecastOperationsController::class, 'assignRegion'])->name('forecasting.customers.region');
Route::patch('/forecasting/recommendations/{recommendation}', [ForecastOperationsController::class, 'updateRecommendation'])->name('forecasting.recommendations.update');
Route::patch('/forecasting/forecasts/{forecast}/evaluate', [ForecastOperationsController::class, 'evaluateForecast'])->name('forecasting.forecasts.evaluate');
Route::post('/forecasting/targets', [SalesTargetController::class, 'store'])->name('forecasting.targets.store');
Route::delete('/forecasting/targets/{salesTarget}', [SalesTargetController::class, 'destroy'])->name('forecasting.targets.destroy');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

Route::get('/about', [AboutController::class, 'index'])->name('about.index');

// =========================
// Sales Order Management
// =========================

Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales.index');
Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales.create');
Route::post('/sales-orders', [SalesOrderController::class, 'store'])->name('sales.store');
Route::delete('/sales-orders', [SalesOrderController::class, 'bulkDestroy'])->name('sales.bulk-destroy');
Route::get('/sales-orders/{salesOrder}/edit', [SalesOrderController::class, 'edit'])->name('sales.edit');
Route::put('/sales-orders/{salesOrder}', [SalesOrderController::class, 'update'])->name('sales.update');
Route::delete('/sales-orders/{salesOrder}', [SalesOrderController::class, 'destroy'])->name('sales.destroy');
Route::get('/sales-order-management/profile/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales.profile');
Route::patch('/sales-order-management/profile/{salesOrder}/status', [SalesOrderController::class, 'updateStatus'])->name('sales.update-status');
Route::post('/sales-orders/{salesOrder}/shipments', [ShipmentController::class, 'store'])->name('sales.shipments.store');
Route::patch('/shipments/{shipment}', [ShipmentController::class, 'update'])->name('sales.shipments.update');
Route::post('/invoices/{invoice}/credit-notes', [CreditNoteController::class, 'store'])->name('invoices.credit-notes.store');
Route::patch('/sales-approvals/{approval}', [SalesApprovalController::class, 'update'])->name('sales.approvals.update');

Route::resource('pricing-rules', PricingRuleController::class);
Route::resource('invoices', InvoiceController::class);

Route::resource('quotations', QuotationController::class);
Route::post('/quotations/{quotation}/convert', [QuotationController::class, 'convert'])->name('quotations.convert');
