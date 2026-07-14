@extends('layouts.app')

@section('title', 'Generate Invoice')
@section('page-title', 'Generate Invoice')

@section('content')

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

        <div class="info-row">

            <span>Transaction ID</span>

            <strong>

                INVT-{{ str_pad($invoice->invoice_id, 4, '0', STR_PAD_LEFT) }}

            </strong>

        </div>

        <div class="info-row">

            <span>Status</span>

            <span class="badge bg-success">

                Posted

            </span>

        </div>

        <div class="info-row">

            <span>Reference</span>

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

    </div>

    <!-- Finance Transaction -->

    <div class="custom-card">

        <div class="info-title">

            Finance Transaction

        </div>

        <div class="info-row">

            <span>Journal ID</span>

            <strong>

                FIN-{{ str_pad($invoice->invoice_id, 4, '0', STR_PAD_LEFT) }}

            </strong>

        </div>

        <div class="info-row">

            <span>Payment Method</span>

            <strong>

                {{ $invoice->payment_method }}

            </strong>

        </div>

        <div class="info-row">

            <span>Payment Status</span>

            <span class="badge bg-primary">

                {{ $invoice->payment_status }}

            </span>

        </div>

        <div class="info-row">

            <span>Total Amount</span>

            <strong class="text-primary">

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

                            INVT-{{ str_pad($invoice->invoice_id,4,'0',STR_PAD_LEFT) }}

                        </td>

                        <td>

                            <span class="badge bg-primary">

                                Posted

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Finance

                        </td>

                        <td>

                            FIN-{{ str_pad($invoice->invoice_id,4,'0',STR_PAD_LEFT) }}

                        </td>

                        <td>

                            <span class="badge bg-primary">

                                Posted

                            </span>

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