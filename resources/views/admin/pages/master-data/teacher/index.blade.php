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
        $data_column = [
            "full_name" => "Nama",
            "email" => "Email",
            "phone_number" => "Nomor",
            "action" => "Aksi"
        ];
    @endphp
    <x-datatable
        card-title="Tabel Guru"
        data-url="{{ route('teacher.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="teacher/soft-delete/deleted_id"
        data-add-url="{{ url('teacher/create') }}"
    />

</div>
@endsection