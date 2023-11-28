
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | SIM-SMANESA</title>

    <link rel="shortcut icon" href="{{ asset('assets/compiled/logos/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
</head>

<body>
<script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
<div id="auth">

    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="d-lg-none d-block mb-5">
                    <a href="{{ url('login') }}" class="d-flex justify-content-start align-items-end">
                        <img src="{{ asset('assets/compiled/logos/logo.png') }}" alt="Logo" height="50">
                        <div class="fw-bold ms-3">SMAN 1 Purwosari</div>
                    </a>
                </div>
                <h1 class="auth-title">Masuk</h1>
                <p class="auth-subtitle mb-5">Masuk ke akun anda untuk menuju halaman beranda</p>

                <form action="{{ url('login') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email" id="email" class="form-control form-control-xl" placeholder="Email" autofocus>
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password" id="password" class="form-control form-control-xl" placeholder="Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Masuk</button>
                </form>
                {{-- <div class="text-center mt-5 text-lg fs-4">
                    <p><a class="font-bold" href="auth-forgot-password.html">Lupa password?</a>.</p>
                </div> --}}
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right" class="d-flex flex-column justify-content-center align-items-center gap-0">
                <img class="mb-5" src="{{ asset('assets/compiled/logos/logo-sm.png') }}" alt="{{ asset('assets/compiled/logos/logo.png') }}" width="150" height="150">
                <div class="text-white text-center fw-bold fs-4">SMAN 1 Purwosari</div>
                <div class="text-white text-center lead">Berimtaq, Unggul dan Berbudaya</div>
            </div>
        </div>
    </div>

</div>
</body>

</html>
