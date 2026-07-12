<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SQMS') | Customer Relationship Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

    body{
        background:#F8FAFC;
        font-family:Segoe UI,sans-serif;
    }

    .sidebar{
        position: fixed;
        top: 0;
        left: 0;
        width: 270px;
        height: 100vh;
        background:#5347CE;
        color:#fff;
        overflow-y:auto;
        box-shadow:3px 0 15px rgba(0,0,0,.15);
    }

    .sidebar h4{
        padding:25px;
        margin:0;
        text-align:center;
        font-weight:bold;
        border-bottom:1px solid rgba(255,255,255,.15);
    }

    .sidebar .text-uppercase{
        color:rgba(255,255,255,.75)!important;
        font-size:12px;
        margin-top:20px;
        margin-bottom:10px;
        padding-left:25px;
    }

    .sidebar a{
        display:flex;
        align-items:center;
        gap:12px;
        padding:14px 25px;
        color:white;
        text-decoration:none;
        transition:.3s;
    }

    .sidebar a:hover{
        background:#887CFD;
        padding-left:32px;
    }

    .sidebar a.active{
        background:#16C8C7;
        border-left:5px solid white;
        font-weight:bold;
    }

    .content{
        margin-left:270px;
        padding:20px;
    }

    .navbar{
        background:white;
        border-radius:15px;
        box-shadow:0 5px 15px rgba(0,0,0,.08);
        margin-bottom:25px;
    }

    .card{
        border:none;
        border-radius:15px;
        box-shadow:0 5px 20px rgba(0,0,0,.08);
    }

    </style>

    @stack('styles')

</head>

<body>

    <!-- Sidebar -->

    <div class="sidebar">

        <h4>SQMS</h4>

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <div class="text-uppercase fw-bold">
            Customer Relationship Management
        </div>

        <a href="{{ route('crm.directory') }}" class="{{ request()->routeIs('crm.directory') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Customer Directory
        </a>

        <a href="{{ route('crm.profiles') }}" class="{{ request()->routeIs('crm.profiles') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            Customer Profiles
        </a>

        <a href="{{ route('crm.purchase') }}" class="{{ request()->routeIs('crm.purchase') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i>
            Purchase History
        </a>

        <a href="{{ route('crm.logs') }}" class="{{ request()->routeIs('crm.logs') ? 'active' : '' }}">
            <i class="bi bi-chat-left-text"></i>
            Communication Logs
        </a>

        <a href="{{ route('crm.followups') }}" class="{{ request()->routeIs('crm.followups') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            Follow-Ups
        </a>

        <a href="{{ route('crm.loyalty') }}" class="{{ request()->routeIs('crm.loyalty') ? 'active' : '' }}">
            <i class="bi bi-award"></i>
            Loyalty Program
        </a>

        <a href="{{ route('crm.segmentation') }}" class="{{ request()->routeIs('crm.segmentation') ? 'active' : '' }}">
            <i class="bi bi-diagram-3"></i>
            Customer Segmentation
        </a>

    </div>

    <!-- Main Content -->

    <div class="content">

        <nav class="navbar navbar-expand-lg px-4 py-3">

            <h4 class="mb-0">@yield('page-title', 'Customer Relationship Management')</h4>

            <div class="ms-auto">
                <i class="bi bi-person-circle"></i> Admin
            </div>

        </nav>

        <div class="container-fluid">

            @yield('content')

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>