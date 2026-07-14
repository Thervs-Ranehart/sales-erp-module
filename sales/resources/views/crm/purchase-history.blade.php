@extends('layouts.app')

@section('title', 'Purchase History')
@section('page-title', 'Purchase History')

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<style>

.purchase-card {
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
}

.summary-box {
    padding:22px;
    border-radius:12px;
    border:1px solid #e9ecef;
    background:#fff;
}

.summary-label {
    color:#6c757d;
    font-size:14px;
}

.summary-value {
    font-size:26px;
    font-weight:600;
}

.category-item {
    display:flex;
    justify-content:space-between;
    padding:12px 0;
    border-bottom:1px solid #eee;
}

.category-item:last-child {
    border-bottom:none;
}

.table th {
    background:#f8f9fa;
    font-size:13px;
    color:#495057;
    font-weight:600;
}

.table td {
    padding:15px 12px;
    vertical-align:middle;
}

.form-control,
.form-select {
    border-radius:8px;
}

.btn {
    border-radius:8px;
}

</style>

{{-- Header --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h4 class="fw-semibold mb-1">
Purchase History
</h4>

<p class="text-muted mb-0">
Monitor customer buying behavior and transaction records.
</p>

</div>


<a href="{{ route('crm.purchase.export', request()->query()) }}" class="btn btn-outline-secondary">

<i class="bi bi-download"></i>
Export Report

</a>


</div>


{{-- Summary Cards --}}

<div class="row g-3 mb-4">

<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Total Transactions
</div>

<div class="summary-value">
{{ number_format($totalTransactions ?? 0) }}
</div>

</div>

</div>


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Total Sales
</div>

<div class="summary-value">
₱{{ number_format($totalSales ?? 0, 2) }}
</div>

</div>

</div>


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Monthly Orders
</div>

<div class="summary-value">
{{ number_format($monthlyOrders ?? 0) }}
</div>

</div>

</div>


<div class="col-md-3">

<div class="summary-box">

<div class="summary-label">
Average Purchase
</div>

<div class="summary-value">
₱{{ number_format($averagePurchase ?? 0, 2) }}
</div>

</div>

</div>

</div>



{{-- Insights and Search --}}

<div class="row g-4 mb-4">

    {{-- Customer Insights --}}
    <div class="col-md-4">
        <div class="card purchase-card p-4">

            <h6 class="fw-semibold mb-3">
                Customer Insights
            </h6>

            <div class="category-item">
                <span>Top Customer</span>
                <strong>{{ $topCustomerName ?? '—' }}</strong>
            </div>

            <div class="category-item">
                <span>Highest Purchase</span>
                <strong>₱{{ number_format($highestPurchase ?? 0, 2) }}</strong>
            </div>

            <div class="category-item">
                <span>Most Purchased</span>
                <strong>{{ $mostPurchasedCategory ?? '—' }}</strong>
            </div>

            <div class="category-item">
                <span>Purchase Frequency</span>
                <strong>{{ number_format($purchaseFrequency ?? 0, 1) }} orders/customer</strong>
            </div>

        </div>
    </div>

    {{-- Search --}}
    <div class="col-md-8">
        <div class="card purchase-card p-4">

            <h6 class="fw-semibold mb-3">
                Search Transactions
            </h6>

            <form method="GET" action="{{ route('crm.purchase') }}">
                <div class="row g-3">

                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search ?? '' }}"
                                class="form-control"
                                placeholder="Search customer, product, or transaction ID">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Category</option>

                            <option value="Electronics"
                                {{ ($category ?? '') == 'Electronics' ? 'selected' : '' }}>
                                Electronics
                            </option>

                            <option value="Office Supplies"
                                {{ ($category ?? '') == 'Office Supplies' ? 'selected' : '' }}>
                                Office Supplies
                            </option>

                            <option value="Accessories"
                                {{ ($category ?? '') == 'Accessories' ? 'selected' : '' }}>
                                Accessories
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Status</option>

                            <option value="Paid"
                                {{ ($paymentStatus ?? '') == 'Paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="Pending"
                                {{ ($paymentStatus ?? '') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Cancelled"
                                {{ ($paymentStatus ?? '') == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" type="submit">
    <i class="bi bi-funnel"></i>
    Filter
</button>
                    </div>

                </div>
            </form>

        </div>
    </div>

</div>

{{-- Top Categories --}}

<div class="card purchase-card p-4 mb-4">

@php
// best-effort: keep layout, avoid redesign
$totalAmount = $invoices->sum('total_amount');
@endphp


<h6 class="fw-semibold mb-3">
Top Purchase Categories
</h6>


<div class="row">

@php
    $topCategories = collect($categoryTotals ?? [])->take(3);
@endphp

@forelse($topCategories as $categoryName => $amount)
<div class="col-md-4">

<div class="category-item">

<span>
{{ $categoryName }}
</span>

<strong>
{{ round(($amount / ($grandTotal ?? 1)) * 100) }}%
</strong>

</div>

</div>
@empty
<div class="col-12 text-muted">No category data available.</div>
@endforelse


</div>


</div>




{{-- Transaction Records --}}

<div class="card purchase-card p-4">


<div class="mb-3">

<h5 class="fw-semibold mb-1">
Transaction Records
</h5>


<small class="text-muted">
Customer purchase history and sales information
</small>


</div>




<div class="table-responsive">


<table class="table align-middle">


<thead>


<tr>

<th>
Transaction ID
</th>

<th>
Customer
</th>

<th>
Product
</th>

<th>
Category
</th>

<th class="text-center">
Qty
</th>

<th>
Amount
</th>

<th class="text-center">
Payment Status
</th>

<th>
Purchase Date
</th>

<th class="text-center">
Action
</th>

</tr>


</thead>




<tbody>


@foreach($invoices as $invoice)


@php
$customer = optional($invoice->order?->customer)->display_name ?? $invoice->order?->customer_id ?? '—';

$firstItem = $invoice->items->first();
$product = $firstItem?->product;

$qty = $invoice->items->sum('quantity');

$amount = $invoice->total_amount;

$badgeClass = match($invoice->payment_status) {
    'Paid' => 'bg-success',
    'Pending' => 'bg-warning text-dark',
    'Cancelled' => 'bg-secondary',
    default => 'bg-light text-dark',
};
@endphp


<tr>


<td>
{{ $invoice->invoice_number }}
</td>


<td>
{{ $customer }}
</td>


<td>
{{ $product?->product_name ?? '—' }}
</td>


<td>
{{ $product?->category ?? '—' }}
</td>


<td class="text-center">
{{ $qty }}
</td>


<td>
₱{{ number_format($amount ?? 0, 2) }}
</td>


<td class="text-center">


<span class="badge {{ $badgeClass }}">
{{ $invoice->payment_status ?? '—' }}
</span>


</td>


<td>
{{ optional($invoice->invoice_date)->format('M j, Y') }}
</td>


<td class="text-center">


<a class="btn btn-sm btn-outline-primary" href="{{ route('crm.purchase.show', $invoice) }}">
View
</a>


<a class="btn btn-sm btn-outline-secondary" href="{{ route('crm.purchase.receipt', $invoice) }}">
Receipt
</a>


</td>


</tr>


@endforeach


</tbody>


</table>


</div>

<div class="mt-3">
    {{ $invoices->links() }}
</div>


</div>


@if(isset($selectedInvoice))
<div class="modal fade show" id="transactionModal" tabindex="-1" style="display:block;background:rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details — {{ $selectedInvoice->invoice_number }}</h5>
                <a href="{{ route('crm.purchase', request()->except('selectedInvoice')) }}" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <p><strong>Customer:</strong> {{ optional($selectedInvoice->order?->customer)->display_name ?? '—' }}</p>
                <p><strong>Invoice Date:</strong> {{ optional($selectedInvoice->invoice_date)->format('M j, Y') }}</p>
                <p><strong>Payment Status:</strong> {{ $selectedInvoice->payment_status }}</p>
                <p><strong>Payment Method:</strong> {{ $selectedInvoice->payment_method ?? '—' }}</p>
                <p><strong>Total Amount:</strong> ₱{{ number_format($selectedInvoice->total_amount, 2) }}</p>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedInvoice->items as $item)
                        <tr>
                            <td>{{ $item->product?->product_name ?? '—' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->unit_price, 2) }}</td>
                            <td>₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a href="{{ route('crm.purchase.receipt', $selectedInvoice) }}" class="btn btn-outline-secondary">View Receipt</a>
                <a href="{{ route('crm.purchase') }}" class="btn btn-primary">Close</a>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($receiptInvoice))
<div class="modal fade show" id="receiptModal" tabindex="-1" style="display:block;background:rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receipt — {{ $receiptInvoice->invoice_number }}</h5>
                <a href="{{ route('crm.purchase') }}" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h4>Sales Receipt</h4>
                    <p class="text-muted mb-0">{{ $receiptInvoice->invoice_number }}</p>
                </div>
                <p><strong>Customer:</strong> {{ optional($receiptInvoice->order?->customer)->display_name ?? '—' }}</p>
                <p><strong>Date:</strong> {{ optional($receiptInvoice->invoice_date)->format('F j, Y') }}</p>
                <hr>
                @foreach($receiptInvoice->items as $item)
                <div class="d-flex justify-content-between">
                    <span>{{ $item->product?->product_name ?? 'Item' }} x {{ $item->quantity }}</span>
                    <span>₱{{ number_format($item->subtotal, 2) }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-semibold">
                    <span>Total</span>
                    <span>₱{{ number_format($receiptInvoice->total_amount, 2) }}</span>
                </div>
                <p class="mt-3 mb-0"><strong>Status:</strong> {{ $receiptInvoice->payment_status }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('crm.purchase') }}" class="btn btn-primary">Close</a>
            </div>
        </div>
    </div>
</div>
@endif


@endsection