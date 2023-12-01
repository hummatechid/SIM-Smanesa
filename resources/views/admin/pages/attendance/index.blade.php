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
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-2">
            <div class="card bg-success">
                <div class="border-white card-header border-bottom m-0 p-2 fw-bold bg-success text-center text-white">Tepat Waktu</div>
                <div class="card-body m-0 p-3 lead fs-5 text-center text-white">100</div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card bg-primary">
                <div class="border-white card-header border-bottom m-0 p-2 fw-bold bg-primary text-center text-white">Terlambat</div>
                <div class="card-body m-0 p-3 lead fs-5 text-center text-white">100</div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card bg-danger">
                <div class="border-white card-header border-bottom m-0 p-2 fw-bold bg-danger text-center text-white">Tidak Hadir</div>
                <div class="card-body m-0 p-3 lead fs-5 text-center text-white">100</div>
            </div>
        </div>
    </div>

    @php
        $data_column = ["name" => "Siswa", "time" => "Waktu Kehadiran"];
    @endphp
    <x-datatable
        card-title="Tabel Data Kehadiran"
        data-url="{{ route('attendance.get-main-datatables') }}"
        :table-columns="$data_column"
        default-order="3"
    />

</div>
@endsection