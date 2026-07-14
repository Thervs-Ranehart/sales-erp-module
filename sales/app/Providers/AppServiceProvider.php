<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\SupportTicket;
use App\Observers\BroadcastsChangesObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Any model listed here will instantly notify every connected
        // device (create/update/delete) via the "erp-updates" broadcast
        // channel. Add more models to this array as needed.
        foreach ([
            SalesOrder::class,
            Invoice::class,
            Quotation::class,
            Product::class,
            Customer::class,
            SupportTicket::class,
        ] as $model) {
            $model::observe(BroadcastsChangesObserver::class);
        }
    }
}
