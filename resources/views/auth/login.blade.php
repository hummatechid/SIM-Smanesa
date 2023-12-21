
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
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .parsley-errors-list {
            color: var(--bs-danger)
        }
        .parsley-error {
            border-color: var(--bs-danger)!important
        }
    </style>
</head>

<body>
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

                    <form action="{{ url('login') }}" id="form" method="POST">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" name="email" id="email" class="form-control form-control-xl @error('email')is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autofocus>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" name="password" id="password" class="form-control form-control-xl" placeholder="Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <button type="button" class="btn-password position-absolute end-0 px-3 bg-transparent border-0" style="top:9px">
                                <i class="bi bi-eye-fill fs-3 text-muted"></i>
                            </button>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Masuk</button>
                    </form>
                    <div class="text-center mt-3 fs-5">
                        <a class="font-bold" href="{{ route('password.request') }}">Lupa password?</a>
                    </div>
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
    
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.js') }}"></script>
    <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.extra.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        function swalToast(type, message) {
            Swal.fire({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                icon: type,
                title: message,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            })
        }
    </script>
    @if (session('success'))
    <script>
        swalToast("success", "{{ session('success') }}")
    </script>
    @endif
    @if (session('error'))
    <script>
        swalToast("error", "{{ session('error') }}")
    </script>
    @endif
    <script>
        $(document).on('click', '.btn-password', (e) => {
            const input = e.currentTarget.parentNode.querySelector('input')
            const icon = e.currentTarget.querySelector('i')
            if(input.getAttribute('type') == 'password') {
                input.setAttribute('type', 'text')
                icon.classList.remove('bi-eye-fill')
                icon.classList.add('bi-eye-slash-fill')
            } else {
                input.setAttribute('type', 'password')
                icon.classList.remove('bi-eye-slash-fill')
                icon.classList.add('bi-eye-fill')
            }
        })
        
        $('#form').parsley()

        $(document).on('input change', 'input', (e) => {
            let id = e.target.getAttribute('id')
            if(id) $('#'+id).parsley().validate()
        })
    </script>
</body>

</html>
