<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIM | Smanesa</title>

    <link rel="shortcut icon" href="{{ asset('assets/compiled/logos/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
</head>

<body>
    <header class="sticky-top top-0 bg-body">
        <nav class="navbar py-3 border-bottom shadow">
            <div class="container-fluid">
                <img src="{{ asset('assets/compiled/logos/logo-sm.png') }}" alt="Smanesa" height="50">
                <div class="d-flex gap-2">
                    @if(Auth::check())
                    <a href="{{ route('scan.index') }}" class="btn btn-primary">Scan Kehadiran</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="d-flex flex-column gap-3">
            <section id="about" class="bg-primary">
                <div class="container px-4 py-5">
                    <div class="row flex-lg-row-reverse align-items-center justify-content-center justify-content-lg-start g-5 py-5">
                        <div class="col-10 col-sm-8 col-lg-6">
                            <img src="{{ asset('images/illustration/illustration-2.jpg') }}"
                                class="d-block mx-lg-auto img-fluid rounded-4" alt="Bootstrap Themes" width="700"
                                height="500" loading="lazy">
                        </div>
                        <div class="col-lg-6">
                            <h1 class="display-5 fw-bold lh-1 mb-3 text-white text-center text-lg-start">SMANESA</h1>
                            <p class="lead text-light text-center text-lg-start">Berimtaq, Unggul, dan Berbudaya</p>
                            <p class="lead text-light text-center text-lg-start">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis corporis molestiae
                                adipisci dolorum nemo consequuntur deserunt ipsa quisquam officia sunt!
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section id="list" class="my-5">
                <div class="container">
                    <div class="text-center mb-3 pb-0 mb-lg-5 pb-lg-3">
                        <h2 class="mb-0">SMAN 1 Purwosari</h2>
                        <div class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. In, officiis.</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                            <div class="bg-primary-subtle border border-primary rounded-4">
                                <div class="fs-1 fw-bold">{{ $count_student }}</div>
                                <div class="lead">Siswa Aktif</div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                            <div class="bg-primary-subtle border border-primary rounded-4">
                                <div class="fs-1 fw-bold">{{ $count_teacher }}</div>
                                <div class="lead">Pengajar</div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                            <div class="bg-primary-subtle border border-primary rounded-4">
                                <div class="fs-1 fw-bold">{{ $count_admin }}</div>
                                <div class="lead">Staf Lain</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer id="footer" class="bg-body-secondary">
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-6 col-md-4 mb-3">
                            <h5>Tentang</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2"><span class="nav-link p-0 text-body-secondary">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Corrupti minima recusandae perspiciatis!</span></li>
                            </ul>
                        </div>

                        <div class="col-6 col-md-4 mb-3">
                            <h5>Fitur</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2"><span class="nav-link p-0 text-body-secondary">Manajemen Kehadiran</span></li>
                                <li class="nav-item mb-2"><span class="nav-link p-0 text-body-secondary">Manajemen Pelanggaran</span></li>
                                <li class="nav-item mb-2"><span class="nav-link p-0 text-body-secondary">Manajemen Perizinan</span></li>
                            </ul>
                        </div>

                        <div class="col-6 col-md-4 mb-3">
                            <h5>Alamat</h5>
                            <ul class="nav flex-column">
                                <li class="nav-item mb-2">
                                    <span href="#" class="nav-link p-0 text-body-secondary">
                                        Jl. Pegadaian No. 1B Purwosari
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between pt-4 mt-4 border-top">
                        <p>{{ date('Y') }} © SMAN 1 Purwosari</p>
                        <ul class="list-unstyled d-flex">
                            <li class="ms-3">
                                <a href="http://www.sman1purwosari.sch.id" target="_blank" class="link-body-emphasis" href="#"><i class="bi bi-globe"></i></a>
                            </li>
                            <li class="ms-3"><a href="mailto:sman1purwosari@yahoo.co.id" class="link-body-emphasis" href="#"><i class="bi bi-envelope-fill"></i></a></li>
                        </ul>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
</body>

</html>
