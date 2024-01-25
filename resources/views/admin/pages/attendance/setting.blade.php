@extends('admin.layouts.app')

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

    <form action="{{ route('attendance.store-time-setting') }}" method="post" class="card">
        <div class="card-header">
            <h5 class="card-title">Atuh Jam Hadir & Pulang</h5>
        </div>
        <div class="card-body">
            <x-session-alert />
            @csrf
            <div class="form-group mb-3">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="row">
                <div class="col-md-6 col-12 form-group mb-3">
                    <label for="attendance" class="form-label">Jam Kehadiran</label>
                    <input type="time" name="attendance" id="attendance" class="form-control" value="07:00">
                </div>
                <div class="col-md-6 col-12 form-group mb-3">
                    <label for="departure" class="form-label">Jam Pulang</label>
                    <input type="time" name="departure" id="departure" class="form-control" value="16:00">
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ url('attendance') }}" class="btn btn-secondary">&#10094; Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection

@push('custom-script')
<script>
    $(document).ready(function() {
        getAttendanceDepartureTime()
        $('#date').on('change', getAttendanceDepartureTime)

        function getAttendanceDepartureTime() {
            let date = $('#date').val()
            
            $.ajax({
                url: "{{ route('attendance.get-time-setting') }}",
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    date
                }, success: function(rows) {
                    let attendance = rows.data.attendance
                    let departure = rows.data.departure
                    setAttendanceDepartureTime(attendance, departure)
                }, error: function() {
                    setAttendanceDepartureTime()
                }
            })
        }

        function setAttendanceDepartureTime(attendance = "07:00", departure = "16:00") {
            $('#attendance').val(attendance)
            $('#departure').val(departure)
        }
    })
</script>
@endpush