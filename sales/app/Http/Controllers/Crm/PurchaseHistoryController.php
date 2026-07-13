<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PurchaseHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $category = $request->query('category');
        $paymentStatus = $request->query('status');
        $query = $this->filteredInvoices($request);

        $invoices = $query->orderByDesc('invoice_date')->paginate(10)->withQueryString();

        $totalTransactions = Invoice::count();
        $totalSales = Invoice::sum('total_amount');
        $monthlyOrders = Invoice::whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->count();
        $averagePurchase = $totalTransactions > 0 ? ($totalSales / $totalTransactions) : 0;

        $categoryTotals = Invoice::with('items.product')
            ->get()
            ->flatMap(fn ($invoice) => $invoice->items)
            ->groupBy(fn ($item) => $item->product?->category ?? 'Other')
            ->map(fn ($items) => $items->sum('subtotal'))
            ->sortDesc();

        $grandTotal = max(1, $categoryTotals->sum());

        return view('crm.purchase-history', [
            'invoices' => $invoices,
            'totalTransactions' => $totalTransactions,
            'totalSales' => $totalSales,
            'monthlyOrders' => $monthlyOrders,
            'averagePurchase' => $averagePurchase,
            'search' => $search,
            'category' => $category,
            'paymentStatus' => $paymentStatus,
            'categoryTotals' => $categoryTotals,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items.product', 'order.customer']);

        return view('crm.purchase-history', array_merge(
            $this->indexData(request()),
            [
                'selectedInvoice' => $invoice,
            ]
        ));
    }

    public function receipt(Invoice $invoice)
    {
        $invoice->load(['items.product', 'order.customer']);

        return view('crm.purchase-history', array_merge(
            $this->indexData(request()),
            [
                'receiptInvoice' => $invoice,
            ]
        ));
    }

    public function export(Request $request)
    {
        $invoices = $this->filteredInvoices($request)->orderByDesc('invoice_date')->get();

        $lines = [
            'Invoice ID,Invoice Number,Customer,Invoice Date,Payment Status,Total Amount,Products',
        ];

        foreach ($invoices as $inv) {
            $cust = optional($inv->order?->customer)->display_name ?? '';
            $products = $inv->items->map(fn ($item) => $item->product?->product_name ?? 'Item')->implode('; ');
            $lines[] = implode(',', [
                $inv->invoice_id,
                Str::replace(',', ' ', $inv->invoice_number),
                Str::replace(',', ' ', $cust),
                optional($inv->invoice_date)->toDateString(),
                Str::replace(',', ' ', $inv->payment_status),
                $inv->total_amount,
                Str::replace(',', ' ', $products),
            ]);
        }

        $csv = implode("\n", $lines);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="purchase_history_export.csv"',
        ]);
    }

    private function indexData(Request $request): array
    {
        $search = trim((string) $request->query('search', ''));
        $category = $request->query('category');
        $paymentStatus = $request->query('status');

        $query = $this->filteredInvoices($request);

        $invoices = $query->orderByDesc('invoice_date')->paginate(10)->withQueryString();

        $totalTransactions = Invoice::count();
        $totalSales = Invoice::sum('total_amount');
        $monthlyOrders = Invoice::whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->count();
        $averagePurchase = $totalTransactions > 0 ? ($totalSales / $totalTransactions) : 0;

        $categoryTotals = Invoice::with('items.product')
            ->get()
            ->flatMap(fn ($invoice) => $invoice->items)
            ->groupBy(fn ($item) => $item->product?->category ?? 'Other')
            ->map(fn ($items) => $items->sum('subtotal'))
            ->sortDesc();

        $grandTotal = max(1, $categoryTotals->sum());

        return [
            'invoices' => $invoices,
            'totalTransactions' => $totalTransactions,
            'totalSales' => $totalSales,
            'monthlyOrders' => $monthlyOrders,
            'averagePurchase' => $averagePurchase,
            'search' => $search,
            'category' => $category,
            'paymentStatus' => $paymentStatus,
            'categoryTotals' => $categoryTotals,
            'grandTotal' => $grandTotal,
        ];
    }

    private function filteredInvoices(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $category = $request->query('category');
        $paymentStatus = $request->query('status');

        $query = Invoice::query()->with(['items.product', 'order.customer']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('invoice_id', 'like', "%{$search}%")
                    ->orWhereHas('order.customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('customer_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('items.product', function ($productQuery) use ($search) {
                        $productQuery->where('product_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($category) {
            $query->whereHas('items.product', function ($productQuery) use ($category) {
                $productQuery->where('category', $category);
            });
        }

        return $query;
    }
}
