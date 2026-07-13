@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Sales Order Management')

@section('content')
 
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
</head>

<body>



<div class="content">

    <div class="topbar">

        <h3>Sales Order Management</h3>

        <div class="top-icons">
            <i class="bi bi-bell"></i>
            <i class="bi bi-envelope"></i>
            <i class="bi bi-question-circle"></i>
            <i class="bi bi-person-circle"></i>
        </div>

    </div>


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
                        {{ $id ?? 'SO-001' }}
                    </div>

                    <div class="info-row">
                        <span class="info-label">Customer Name:</span>
                        Adelaide Ful
                    </div>

                    <div class="info-row">
                        <span class="info-label">Phone Number:</span>
                        0917-123-4567
                    </div>

                    <div class="info-row">
                        <span class="info-label">Email Address:</span>
                        fiona@gmail.com
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="info-row">
                        <span class="info-label">Order Date:</span>
                        July 9, 2026
                    </div>

                    <div class="info-row">
                        <span class="info-label">Shipping Address:</span><br>
                        067 Latania St., Tanza<br>
                        Island, Cavite
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

                        <tr>
                            <td>PC</td>
                            <td>1</td>
                            <td>₱45,000.00</td>
                            <td>₱45,000.00</td>
                        </tr>

                        <tr>
                            <td>Monitor</td>
                            <td>1</td>
                            <td>₱8,500.00</td>
                            <td>₱8,500.00</td>
                        </tr>

                        <tr>
                            <td>Keyboard</td>
                            <td>1</td>
                            <td>₱2,500.00</td>
                            <td>₱2,500.00</td>
                        </tr>

                    </tbody>

                </table>

            </div>


            <div class="totals">

                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>₱56,000</span>
                </div>

                <div class="total-row">
                    <span>VAT (12%):</span>
                    <span>₱6,720</span>
                </div>

                <div class="total-row">
                    <span>Discount:</span>
                    <span>₱1,000</span>
                </div>

                <div class="total-row grand-total">
                    <span>Grand Total:</span>
                    <span>₱61,720</span>
                </div>

            </div>


            <div class="status-section">

                <h6 class="info-label">Order Status:</h6>

                <div class="status-options">

                    <label>
                        <input type="radio" name="status" value="pending" checked>
                        Pending
                    </label>

                    <label>
                        <input type="radio" name="status" value="processed">
                        Processed
                    </label>

                    <label>
                        <input type="radio" name="status" value="shipped">
                        Shipped
                    </label>

                    <label>
                        <input type="radio" name="status" value="delivered">
                        Delivered
                    </label>

                    <label>
                        <input type="radio" name="status" value="cancelled">
                        Cancelled
                    </label>

                </div>

                <button class="update-btn">
                    Update Status
                </button>

            </div>

        </div>

    </div>

</div>

</body>
</html>
@endsection