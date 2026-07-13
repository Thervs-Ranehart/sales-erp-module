@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Sales Order Management')

@section('content')

@include('sales.partials.alerts')

    <style>
         
        :root{
            --primary:#5347CE;
            --secondary:#887CFD;
            --accent:#4896FE;
            --success:#16C8C7;
            --bg:#F8FAFC;
            --text:#1F2937;
            --text2:#6B7280;
        }

        body{
            margin:0;
            background:var(--bg);
            font-family:"Segoe UI",sans-serif;
            color:var(--text);
        }

       
        .page-container{
            padding:28px;
        }

        .page-title{
            color:var(--primary);
            font-weight:700;
            margin-bottom:18px;
        }

        /* PROFILE CARD */

        .profile-card{
            background:white;
            padding:28px;
            border-radius:15px;
            box-shadow:0 5px 20px rgba(0,0,0,.08);
        }

        .back-btn{
            display:inline-block;
            padding:8px 25px;
            margin-bottom:20px;
            background:#D8D3FF;
            color:var(--primary);
            border-radius:20px;
            text-decoration:none;
            font-weight:600;
        }

        .back-btn:hover{
            background:var(--primary);
            color:white;
        }

        .profile-title{
            color:var(--primary);
            font-weight:700;
            margin-bottom:25px;
        }

        .info-label{
            font-weight:600;
            color:var(--primary);
        }

        .info-row{
            margin-bottom:12px;
        }

        /* PRODUCT TABLE */

        .product-table{
            margin-top:30px;
            max-width:750px;
        }

        .product-table th{
            background:#F0EFFF;
            color:var(--primary);
        }

        .product-table th,
        .product-table td{
            border:1px solid #887CFD;
            text-align:center;
            padding:10px;
        }

        /* TOTALS */

        .totals{
            width:380px;
            margin-top:25px;
        }

        .total-row{
            display:flex;
            justify-content:space-between;
            margin-bottom:7px;
        }

        .grand-total{
            border-top:1px dashed var(--primary);
            padding-top:10px;
            font-weight:700;
            color:var(--primary);
        }

        /* STATUS */

        .status-section{
            margin-top:30px;
        }

        .status-options label{
            display:block;
            margin-bottom:8px;
        }

        .status-options input{
            accent-color:var(--primary);
            margin-right:8px;
        }

        .update-btn{
            margin-top:15px;
            padding:11px 30px;
            border:none;
            border-radius:8px;
            background:var(--primary);
            color:white;
            font-weight:600;
        }

        .update-btn:hover{
            background:var(--secondary);
        }
    </style>

    <div class="page-container">

        <h4 class="page-title">Order Management</h4>

        <div class="profile-card">

            <a href="{{ route('sales.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <h3 class="profile-title">Customer Profile</h3>


            <div class="row">

                <div class="col-md-6">

                    <div class="info-row">
                        <span class="info-label">Order No.:</span>
                        {{ $order->order_number }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Customer Name:</span>
                        {{ $order->customer?->full_name ?? 'N/A' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Phone Number:</span>
                        {{ $order->customer?->contact_no ?? '—' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Email Address:</span>
                        {{ $order->customer?->email ?? '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-row">
                        <span class="info-label">Order Date:</span>
                        {{ $order->order_date?->format('F j, Y') ?? '—' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Shipping Address:</span><br>
                        {!! nl2br(e($order->customer?->address ?? '—')) !!}
                    </div>

                </div>

            </div>


            <div class="table-responsive product-table">

                <table class="table mb-0">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>QTY</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($order->items as $item)
                        <tr>
                            <td>{{ $item->product?->product_name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td>₱{{ number_format((float) $item->subtotal, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No products on this order.</td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="totals">

                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format((float) $order->subtotal, 0) }}</span>
                </div>

                <div class="total-row">
                    <span>VAT ({{ $order->taxPercent() }}%):</span>
                    <span>₱{{ number_format((float) $order->tax, 0) }}</span>
                </div>

                <div class="total-row">
                    <span>Discount:</span>
                    <span>₱{{ number_format((float) $order->discount, 0) }}</span>
                </div>

                <div class="total-row grand-total">
                    <span>Grand Total:</span>
                    <span>₱{{ number_format((float) $order->total_amount, 0) }}</span>
                </div>

            </div>


            <div class="status-section">

                <h6 class="info-label">Order Status:</h6>

                <form action="{{ route('sales.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="status-options">

                        @foreach (['pending', 'processed', 'shipped', 'delivered', 'cancelled'] as $status)
                        <label>
                            <input type="radio" name="status" value="{{ $status }}"
                                @checked(old('status', $order->order_status) === $status)>
                            {{ ucfirst($status) }}
                        </label>
                        @endforeach

                    </div>

                    <button type="submit" class="update-btn">
                        Update Status
                    </button>
                </form>

            </div>

        </div>

    </div>

@endsection
