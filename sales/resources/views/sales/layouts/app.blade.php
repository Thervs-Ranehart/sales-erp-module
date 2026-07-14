<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sales Order Management')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>

        :root{
            --primary:#5347CE;
            --secondary:#887CFD;
            --accent:#4896FE;
            --success:#16C8C7;
            --warning:#F59E0B;
            --danger:#EF4444;
            --bg:#F8FAFC;
            --text:#1F2937;
            --muted:#6B7280;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:var(--bg);
            font-family:'Poppins',sans-serif;
        }

        .content{
            margin-left:260px;
            min-height:100vh;
        }

        .topbar{
            background:#fff;
            padding:20px 30px;
            border-bottom:1px solid #e5e7eb;
            box-shadow:0 2px 12px rgba(0,0,0,.04);
        }

        .topbar h3{
            margin:0;
            font-weight:700;
            color:#1F2937;
        }

        main{
            padding:30px;
        }

        .card{
            border:none;
            border-radius:16px;
            box-shadow:0 8px 20px rgba(0,0,0,.06);
        }

        .table th{
            color:#6B7280;
            font-weight:600;
        }

        @media(max-width:768px){

            .content{
                margin-left:0;
            }

            main{
                padding:20px;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- SALES SIDEBAR --}}
    @include('sales.partials.sidebar')

    <div class="content">

        <div class="topbar">

            <h3>@yield('page-title','Sales Order Management')</h3>

        </div>

        <main>

            @yield('content')

        </main>

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

@stack('scripts')


</body>
</html>