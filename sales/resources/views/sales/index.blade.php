@extends('layouts.app')

@section('title', 'index/Sales Orders')
@section('page-title', 'Sales Order Management')

@section('content')

    @include('sales.partials.alerts')

    <style>
       
        :root{
            --primary:#5347CE;
            --secondary:#887CFD;
            --accent:#4896FE;
            --success:#16C8C7;
            --white:#FFFFFF;
            --bg:#F8FAFC;
            --text:#1F2937;
            --text2:#6B7280;
        }

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            background:var(--bg);
            font-family:"Segoe UI",sans-serif;
            color:var(--text);
        }

    
        /* ================= PAGE ================= */

        .page-container{
            padding:28px;
        }

        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:22px;
        }

        .page-header h2{
            margin:0;
            font-size:30px;
            font-weight:600;
        }

        .new-order-btn{
            background:var(--primary);
            border:none;
            color:white;
            padding:12px 20px;
            border-radius:8px;
            transition:.2s;
        }

        .new-order-btn:hover{
            background:var(--secondary);
        }

        /* ================= ORDER MANAGEMENT CARD ================= */

        .order-card{
            background:white;
            border-radius:18px;
            box-shadow:0 5px 20px rgba(0,0,0,.07);
            overflow:hidden;
        }

        .order-header{
            padding:24px 26px 18px;
            border-bottom:1px solid #E5E7EB;
        }

        .order-title-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
            margin-bottom:22px;
        }

        .order-title-row h4{
            margin:0;
            font-weight:700;
            color:var(--text);
        }

        /* SEARCH */

        .search-box{
            width:350px;
            height:42px;
            display:flex;
            align-items:center;
            gap:10px;
            padding:0 15px;
            background:var(--bg);
            border:1px solid #E5E7EB;
            border-radius:25px;
        }

        .search-box i{
            color:var(--text2);
        }

        .search-box input{
            width:100%;
            border:none;
            outline:none;
            background:transparent;
            font-size:14px;
        }

        /* ================= FILTERS ================= */

        .filter-container{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }

        .filter-btn{
            border:none;
            padding:10px 22px;
            border-radius:25px;
            background:#F1F3F7;
            color:var(--text2);
            font-size:14px;
            transition:.2s;
        }

        .filter-btn:hover{
            background:var(--secondary);
            color:white;
        }

        .filter-btn.active{
            background:var(--primary);
            color:white;
        }

        /* ================= TABLE ================= */

        .table-responsive{
            overflow-x:auto;
        }

        .order-table{
            width:100%;
            border-collapse:collapse;
            min-width:1000px;
        }

        .order-table thead{
            background:#F3F4F8;
        }

        .order-table th{
            padding:16px 20px;
            color:var(--primary);
            font-size:12px;
            font-weight:700;
            text-transform:uppercase;
            border-bottom:2px solid var(--primary);
            white-space:nowrap;
        }

        .order-table td{
            padding:18px 20px;
            font-size:14px;
            border-bottom:1px solid #E5E7EB;
            vertical-align:middle;
        }

        .order-table tbody tr:hover{
            background:#FAFAFF;
        }

        .order-id{
            color:var(--primary);
            font-weight:600;
        }

        .small-text{
            display:block;
            margin-top:3px;
            color:var(--text2);
            font-size:11px;
        }

        .discount{
            color:#DC3545;
        }

   /* ================= STATUS ================= */

/* ================= STATUS ================= */

.status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    min-width:100px;
}

/* Pending */
.status-pending{
    background:#FBBF24;
    color:#1F2937;
}

/* Processed */
.status-processed{
    background:#2563EB;
    color:#FFFFFF;
}

/* Shipped */
.status-shipped{
    background:#5347CE;
    color:#FFFFFF;
}

/* Delivered */
.status-delivered{
    background:#198754;
    color:#FFFFFF;
}

/* Active */
.status-active{
    background:#198754;
    color:#FFFFFF;
}

/* Draft */
.status-draft{
    background:#E9D5FF;
    color:#6B21A8;
}
/* ================= ACTION BUTTONS ================= */

.action-btn{
    width:40px;
    height:40px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    border:1.5px solid;
    text-decoration:none;
    transition:.25s;
    margin-right:6px;
    background:#fff;
}

/* View */

.view-btn{
    border-color:#2563EB;
    color:#2563EB;
}

.view-btn:hover{
    background:#2563EB;
    color:#fff;
}

/* Edit */

.edit-btn{
    border-color:#F4B400;
    color:#F4B400;
}

.edit-btn:hover{
    background:#F4B400;
    color:#111827;
}

/* Delete */

.delete-btn{
    border-color:#EF4444;
    color:#EF4444;
}

.delete-btn:hover{
    background:#EF4444;
    color:#fff;
}
        /* ================= RESPONSIVE ================= */

        @media(max-width:900px){

            .sidebar{
                width:230px;
            }

            .content{
                margin-left:230px;
            }

            .order-title-row{
                flex-direction:column;
                align-items:flex-start;
            }

            .search-box{
                width:100%;
            }
        }

    </style>
