<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invoices</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
          /* Your existing CSS */

        .sidebar .sub-menu {
            padding-left: 50px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.08);
            border-left: 3px solid #887CFD;
        }

        .sidebar .sub-menu:hover {
            background: #887CFD;
        }

        .sidebar .sub-menu.active {
            background: #16C8C7;
            border-left: 4px solid #fff;
        }
        :root{
            --primary:#5347CE;
            --secondary:#887CFD;
            --accent:#4896FE;
            --success:#16C8C7;
            --white:#FFFFFF;
            --bg:#F8FAFC;
            --text:#1F2937;
            --text2:#6B7280;
            --border:#E5E7EB;
            --light-purple:#EEECFF;
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

        /* SIDEBAR */

        .sidebar{
            position:fixed;
            top:0;
            left:0;
            width:285px;
            height:100vh;
            background:var(--primary);
            color:white;
            overflow-y:auto;
        }

        .logo{
            padding:27px;
            font-size:27px;
            font-weight:700;
        }

        .menu-title{
            padding:18px 27px 8px;
            font-size:11px;
            font-weight:600;
            text-transform:uppercase;
            color:rgba(255,255,255,.65);
        }

        .sidebar a{
            display:flex;
            align-items:center;
            gap:12px;
            margin:3px 14px;
            padding:13px 15px;
            border-radius:8px;
            color:white;
            text-decoration:none;
            font-size:15px;
            transition:.2s;
        }

        .sidebar a i{
            width:22px;
            font-size:18px;
        }

        .sidebar a:hover{
            background:var(--secondary);
        }

        .sidebar a.active{
            background:white;
            color:var(--primary);
            font-weight:600;
        }

        /* MAIN */

        .main-content{
            margin-left:285px;
            min-height:100vh;
        }

        /* TOPBAR */

        .topbar{
            height:82px;
            background:white;
            display:flex;
            align-items:center;
            padding:0 30px;
            box-shadow:0 2px 10px rgba(0,0,0,.06);
        }

        .topbar-title{
            font-size:25px;
            font-weight:600;
            margin:0;
        }

        .top-icons{
            margin-left:auto;
            display:flex;
            align-items:center;
            gap:18px;
            color:var(--primary);
            font-size:19px;
        }

        .profile-icon{
            font-size:32px;
            color:var(--accent);
        }

        /* PAGE */

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

        .new-btn{
            display:inline-flex;
            align-items:center;
            gap:7px;
            background:var(--primary);
            color:white;
            border:none;
            padding:11px 20px;
            border-radius:8px;
            font-weight:600;
            text-decoration:none;
        }

        .new-btn:hover{
            background:var(--secondary);
            color:white;
        }

        /* SEARCH */

        .toolbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:15px;
            margin-bottom:20px;
        }

        .search-box{
            width:380px;
            position:relative;
        }

        .search-box i{
            position:absolute;
            left:15px;
            top:50%;
            transform:translateY(-50%);
            color:var(--text2);
        }

        .search-box input{
            width:100%;
            border:1px solid var(--border);
            border-radius:25px;
            padding:11px 15px 11px 42px;
            outline:none;
        }

        /* FILTERS */

        .filter-buttons{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-bottom:22px;
        }

        .filter-btn{
            border:none;
            background:white;
            color:var(--primary);
            padding:10px 22px;
            border-radius:25px;
            font-size:14px;
            box-shadow:0 2px 8px rgba(0,0,0,.04);
        }

        .filter-btn.active{
            background:#DCD8FF;
            color:#4035A8;
            font-weight:600;
        }

        /* TABLE */

        .table-card{
            background:white;
            border-radius:16px;
            padding:20px;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
        }

        .table{
            margin-bottom:0;
            vertical-align:middle;
        }

        .table thead th{
            background:var(--light-purple);
            color:var(--primary);
            border-bottom:2px solid var(--primary);
            padding:15px 12px;
            font-size:13px;
            white-space:nowrap;
        }

        .table tbody td{
            padding:16px 12px;
            border-color:#E8EAF0;
            font-size:14px;
            white-space:nowrap;
        }

        /* STATUS */

        .status{
            display:inline-block;
            min-width:92px;
            padding:7px 13px;
            border-radius:20px;
            text-align:center;
            font-size:11px;
            font-weight:700;
        }

        .status-paid{
            background:#77D9D4;
            color:#075F5E;
        }

        .status-pending{
            background:#B5ACF4;
            color:#392E9C;
        }

        .status-overdue{
            background:#A9BFE8;
            color:#294E89;
        }

        .status-draft{
            background:#D0CCF8;
            color:#4A419B;
        }

        /* ACTION BUTTONS */

        .action-btn{
            width:36px;
            height:36px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border:none;
            border-radius:8px;
            margin-right:4px;
            color:white;
            text-decoration:none;
            transition:.2s;
        }

        .view-btn{
            background:var(--accent);
        }

        .edit-btn{
            background:var(--secondary);
        }

        .download-btn{
            background:var(--success);
        }

        .delete-btn{
            background:var(--primary);
        }

        .action-btn:hover{
            color:white;
            opacity:.85;
            transform:translateY(-2px);
        }

        @media(max-width:900px){
            .sidebar{
                width:220px;
            }

            .main-content{
                margin-left:220px;
            }

            .page-header{
                flex-direction:column;
                align-items:flex-start;
                gap:15px;
            }
        }
    </style>
