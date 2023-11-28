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
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @php
        $data_column = ["category" => "Kategori", "score" => "Poin","treatment" => "Tindakan" ,"action" => "Aksi"];
        $data_settings = [
            "category" => [
                "title" => "Kategori",
                "type" => "select",
                "first_option" => "-- pilih kategori pelanggaran --",
                "options" => [
                    "Pelanggaran ringan" => "Pelanggaran Ringan",
                    "Pelanggaran sedang" => "Pelanggaran Ringan",
                    "Pelanggaran berat" => "Pelanggaran Berat",
                ],
                "attr" => ["required" => "required"],
            ], "min_score" => [
                "title" => "Skor Minimum",
                "type" => "number",
                "attr" => ["required" => "required", "min"=>1, "max"=>999]
            ], "max_score" => [
                "title" => "Skor Maksimum",
                "type" => "number",
                "attr" => ["required" => "required", "min"=>1, "max"=>999]
            ],
            "action" => [
                "title" => "Tindakan",
                "type" => "text",
                "attr" => ["required" => "required"]
            ], 
        ];
    @endphp
    <x-datatable
        card-title="Tabel Tindak Lanjut"
        data-url="api/treatment/get-main-data"
        :table-columns="$data_column"
        delete-option="treatment/soft-delete/deleted_id"
        :default-order="2"
        data-add-url="{{ route('treatment.store') }}"
        data-add-type="modal"
        :data-add-settings="$data_settings"
    />

</div>
@endsection