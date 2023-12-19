<!DOCTYPE html>
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
                                value: 'api',
                                _token: "{{ csrf_token() }}"
                            },
                            success: (data) => {
                                // toast('success', 'Berhasil melakukan absensi');
                                toast(data.status, data.message)
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
</html>
