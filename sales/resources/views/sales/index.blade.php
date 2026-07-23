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

        .page-header-actions{
            display:flex;
            align-items:center;
            gap:9px;
        }

        .bulk-action-btn{
            min-height:44px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:7px;
            padding:10px 14px;
            border-radius:8px;
            font-size:12px;
            font-weight:700;
            transition:background .2s ease,color .2s ease,border-color .2s ease,transform .2s ease,box-shadow .2s ease;
        }

        .select-all-orders-btn{
            border:1px solid #cbd5e1;
            color:#334155;
            background:#fff;
        }

        .select-all-orders-btn:hover,
        .select-all-orders-btn:focus-visible{
            border-color:#5347CE;
            color:#5347CE;
            background:#f5f3ff;
            transform:translateY(-1px);
        }

        .bulk-delete-orders-btn{
            border:1px solid #dc2626;
            color:#fff;
            background:#dc2626;
            box-shadow:0 7px 15px rgba(220,38,38,.16);
        }

        .bulk-delete-orders-btn:hover,
        .bulk-delete-orders-btn:focus-visible{
            border-color:#b91c1c;
            color:#fff;
            background:#b91c1c;
            transform:translateY(-1px);
        }

        .bulk-delete-orders-btn:disabled{
            border-color:#e2e8f0;
            color:#94a3b8;
            background:#f1f5f9;
            box-shadow:none;
            cursor:not-allowed;
            transform:none;
        }

        .new-order-btn{
            background:var(--primary);
            border:none;
            color:white;
            padding:12px 20px;
            border-radius:8px;
            text-decoration:none;
            transition:.2s;
        }

        .new-order-btn:hover,
        .new-order-btn:focus,
        .new-order-btn:active{
            background:var(--secondary);
            color:white;
            text-decoration:none;
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
    margin:0;
    background:#fff;
}

.order-action-cell{
    min-width:128px;
}

.order-actions{
    display:flex;
    align-items:center;
    justify-content:flex-start;
    flex-wrap:nowrap;
    gap:6px;
    width:100%;
}

.order-actions form{
    display:flex;
    margin:0;
}

.table-responsive{
    container-type:inline-size;
}

@container (max-width:1100px){
    .order-action-cell{
        min-width:112px;
    }

    .order-actions{
        gap:4px;
    }

    .order-actions .action-btn{
        width:34px;
        height:34px;
        border-radius:8px;
        font-size:13px;
    }
}

@container (max-width:900px){
    .order-action-cell{
        min-width:104px;
    }

    .order-actions{
        max-width:none;
        gap:3px;
    }

    .order-actions .action-btn{
        width:31px;
        height:31px;
        font-size:12px;
    }
}

#app-sidebar:hover ~ .content-area .order-action-cell{
    min-width:104px;
}

#app-sidebar:hover ~ .content-area .order-actions{
    flex-wrap:nowrap;
    gap:3px;
}

#app-sidebar:hover ~ .content-area .order-actions .action-btn{
    width:31px;
    height:31px;
    border-radius:8px;
    font-size:12px;
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

.order-select-cell{
    width:46px;
    text-align:center;
}

.order-select-checkbox{
    width:17px;
    height:17px;
    border-color:#cbd5e1;
    accent-color:#5347CE;
    cursor:pointer;
}

.sales-delete-modal{
    position:fixed;
    inset:0;
    z-index:2000;
    display:grid;
    place-items:center;
    padding:20px;
    background:rgba(15,23,42,.58);
    backdrop-filter:blur(5px);
    opacity:0;
    transition:opacity .22s ease;
}

.sales-delete-modal[hidden]{
    display:none;
}

.sales-delete-modal.is-open{
    opacity:1;
}

.sales-delete-panel{
    width:min(100%,460px);
    padding:30px;
    border:1px solid #fee2e2;
    border-radius:22px;
    background:#fff;
    box-shadow:0 30px 70px rgba(15,23,42,.28);
    text-align:center;
    transform:translateY(18px) scale(.96);
    opacity:0;
    transition:transform .24s cubic-bezier(.22,1,.36,1),opacity .2s ease;
}

.sales-delete-modal.is-open .sales-delete-panel{
    transform:translateY(0) scale(1);
    opacity:1;
}

.sales-delete-icon{
    width:68px;
    height:68px;
    display:grid;
    place-items:center;
    margin:0 auto 18px;
    border:1px solid #fecaca;
    border-radius:20px;
    color:#dc2626;
    background:#fef2f2;
    font-size:30px;
    box-shadow:0 10px 24px rgba(220,38,38,.12);
}

