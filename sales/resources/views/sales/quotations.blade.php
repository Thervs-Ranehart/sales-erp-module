<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Quotations</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        <head>
    <!-- Bootstrap links -->

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

        /* ================= SIDEBAR ================= */

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

        /* ================= MAIN CONTENT ================= */

        .main-content{
            margin-left:285px;
            min-height:100vh;
        }

        /* ================= TOPBAR ================= */

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

        /* ================= PAGE ================= */

        .page-content{
            padding:28px;
        }

        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:22px;
        }

        .page-title{
            margin:0;
            font-size:28px;
            font-weight:700;
        }

        .new-btn{
            background:var(--primary);
            color:white;
            border:none;
            padding:11px 20px;
            border-radius:8px;
            font-weight:600;
        }

        .new-btn:hover{
            background:var(--secondary);
            color:white;
        }

        /* ================= SEARCH ================= */

        .toolbar{
            display:flex;
            align-items:center;
            gap:15px;
            margin-bottom:20px;
        }

        .search-box{
            width:350px;
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

        .search-box input:focus{
            border-color:var(--primary);
        }

        /* ================= FILTERS ================= */

        .filter-buttons{
            display:flex;
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
            color:var(--primary);
            font-weight:600;
        }

        /* ================= TABLE CARD ================= */

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
           background:#EEECFF;
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
        }

        /* ================= STATUS ================= */

        .status{
            display:inline-block;
            min-width:90px;
            padding:7px 12px;
            border-radius:20px;
            text-align:center;
            font-size:11px;
            font-weight:700;
        }

        .status-draft{
            background:#D9D4FF;
            color:#4035A8;
        }

        .status-pending{
            background:#AFA5F5;
            color:#33279B;
        }

        .status-approved{
            background:#70D8D3;
            color:#086B6A;
        }

        .status-rejected{
            background:#B7CFF5;
            color:#285A9C;
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
            margin-right:4px;
            text-decoration:none;
            transition:.2s;
        }

        .view-btn{
            background:#4896FE;
            color:white;
        }

        .edit-btn{
            background:#887CFD;
            color:white;
        }

        .delete-btn{
            background:#5347CE;
            color:white;
        }

        .action-btn:hover{
            transform:translateY(-2px);
            color:white;
            opacity:.85;
        }

        @media(max-width:900px){
            .sidebar{
                width:220px;
            }

            .main-content{
                margin-left:220px;
            }
        }
    </style>
</head>

<body>

@include('sales.partials.sidebar')
<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

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


    <div class="page-content">

        <div class="page-header">

            <h2 class="page-title">
                Quotations
            </h2>

            <button class="new-btn">
                <i class="bi bi-plus-circle me-1"></i>
                New Quotation
            </button>

        </div>


        <!-- SEARCH -->

        <div class="toolbar">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="quotationSearch"
                    placeholder="Search Quotation ID or Customer Name"
                    onkeyup="searchQuotation()"
                >

            </div>

        </div>


        <!-- FILTERS -->

        <div class="filter-buttons">

            <button
                class="filter-btn active"
                onclick="filterQuotation('all', this)"
            >
                All (4)
            </button>

            <button
                class="filter-btn"
                onclick="filterQuotation('draft', this)"
            >
                Draft (1)
            </button>

            <button
                class="filter-btn"
                onclick="filterQuotation('pending', this)"
            >
                Pending (1)
            </button>

            <button
                class="filter-btn"
                onclick="filterQuotation('approved', this)"
            >
                Approved (1)
            </button>

            <button
                class="filter-btn"
                onclick="filterQuotation('rejected', this)"
            >
                Rejected (1)
            </button>

        </div>


        <!-- TABLE -->

        <div class="table-card">

            <div class="table-responsive">

                <table class="table" id="quotationTable">

                    <thead>

                        <tr>
                            <th>Quotation ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Valid Until</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr data-status="draft">
                            <td>QT-001</td>
                            <td>Adelaide Ful</td>
                            <td>09-07-2026</td>
                            <td>09-21-2026</td>
                            <td>₱45,000</td>
                            <td>₱2,000</td>
                            <td>₱5,160</td>
                            <td>₱48,160</td>

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

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>


                        <tr data-status="pending">
                            <td>QT-002</td>
                            <td>Maria Santos</td>
                            <td>09-10-2026</td>
                            <td>09-24-2026</td>
                            <td>₱32,500</td>
                            <td>₱1,500</td>
                            <td>₱3,720</td>
                            <td>₱34,720</td>

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

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>


                        <tr data-status="approved">
                            <td>QT-003</td>
                            <td>Jose Reyes</td>
                            <td>09-12-2026</td>
                            <td>09-26-2026</td>
                            <td>₱58,000</td>
                            <td>₱3,000</td>
                            <td>₱6,600</td>
                            <td>₱61,600</td>

                            <td>
                                <span class="status status-approved">
                                    Approved
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button class="action-btn delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>


                        <tr data-status="rejected">
                            <td>QT-004</td>
                            <td>Juan Dela Cruz</td>
                            <td>09-15-2026</td>
                            <td>09-29-2026</td>
                            <td>₱27,000</td>
                            <td>₱1,000</td>
                            <td>₱3,120</td>
                            <td>₱29,120</td>

                            <td>
                                <span class="status status-rejected">
                                    Rejected
                                </span>
                            </td>

                            <td>
                                <a href="#" class="action-btn view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="#" class="action-btn edit-btn">
                                    <i class="bi bi-pencil"></i>
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


<script>

    function filterQuotation(status, button){

        const rows = document.querySelectorAll(
            '#quotationTable tbody tr'
        );

        const buttons = document.querySelectorAll(
            '.filter-btn'
        );

        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        button.classList.add('active');

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
    }


    function searchQuotation(){

        const input = document
            .getElementById('quotationSearch')
            .value
            .toLowerCase();

        const rows = document.querySelectorAll(
            '#quotationTable tbody tr'
        );

        rows.forEach(row => {

            const text = row
                .innerText
                .toLowerCase();

            row.style.display =
                text.includes(input)
                ? ''
                : 'none';

        });
    }

</script>

</body>
</html>