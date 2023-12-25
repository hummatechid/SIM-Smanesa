@extends('admin.layouts.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $page_title }}</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        @foreach($bread_crumbs as $title => $url)
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $title }}</a></li>
                        @endforeach
                        <li class="breadcrumb-item active" aria-current="page">{{ $now_page }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 col-12 mb-2">
            <div class="card bg-primary mb-0">
                <div class="border-white card-header border-bottom m-0 p-2 fw-bold bg-primary text-center text-white">Siswa Aktif</div>
                <div class="card-body m-0 p-3 lead fs-5 text-center text-white">{{ $data->student }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-12 mb-2">
            <div class="card bg-success mb-0">
                <div class="border-white card-header border-bottom m-0 p-2 fw-bold bg-success text-center text-white">Guru</div>
                <div class="card-body m-0 p-3 lead fs-5 text-center text-white">{{ $data->teacher }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12 mb-4">
            <x-datatable
                card-title="Siswa Sering Melanggar"
                data-url="{{ route('violation.count-student').'?limit=6' }}"
                :table-columns="$data_violation"
                default-order="2"
                arrange-order="desc"
                :pagging-table="false"
                :searchable-table="false"
                :server-side="false"
            />
        </div>
        <div class="col-md-6 col-12 mb-4">
            <x-datatable
                card-title="Siswa Sering Terlambat"
                data-url="{{ route('attendance.must-late',).'?status=masuk&limit=6' }}"
                :table-columns="$data_late"
                default-order="2"
                arrange-order="desc"
                table-id="table1"
                :pagging-table="false"
                :searchable-table="false"
                :server-side="false"
            />
        </div>
        <div class="col-12 mb-4">
            <x-datatable
                card-title="Kehadiran Terbaru"
                data-url="{{ route('attendance.new-list',).'?status=masuk&limit=6' }}"
                :table-columns="$data_presence"
                default-order="2"
                arrange-order="desc"
                table-id="table2"
                :pagging-table="false"
                :searchable-table="false"
                :server-side="false"
            />
        </div>
    </div>

</div>
@endsection