@extends('layouts.app')

@section('title', $invoice->payment_status === 'Paid' ? 'Payment Receipt' : 'Invoice Details')
@section('page-title', $invoice->payment_status === 'Paid' ? 'Payment Receipt' : 'Invoice Details')

@section('content')

@php
    $rewardDiscountSources = collect($invoice->salesOrder?->rewardRedemptions)
        ->where('status', 'Fulfilled')
        ->pluck('reward.name');

    $discountSources = collect([$invoice->salesOrder?->pricingRule?->rule_name])
        ->merge($rewardDiscountSources)
        ->filter()
        ->unique()
        ->values();
@endphp

<style>

:root{
    --primary:#5347CE;
    --secondary:#887CFD;
    --accent:#4896FE;
    --success:#16C8C7;
    --border:#E5E7EB;
    --light-purple:#EEECFF;
    --text:#1F2937;
    --text2:#6B7280;
}

.page-content{
    max-width:1280px;
    margin:0 auto;
    padding:32px 28px 48px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:24px;
    margin-bottom:28px;
    padding:28px 30px;
    border-radius:20px;
    color:#fff;
    background:linear-gradient(125deg, #4537b8 0%, #6657dc 58%, #8d7ff5 100%);
    box-shadow:0 16px 32px rgba(83,71,206,.20);
}

.page-title{
    margin:0;
    font-size:28px;
    font-weight:700;
}

.page-subtitle{
    margin:7px 0 0;
    color:rgba(255,255,255,.80);
}

.receipt-label{
    display:inline-flex;
    align-items:center;
    gap:7px;
    margin-bottom:9px;
    color:#e9e6ff;
    font-size:12px;
    font-weight:700;
    letter-spacing:.08em;
    text-transform:uppercase;
}

.page-header .btn{ border-radius:9px; font-weight:600; padding:10px 15px; }
.page-header .btn-outline-secondary{ border-color:rgba(255,255,255,.55); color:#fff; background:rgba(255,255,255,.08); }
.page-header .btn-outline-secondary:hover{ background:#fff; border-color:#fff; color:var(--primary); }
.page-header .btn-primary{ border-color:#fff; color:var(--primary); background:#fff; }
.page-header .btn-primary:hover{ background:#efedff; border-color:#efedff; color:#4135ad; }

.custom-card{
    border:1px solid #edf0f5;
    border-radius:18px;
    padding:24px;
    box-shadow:0 8px 24px rgba(31,41,55,.055);
    background:#fff;
    margin-bottom:24px;
}

.info-title{
    color:var(--primary);
    font-weight:700;
    font-size:16px;
    margin-bottom:15px;
}

.info-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:18px;
    padding:12px 0;
    border-bottom:1px solid var(--border);
}

.info-row:last-of-type{
    border-bottom:none;
}

.info-row span{
    color:var(--text2);
}

.status{
    display:inline-block;
    padding:4px 12px;
    border-radius:20px;
    background:#dcfce7;
    color:#15803d;
    font-weight:600;
    font-size:13px;
}

.summary-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 0;
}

.summary-card{ border-color:#dfdbff; background:linear-gradient(145deg, #fff 0%, #f7f6ff 100%); }
.summary-card .info-title{ display:flex; align-items:center; gap:9px; }
.summary-card .info-title::before{ content:''; width:4px; height:19px; border-radius:10px; background:var(--primary); }
.summary-item span{ color:var(--text2); }
.summary-item strong{ font-variant-numeric:tabular-nums; }
.grand-total{ margin:11px -4px -4px; padding:16px 18px; border-radius:13px; background:var(--primary); color:#fff; }
.grand-total span{ color:#fff; font-size:17px; font-weight:700; }
.grand-total strong{ color:#fff !important; font-size:28px !important; }

.invoice-info-card{ height:calc(100% - 24px); }
.transaction-card .info-title{ display:flex; align-items:center; gap:8px; }
.transaction-card .info-title::before{ content:''; width:9px; height:9px; border-radius:50%; background:#16c8c7; box-shadow:0 0 0 4px #d9fbf9; }

@media (max-width:767px){
    .page-content{ padding:20px 15px 35px; }
    .page-header{ align-items:flex-start; flex-direction:column; padding:23px; }
    .page-header .d-flex{ width:100%; }
    .page-header .btn{ flex:1; }
}

@media print{
    .page-header{ color:var(--text); background:#fff !important; box-shadow:none; border:1px solid var(--border); }
    .page-header .btn, .mt-4.d-flex{ display:none !important; }
    .page-subtitle{ color:var(--text2); }
    .custom-card{ box-shadow:none; }
}

</style>

<div class="page-content">

    <div class="page-header">

        <div>

            <div class="receipt-label">
                <i class="bi bi-receipt-cutoff"></i>
                {{ $invoice->payment_status === 'Paid' ? 'Payment received' : 'Invoice record' }}
            </div>

            <h2 class="page-title">
                {{ $invoice->payment_status === 'Paid' ? 'Payment Receipt' : 'Invoice Details' }}
            </h2>

            <p class="page-subtitle">
                {{ $invoice->payment_status === 'Paid' ? 'Payment confirmation and complete invoice summary.' : 'ERP transaction summary for this invoice.' }}
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('invoices.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <button
                class="btn btn-primary"
                onclick="window.print()"
            >
                <i class="bi bi-printer"></i>
                Print Invoice
            </button>

        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-6">

            <div class="custom-card invoice-info-card">

                <div class="info-title">

                    Invoice Information

                </div>

                <div class="info-row">

                    <span>Invoice Number</span>

                    <strong>

                        {{ $invoice->invoice_number }}

                    </strong>

                </div>

                <div class="info-row">

                    <span>Sales Order</span>

                    <strong>

                        {{ $invoice->salesOrder->order_number }}

                    </strong>

                </div>

                <div class="info-row">

                    <span>Customer</span>

                    <strong>

                        {{ $invoice->salesOrder->customer->full_name }}

                    </strong>

                </div>

                <div class="info-row">

                    <span>Invoice Date</span>

                    <strong>

                        {{ optional($invoice->invoice_date)->format('F d, Y') }}

                    </strong>

                </div>

                <div class="info-row">

                    <span>Payment Method</span>

                    <strong>

                        {{ $invoice->payment_method }}

                    </strong>

                </div>

                <div class="info-row">

                    <span>Status</span>

                    <span class="status">

                        {{ $invoice->payment_status }}

                    </span>

                </div>

            </div>

        </div>
        <div class="col-lg-6">

    <!-- Inventory Transaction -->

    <div class="custom-card transaction-card mb-4">

        <div class="info-title">

            Inventory Transaction

        </div>

        @forelse ($invoice->inventoryTransactions as $inventoryTransaction)

        <div class="info-row">

            <span>Transaction ID</span>

            <strong>

                INVT-{{ str_pad($inventoryTransaction->inventory_transaction_id, 4, '0', STR_PAD_LEFT) }}

            </strong>

        </div>

        <div class="info-row">

            <span>Product</span>

            <strong>

                {{ optional($inventoryTransaction->product)->product_name ?? '—' }}

            </strong>

        </div>

        <div class="info-row">

            <span>Quantity Out</span>

            <strong>

                {{ $inventoryTransaction->quantity_out }}

            </strong>

        </div>

        <div class="info-row">

            <span>Transaction Date</span>

            <strong>

                {{ optional($inventoryTransaction->transaction_date)->format('F d, Y') }}

            </strong>

        </div>

        <div class="info-row">

            <span>Status</span>

            <span class="badge bg-success">

                Posted

            </span>

        </div>

        <hr>

        @empty

        <div class="info-row">

            <span class="text-muted">No inventory transactions recorded for this invoice.</span>

        </div>

        @endforelse

    </div>

    <!-- Finance Transaction -->

    <div class="custom-card transaction-card">

        <div class="info-title">

            Finance Transaction

        </div>

        @forelse ($invoice->financeTransactions as $financeTransaction)

        <div class="info-row">

            <span>Journal ID</span>

            <strong>

                FIN-{{ str_pad($financeTransaction->finance_transaction_id, 4, '0', STR_PAD_LEFT) }}

            </strong>

        </div>

        <div class="info-row">

            <span>Payment Method</span>

            <strong>

                {{ $financeTransaction->payment_method }}

            </strong>

        </div>

        <div class="info-row">

            <span>Transaction Date</span>

            <strong>

                {{ optional($financeTransaction->transaction_date)->format('F d, Y') }}

            </strong>

        </div>

        <div class="info-row">

            <span>Amount</span>

            <strong class="text-primary">

                ₱{{ number_format($financeTransaction->amount, 2) }}

            </strong>

        </div>

        @empty

        <div class="info-row">

            <span class="text-muted">No finance transaction recorded for this invoice.</span>

        </div>

        @endforelse

    </div>

</div>

</div>
<div class="row mt-4">

    <div class="col-lg-12">

        <div class="custom-card summary-card">

            <div class="info-title">

                Invoice Summary

            </div>

            <div class="summary-item">

                <span>Subtotal</span>

                <strong>

                    ₱{{ number_format($invoice->subtotal,2) }}

                </strong>

            </div>

            <div class="summary-item">

                <span>
                    Discount{{ $discountSources->isNotEmpty() ? ': '.$discountSources->implode(' + ') : '' }}
                </span>

                <strong class="text-danger">

                    - ₱{{ number_format($invoice->discount,2) }}

                </strong>

            </div>

            <div class="summary-item">

                <span>Tax</span>

                <strong>

                    ₱{{ number_format($invoice->tax,2) }}

                </strong>

            </div>

            <div class="summary-item">

                <span>Shipping Fee</span>

                <strong>

                    ₱{{ number_format($invoice->shipping_fee,2) }}

                </strong>

            </div>

            <hr>

            <div class="summary-item grand-total">

                <span>
                    Grand Total
                </span>

                <strong>

                    ₱{{ number_format($invoice->total_amount,2) }}

                </strong>

            </div>

        </div>

    </div>

</div>
<div class="row mt-4">

    <div class="col-lg-12">

        <div class="custom-card">

            <div class="info-title">

                ERP Synchronization Summary

            </div>

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>Module</th>

                        <th>Reference</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>

                            Sales Order

                        </td>

                        <td>

                            {{ $invoice->salesOrder->order_number }}

                        </td>

                        <td>

                            <span class="badge bg-success">

                                Linked

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Customer

                        </td>

                        <td>

                            {{ $invoice->salesOrder->customer->full_name }}

                        </td>

                        <td>

                            <span class="badge bg-success">

                                Verified

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Inventory

                        </td>

                        <td>

                            @forelse ($invoice->inventoryTransactions as $inventoryTransaction)
                                INVT-{{ str_pad($inventoryTransaction->inventory_transaction_id, 4, '0', STR_PAD_LEFT) }}@if (!$loop->last), @endif
                            @empty
                                —
                            @endforelse

                        </td>

                        <td>

                            @if ($invoice->inventoryTransactions->isNotEmpty())
                            <span class="badge bg-primary">

                                Posted

                            </span>
                            @else
                            <span class="badge bg-secondary">

                                None

                            </span>
                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Finance

                        </td>

                        <td>

                            @forelse ($invoice->financeTransactions as $financeTransaction)
                                FIN-{{ str_pad($financeTransaction->finance_transaction_id, 4, '0', STR_PAD_LEFT) }}
                            @empty
                                —
                            @endforelse

                        </td>

                        <td>

                            @if ($invoice->financeTransactions->isNotEmpty())
                            <span class="badge bg-primary">

                                Posted

                            </span>
                            @else
                            <span class="badge bg-secondary">

                                None

                            </span>
                            @endif

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Invoice

                        </td>

                        <td>

                            {{ $invoice->invoice_number }}

                        </td>

                        <td>

                            <span class="badge bg-success">

                                Generated

                            </span>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card mt-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Credit Note / Product Return</h5>
                <p class="text-muted small mb-0">Issue a controlled refund while restoring returned stock and recording the finance reversal.</p>
            </div>
            <span class="badge bg-light text-dark border">{{ $invoice->creditNotes->count() }} issued</span>
        </div>

        @foreach($invoice->creditNotes as $creditNote)
            <div class="alert alert-light border d-flex justify-content-between gap-3">
                <span><strong>{{ $creditNote->credit_note_number }}</strong> — {{ $creditNote->reason }}</span>
                <strong>−₱{{ number_format((float) $creditNote->amount, 2) }}</strong>
            </div>
        @endforeach

        @if(!in_array($invoice->payment_status, ['Cancelled', 'Expired']))
            <form method="POST" action="{{ route('invoices.credit-notes.store', $invoice) }}">
                @csrf
                <div class="table-responsive mb-3">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Product</th><th>Invoiced</th><th style="width:170px">Return quantity</th></tr></thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                                @php
                                    $credited = $invoice->creditNotes->where('status', 'Issued')
                                        ->flatMap->items
                                        ->where('invoice_item_id', $item->invoice_item_id)
                                        ->sum('quantity');
                                    $refundable = max(0, $item->quantity - $credited);
                                @endphp
                                @if($refundable > 0)
                                    <tr>
                                        <td>{{ $item->product?->product_name ?? 'Product #'.$item->product_id }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><input type="number" class="form-control form-control-sm" name="quantities[{{ $item->invoice_item_id }}]" min="0" max="{{ $refundable }}" value="0"></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex gap-2">
                    <input class="form-control" name="reason" required placeholder="Reason for refund, return, or adjustment">
                    <button class="btn btn-outline-danger text-nowrap" type="submit"><i class="bi bi-arrow-counterclockwise"></i> Issue credit note</button>
                </div>
            </form>
        @endif
    </div>
</div>

<div class="mt-4 d-flex justify-content-end gap-2">

    <a
        href="{{ route('invoices.edit',$invoice) }}"
        class="btn btn-warning"
    >

        <i class="bi bi-pencil-square"></i>

        Edit Invoice

    </a>

    <a
        href="{{ route('invoices.index') }}"
        class="btn btn-secondary"
    >

        <i class="bi bi-arrow-left"></i>

        Back to Invoices

    </a>

    <button
        onclick="window.print()"
        class="btn btn-primary"
    >

        <i class="bi bi-printer"></i>

        Print

    </button>

</div>

@endsection
