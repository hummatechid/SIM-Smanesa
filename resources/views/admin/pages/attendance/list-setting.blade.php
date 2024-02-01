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

    @php
        $data_column = ["date" => "Tanggal", "attendance" => "Jam Hadir", "departure" => "Jam Pulang", "action" => "Aksi"];
    @endphp
    <x-datatable
        card-title="Tabel Jam Hadir & Pulang"
        data-url="{{ route('attendance.all-time-setting') }}"
        :table-columns="$data_column"
        default-order="1"
        arrange-order="desc"
        :data-add-url="route('attendance.time-setting')"
    />

</div>

<div class="modal fade" id="modal-edit-data" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('attendance.store-time-setting') }}" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Jam Hadir & Pulang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group mb-3">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="hidden" name="date" id="date" value="{{ $time->date ?? date('Y-m-d') }}" required readonly>
                    <input type="date" id="date_show" class="form-control" value="{{ $time->date ?? date('Y-m-d') }}" disabled>
                </div>
                <div class="row">
                    <div class="col-md-6 col-12 form-group mb-3">
                        <label for="attendance" class="form-label">Jam Kehadiran <span class="text-danger">*</span></label>
                        <input type="time" name="attendance" id="attendance" class="form-control" value="{{ $time->time_start ?? "07:05" }}" required>
                    </div>
                    <div class="col-md-6 col-12 form-group mb-3">
                        <label for="departure" class="form-label">Jam Pulang <span class="text-danger">*</span></label>
                        <input type="time" name="departure" id="departure" class="form-control" value="{{ $time->time_start ?? "12:00" }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ubah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('custom-script')
<script>
    $(document).on('click', '.btn-change', function() {
        let data = $(this).data('data')

        $('#date').val(data.date)
        $('#date_show').val(data.date)
        $('#attendance').val(data.time_start)
        $('#departure').val(data.time_end)
    })
</script>
@endpush