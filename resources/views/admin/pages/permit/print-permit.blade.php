@inject('carbon', 'Carbon\Carbon')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan Izin</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Laporan Perizinan Siswa</h1>

        <table class="table table-striped w-100 my-3">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $permit->student->full_name }}</td>
                    <td>{{ $permit->student->nama_rombel }}</td>
                    <td>{{ $carbon::parse($permit->created_at)->locale('id_ID')->isoFormat('DD MMMM YYYY HH:mm') }}</td>
                    <td>{{ $permit->status }}</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column justify-content-between align-items-center">
                <div class="text-center mb-5">Diizinkan oleh</div>
                <div class="text-center">{{ $user_created ? $user_created->full_name : "-" }}</div>
            </div>
            <div class="d-flex flex-column justify-content-between align-items-center">
                <div class="text-center mb-5">Disetujui oleh</div>
                <div class="text-center">{{ $user_acc ? $user_acc->full_name : "Belum di setujui" }}</div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
</body>
</html>