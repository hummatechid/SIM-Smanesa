<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIM Smanesa | Absensi</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .card {
            margin-bottom: 0!important;
        }
        html {
            --height-scanner: 200px;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            height: 100dvh;
        }
        #wrapper-scan {
            position: relative;
            min-height: var(--height-scanner);
            grid-area: scan;
        }
        #nisn, #background-scan, #nisn:focus, #nisn:active, #nisn:hover {
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
        #nisn:not(:focus) ~ #msg::after {
            content: ", Tap Area Ini";
        }
        #msg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%)
        }
        #main-container {
            display: grid;
            grid-template-areas: "tb howto" "tb scan";
            grid-template-rows: 1fr auto;
            grid-template-columns: repeat(2, 1fr);
            min-height: 100dvh;
            gap: 1.5rem;
            padding: 1.5rem;
        }
        #tb { grid-area: tb;}
        #howto {grid-area: howto;}

        @media only screen and (max-width: 720px) {
            html {
                --height-scanner: 100px;
            }
            #main-container {
                grid-template-areas: "scan""howto""tb";
                grid-template-rows: repeat(3, auto);
                grid-template-columns: auto;
            }
        }
    </style>
    @stack('custom-style')
</head>
<body>
    <div class="container-fluid">
        <div id="main-container">
            <div id="tb">
                @php
                    $data_column = ["student" => "Siswa", "present_at" => "Waktu Kehadiran", "status" => "Status"];
                @endphp
                <x-datatable
                    card-title="Data Kehadiran Terbaru"
                    data-url="{{ route('attendance.get-main-datatables') }}"
                    :table-columns="$data_column"
                    default-order="2"
                    arrange-order="desc"
                    :searchable-table="false"
                />
            </div>
            <div id="howto">
                <div class="card h-100">
                    <div class="card-header">
                        <h4>Cara Absensi</h4>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Buka laman <a href="{{ route('scan.index') }}">"{{ route('scan.index') }}"</a></li>
                            <li>Jika terdapat area berwarna merah, klik area tersebut</li>
                            <li>Area akan berubah berwarna hijau</li>
                            <li>Mulailah melakukan scan untuk melakukan absensi</li>
                            <li>Pastikan data siswa telah ditambahkan pada tabel</li>
                        </ol>    
                    </div>
                </div>
            </div>
            <div id="wrapper-scan">
                <input type="text" autofocus class="form-control" id="nisn">
                <div id="background-scan"></div>
                <div id="msg" class="text-center lead fs-4 fw-bold">Siap Melakukan Scan</div>
            </div>
        </div>
    </div>
    

    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        const time_before_send = 300;
        const waiting_detect_time = 3000;
        var waiting_on_detect = false;
        var timer_timeout;
    
        $(window).on('load',  () => {
    
            $('#nisn').on('change input', () => {
                if(timer_timeout) clearTimeout(timer_timeout)
                
                if(!waiting_on_detect) {
                    timer_timeout = setTimeout(() => {
                        var nipd = $('#nisn').val()
                        $('#nisn').val("")
    
                        waiting_on_detect = true;
                        setTimeout(() => {
                            waiting_on_detect = false;
                        }, waiting_detect_time)
    
                        $.ajax({
                            url: "{{ route('attendance.store') }}",
                            method: "post",
                            data: {
                                nipd: nipd,
                                type: 'api',
                                _token: "{{ csrf_token() }}"
                            },
                            success: (data) => {
                                console.log(data)
                                // toast('success', 'Berhasil melakukan absensi');
                                toast(data.status, data.message)
                                if(data.status == "success") table.ajax.reload()
                            },
                            error: (data) => {
                                toast(data.responseJSON.status, data.responseJSON.message)
                            }
                        })
                    }, time_before_send);
                } else {
                    $('#nisn').val("")
                }
            })
    
            const toast = (type = "success", message = "message") => {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    title: message,
                    icon: type
                });
            }
        })
    </script>
    @stack('custom-script')
</body>
</html>