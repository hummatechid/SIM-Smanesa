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
        $data_column = ["name" => "Pelanggaran", "score" => "Poin", "action" => "Aksi"];
        $data_settings = [
            "name" => [
                "title" => "Pelanggaran",
                "type" => "text",
                "attr" => ["required" => "required"]
            ], "score" => [
                "title" => "Poin",
                "type" => "number",
                "attr" => ["required" => "required", "min"=>1]
            ]
        ];
    @endphp
    <x-datatable
        card-title="Tabel Jenis Pelanggaran"
        data-url="{{ route('violation-type.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="violation-type/soft-delete/deleted_id"
        data-add-url="{{ route('violation-type.store') }}"
        data-add-type="modal"
        :data-add-settings="$data_settings"
    />

</div>
@endsection

@push('custom-script')
<script>
    $(document).on('click', '.show-detail', function() {
        console.log($(this).data('data'))
        var student = $(this).data('data')

        // var body = `
        //     <table>
        //         <tr>
        //             <th>Nama</th>
        //             <td>:</td>
        //             <td>${student.full_name}</td>
        //         </tr>
        //         <tr>
        //             <th>NISN</th>
        //             <td>:</td>
        //             <td>${student.nisn}</td>
        //         </tr>
        //         <tr>
        //             <th>NIPD</th>
        //             <td>:</td>
        //             <td>${student.nipd}</td>
        //         </tr>
        //         <tr>
        //             <th>NIK</th>
        //             <td>:</td>
        //             <td>${student.nik}</td>
        //         </tr>
        //         <tr>
        //             <th>Jenis Kelamin</th>
        //             <td>:</td>
        //             <td>${student.gender}</td>
        //         </tr>
        //         <tr>
        //             <th>No Handphone</th>
        //             <td>:</td>
        //             <td>${student.phone_number}</td>
        //         </tr>
        //         <tr>
        //             <th>Alamat</th>
        //             <td>:</td>
        //             <td>${student.address}</td>
        //         </tr>
        //         <tr>
        //             <th>Agama</th>
        //             <td>:</td>
        //             <td>${student.religion}</td>
        //         </tr>
        //         <tr>
        //             <th>Poin Pelanggaran</th>
        //             <td>:</td>
        //             <td>${student.violation_score}</td>
        //         </tr>
        //     </table>
        // `;

        // $(`#modal-detail-body`).html(body);
    });
</script>
@endpush