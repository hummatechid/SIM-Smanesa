@extends('admin.layouts.app') @section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $page_title }}</h3>
                <p class="text-subtitle text-muted">{{ $sub_title }}</p>
            </div>
            @role('superadmin')
            <div class="col-6 order-md-2 order-last align-items-end text-end">
                <button
                    id="sync-siswa"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop"
                >
                    Sync Siswa
                </button>

                <!--Basic Modal -->
                <div
                    class="modal fade text-center modal-borderless"
                    data-bs-backdrop="false"
                    data-backdrop="static"
                    id="staticBackdrop"
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="myModalLabel1"
                    aria-hidden="true"
                >
                    <div
                        class="modal-dialog modal-dialog-scrollable"
                        role="document"
                    >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel1">
                                    Sinkronisasi Siswa Dapodik
                                </h5>
                            </div>
                            <div class="modal-body">
                                <div
                                    class="spinner-border"
                                    style="width: 3rem; height: 3rem"
                                    role="status"
                                >
                                    <span class="visually-hidden"
                                        >Loading...</span
                                    >
                                </div>
                                <p class="mt-3">
                                    Sinkronisasi data siswa sedang berlangsung.
                                    Mohon tunggu sebentar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endrole
        </div>
    </div>

    <!-- Detail Siswa Modal -->
    <div
        class="modal fade modal-borderless"
        id="exampleModalCenter"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        Detail Siswa
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body" id="modal-detail-body"></div>
            </div>
        </div>
    </div>

    @php $data_column = [ "full_name" => "Nama", "nisn" => "NISN", "nipd" =>
    "NIPD", "gender" => "Jenis Kelamin", "nama_rombel" => "Kelas", "action" =>
    "Aksi" ]; @endphp
    <x-datatable
        card-title="Tabel Siswa"
        data-url="{{ route('student.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="student/soft-delete/deleted_id"
    />
</div>
@endsection @push('custom-script')
<script>
    $(document).ready(function () {
        // fetchData()
        function fetchData() {
            $.ajax({
                headers: {
                    Authorization: "Bearer NsVOp7o8jSrf8Ix",
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                type: "GET",
                url: "http://dapo.smanesa.id:5774/WebService/getPesertaDidik?npsn=20519298",
                dataType: "json",
                success: function (data) {
                    console.log(data);
                },
                error: function (err) {
                    console.log(err);
                },
            });
        }

        $(document).on("click", ".btn-detail", function () {
            console.log($(this).data("data"));
            var student = $(this).data("data");
            var body = `
                <table>
                    <tr>
                        <th>Nama</th>
                        <td>:</td>
                        <td>${student.full_name}</td>
                    </tr>
                    <tr>
                        <th>NISN</th>
                        <td>:</td>
                        <td>${student.nisn}</td>
                    </tr>
                    <tr>
                        <th>NIPD</th>
                        <td>:</td>
                        <td>${student.nipd}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>:</td>
                        <td>${student.nik}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>:</td>
                        <td>${student.gender}</td>
                    </tr>
                    <tr>
                        <th>No Handphone</th>
                        <td>:</td>
                        <td>${student.phone_number}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td>${student.address}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>:</td>
                        <td>${student.religion}</td>
                    </tr>
                    <tr>
                        <th>Poin Pelanggaran</th>
                        <td>:</td>
                        <td>${student.violation_score}</td>
                    </tr>
                </table>
            `;

            $(`#modal-detail-body`).html(body);
        });

        $("#sync-siswa").on("click", function () {
            // $('.modal').modal('show');

            $.ajax({
                type: "GET",
                url: "{{ route('student.sync') }}",
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (err) {
                    console.log(err);
                    $("#staticBackdrop").modal("hide");
                    Toastify({
                        text: "Gagal sinkronisasi dapodik!",
                        duration: 3000,
                        backgroundColor: "#DD3B4B",
                    }).showToast();
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                },
            });
        });
    }); //end of document.ready
</script>
@endpush
