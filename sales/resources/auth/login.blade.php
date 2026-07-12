
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Quotation Management System | Login</title>

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
            background:var(--light);
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
            width:80px;
            height:80px;
            border-radius:50%;
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
        }

        .form-control{
            height:50px;
            border-radius:10px;
        }

        .form-control:focus{
            border-color:var(--accent);
            box-shadow:0 0 0 .2rem rgba(72,150,254,.15);
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

                        <div class="logo">
                            Company Logo
                        </div>

                        <h1>Sales and Customer Support Management System</h1>

                        <p>
                            Manage quotations, sales orders, invoices,
                            pricing rules, inventory, and finance in one
                            centralized platform.
                        </p>

                    </div>

                    <div class="col-lg-6 right-panel">

                        <h2 class="fw-bold mb-2" style="color:#1F2937;">
                            Welcome Back
                        </h2>

                        <p class="text-muted mb-4">
                            Sign in to continue.
                        </p>
<form action="{{ url('/login') }}" method="POST">
    @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    Username
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="username"
                                    placeholder="Enter Username"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="Enter Password"
                                    required>

                            </div>

                            <div class="d-flex justify-content-between mb-4">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox">

                                    <label class="form-check-label">
                                        Remember Me
                                    </label>

                                </div>

                                <a href="#">
                                    Forgot Password?
                                </a>

                            </div>

                            <button type="submit" class="btn btn-login w-100">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login
                            </button>

                        </form>

                        <hr class="my-4">

                        <p class="text-center text-muted mb-0">
                            © {{ date('Y') }} Sales and Customer Support Management System
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>

          