.sales-delete-panel h3{
    margin:0 0 8px;
    color:#111827;
    font-size:22px;
    font-weight:700;
}

.sales-delete-panel>p{
    margin:0;
    color:#64748b;
    font-size:13px;
    line-height:1.65;
}

.sales-delete-record{
    max-height:260px;
    overflow-y:auto;
    margin:20px 0 22px;
    padding:5px 16px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    background:#f8fafc;
    text-align:left;
}

.sales-delete-record-entry{
    padding:13px 0;
    border-bottom:1px solid #e5e7eb;
}

.sales-delete-record-entry:last-child{
    border-bottom:0;
}

.sales-delete-record-entry strong{
    display:block;
    margin-bottom:9px;
    color:#1e293b;
    font-size:14px;
}

.sales-delete-record-entry dl{
    display:grid;
    grid-template-columns:auto 1fr;
    gap:7px 14px;
    margin:0;
    font-size:11px;
}

.sales-delete-record-entry dt{
    color:#64748b;
    font-weight:500;
}

.sales-delete-record-entry dd{
    margin:0;
    overflow:hidden;
    color:#334155;
    font-weight:700;
    text-align:right;
    text-overflow:ellipsis;
    white-space:nowrap;
}

.sales-delete-countdown{
    display:flex;
    align-items:center;
    gap:10px;
    margin:-6px 0 18px;
    padding:11px 13px;
    border:1px solid #fecaca;
    border-radius:11px;
    color:#991b1b;
    background:#fff7f7;
    font-size:11px;
    line-height:1.45;
    text-align:left;
}

.sales-delete-countdown[hidden]{
    display:none;
}

.sales-delete-countdown i{
    flex:0 0 auto;
    color:#dc2626;
    font-size:18px;
}

.sales-delete-countdown strong{
    white-space:nowrap;
}

.sales-delete-actions{
    display:flex;
    justify-content:center;
    gap:10px;
}

.sales-delete-actions button{
    min-height:44px;
    padding:10px 18px;
    border-radius:11px;
    font-size:12px;
    font-weight:700;
    transition:transform .18s ease,box-shadow .18s ease,background .18s ease;
}

.sales-delete-cancel{
    border:1px solid #dbe1ea;
    color:#475569;
    background:#fff;
}

.sales-delete-cancel:hover,
.sales-delete-cancel:focus-visible{
    color:#1e293b;
    background:#f1f5f9;
}

.sales-delete-confirm{
    border:1px solid #dc2626;
    color:#fff;
    background:#dc2626;
    box-shadow:0 8px 18px rgba(220,38,38,.2);
}

.sales-delete-confirm:hover,
.sales-delete-confirm:focus-visible{
    border-color:#b91c1c;
    background:#b91c1c;
    box-shadow:0 11px 22px rgba(185,28,28,.28);
    transform:translateY(-2px);
}

body.sales-delete-modal-open{
    overflow:hidden;
}

@media(max-width:520px){
    .sales-delete-panel{
        padding:24px 18px;
    }

    .sales-delete-actions{
        flex-direction:column-reverse;
    }

    .sales-delete-actions button{
        width:100%;
    }
}
        /* ================= RESPONSIVE ================= */

        @media(max-width:900px){

            .page-header{
                align-items:flex-start;
                flex-direction:column;
            }

            .page-header-actions{
                width:100%;
                flex-wrap:wrap;
            }

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

            <div class="page-header-actions">
                <button type="button" class="bulk-action-btn select-all-orders-btn" id="select-all-orders">
                    <i class="bi bi-check2-square"></i>
                    <span>Select all</span>
                </button>
                <button type="button" class="bulk-action-btn bulk-delete-orders-btn" id="delete-selected-orders" disabled>
                    <i class="bi bi-trash3"></i>
                    <span>Delete</span>
                </button>
                <a href="{{ route('sales.create') }}" class="new-order-btn">
                    <i class="bi bi-plus-circle"></i>
                    New Sales Order
                </a>
            </div>

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

                            <th class="order-select-cell">
                                <span class="visually-hidden">Select</span>
                            </th>

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

                            <td class="order-select-cell">
                                <input
                                    type="checkbox"
                                    class="form-check-input order-select-checkbox"
                                    value="{{ $order->order_id }}"
                                    data-order-number="{{ $order->order_number }}"
                                    data-customer="{{ $order->customer?->full_name ?? 'No customer assigned' }}"
                                    data-total="₱{{ number_format((float) $order->total_amount, 2) }}"
                                    data-status="{{ $order->formattedStatus() }}"
                                    aria-label="Select sales order {{ $order->order_number }}"
                                >
                            </td>

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

                            <td class="order-action-cell">
                                <div class="order-actions">
                                    <a href="{{ route('sales.profile', $order) }}"
                                       class="action-btn view-btn"
                                       aria-label="View sales order {{ $order->order_number }}">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('sales.edit', $order) }}"
                                       class="action-btn edit-btn"
                                       aria-label="Edit sales order {{ $order->order_number }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('sales.destroy', $order) }}"
                                          method="POST"
                                          id="delete-order-form-{{ $order->order_id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="button"
                                            class="action-btn delete-btn"
                                            data-delete-order
                                            data-form-id="delete-order-form-{{ $order->order_id }}"
                                            data-order-number="{{ $order->order_number }}"
                                            data-customer="{{ $order->customer?->full_name ?? 'No customer assigned' }}"
                                            data-total="₱{{ number_format((float) $order->total_amount, 2) }}"
                                            data-status="{{ $order->formattedStatus() }}"
                                            aria-label="Delete sales order {{ $order->order_number }}"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
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

