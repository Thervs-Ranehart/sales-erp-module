@extends('layouts.app')

@section('title', 'index/Sales Orders')
@section('page-title', 'Sales Order Management')

@section('content')

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

   /* ================= STATUS BADGES ================= */


.status{
    display:inline-block;
    min-width:90px;
    padding:7px 12px;
    border-radius:20px;
    text-align:center;
    font-size:11px;
    font-weight:700;
}

/* Pending - Deep Lavender */
.status-pending{
    background:#AFA5F5;
    color:#33279B;
}

/* Ordered - Strong Accent Blue */
.status-processed{
    background:#86BAFF;
    color:#174F9B;
}

/* Shipped - Dashboard Purple */
.status-shipped{
    background:#9B8FE8;
    color:#3F348F;
}

/* Delivered - Dashboard Turquoise */
.status-delivered{
    background:#70D8D3;
    color:#086B6A;
}

/* ================= ACTION BUTTONS ================= */

.action-btn{
    width:36px;
    height:36px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:none;
    border-radius:8px;
    text-decoration:none;
}
/* View */
.view-btn{
    background:#4896FE;
    color:#FFFFFF;
}

/* Edit */
.edit-btn{
    background:#887CFD;
    color:#FFFFFF;
}

/* Delete */
.delete-btn{
    background:#5347CE;
    color:#FFFFFF;
}

/* Hover Effects */
.view-btn:hover{
    background:#2876D8;
    color:#FFFFFF;
    transform:translateY(-2px);
}

.edit-btn:hover{
    background:#7164E8;
    color:#FFFFFF;
    transform:translateY(-2px);
}

.delete-btn:hover{
    background:#4035A8;
    color:#FFFFFF;
    transform:translateY(-2px);
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

            <button class="new-order-btn">
                <i class="bi bi-plus-circle"></i>
                New Sales Order
            </button>

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
                        All (5)
                    </button>


                    <button
                        class="filter-btn"
                        onclick="filterOrders('pending', this)"
                    >
                        Pending (1)
                    </button>


                    <button
    class="filter-btn"
    onclick="filterOrders('processed', this)"
>
    Processed (2)
</button>


                    <button
                        class="filter-btn"
                        onclick="filterOrders('shipped', this)"
                    >
                        Shipped (1)
                    </button>


                    <button
                        class="filter-btn"
                        onclick="filterOrders('delivered', this)"
                    >
                        Delivered (1)
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


                        <!-- ORDER 1 -->

                        <tr data-status="pending">

                            <td class="order-id">
                                SO-001
                            </td>

                            <td>
                                ABC Company
                            </td>

                            <td>
                                July 7, 2026

                                <span class="small-text">
                                    Due July 18, 2026
                                </span>
                            </td>

                            <td>
                                ₱25,000
                            </td>

                            <td class="discount">
                                - ₱1,000

                                <span class="small-text">
                                    4%
                                </span>
                            </td>

                            <td>
                                ₱2,880

                                <span class="small-text">
                                    12%
                                </span>
                            </td>

                            <td>
                                ₱26,880
                            </td>

                            <td>

                                <span class="status status-pending">
                                    Pending
                                </span>

                            </td>

                            <td>
                                  <a href="{{ route('sales.profile', ['id' => 'SO-001']) }}"
   class="action-btn view-btn">
    <i class="bi bi-eye"></i>
</a>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>

                        </tr>


                        <!-- ORDER 2 -->
                            <tr data-status="processed">

                            <td class="order-id">
                                SO-002
                            </td>

                            <td>
                                XYZ Trading
                            </td>

                            <td>
                                July 8, 2026

                                <span class="small-text">
                                    Due July 20, 2026
                                </span>
                            </td>

                            <td>
                                ₱18,500
                            </td>

                            <td class="discount">
                                - ₱500
                            </td>

                            <td>
                                ₱2,160
                            </td>

                            <td>
                                ₱20,160
                            </td>

                            <td>

                               <span class="status status-processed">
    Processed
</span>

                            </td>

                            <td>

                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>

                        </tr>


                        <!-- ORDER 3 -->

                        <tr data-status="shipped">

                            <td class="order-id">
                                SO-003
                            </td>

                            <td>
                                John Doe
                            </td>

                            <td>
                                July 9, 2026

                                <span class="small-text">
                                    Due July 21, 2026
                                </span>
                            </td>

                            <td>
                                ₱31,200
                            </td>

                            <td class="discount">
                                - ₱1,200
                            </td>

                            <td>
                                ₱3,600
                            </td>

                            <td>
                                ₱33,600
                            </td>

                            <td>

                                <span class="status status-shipped">
                                    Shipped
                                </span>

                            </td>

                            <td>

                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>

                        </tr>


                        <!-- ORDER 4 -->

                        <tr data-status="delivered">

                            <td class="order-id">
                                SO-004
                            </td>

                            <td>
                                Maria Santos
                            </td>

                            <td>
                                July 10, 2026
                            </td>

                            <td>
                                ₱15,000
                            </td>

                            <td class="discount">
                                - ₱500
                            </td>

                            <td>
                                ₱1,740
                            </td>

                            <td>
                                ₱16,240
                            </td>

                            <td>

                                <span class="status status-delivered">
                                    Delivered
                                </span>

                            </td>

                            <td>

                                <button class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>

                        </tr>


                      
                        </tr>


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