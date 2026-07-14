@extends('layouts.app')

@section('title', 'Pricing Rules')
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

/* PAGE */

.page-content{
    padding:30px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title{
    font-size:28px;
    font-weight:700;
    margin:0;
}

.new-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:var(--primary);
    color:#fff;
    text-decoration:none;
    padding:12px 20px;
    border-radius:10px;
    font-weight:600;
    transition:.25s;
}

.new-btn:hover{
    background:var(--secondary);
    color:#fff;
}

/* KPI */

.stat-card{
    background:#fff;
    border-radius:18px;
    padding:22px;
    box-shadow:0 8px 25px rgba(0,0,0,.06);
    height:100%;
}

.stat-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.stat-label{
    color:#6B7280;
    font-size:14px;
    font-weight:600;
}

.stat-number{
    font-size:42px;
    font-weight:700;
    margin-top:10px;
}

.stat-icon{
    width:55px;
    height:55px;
    border-radius:15px;
    background:#EEECFF;
    color:#5347CE;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

/* FILTER */

.filter-card{
    background:#fff;
    margin-top:25px;
    padding:20px;
    border-radius:16px;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.search-box{
    position:relative;
}

.search-box i{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:#6B7280;
}

.search-box input{
    padding-left:45px;
}

.filter-buttons{
    display:flex;
    gap:10px;
    justify-content:flex-end;
}

.filter-btn{
    border:none;
    background:#fff;
    border:1px solid #E5E7EB;
    color:#5347CE;
    padding:10px 18px;
    border-radius:999px;
    font-weight:600;
    transition:.2s;
}

.filter-btn.active,
.filter-btn:hover{
    background:#5347CE;
    color:#fff;
}

/* TABLE */

.table-card{
    background:#fff;
    margin-top:25px;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 8px 20px rgba(0,0,0,.05);
}

.table-header{
    padding:22px;
    border-bottom:1px solid #ECECEC;
}

.table-header h5{
    margin:0;
    font-weight:700;
}

.pricing-table{
    margin:0;
}

.pricing-table thead th{
    background:#EEECFF;
    color:#5347CE;
    border:none;
    padding:16px;
}

.pricing-table tbody td{
    padding:16px;
    vertical-align:middle;
}

.rule-name{
    font-weight:700;
}

.rule-code{
    color:#6B7280;
    font-size:12px;
}

.custom-badge{
    display:inline-block;
    padding:7px 14px;
    border-radius:20px;
    color:#fff;
    font-size:12px;
    font-weight:600;
}

.status-active{background:#198754;}
.status-inactive{background:#6C757D;}

.type-discount{background:#5347CE;}
.type-fixed{background:#F4B400;color:#111;}

.action-btn{
    width:38px;
    height:38px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    border:1.5px solid;
    background:#fff;
    text-decoration:none;
    margin-right:5px;
    transition:.2s;
}

.view-btn{
    color:#2563EB;
    border-color:#2563EB;
}

.view-btn:hover{
    background:#2563EB;
    color:#fff;
}

.edit-btn{
    color:#F4B400;
    border-color:#F4B400;
}

.edit-btn:hover{
    background:#F4B400;
    color:#111;
}

.delete-btn{
    color:#EF4444;
    border-color:#EF4444;
}

.delete-btn:hover{
    background:#EF4444;
    color:#fff;
}

</style>

<div class="page-content">

<div class="page-header">

<h2 class="page-title">
Pricing Rules
</h2>

<a href="{{ route('pricing-rules.create') }}" class="new-btn">
<i class="bi bi-plus-circle"></i>
New Pricing Rule
</a>

</div>
<!-- ================= KPI CARDS ================= -->

<div class="row g-4">

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <div class="stat-top">

                <div>

                    <div class="stat-label">
                        Total Pricing Rules
                    </div>

                    <div class="stat-number">
                        {{ $statusCounts['all'] }}
                    </div>

                </div>

                <div class="stat-icon">
                    <i class="bi bi-tags"></i>
                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <div class="stat-top">

                <div>

                    <div class="stat-label">
                        Active Rules
                    </div>

                    <div class="stat-number">
                        {{ $statusCounts['active'] }}
                    </div>

                </div>

                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <div class="stat-top">

                <div>

                    <div class="stat-label">
                        Percentage Discounts
                    </div>

                    <div class="stat-number">
                        {{ $pricingRules->where('discount_type','Percentage')->count() }}
                    </div>

                </div>

                <div class="stat-icon">
                    <i class="bi bi-percent"></i>
                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <div class="stat-top">

                <div>

                    <div class="stat-label">
                        Inactive Rules
                    </div>

                    <div class="stat-number">
                        {{ $statusCounts['inactive'] }}
                    </div>

                </div>

                <div class="stat-icon">
                    <i class="bi bi-pause-circle"></i>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================= FILTER ================= -->

<div class="filter-card">

    <div class="row g-3 align-items-center">

        <div class="col-lg-5">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="pricingSearch"
                    class="form-control"
                    placeholder="Search Pricing Rule..."
                    onkeyup="searchPricing()"
                >

            </div>

        </div>

        <div class="col-lg-7">

            <div class="filter-buttons">

                <button
                    class="filter-btn active"
                    onclick="filterPricing('all',this)"
                >
                    All ({{ $statusCounts['all'] }})
                </button>

                <button
                    class="filter-btn"
                    onclick="filterPricing('active',this)"
                >
                    Active ({{ $statusCounts['active'] }})
                </button>

                <button
                    class="filter-btn"
                    onclick="filterPricing('inactive',this)"
                >
                    Inactive ({{ $statusCounts['inactive'] }})
                </button>

            </div>

        </div>

    </div>

</div>

<!-- ================= TABLE ================= -->

<div class="table-card">

    <div class="table-header">

        <h5>
            Pricing Rules List
        </h5>

    </div>

    <div class="table-responsive">

        <table
            class="table pricing-table"
            id="pricingTable"
        >

            <thead>

                <tr>

                    <th>Rule</th>

                    <th>Type</th>

                    <th>Discount</th>

                    <th>Tax</th>

                    <th>Start Date</th>

                    <th>End Date</th>

                    <th>Status</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>
                @forelse($pricingRules as $rule)

<tr data-status="{{ strtolower($rule->status) }}">

    <td>

        <div class="rule-name">
            {{ $rule->rule_name }}
        </div>

        <div class="rule-code">
            PR-{{ str_pad($rule->pricing_rule_id,3,'0',STR_PAD_LEFT) }}
        </div>

    </td>

    <td>

        @if($rule->discount_type == 'Percentage')

            <span class="custom-badge type-discount">
                Percentage
            </span>

        @else

            <span class="custom-badge type-fixed">
                Fixed
            </span>

        @endif

    </td>

    <td>

        @if($rule->discount_type == 'Percentage')

            {{ number_format($rule->discount_value,2) }}%

        @else

            ₱{{ number_format($rule->discount_value,2) }}

        @endif

    </td>

    <td>

        {{ number_format($rule->tax_rate,2) }}%

    </td>

    <td>

        {{ \Carbon\Carbon::parse($rule->start_date)->format('M d, Y') }}

    </td>

    <td>

        {{ \Carbon\Carbon::parse($rule->end_date)->format('M d, Y') }}

    </td>

    <td>

        @if(strtolower($rule->status) == 'active')

            <span class="custom-badge status-active">
                Active
            </span>

        @else

            <span class="custom-badge status-inactive">
                Inactive
            </span>

        @endif

    </td>

    <td>

        <a
            href="{{ route('pricing-rules.show',$rule) }}"
            class="action-btn view-btn"
        >
            <i class="bi bi-eye"></i>
        </a>

        <a
            href="{{ route('pricing-rules.edit',$rule) }}"
            class="action-btn edit-btn"
        >
            <i class="bi bi-pencil"></i>
        </a>

        <form
            action="{{ route('pricing-rules.destroy',$rule) }}"
            method="POST"
            style="display:inline-block"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="action-btn delete-btn"
                onclick="return confirm('Delete this pricing rule?')"
            >
                <i class="bi bi-trash"></i>
            </button>

        </form>

    </td>

</tr>

@empty

<tr>

    <td colspan="8" class="text-center py-5">

        <h6 class="text-muted mb-0">

            No Pricing Rules Found.

        </h6>

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>
<script>

function filterPricing(status, button){

    const rows = document.querySelectorAll('#pricingTable tbody tr');

    document.querySelectorAll('.filter-btn').forEach(btn=>{
        btn.classList.remove('active');
    });

    button.classList.add('active');

    rows.forEach(row=>{

        if(status === 'all'){

            row.style.display = '';

            return;
        }

        if(row.dataset.status === status){

            row.style.display = '';

        }else{

            row.style.display = 'none';

        }

    });

}

function searchPricing(){

    const keyword = document
        .getElementById('pricingSearch')
        .value
        .toLowerCase();

    const rows = document.querySelectorAll('#pricingTable tbody tr');

    rows.forEach(row=>{

        const text = row.innerText.toLowerCase();

        if(text.includes(keyword)){

            row.style.display='';

        }else{

            row.style.display='none';

        }

    });

}

</script>

@endsection