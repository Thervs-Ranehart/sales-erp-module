<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SalesForecast;
use App\Models\SalesOrder;
use App\Models\SupportTicket;
use App\Services\SalesAnalyticsService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct(
        private readonly SalesAnalyticsService $analytics,
    ) {}

    public function index(Request $request)
    {
        $period = in_array($request->string('period')->toString(), [
            'this-month',
            'quarter',
            'last-6-months',
            'year',
        ], true) ? $request->string('period')->toString() : 'last-6-months';

        $snapshot = $this->analytics->snapshot((int) now()->year, ['period' => $period]);
        $start = CarbonImmutable::instance($snapshot['start']);
        $end = CarbonImmutable::instance($snapshot['end']);
        $previousEnd = $start->subDay();
        $previousStart = $previousEnd->subDays($start->diffInDays($end));

        $salesValue = (float) $snapshot['totalRevenue'];
        $previousSalesValue = (float) SalesOrder::query()
            ->whereBetween('order_date', [$previousStart->toDateString(), $previousEnd->toDateString()])
            ->where(fn ($query) => $query
                ->whereNull('order_status')
                ->orWhereRaw('LOWER(order_status) != ?', ['cancelled']))
            ->sum('total_amount');

        $paidRevenue = (float) Invoice::query()
            ->whereRaw('LOWER(payment_status) = ?', ['paid'])
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->sum('total_amount');

        $previousPaidRevenue = (float) Invoice::query()
            ->whereRaw('LOWER(payment_status) = ?', ['paid'])
            ->whereBetween('invoice_date', [$previousStart->toDateString(), $previousEnd->toDateString()])
            ->sum('total_amount');

        $salesTarget = (float) array_sum($snapshot['monthlyTargets']);
        $ordersTarget = (int) $snapshot['targets']->sum('sales_target');
        $targetAchievement = $salesTarget > 0 ? ($salesValue / $salesTarget) * 100 : null;
        $ordersAchievement = $ordersTarget > 0 ? ($snapshot['totalOrders'] / $ordersTarget) * 100 : null;

        $employeeId = (int) $request->session()->get('employee_id', 0);
        $notifications = Notification::query()
            ->where('employee_id', $employeeId)
            ->latest('created_at')
            ->take(5)
            ->get();

        $openSupportCases = SupportTicket::query()
            ->whereNotIn(DB::raw('LOWER(status)'), ['closed', 'resolved'])
            ->count();

        $attentionItems = $this->attentionItems();
        $forecast = Schema::hasTable('sales_forecasts')
            ? SalesForecast::query()->latest('generated_at')->first()
            : null;

        return view('dashboard.index', [
            'period' => $period,
            'periodLabel' => $this->periodLabel($period, $start, $end),
            'snapshot' => $snapshot,
            'salesValue' => $salesValue,
            'salesChangePercent' => $this->percentageChange($salesValue, $previousSalesValue),
            'paidRevenue' => $paidRevenue,
            'paidRevenueChangePercent' => $this->percentageChange($paidRevenue, $previousPaidRevenue),
            'totalOrders' => (int) $snapshot['totalOrders'],
            'activeCustomers' => (int) $snapshot['activeCustomers'],
            'totalCustomers' => Customer::query()->count(),
            'openSupportCases' => $openSupportCases,
            'salesTarget' => $salesTarget,
            'ordersTarget' => $ordersTarget,
            'targetAchievement' => $targetAchievement,
            'ordersAchievement' => $ordersAchievement,
            'notifications' => $notifications,
            'unreadNotifications' => $notifications->where('is_read', false)->count(),
            'recentActivities' => $this->recentActivities(),
            'attentionItems' => $attentionItems,
            'attentionCount' => $attentionItems->sum('count'),
            'forecast' => $forecast,
            'trendChart' => [
                'labels' => $snapshot['labels'],
                'targetSeries' => $snapshot['monthlyTargets'],
                'actualSeries' => $snapshot['monthlyRevenue'],
            ],
            'topProductsChart' => $this->chartData($snapshot['productSales'], 5),
            'representativesChart' => $this->chartData($snapshot['representativeSales'], 5),
            'regionsChart' => $this->chartData($snapshot['regionalSales'], 5),
        ]);
    }

    private function attentionItems(): Collection
    {
        $items = collect([
            [
                'label' => 'Orders awaiting completion',
                'description' => 'Orders that are not delivered or cancelled.',
                'count' => SalesOrder::query()
                    ->whereNotIn(DB::raw('LOWER(order_status)'), ['delivered', 'cancelled'])
                    ->count(),
                'icon' => 'cart-check',
                'tone' => 'warning',
                'route' => 'sales.index',
            ],
            [
                'label' => 'Unpaid invoices',
                'description' => 'Invoices still awaiting payment.',
                'count' => Invoice::query()
                    ->where(fn ($query) => $query
                        ->whereNull('payment_status')
                        ->orWhereRaw('LOWER(payment_status) != ?', ['paid']))
                    ->count(),
                'icon' => 'receipt',
                'tone' => 'danger',
                'route' => 'invoices.index',
            ],
            [
                'label' => 'Quotations expiring soon',
                'description' => 'Open quotations expiring within seven days.',
                'count' => Quotation::query()
                    ->whereIn(DB::raw('LOWER(quotation_status)'), ['draft', 'sent'])
                    ->whereBetween('valid_until', [today(), today()->addDays(7)])
                    ->count(),
                'icon' => 'file-earmark-text',
                'tone' => 'warning',
                'route' => 'quotations.index',
            ],
            [
                'label' => 'Open support cases',
                'description' => 'Customer cases that still require resolution.',
                'count' => SupportTicket::query()
                    ->whereNotIn(DB::raw('LOWER(status)'), ['closed', 'resolved'])
                    ->count(),
                'icon' => 'headset',
                'tone' => 'danger',
                'route' => 'support.tickets',
            ],
            [
                'label' => 'Low-stock products',
                'description' => 'Active products with 10 units or fewer.',
                'count' => Product::query()
                    ->where('stock_quantity', '<=', 10)
                    ->whereRaw('LOWER(product_status) = ?', ['active'])
                    ->count(),
                'icon' => 'boxes',
                'tone' => 'info',
                'route' => 'sales.index',
            ],
        ]);

        if (Schema::hasTable('service_requests')) {
            $items->push([
                'label' => 'Pending service requests',
                'description' => 'Service work that has not been completed.',
                'count' => DB::table('service_requests')
                    ->where(fn ($query) => $query
                        ->whereNull('service_status')
                        ->orWhereNotIn(DB::raw('LOWER(service_status)'), ['completed', 'closed']))
                    ->count(),
                'icon' => 'tools',
                'tone' => 'primary',
                'route' => 'support.service-requests',
            ]);
        }

        if (Schema::hasTable('warranty_claims')) {
            $items->push([
                'label' => 'Pending warranty claims',
                'description' => 'Claims still waiting for a final decision.',
                'count' => DB::table('warranty_claims')
                    ->where(fn ($query) => $query
                        ->whereNull('claim_status')
                        ->orWhereNotIn(DB::raw('LOWER(claim_status)'), ['approved', 'rejected', 'closed']))
                    ->count(),
                'icon' => 'shield-check',
                'tone' => 'primary',
                'route' => 'support.warranty-claims',
            ]);
        }

        return $items->values();
    }

    private function recentActivities(): Collection
    {
        $orders = SalesOrder::query()
            ->with('customer')
            ->latest('order_date')
            ->take(4)
            ->get()
            ->map(fn (SalesOrder $order): array => [
                'title' => "Sales order {$order->order_number}",
                'description' => $order->customer?->full_name
                    ? "Created for {$order->customer->full_name}"
                    : 'A sales order was created.',
                'status' => $order->formattedStatus(),
                'icon' => 'cart-check',
                'tone' => 'primary',
                'at' => $order->order_date,
                'url' => route('sales.profile', $order),
            ]);

        $customers = Customer::query()
            ->latest('created_at')
            ->take(3)
            ->get()
            ->map(fn (Customer $customer): array => [
                'title' => 'New customer registered',
                'description' => $customer->full_name,
                'status' => 'CRM',
                'icon' => 'person-plus',
                'tone' => 'success',
                'at' => $customer->created_at,
                'url' => route('crm.directory.show', $customer),
            ]);

        $payments = Invoice::query()
            ->whereRaw('LOWER(payment_status) = ?', ['paid'])
            ->latest('invoice_date')
            ->take(3)
            ->get()
            ->map(fn (Invoice $invoice): array => [
                'title' => "Payment received: {$invoice->invoice_number}",
                'description' => '₱'.number_format((float) $invoice->total_amount, 2),
                'status' => 'Paid',
                'icon' => 'cash-coin',
                'tone' => 'success',
                'at' => $invoice->invoice_date,
                'url' => route('invoices.show', $invoice),
            ]);

        $tickets = SupportTicket::query()
            ->latest('created_at')
            ->take(3)
            ->get()
            ->map(fn (SupportTicket $ticket): array => [
                'title' => "Support ticket #{$ticket->ticket_id}",
                'description' => $ticket->subject,
                'status' => $ticket->status ?: 'Open',
                'icon' => 'headset',
                'tone' => 'danger',
                'at' => $ticket->created_at,
                'url' => route('support.tickets'),
            ]);

        return $orders
            ->concat($customers)
            ->concat($payments)
            ->concat($tickets)
            ->sortByDesc('at')
            ->take(7)
            ->values();
    }

    private function chartData(Collection $values, int $limit): array
    {
        $ranked = $values->take($limit);

        return [
            'labels' => $ranked->keys()->values()->all(),
            'values' => $ranked->values()->map(fn ($value): float => (float) $value)->all(),
        ];
    }

    private function percentageChange(float $current, float $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function periodLabel(string $period, CarbonImmutable $start, CarbonImmutable $end): string
    {
        return match ($period) {
            'this-month' => $start->format('F Y'),
            'quarter' => "Q{$start->quarter} {$start->year}",
            'year' => (string) $start->year,
            default => $start->format('M Y').' – '.$end->format('M Y'),
        };
    }
}
