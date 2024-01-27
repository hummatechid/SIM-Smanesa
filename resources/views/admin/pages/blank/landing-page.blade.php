<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>SIM | Smanesa</title>

        <link
            rel="shortcut icon"
            href="{{ asset('assets/compiled/logos/favicon.ico') }}"
            type="image/x-icon"
        />

        <link
            rel="stylesheet"
            href="{{ asset('assets/compiled/css/app.css') }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset('assets/compiled/css/app-dark.css') }}"
        />
        <style>
            @media only screen and (max-width: 768px) {
                #btn-header-group > .btn-header {
                    display: block !important;
                    margin: 0;
                    background: transparent;
                    border: 0;
                    color: var(--bs-dark);
                }
                html[data-bs-theme="dark"] #btn-header-group > .btn-header {
                    color: var(--bs-white);
                }
                #btn-header-group > .btn-header:hover,
                #btn-header-group > .btn-header:focus,
                #btn-header-group > .btn-header:active {
                    display: block !important;
                    margin: 0;
                    background: #435ebe18;
                    border: 0;
                }
                #btn-open-nav {
                    background: transparent;
                    border: 0;
                    outline: 0;
                }
            }
        </style>
    </head>

    <body>
        <header class="sticky-top top-0 bg-body">
            <nav class="navbar py-3 border-bottom shadow">
                <div class="container-fluid d-md-flex d-block">
                    <div
                        class="d-flex align-items-center justify-content-between"
                    >
                        <div class="d-flex align-items-center gap-3">
                            <img
                                src="{{
                                    asset('assets/compiled/logos/logo-sm.png')
                                }}"
                                alt="Smanesa"
                                height="50"
                            />
                            <h4 class="mb-0 d-none d-md-block">
                                SMAN 1 Purwosari
                            </h4>
                        </div>
                        <button
                            type="button"
                            id="btn-open-nav"
                            class="d-inline-block d-md-none"
                            data-status="hidden"
                        >
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                    <div
                        class="flex-column flex-md-row gap-0 gap-md-2 mt-3 mt-md-0"
                        id="btn-header-group"
                    >
                        <!-- <a
                            href="{{ route('scan.index') }}"
                            class="btn btn-header btn-primary"
                            >Scan Kehadiran</a
                        > -->
                        @if(Auth::check())
                        <a
                            href="{{ route('dashboard') }}"
                            class="btn btn-header btn-primary"
                            >Dashboard</a
                        >
                        @else
                        <a
                            href="{{ route('login') }}"
                            class="btn btn-header btn-primary"
                            >Masuk</a
                        >
                        @endif
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <div class="d-flex flex-column gap-3">
                <section id="about" class="bg-primary">
                    <div class="container px-4 py-5">
                        <div
                            class="row flex-lg-row-reverse align-items-center justify-content-center justify-content-lg-start g-5 py-5"
                        >
                            <div class="col-10 col-sm-8 col-lg-6">
                                <img
                                    src="{{
                                        asset(
                                            'images/illustration/illustration-2-sm.jpg'
                                        )
                                    }}"
                                    class="d-block mx-lg-auto img-fluid rounded-4"
                                    alt="Bootstrap Themes"
                                    width="700"
                                    height="500"
                                    loading="lazy"
                                />
                            </div>
                            <div class="col-lg-6">
                                <h1
                                    class="display-5 fw-bold lh-1 mb-3 text-white text-center text-lg-start"
                                >
                                    SMANESA
                                </h1>
                                <p
                                    class="lead text-light text-center text-lg-start"
                                >
                                    Berimtaq, Unggul, dan Berbudaya
                                </p>
                                <p
                                    class="lead text-light text-center text-lg-start"
                                >
                                    Sistem Informasi Manajemen SMAN 1 Purwosari
                                    yang terintegrasi dengan data Dapodik
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="list" class="my-5">
                    <div class="container">
                        <div class="text-center mb-3 pb-0 mb-lg-5 pb-lg-3">
                            <h2 class="mb-0">SMAN 1 Purwosari</h2>
                            <div class="lead">Data pada website saat ini</div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                                <div
                                    class="bg-primary-subtle border border-primary rounded-4"
                                >
                                    <div class="fs-1 fw-bold">
                                        {{ $count_student }}
                                    </div>
                                    <div class="lead">Siswa Aktif</div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                                <div
                                    class="bg-primary-subtle border border-primary rounded-4"
                                >
                                    <div class="fs-1 fw-bold">
                                        {{ $count_teacher }}
                                    </div>
                                    <div class="lead">Pengajar</div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                                <div
                                    class="bg-primary-subtle border border-primary rounded-4"
                                >
                                    <div class="fs-1 fw-bold">
                                        {{ $count_admin }}
                                    </div>
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
                                    <li class="nav-item mb-2">
                                        <span
                                            class="nav-link p-0 text-body-secondary"
                                            >Sistem Informasi Manajemen SMAN 1
                                            Purwosari yang terintegrasi dengan
                                            data Dapodik</span
                                        >
                                    </li>
                                </ul>
                            </div>

                            <div class="col-6 col-md-4 mb-3">
                                <h5>Fitur</h5>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2">
                                        <span
                                            class="nav-link p-0 text-body-secondary"
                                            >Manajemen Kehadiran</span
                                        >
                                    </li>
                                    <li class="nav-item mb-2">
                                        <span
                                            class="nav-link p-0 text-body-secondary"
                                            >Manajemen Pelanggaran</span
                                        >
                                    </li>
                                    <li class="nav-item mb-2">
                                        <span
                                            class="nav-link p-0 text-body-secondary"
                                            >Manajemen Perizinan</span
                                        >
                                    </li>
                                </ul>
                            </div>

                            <div class="col-6 col-md-4 mb-3">
                                <h5>Alamat</h5>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2">
                                        <span
                                            href="#"
                                            class="nav-link p-0 text-body-secondary"
                                        >
                                            Jl. Pegadaian No. 1B Purwosari
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between pt-4 mt-4 border-top"
                        >
                            <p>{{ date("Y") }} © SMAN 1 Purwosari</p>
                            <ul class="list-unstyled d-flex">
                                <li class="ms-3">
                                    <a
                                        href="http://www.sman1purwosari.sch.id"
                                        target="_blank"
                                        class="link-body-emphasis"
                                        href="#"
                                        ><i class="bi bi-globe"></i
                                    ></a>
                                </li>
                                <li class="ms-3">
                                    <a
                                        href="mailto:sman1purwosari@yahoo.co.id"
                                        class="link-body-emphasis"
                                        href="#"
                                        ><i class="bi bi-envelope-fill"></i
                                    ></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </footer>
            </div>
        </main>

        <script src="{{ asset('assets/static/js/initTheme.js') }}">
            </sty>
                <script src="{{ asset('assets/static/js/components/dark.js') }}">
        </script>
        <script src="{{
                asset(
                    'assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js'
                )
            }}"></script>
        <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
        <script src="{{
                asset('assets/extensions/jquery/jquery.min.js')
            }}"></script>
        <script>
            $(document).on("click", "#btn-open-nav", function () {
                setNavDisplay();
            });

            $(window).on("resize", function () {
                setDisplay();
            });

            setDisplay();

            function setDisplay() {
                if ($(window).width() < 768) {
                    $("#btn-header-group").hide();
                    $("#btn-open-nav").data("status", "hidden");
                    $("#btn-open-nav > i").addClass("bi-list");
                    $("#btn-open-nav > i").removeClass("bi-x");
                } else {
                    $("#btn-header-group").css("display", "flex");
                }
            }
            function setNavDisplay() {
                if ($("#btn-open-nav").data("status") == "hidden") {
                    $("#btn-open-nav").data("status", "show");
                    $("#btn-header-group").show();
                    $("#btn-open-nav > i").addClass("bi-x");
                    $("#btn-open-nav > i").removeClass("bi-list");
                } else {
                    $("#btn-header-group").hide();
                    $("#btn-open-nav").data("status", "hidden");
                    $("#btn-open-nav > i").addClass("bi-list");
                    $("#btn-open-nav > i").removeClass("bi-x");
                }
            }
        </script>
    </body>
</html>
