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

    @php
        $data_column = ["name" => "Pelanggar", "violation" => "Pelanggaran", "score" => "Poin", "date" => 'Tanggal'];
    @endphp
    <x-datatable
        card-title="Tabel Data Pelanggaran"
        data-url="api/violation/get-main-data"
        :table-columns="$data_column"
        default-order="4"
        data-add-url="{{ url('violation/create') }}"
        delete-option="violation/soft-delete/delete_id"
    />

</div>
@endsection