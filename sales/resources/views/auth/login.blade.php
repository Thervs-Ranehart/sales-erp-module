<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Quotation Management System | Login</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('cl-logo.svg') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --primary:#5347CE;
            --secondary:#887CFD;
            --accent:#4896FE;
            --success:#16C8C7;
            --white:#FFFFFF;
            --light:#F8FAFC;
            --text:#1F2937;
            --text2:#6B7280;
        }

        body{
            background:
                linear-gradient(rgba(15,23,42,.18),rgba(15,23,42,.18)),
                url("{{ asset('images/login_bg.jpeg') }}") center/cover no-repeat fixed;
            font-family:'Segoe UI',sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
        }

        .login-card{
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 15px 35px rgba(0,0,0,.08);
        }

        .left-panel{
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff;
            padding:60px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .left-panel h1{
            font-weight:700;
            margin-bottom:20px;
        }

        .left-panel p{
            opacity:.9;
        }

        .logo{
            width: auto;
            height:80px;
            border-radius:5%/20%;
            background:#fff;
            color:var(--primary);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:30px;
            font-weight:bold;
            margin-bottom:30px;
        }

        .right-panel{
            background:#fff;
            padding:60px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .form-control{
            height:50px;
            border-radius:10px;
        }

        .form-control:focus{
            border-color:var(--accent);
            box-shadow:0 0 0 .2rem rgba(72,150,254,.15);
        }

        .password-wrapper{
            position: relative;
        }

        .password-toggle{
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--text2);
            cursor: pointer;
            padding: 0;
        }

        .password-toggle:hover{
            color: var(--primary);
        }

        .btn-login{
            background:var(--primary);
            color:#fff;
            height:50px;
            border:none;
            border-radius:10px;
            font-weight:600;
        }

        .btn-login:hover{
            background:var(--secondary);
            color:#fff;
        }

        a{
            color:var(--accent);
            text-decoration:none;
        }

        a:hover{
            color:var(--primary);
        }

        .text-muted{
            color:var(--text2)!important;
        }

        @media(max-width:992px){
            .left-panel{
                display:none;
            }

            .right-panel{
                padding:40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card login-card">
                    <div class="row g-0">
                        <div class="col-lg-6 left-panel">
                            <div class="logo">Company Logo</div>
                            <h1>Sales and Customer Support Management System</h1>
                            <p>
                                Manage quotations, sales orders, invoices,
                                pricing rules, inventory, and finance in one
                                centralized platform.
                            </p>
                        </div>

                        <div class="col-lg-6 right-panel">
                            <h2 class="fw-bold mb-2" style="color:#1F2937;">Welcome Back</h2>
                            <p class="text-muted mb-4">Sign in to continue.</p>

                            @if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">

    {{ $errors->first() }}

    {{-- Remaining login attempts --}}
    @if(session('remaining_attempts'))
        <br>
        <small class="fw-semibold">
            {{ session('remaining_attempts') }}
            login attempt{{ session('remaining_attempts') > 1 ? 's' : '' }}
            remaining before your account is temporarily locked.
        </small>
    @endif

    {{-- Lockout countdown --}}
    @if(session('lockout_seconds'))
        <br>
        Please try again in
        <strong id="lockoutTimer"></strong>.
    @endif

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close">
    </button>

</div>
@endif

                            <form action="{{ url('/login') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input
                                        type="text"
                                        class="form-control @error('username') is-invalid @enderror"
                                        name="username"
                                        value="{{ old('username') }}"
                                        placeholder="Enter Username"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="password-wrapper">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="passwordField"
                                            name="password"
                                            placeholder="Enter Password"
                                            required>
                                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
                                            <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">Remember Me</label>
                                    </div>
                                    <a href="#">Forgot Password?</a>
                                </div>

                                <button type="submit" class="btn btn-login w-100" id="loginSubmitBtn">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Login
                                </button>
                            </form>

                            <hr class="my-4">

                            <p class="text-center text-muted mb-0">© {{ date('Y') }} Sales and Customer Support Management System</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById('passwordField');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        if (passwordField && togglePassword && togglePasswordIcon) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                togglePasswordIcon.className = isPassword ? 'bi bi-eye' : 'bi bi-eye-slash';
                togglePassword.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        }

        @if (session('lockout_seconds'))
        (function () {
            let secondsLeft = {{ (int) session('lockout_seconds') }};
            const timerEl = document.getElementById('lockoutTimer');
            const submitBtn = document.getElementById('loginSubmitBtn');

            function formatTime(totalSeconds) {
                const m = Math.floor(totalSeconds / 60);
                const s = totalSeconds % 60;
                return m + ':' + String(s).padStart(2, '0');
            }

            if (submitBtn) {
                submitBtn.disabled = true;
            }

            if (timerEl) {
                timerEl.textContent = formatTime(secondsLeft);
            }

            const interval = setInterval(function () {
                secondsLeft -= 1;

                if (secondsLeft <= 0) {
                    clearInterval(interval);
                    if (timerEl) {
                        timerEl.textContent = '0:00';
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                    return;
                }

                if (timerEl) {
                    timerEl.textContent = formatTime(secondsLeft);
                }
            }, 1000);
        })();
        @endif
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
