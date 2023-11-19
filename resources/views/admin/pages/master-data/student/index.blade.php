@extends('admin.layouts.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $page_title }}</h3>
                <p class="text-subtitle text-muted">{{ $sub_title }}</p>
            </div>
            <div class="col-6 order-md-2 order-last align-items-end text-end">
                <button id="sync-siswa" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">
                    Sync Siswa</button>

                <!--Basic Modal -->
                <div class="modal fade text-center modal-borderless" data-bs-backdrop="false" data-backdrop="static" id="staticBackdrop" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel1">Sinkronisasi Siswa Dapodik</h5>
                            </div>
                            <div class="modal-body">
                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3">
                                    Sinkronisasi data siswa sedang berlangsung. Mohon tunggu sebentar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $data_column = [
            "full_name" => "Nama",
            "nisn" => "NISN",
            "nipd" => "NIPD",
            "gender" => "Jenis Kelamin",
            "nama_rombel" => "Kelas",
            "action" => "Aksi"
        ];
    @endphp
    <x-datatable
        card-title="Tabel Siswa"
        data-url="api/student/get-main-data"
        :table-columns="$data_column"
        delete-option="student/deleted_id"
    />

</div>
@endsection

@push('custom-script')
    <script>
        $(document).ready(function () {

            $('#sync-siswa').on('click', function () {
                // $('.modal').modal('show');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('student.sync') }}',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);

                    },
                    error: function (err) {
                        console.log(err);
                    },
                });

                location.reload();
            });

        }); //end of document.ready


    </script>
@endpush