</head>

<body>


    <!-- PAGE CONTENT -->

    <div class="page-container">


        <!-- PAGE TITLE -->

        <div class="page-header">

            <h2>Sales Orders</h2>

           <a href="{{ route('sales.create') }}" class="new-order-btn">
    <i class="bi bi-plus-circle"></i>
    New Sales Order
</a>

        </div>


        <!-- ORDER CARD -->

        <div class="order-card">


            <!-- ORDER HEADER -->

            <div class="order-header">

                <div class="order-title-row">

                    <h4>Order Management</h4>


                    <!-- SEARCH -->

                    <div class="search-box">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="orderSearch"
                            placeholder="Search Order ID or Customer Name"
                            onkeyup="searchOrders()"
                        >

                    </div>

                </div>


                <!-- STATUS FILTERS -->

                <div class="filter-container">

                    <button
                        class="filter-btn active"
                        onclick="filterOrders('all', this)"
                    >
                        All ({{ $statusCounts['all'] ?? 0 }})
                    </button>


                    <button
                        class="filter-btn"
                        onclick="filterOrders('pending', this)"
                    >
                        Pending ({{ $statusCounts['pending'] ?? 0 }})
                    </button>


                    <button
    class="filter-btn"
    onclick="filterOrders('processed', this)"
>
    Processed ({{ $statusCounts['processed'] ?? 0 }})
</button>


                    <button
                        class="filter-btn"
                        onclick="filterOrders('shipped', this)"
                    >
                        Shipped ({{ $statusCounts['shipped'] ?? 0 }})
                    </button>


                    <button
                        class="filter-btn"
                        onclick="filterOrders('delivered', this)"
                    >
                        Delivered ({{ $statusCounts['delivered'] ?? 0 }})
                    </button>

                </div>

            </div>


            <!-- TABLE -->

            <div class="table-responsive">

                <table class="order-table" id="orderTable">

                    <thead>

                        <tr>

                            <th>Order ID</th>

                            <th>Customer</th>

                            <th>Order Date</th>

                            <th>Subtotal</th>

                            <th>Discount</th>

                            <th>Tax</th>

                            <th>Total</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($orders as $order)
                        <tr data-status="{{ strtolower($order->order_status) }}">

                            <td class="order-id">
                                {{ $order->order_number }}
                            </td>

                            <td>
                               {{ $order->customer?->full_name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $order->order_date?->format('F j, Y') ?? '—' }}
                            </td>

                            <td>
                                ₱{{ number_format((float) $order->subtotal, 0) }}
                            </td>

                            <td class="discount">
                                - ₱{{ number_format((float) $order->discount, 0) }}

                                @if ($order->discountPercent() > 0)
                                <span class="small-text">
                                    {{ $order->discountPercent() }}%
                                </span>
                                @endif
                            </td>

                            <td>
                                ₱{{ number_format((float) $order->tax, 0) }}

                                @if ($order->taxPercent() > 0)
                                <span class="small-text">
                                    {{ $order->taxPercent() }}%
                                </span>
                                @endif
                            </td>

                            <td>
                                ₱{{ number_format((float) $order->total_amount, 0) }}
                            </td>

                            <td>
                                <span class="status {{ $order->statusCssClass() }}">
                                    {{ $order->formattedStatus() }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('sales.profile', $order) }}"
                                   class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('sales.edit', $order) }}"
                                   class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('sales.destroy', $order) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Delete this sales order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                No sales orders found. Create your first order to get started.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- ==================================================
                    JAVASCRIPT
================================================== -->

<script>

    /*
    |--------------------------------------------------------------------------
    | FILTER ORDERS
    |--------------------------------------------------------------------------
    */

    function filterOrders(status, button){

        const rows =
            document.querySelectorAll(
                '#orderTable tbody tr'
            );


        rows.forEach(row => {

            if(
                status === 'all' ||
                row.dataset.status === status
            ){

                row.style.display = '';

            }
            else{

                row.style.display = 'none';

            }

        });


        document
            .querySelectorAll('.filter-btn')
            .forEach(btn => {

                btn.classList.remove('active');

            });


        button.classList.add('active');

    }



    /*
    |--------------------------------------------------------------------------
    | SEARCH ORDERS
    |--------------------------------------------------------------------------
    */

    function searchOrders(){

        const search =
            document
                .getElementById('orderSearch')
                .value
                .toLowerCase();


        const rows =
            document.querySelectorAll(
                '#orderTable tbody tr'
            );


        rows.forEach(row => {

            const orderText =
                row.innerText.toLowerCase();


            if(orderText.includes(search)){

                row.style.display = '';

            }
            else{

                row.style.display = 'none';

            }

        });

    }

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
@endsection