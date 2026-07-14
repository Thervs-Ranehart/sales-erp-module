@extends('layouts.app')

@section('title', 'Generate Invoice')
@section('page-title', 'Generate Invoice')

@section('content')

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
    padding:28px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.page-title{
    margin:0;
    font-size:28px;
    font-weight:700;
}

.page-subtitle{
    margin:5px 0 0;
    color:var(--text2);
}

.custom-card{
    border:none;
    border-radius:16px;
    padding:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.06);
    background:#fff;
    margin-bottom:24px;
}

.info-title{
    color:var(--primary);
    font-weight:700;
    font-size:17px;
    margin-bottom:18px;
}

.info-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 0;
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
    background:var(--light-purple);
    color:var(--primary);
    font-weight:600;
    font-size:13px;
}

.summary-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:8px 0;
}

</style>

<div class="page-content">

    <div class="page-header">

        <div>

            <h2 class="page-title">
                Invoice Generated
            </h2>

            <p class="page-subtitle">
                ERP transaction summary for this invoice.
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

            <div class="custom-card">

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

    <div class="custom-card mb-4">

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

    <div class="custom-card">

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

        <div class="custom-card">

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

                <span>Discount</span>

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

            <div class="summary-item">

                <span
                    style="
                        font-size:18px;
                        font-weight:700;
                    "
                >
                    Grand Total
                </span>

                <strong
                    style="
                        font-size:26px;
                        color:#5347CE;
                    "
                >

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