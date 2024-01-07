<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>
            SIM Smanesa |
            {{ isset($sub_title) && $sub_title ? $sub_title : $page_title }}
        </title>
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
        <link
            rel="stylesheet"
            href="{{
                asset('assets/extensions/sweetalert2/sweetalert2.min.css')
            }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}"
        />
        <style>
            .btn-password {
                position: absolute;
                transform: translateY(-50%);
                top: 20px;
                right: 25px;
                background: transparent;
                border: 0;
            }
            .sidebar-wrapper .menu .submenu .submenu-item a:hover {
                margin-left: 0 !important;
            }
        </style>
        @stack("custom-style")
    </head>

    <body>
        <div id="app">
            @include('admin.layouts.sidebar')

            <div id="main" class="layout-navbar">
                @include('admin.layouts.navbar')

                <div id="main-content">@yield('content')</div>

                @include('admin.layouts.footer')
            </div>
        </div>

        <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
        <script src="{{
                asset('assets/static/js/components/dark.js')
            }}"></script>
        <script src="{{
                asset(
                    'assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js'
                )
            }}"></script>
        <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
        <script src="{{
                asset('assets/extensions/jquery/jquery.min.js')
            }}"></script>
        <script src="{{
                asset('assets/extensions/sweetalert2/sweetalert2.all.min.js')
            }}"></script>
        <script src="{{
                asset('assets/extensions/toastify-js/src/toastify.js')
            }}"></script>
        <script>
            // If you want to use tooltips in your project, we suggest initializing them globally
            // instead of a "per-page" level.
            document.addEventListener("DOMContentLoaded", function () {
                var burgerBtn = document.querySelector(".burger-btn");
                var sidebar = document.querySelector("#sidebar");
                var currentSidebar = sidebar.classList[1];

                burgerBtn.addEventListener("click", function () {
                    1;
                    sidebar.classList.remove(currentSidebar);
                    switch (currentSidebar) {
                        case "active":
                            currentSidebar = "inactive";
                            sidebar.classList.add("inactive");
                            break;
                        case "inactive":
                            currentSidebar = "active";
                            sidebar.classList.add("active");
                            break;
                    }
                });
            });
        </script>
        <script>
            $(".only-number").on("input", function () {
                $(this).val(toNumber($(this).val()));
            });
            function toNumber(text) {
                var str = text.toString();
                return str.replace(/[^0-9.]/g, "");
            }
        </script>
        @stack("custom-script")
    </body>
</html>
