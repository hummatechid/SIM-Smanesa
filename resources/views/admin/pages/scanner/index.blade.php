@inject('carbon', 'Carbon\Carbon')

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>SIM Smanesa | Absensi</title>
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
            href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css"
            rel="stylesheet"
        />
        <style>
            .card {
                margin-bottom: 0 !important;
            }
            html {
                --height-scanner: 200px;
            }
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            #wrapper-scan {
                position: relative;
                min-height: var(--height-scanner);
                grid-area: scan;
            }
            #nisn,
            #background-scan,
            #nisn:focus,
            #nisn:active,
            #nisn:hover {
                width: 100%;
                height: var(--height-scanner);
                border: 0;
                outline: 0;
                cursor: pointer;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border-radius: 15px;
            }
            #nisn {
                opacity: 0;
                z-index: 1;
            }

            #msg {
                transition: all 1s;
                user-select: none;
                color: white;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            #nisn:not(:focus) ~ #background-scan {
                background-color: var(--bs-danger);
            }
            #nisn:focus ~ #background-scan {
                background-color: var(--bs-success);
            }
            #nisn:not(:focus) ~ #msg::before {
                content: "Belum ";
            }
            #nisn:focus ~ #msg > #msg-invalid, #nisn:focus ~ #msg > #msg-break {
                display: none;
            }
            #nisn:not(:focus) ~ #msg > #msg-invalid, #nisn:not(:focus) ~ #msg > #msg-break {
                display: inline;
            }
            #msg {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            #main-container {
                display: grid;
                grid-template-areas: "tb howto" "tb scan";
                grid-template-rows: 1fr auto;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
                padding: 1.5rem;
                max-width: 100vw;
                min-height: 100dvh;
                overflow: hidden;
            }
            #tb {
                grid-area: tb;
            }
            #howto {
                grid-area: howto;
            }
            #back-button {
                position: fixed;
                bottom: 25px;
                left: 25px;
                z-index: 999;
            }

            @media only screen and (max-width: 720px) {
                html {
                    --height-scanner: 100px;
                }
                #main-container {
                    grid-template-areas: "scan" "howto" "tb";
                    grid-template-rows: repeat(3, auto);
                    grid-template-columns: 100%;
                }
            }
        </style>
        @stack('custom-style')
    </head>
    <body>
        <div id="custom-container">
            <div id="main-container">
                <div id="tb">
                    @php 
                        $data_column = [
                            "student" => [
                                "title" => "Siswa",
                                "width" => "200px"
                            ], "class" => [
                                "title" => "Kelas",
                                "width" => "75px",
                            ], "present_at" => [
                                "title" => "Waktu Kehadiran",
                                "width" => "170px",
                            ], "status" => [
                                "title" => "Status",
                                "width" => "75px",
                            ]
                        ];
                        $card_title = '<div class="row">
                            <div class="col-12 col-lg-6 mb-2">
                                Data Kehadiran Terbaru
                            </div>
                            <div class="col-12 col-lg-6 mb-2">
                                <span class="badge bg-primary">
                                    '.$carbon::parse(now())->locale('id_ID')->isoFormat('DD MMMM YYYY').' - 
                                    <span id="clock"></span>
                                </span>
                            </div>
                        </div>';
                    @endphp
                    <x-datatable
                        :card-title="$card_title"
                        data-url="{{ route('attendance.get-main-datatables') }}"
                        :table-columns="$data_column"
                        default-order="3"
                        arrange-order="desc"
                        :searchable-table="false"
                        :datatable-responsive="true"
                    />
                </div>
                <div id="howto">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Cara Absensi</h4>
                        </div>
                        <div class="card-body">
                            <ol>
                                <!-- <li>Buka laman <a href="{{ route('scan.index') }}">"{{ route('scan.index') }}"</a></li> -->
                                <li>
                                    Jika terdapat area berwarna merah, klik area
                                    tersebut
                                </li>
                                <li>Area akan berubah berwarna hijau</li>
                                <li>
                                    Mulailah melakukan scan untuk melakukan
                                    absensi
                                </li>
                                <li>
                                    Pastikan data siswa telah ditambahkan pada
                                    tabel
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div id="wrapper-scan">
                    <input
                        type="text"
                        autofocus
                        class="form-control"
                        id="nisn"
                    />
                    <div id="background-scan"></div>
                    <div id="msg" class="text-center lead fs-4 fw-bold">
                        Siap Melakukan Scan<div id="msg-break">,</div> <span id="msg-invalid">Tap Area Ini</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="back-button" class="btn-group">
            <a href="{{ route('landing-page') }}" class="btn btn-lg btn-primary"
                ><i class="bi bi-house-fill"></i
            ></a>
            {{-- <div
                id="clock"
                class="btn btn-lg bg-secondary text-white"
                style="cursor: default"
            ></div> --}}
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
        <script>
            const time_before_send = 300;
            const waiting_detect_time = 3000;
            var waiting_on_detect = false;
            var timer_timeout;

            $(window).on("load", () => {
                $("#nisn").on("change input", () => {
                    if (timer_timeout) clearTimeout(timer_timeout);

                    if (!waiting_on_detect) {
                        timer_timeout = setTimeout(() => {
                            var nipd = $("#nisn").val();
                            $("#nisn").val("");

                            waiting_on_detect = true;
                            setTimeout(() => {
                                waiting_on_detect = false;
                            }, waiting_detect_time);

                            $.ajax({
                                url: "{{ route('presence.attendance') }}",
                                method: "post",
                                data: {
                                    nipd: nipd,
                                    type: "api",
                                    _token: "{{ csrf_token() }}",
                                },
                                success: (data) => {
                                    console.log(data);
                                    // toast('success', 'Berhasil melakukan absensi');
                                    toast(data.status, data.message);
                                    if (data.status == "success")
                                        table.ajax.reload();
                                },
                                error: (data) => {
                                    toast(
                                        data.responseJSON.status,
                                        data.responseJSON.message
                                    );
                                },
                            });
                        }, time_before_send);
                    } else {
                        $("#nisn").val("");
                    }
                });

                const toast = (type = "success", message = "message") => {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        title: message,
                        icon: type,
                    });
                };

                startTime();
                function startTime() {
                    const today = new Date();
                    let h = today.getHours();
                    let m = today.getMinutes();
                    let s = today.getSeconds();
                    m = checkTime(m);
                    s = checkTime(s);
                    document.getElementById("clock").innerHTML =
                        h + ":" + m + ":" + s;
                    setTimeout(startTime, 1000);
                }

                function checkTime(i) {
                    if (i < 10) {
                        i = "0" + i;
                    } // add zero in front of numbers < 10
                    return i;
                }
            });
            
            // Disable right click
            document.addEventListener('contextmenu', (e) => e.preventDefault());

            function ctrlShiftKey(e, keyCode) {
                return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
            }

            document.onkeydown = (e) => {
            // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
                if (
                    event.keyCode === 123 ||
                    ctrlShiftKey(e, 'I') ||
                    ctrlShiftKey(e, 'J') ||
                    ctrlShiftKey(e, 'C') ||
                    (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
                ) {
                    return false;
                }
            };
        </script>
        @stack('custom-script')
    </body>
</html>
