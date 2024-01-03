@extends('admin.layouts.app') @section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $page_title }}</h3>
                <p class="text-subtitle text-muted">{{ $sub_title }}</p>
            </div>
            <div class="col-12 col-md-6 text-end order-md-2 order-first">
                <!-- <nav
                    aria-label="breadcrumb"
                    class="breadcrumb-header float-start float-lg-end"
                >
                    <ol class="breadcrumb">
                        @foreach($bread_crumbs as $title => $url)
                        <li class="breadcrumb-item">
                            <a href="{{ $url }}">{{ $title }}</a>
                        </li>
                        @endforeach
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $now_page }}
                        </li>
                    </ol>
                </nav> -->
                <button
                    id="sync-teacher"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop"
                >
                    Sinkronisasi Guru
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
                                    Sinkronisasi Guru Dapodik
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
                                    Sinkronisasi data gutu sedang berlangsung.
                                    Mohon tunggu sebentar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php $data_column = [ "full_name" => "Nama", "email" => "Email / NIK",
    "phone_number" => "Nomor", "action" => "Aksi" ]; @endphp
    <x-datatable
        card-title="Tabel Guru"
        data-url="{{ route('teacher.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="teacher/soft-delete/deleted_id"
        data-add-url="{{ url('teacher/create') }}"
    />
</div>
@endsection @push('custom-script')
<script>
    $(document).ready(function () {
        $("#sync-teacher").on("click", function () {
            // $('.modal').modal('show');

            $.ajax({
                type: "GET",
                url: "{{ route('teacher.sync') }}",
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (err) {
                    console.log(err);
                    location.reload();
                },
            });
        });
    }); //end of document.ready
</script>
@endpush
