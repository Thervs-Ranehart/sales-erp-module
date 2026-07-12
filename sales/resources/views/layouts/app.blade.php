<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SQMS' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5347CE;
            --secondary: #887CFD;
            --accent: #4896FE;
            --success: #16C8C7;
            --warning: #F59E0B;
            --danger: #EF4444;
            --bg: #F8FAFC;
            --text: #1F2937;
            --muted: #6B7280;
        }

        body {
            background: var(--bg);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: var(--text);
        }

        .sidebar {
            width: 78px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary), #4338CA);
            color: #fff;
            position: sticky;
            top: 0;
            overflow: hidden;
            transition: width .25s ease;
        }

        .sidebar:hover {
            width: 270px;
        }

        .sidebar .brand {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,.15);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .sidebar .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(255,255,255,.16);
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar .brand-text,
        .sidebar .nav-label {
            display: none;
        }

        .sidebar:hover .brand-text {
            display: inline;
        }

        .sidebar:hover .nav-label {
            display: block;
            width: 180px;
            line-height: 1.3;
            white-space: normal;
            word-break: break-word;
            flex: 1;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            padding: 14px 20px;
            color: #fff;
            text-decoration: none;
            transition: all .2s ease;
            white-space: nowrap;
            width: 100%;
        }

        .sidebar:hover a {
            justify-content: flex-start;
            gap: 12px;
            padding: 14px 24px;
            align-items: center;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,.16);
        }

        .sidebar .sidebar-drop-icon {
            margin-left: auto;
            font-size: 12px;
            opacity: 0;
            transition: opacity .2s ease, transform .2s ease;
            color: rgba(255,255,255,.8);
        }

        .sidebar .sidebar-drop-icon.rotated {
            transform: rotate(180deg);
        }

        .sidebar:hover .sidebar-drop-icon {
            opacity: 1;
        }

        .sidebar:hover a:hover,
        .sidebar:hover a.active {
            padding-left: 28px;
        }

        .sidebar .nav-group {
            display: flex;
            flex-direction: column;
        }

        .sidebar .sub-nav {
            display: none;
            flex-direction: column;
            padding-left: 8px;
            margin-bottom: 4px;
        }

        .sidebar .sub-nav.open {
            display: flex;
        }

        .sidebar:hover .sub-nav.open {
            display: flex;
        }

        .sidebar .sub-nav-link {
            padding: 10px 16px 10px 44px;
            font-size: 13px;
            color: rgba(255,255,255,.9);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar .sub-nav-link:hover,
        .sidebar .sub-nav-link.active {
            background: rgba(255,255,255,.12);
            color: #fff;
        }

        .content-area {
            flex: 1;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 12px rgba(0,0,0,.04);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,.06);
        }

        .table th {
            color: var(--muted);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <x-sidebar current-route="{{ Route::currentRouteName() }}" />

        <div class="content-area">
            <x-topbar title="{{ $title ?? 'Sales Quotation Management System' }}" subtitle="{{ $subtitle ?? 'Enterprise dashboard' }}" />

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
