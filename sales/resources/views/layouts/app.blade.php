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
            height: 100vh;
            max-height: 100vh;
            background: linear-gradient(180deg, var(--primary), #4338CA);
            color: #fff;
            position: sticky;
            top: 0;
            bottom: 0;
            align-self: flex-start;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width .34s cubic-bezier(.22, 1, .36, 1), flex-basis .34s cubic-bezier(.22, 1, .36, 1), box-shadow .34s ease;
            will-change: width;
        }

        .sidebar:hover {
            width: 190px;
            height: 100vh;
            max-height: 100vh;
            box-shadow: 12px 0 30px rgba(30, 27, 75, .2);
        }

        .sidebar .brand {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255,255,255,.15);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .sidebar .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 12px;
            background: rgba(255,255,255,.16);
            font-size: 10px;
            flex-shrink: 0;
        }

        .sidebar .brand-text,
        .sidebar .nav-label {
            display: block;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            transform: translateX(-8px);
            transition: opacity .2s ease, transform .3s cubic-bezier(.22, 1, .36, 1), max-width .34s cubic-bezier(.22, 1, .36, 1);
        }

        .sidebar:not(:hover) .brand {
            justify-content: center;
        }

        .sidebar:hover .brand-text {
            display: block;
            font-size: 11px;
            max-width: 112px;
            opacity: 1;
            transform: translateX(0);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar:hover .nav-label {
            display: block;
            width: 120px;
            line-height: 1.2;
            white-space: normal;
            word-break: break-word;
            flex: 1;
            font-size: 11px;
            opacity: 1;
            transform: translateX(0);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            padding: 6px 10px;
            color: #fff;
            text-decoration: none;
            transition: background-color .2s ease, color .2s ease, padding .34s cubic-bezier(.22, 1, .36, 1), gap .34s cubic-bezier(.22, 1, .36, 1), transform .2s ease;
            white-space: nowrap;
            width: 100%;
        }

        .sidebar:hover a {
            justify-content: flex-start;
            gap: 8px;
            padding: 6px 12px;
            align-items: center;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,.16);
        }

        .sidebar a:hover {
            transform: translateX(2px);
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


        .sidebar .nav-group {
            display: flex;
            flex-direction: column;
        }

        .sidebar .sub-nav {
            display: flex;
            flex-direction: column;
            padding-left: 8px;
            margin-bottom: 4px;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height .32s cubic-bezier(.22, 1, .36, 1), opacity .2s ease, padding .32s ease;
        }

        .sidebar .sub-nav.open {
            max-height: 420px;
            opacity: 1;
        }

        .sidebar:hover .sub-nav.open {
            display: flex;
        }

        .sidebar .sub-nav-link {
            padding: 6px 12px 6px 34px;
            font-size: 11px;
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

        /* Layout stability: ensure sidebar never gets covered and content always starts after it */
        .content-area {
            flex: 1 1 auto;
            min-height: 100vh;
            min-width: 0; /* allow content to shrink without overflow */
            overflow-x: hidden;
        }


        .d-flex {
            min-width: 0;
        }

        /* Prevent sidebar from being shrunk by flexbox when content gets wide (tables/modals) */
        #app-sidebar {
            flex: 0 0 78px;
        }

        /* Sidebar: keep width predictable and prevent text clipping */
        #app-sidebar {
            min-width: 78px;
            max-width: 190px;
            flex: 0 0 78px; /* keep collapsed width stable */
            z-index: 1000; /* stay above content */
        }

        #app-sidebar:hover {
            flex-basis: 190px;
        }


        .sidebar a {
            overflow: hidden;
        }

        .sidebar .nav-label {
            min-width: 0;
            max-width: 0;
            overflow: hidden;
        }

        .sidebar:hover .nav-label {
            max-width: 160px;
            overflow: visible;
        }

        /* When collapsed, keep labels hidden but never clip icons */
        .sidebar:not(:hover) .nav-label { pointer-events: none; }


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

        .hero-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem 2rem;
            border-radius: 14px;
            border: 4px solid rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 800;
            text-decoration: none !important;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.18);
            transition: all 0.25s ease;
        }

        .hero-action-btn:hover,
        .hero-action-btn:focus {
            background: #ffffff;
            color: #128B99;
            text-decoration: none !important;
        }

        .hero-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .75rem 1.6rem;
            border: 2px solid rgba(255,255,255,.82);
            border-radius: 14px;
            background: rgba(15, 23, 42, .12);
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            backdrop-filter: blur(8px);
            transition: background .2s ease, color .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .hero-secondary-btn:hover,
        .hero-secondary-btn:focus-visible {
            background: #fff;
            color: #128B99;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15,23,42,.16);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <x-sidebar current-route="{{ Route::currentRouteName() }}" />


        <div class="content-area flex-grow-1">
            <x-topbar title="{{ $title ?? 'Sales Quotation Management System' }}" subtitle="{{ $subtitle ?? 'Enterprise dashboard' }}" />

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!--
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
            height: 100vh;
            max-height: 100vh;
            background: linear-gradient(180deg, var(--primary), #4338CA);
            color: #fff;
            position: sticky;
            top: 0;
            bottom: 0;
            align-self: flex-start;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width .25s ease;
        }

        .sidebar:hover {
            width: 190px;
            height: 100vh;
            max-height: 100vh;
        }

        .sidebar .brand {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255,255,255,.15);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .sidebar .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 12px;
            background: rgba(255,255,255,.16);
            font-size: 10px;
            flex-shrink: 0;
        }

        .sidebar .brand-text,
        .sidebar .nav-label {
            display: none;
        }

        .sidebar:not(:hover) .brand-text,
        .sidebar:not(:hover) .nav-label,
        .sidebar:not(:hover) .sub-nav-link .nav-label {
            display: none !important;
        }

        .sidebar:not(:hover) .brand {
            justify-content: center;
        }

        .sidebar:hover .brand-text {
            display: inline;
            font-size: 11px;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar:hover .nav-label {
            display: block;
            width: 120px;
            line-height: 1.2;
            white-space: normal;
            word-break: break-word;
            flex: 1;
            font-size: 11px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            padding: 6px 10px;
            color: #fff;
            text-decoration: none;
            transition: background-color .2s ease, color .2s ease;
            white-space: nowrap;
            width: 100%;
        }

        .sidebar:hover a {
            justify-content: flex-start;
            gap: 8px;
            padding: 6px 12px;
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
            padding: 6px 12px 6px 34px;
            font-size: 11px;
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

        /* Layout stability: ensure sidebar never gets covered and content always starts after it */
        .content-area {
            flex: 1 1 auto;
            min-height: 100vh;
            min-width: 0; /* allow content to shrink without overflow */
            overflow-x: hidden;
        }


        .d-flex {
            min-width: 0;
        }

        /* Prevent sidebar from being shrunk by flexbox when content gets wide (tables/modals) */
        #app-sidebar {
            flex: 0 0 78px;
        }

        /* Sidebar: keep width predictable and prevent text clipping */
        #app-sidebar {
            min-width: 78px;
            max-width: 190px;
            flex: 0 0 78px; /* keep collapsed width stable */
            z-index: 1000; /* stay above content */
        }

        #app-sidebar:hover {
            flex-basis: 190px;
        }


        .sidebar a {
            overflow: hidden;
        }

        .sidebar .nav-label {
            min-width: 0;
            max-width: 140px;
            overflow: visible;
        }

        .sidebar:hover .nav-label {
            max-width: 160px;
        }

        /* When collapsed, keep labels hidden but never clip icons */
        .sidebar:not(:hover) .nav-label {
            display: none !important;
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

        .hero-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem 2rem;
            border-radius: 14px;
            border: 4px solid rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 800;
            text-decoration: none !important;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.18);
            transition: all 0.25s ease;
        }

        .hero-action-btn:hover,
        .hero-action-btn:focus {
            background: #ffffff;
            color: #128B99;
            text-decoration: none !important;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <x-sidebar current-route="{{ Route::currentRouteName() }}" />


        <div class="content-area flex-grow-1">
            <x-topbar title="{{ $title ?? 'Sales Quotation Management System' }}" subtitle="{{ $subtitle ?? 'Enterprise dashboard' }}" />

          <main class="p-4">
    @yield('content')
</main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if (config('broadcasting.connections.pusher.key'))
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
(function () {
    var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
    });

    var channel = pusher.subscribe('erp-updates');

    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:2000;'
        + 'background:var(--primary,#5347CE);color:#fff;padding:12px 18px;border-radius:10px;'
        + 'box-shadow:0 6px 20px rgba(0,0,0,.2);font-family:sans-serif;font-size:14px;'
        + 'display:none;align-items:center;gap:12px;';
    toast.innerHTML = '<span id="erp-update-text">May bagong update.</span>'
        + '<button id="erp-update-refresh" style="background:#fff;color:var(--primary,#5347CE);border:none;'
        + 'border-radius:6px;padding:4px 10px;cursor:pointer;font-weight:600;">I-refresh</button>';
    document.body.appendChild(toast);

    var refreshTimer = null;

    channel.bind('record.changed', function (data) {
        var text = data.label
            ? 'May update sa ' + data.model + ': ' + data.label
            : 'May bagong update sa ' + data.model + '.';
        document.getElementById('erp-update-text').textContent = text;
        toast.style.display = 'flex';

        clearTimeout(refreshTimer);
        refreshTimer = setTimeout(function () {
            var active = document.activeElement;
            var isTyping = active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.tagName === 'SELECT');
            if (!isTyping) {
                window.location.reload();
            }
        }, 4000);
    });

    document.getElementById('erp-update-refresh').addEventListener('click', function () {
        window.location.reload();
    });
})();
</script>
@endif


</body>
</html>
 -->
