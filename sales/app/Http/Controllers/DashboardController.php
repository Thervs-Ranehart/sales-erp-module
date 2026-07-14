<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\SupportTicket;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        // ===================== TOTAL REVENUE =====================
        // Realized revenue = paid invoices only.
        $revenueThisMonth = Invoice::where('payment_status', 'Paid')
            ->whereYear('invoice_date', $now->year)
            ->whereMonth('invoice_date', $now->month)
            ->sum('total_amount');

        $revenueLastMonth = Invoice::where('payment_status', 'Paid')
            ->whereYear('invoice_date', $now->copy()->subMonth()->year)
            ->whereMonth('invoice_date', $now->copy()->subMonth()->month)
            ->sum('total_amount');

        $totalRevenue = Invoice::where('payment_status', 'Paid')->sum('total_amount');

        $revenueChangePercent = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // ===================== TOTAL ORDERS =====================
        $totalOrders = SalesOrder::count();

        $newOrdersThisMonth = SalesOrder::whereYear('order_date', $now->year)
            ->whereMonth('order_date', $now->month)
            ->count();

        // ===================== ACTIVE CUSTOMERS =====================
        $totalCustomers = Customer::count();

        $newCustomersThisMonth = Customer::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        // ===================== OPEN SUPPORT CASES =====================
        $openSupportCases = SupportTicket::whereNotIn('status', ['Closed', 'Resolved'])
            ->count();

        // ===================== TARGET ACHIEVEMENT =====================
        // No sales-target table exists yet, so this uses a fixed monthly
        // goal as a placeholder. Replace $monthlyTarget with a real value
        // (or a settings/targets table) once one exists.
        $monthlyTarget = 3000000;
        $targetPercent = $monthlyTarget > 0
            ? min(100, round(($revenueThisMonth / $monthlyTarget) * 100))
            : 0;

        // ===================== NOTIFICATIONS =====================
        $notifications = Notification::latest('created_at')
            ->take(4)
            ->get();

        // ===================== RECENT ACTIVITIES =====================
        $recentOrders = SalesOrder::latest('order_date')
            ->take(2)
            ->get()
            ->map(fn ($order) => [
                'text' => "Sales Order #{$order->order_number} Created",
                'at' => $order->order_date,
            ]);

        $recentCustomers = Customer::latest('created_at')
            ->take(2)
            ->get()
            ->map(fn ($customer) => [
                'text' => "New Customer Registered: {$customer->full_name}",
                'at' => $customer->created_at,
            ]);

        $recentPayments = Invoice::where('payment_status', 'Paid')
            ->latest('invoice_date')
            ->take(2)
            ->get()
            ->map(fn ($invoice) => [
                'text' => "Payment Received for {$invoice->invoice_number}",
                'at' => $invoice->invoice_date,
            ]);

        $recentActivities = $recentOrders
            ->concat($recentCustomers)
            ->concat($recentPayments)
            ->sortByDesc('at')
            ->take(4)
            ->values();

        // ===================== SYSTEM SUMMARY =====================
        // No dedicated "leads" table exists; open/draft quotations are
        // used as the closest proxy for CRM leads.
        $crmLeads = Quotation::whereIn('quotation_status', ['Draft', 'Sent'])->count();

        // No forecast-accuracy tracking exists yet (ForecastingReport only
        // stores a forecast_value, not an actual-vs-forecast comparison),
        // so this is left null until that data is available.
        $forecastAccuracy = null;

        return view('dashboard.index', [
            'totalRevenue' => $totalRevenue,
            'revenueChangePercent' => $revenueChangePercent,
            'totalOrders' => $totalOrders,
            'newOrdersThisMonth' => $newOrdersThisMonth,
            'totalCustomers' => $totalCustomers,
            'newCustomersThisMonth' => $newCustomersThisMonth,
            'openSupportCases' => $openSupportCases,
            'targetPercent' => $targetPercent,
            'notifications' => $notifications,
            'recentActivities' => $recentActivities,
            'crmLeads' => $crmLeads,
            'forecastAccuracy' => $forecastAccuracy,
        ]);
    }
}
