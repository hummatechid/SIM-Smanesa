@extends('admin.layouts.no-sidebar-layout')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $page_title }}</h3>
                    <p class="text-subtitle text-muted">{{ $sub_title }}</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            @foreach($bread_crumbs as $title => $url)
                            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $title }}</a></li>
                            @endforeach
                            <li class="breadcrumb-item active" aria-current="page">{{ $now_page}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- <a href="{{ route('dashboard') }}"id="btn-back" class="btn btn-secondary">&#10094; Kembali</a> --}}
    <div id="wrapper-scan" class="mb-4">
        <input type="text" autofocus class="form-control" id="nisn">
        <div id="background-scan"></div>
        <div id="msg" class="text-center lead fs-4 fw-bold">Siap Melakukan Scan</div>
    </div>

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

@endsection

@push('custom-style')
<style>
    html {
        --height-scanner: 100px;
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
        background-color: var(--bs-teal);
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
</style>
@endpush
@push('custom-script')
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
                            value: 'api',
                            _token: "{{ csrf_token() }}"
                        },
                        success: (data) => {
                            // toast('success', 'Berhasil melakukan absensi');
                            toast(data.status, data.message)
                            if(data.status == "success") table.ajax.reload()
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
@endpush

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            height: 100dvh;
        }
        #nisn, #nisn:focus, #nisn:active, #nisn:hover {
            width: 100%;
            height: 100dvh;
            border: 0;
            outline: 0;
            color: transparent;
            cursor: pointer;
        }

        #msg {
            color: var(--bs-danger);
            transition: all 1s;
            user-select: none;
        }
        #nisn:not(:focus) ~ #msg::before {
            content: "Belum ";
            color: var(--bs-danger);
        }
        #nisn:not(:focus) ~ #msg::after {
            content: ", Tap Area di Luar Teks Ini";
            color: var(--bs-danger);
        }
        #nisn:focus ~ #msg {
            color: var(--bs-teal);
        }
        #btn-back {
            position: absolute;
            top: 50px;
            left: 50px;
        }
        #msg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%)
        }
    </style>
</head>
<body>
    <input type="text" autofocus class="form-control" id="nisn">
    <div id="msg" class="text-center lead fs-1">Siap Melakukan Scan</div>

    <a href="{{ route('dashboard') }}"id="btn-back" class="btn btn-secondary">&#10094; Kembali</a>

    
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
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
                                // toast('success', 'Berhasil melakukan absensi');
                                toast(data.status, data.message)
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
</body>
</html> --}}