</head>

<body>
@include('sales.partials.sidebar')
<!-- MAIN CONTENT -->

<div class="main-content">

    <!-- TOPBAR -->

    <div class="topbar">

        <h3 class="topbar-title">
            Sales Order Management
        </h3>

        <div class="top-icons">
            <i class="bi bi-bell"></i>
            <i class="bi bi-envelope"></i>
            <i class="bi bi-question-circle"></i>
            <i class="bi bi-person-circle profile-icon"></i>
        </div>

    </div>

    <!-- PAGE CONTENT -->

    <div class="page-content">

        <div class="page-header">

            <div>
                <h2 class="page-title">Invoices</h2>
                <p class="page-subtitle">
                    Manage and track customer invoices
                </p>
            </div>

            <a href="#" class="new-btn">
                <i class="bi bi-plus-circle"></i>
                Create Invoice
            </a>

        </div>

        <!-- SEARCH -->

        <div class="toolbar">

            <div class="search-box">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    placeholder="Search Invoice ID or Customer Name"
                >
            </div>

        </div>

        <!-- FILTER BUTTONS -->

        <div class="filter-buttons">

            <button class="filter-btn active">
                All (5)
            </button>

            <button class="filter-btn">
                Paid (2)
            </button>

            <button class="filter-btn">
                Pending (1)
            </button>

            <button class="filter-btn">
                Overdue (1)
            </button>

            <button class="filter-btn">
                Draft (1)
            </button>

        </div>

        <!-- INVOICE TABLE -->

        <div class="table-card">

            <div class="table-responsive">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Invoice Date</th>
                            <th>Due Date</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>INV-001</td>
                            <td>SO-001</td>
                            <td>Adelaide Ful</td>
                            <td>09-07-2026</td>
                            <td>09-21-2026</td>
                            <td><strong>₱48,160.00</strong></td>

                            <td>
                                <span class="status status-paid">
                                    Paid
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#" class="action-btn download-btn">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>INV-002</td>
                            <td>SO-002</td>
                            <td>Maria Santos</td>
                            <td>09-10-2026</td>
                            <td>09-24-2026</td>
                            <td><strong>₱34,720.00</strong></td>

                            <td>
                                <span class="status status-pending">
                                    Pending
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#" class="action-btn download-btn">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>INV-003</td>
                            <td>SO-003</td>
                            <td>Jose Reyes</td>
                            <td>08-15-2026</td>
                            <td>08-30-2026</td>
                            <td><strong>₱61,600.00</strong></td>

                            <td>
                                <span class="status status-overdue">
                                    Overdue
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#" class="action-btn download-btn">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>INV-004</td>
                            <td>SO-004</td>
                            <td>Juan Dela Cruz</td>
                            <td>09-15-2026</td>
                            <td>09-29-2026</td>
                            <td><strong>₱29,120.00</strong></td>

                            <td>
                                <span class="status status-draft">
                                    Draft
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#" class="action-btn download-btn">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>INV-005</td>
                            <td>SO-005</td>
                            <td>ABC Corporation</td>
                            <td>09-18-2026</td>
                            <td>10-02-2026</td>
                            <td><strong>₱75,500.00</strong></td>

                            <td>
                                <span class="status status-paid">
                                    Paid
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="#" class="action-btn download-btn">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>