<form action="{{ route('sales.bulk-destroy') }}" method="POST" id="bulk-delete-orders-form" hidden>
    @csrf
    @method('DELETE')
    <div id="bulk-delete-order-inputs"></div>
</form>

<div
    id="sales-delete-modal"
    class="sales-delete-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="sales-delete-title"
    aria-describedby="sales-delete-description"
    hidden
>
    <div class="sales-delete-panel">
        <div class="sales-delete-icon" aria-hidden="true">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h3 id="sales-delete-title">Delete sales order?</h3>
        <p id="sales-delete-description">Are you sure you want to delete this record? This action cannot be undone.</p>

        <div class="sales-delete-record" id="sales-delete-records-list"></div>

        <p class="sales-delete-countdown" id="sales-delete-countdown-message" aria-live="assertive" hidden>
            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
            <span>
                Deletion is scheduled. You can cancel it before the record is deleted.
                <strong><span id="sales-delete-countdown">5</span> seconds remaining.</strong>
            </span>
        </p>

        <div class="sales-delete-actions">
            <button type="button" class="sales-delete-cancel" data-delete-cancel>Cancel</button>
            <button type="button" class="sales-delete-confirm" id="sales-delete-confirm">
                <i class="bi bi-trash3 me-1"></i>
                Delete this record
            </button>
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

    const deleteModal = document.getElementById('sales-delete-modal');
    const deleteConfirmButton = document.getElementById('sales-delete-confirm');
    const selectAllOrdersButton = document.getElementById('select-all-orders');
    const deleteSelectedOrdersButton = document.getElementById('delete-selected-orders');
    const orderSelectionCheckboxes = Array.from(document.querySelectorAll('.order-select-checkbox'));
    const bulkDeleteForm = document.getElementById('bulk-delete-orders-form');
    const bulkDeleteInputs = document.getElementById('bulk-delete-order-inputs');
    let selectedDeleteForm = null;
    let selectedDeleteTrigger = null;
    let deleteModalTimer = null;
    let deleteCountdownTimer = null;
    let deleteCountdownValue = 5;

    function resetDeleteCountdown(){
        clearInterval(deleteCountdownTimer);
        deleteCountdownTimer = null;
        deleteCountdownValue = 5;
        document.getElementById('sales-delete-countdown').textContent = '5';
        document.getElementById('sales-delete-countdown-message').hidden = true;
        deleteConfirmButton.disabled = false;
        deleteConfirmButton.innerHTML =
            '<i class="bi bi-trash3 me-1"></i> Delete this record';
    }

    function openDeleteModal(trigger){
        resetDeleteCountdown();
        selectedDeleteForm = document.getElementById(trigger.dataset.formId);
        selectedDeleteTrigger = trigger;

        if(!selectedDeleteForm){
            return;
        }

        const records = trigger.selectedRecords ?? [{
            orderNumber: trigger.dataset.orderNumber,
            customer: trigger.dataset.customer,
            total: trigger.dataset.total,
            status: trigger.dataset.status,
        }];

        document.getElementById('sales-delete-title').textContent =
            records.length > 1
                ? `Delete ${records.length} sales orders?`
                : 'Delete sales order?';
        renderDeleteRecords(records);

        clearTimeout(deleteModalTimer);
        deleteModal.hidden = false;
        document.body.classList.add('sales-delete-modal-open');

        requestAnimationFrame(() => {
            deleteModal.classList.add('is-open');
            deleteConfirmButton.focus();
        });
    }

    function renderDeleteRecords(records){
        const recordsList = document.getElementById('sales-delete-records-list');
        recordsList.replaceChildren();

        records.forEach(record => {
            const entry = document.createElement('div');
            entry.className = 'sales-delete-record-entry';

            const heading = document.createElement('strong');
            heading.textContent = `Sales order ${record.orderNumber}`;

            const details = document.createElement('dl');
            [
                ['Customer', record.customer],
                ['Total amount', record.total],
                ['Status', record.status],
            ].forEach(([label, value]) => {
                const term = document.createElement('dt');
                const description = document.createElement('dd');
                term.textContent = label;
                description.textContent = value;
                details.append(term, description);
            });

            entry.append(heading, details);
            recordsList.appendChild(entry);
        });
    }

    function closeDeleteModal(){
        resetDeleteCountdown();
        deleteModal.classList.remove('is-open');
        document.body.classList.remove('sales-delete-modal-open');

        deleteModalTimer = setTimeout(() => {
            deleteModal.hidden = true;
            selectedDeleteTrigger?.focus();
            selectedDeleteForm = null;
            selectedDeleteTrigger = null;
        }, 220);
    }

    document.querySelectorAll('[data-delete-order]').forEach(trigger => {
        trigger.addEventListener('click', () => openDeleteModal(trigger));
    });

    function selectedOrderCheckboxes(){
        return orderSelectionCheckboxes.filter(checkbox => checkbox.checked);
    }

    function updateOrderSelectionActions(){
        const selectedCount = selectedOrderCheckboxes().length;
        const allSelected = orderSelectionCheckboxes.length > 0 &&
            selectedCount === orderSelectionCheckboxes.length;

        deleteSelectedOrdersButton.disabled = selectedCount === 0;
        deleteSelectedOrdersButton.querySelector('span').textContent =
            selectedCount > 0 ? `Delete (${selectedCount})` : 'Delete';
        selectAllOrdersButton.querySelector('span').textContent =
            allSelected ? 'Clear selection' : 'Select all';
        selectAllOrdersButton.querySelector('i').className =
            allSelected ? 'bi bi-x-square' : 'bi bi-check2-square';
    }

    selectAllOrdersButton.addEventListener('click', () => {
        const shouldSelectAll = selectedOrderCheckboxes().length !==
            orderSelectionCheckboxes.length;

        orderSelectionCheckboxes.forEach(checkbox => {
            checkbox.checked = shouldSelectAll;
        });

        updateOrderSelectionActions();
    });

    orderSelectionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateOrderSelectionActions);
    });

    deleteSelectedOrdersButton.addEventListener('click', () => {
        const selected = selectedOrderCheckboxes();

        if(selected.length === 0){
            return;
        }

        bulkDeleteInputs.replaceChildren();
        selected.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_order_ids[]';
            input.value = checkbox.value;
            bulkDeleteInputs.appendChild(input);
        });

        deleteSelectedOrdersButton.dataset.formId = bulkDeleteForm.id;
        deleteSelectedOrdersButton.selectedRecords = selected.map(checkbox => ({
            orderNumber: checkbox.dataset.orderNumber,
            customer: checkbox.dataset.customer,
            total: checkbox.dataset.total,
            status: checkbox.dataset.status,
        }));
        openDeleteModal(deleteSelectedOrdersButton);
    });

    updateOrderSelectionActions();

    document.querySelectorAll('[data-delete-cancel]').forEach(button => {
        button.addEventListener('click', closeDeleteModal);
    });

    deleteModal.addEventListener('click', event => {
        if(event.target === deleteModal){
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', event => {
        if(event.key === 'Escape' && !deleteModal.hidden){
            closeDeleteModal();
        }
    });

    deleteConfirmButton.addEventListener('click', () => {
        if(!selectedDeleteForm || deleteCountdownTimer){
            return;
        }

        deleteConfirmButton.disabled = true;
        document.getElementById('sales-delete-countdown-message').hidden = false;
        document.getElementById('sales-delete-countdown').textContent =
            deleteCountdownValue;
        deleteConfirmButton.innerHTML =
            `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting in ${deleteCountdownValue}s`;

        deleteCountdownTimer = setInterval(() => {
            deleteCountdownValue -= 1;
            document.getElementById('sales-delete-countdown').textContent =
                deleteCountdownValue;
            deleteConfirmButton.innerHTML =
                `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting in ${deleteCountdownValue}s`;

            if(deleteCountdownValue <= 0){
                clearInterval(deleteCountdownTimer);
                deleteCountdownTimer = null;
                deleteConfirmButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting...';
                selectedDeleteForm.submit();
            }
        }, 1000);
    });

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
@endsection
