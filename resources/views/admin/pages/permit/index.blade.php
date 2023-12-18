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
            "selection" => "",
            "date" => "Waktu",
            "student" => "Siswa",
            "reason" => "Alasan",
            "status" => "Status",
            "action" => "Aksi"
        ];
        $custom_group = [
            "status" => [
                "title" => "Status",
                "options" => [
                    "" => "Semua",
                    "pending" => "Pending",
                    "accepted" => "Dibolehkan",
                    "rejected" => "Dilarang",
                    "back" => "Telah Kembali"
                ]
            ]
        ]
    @endphp
    <x-datatable
        card-title="Tabel Izin"
        data-url="{{ route('permit.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="permit/soft-delete/deleted_id"
        data-add-url="{{ url('permit/create') }}"
        :with-multiple-select="true"
        :with-custom-groups="$custom_group"
        arrange-order="desc"
    />

    <div class="card">
        <div class="card-body d-flex justify-content-start align-items-center gap-3">
            <div class="input-group">
                <input type="checkbox" name="select-all" id="select-all" class="me-2">
                <label for="select-all">pilih semua data pending</label>
            </div>
            <div class="input-group">
                <select name="select-action" id="select-action" class="form-select">
                    <option value="">Pilih Aksi</option>
                    <option value="accepted">Setujui</option>
                    <option value="rejected">Tolak</option>
                </select>
            </div>

        </div>
    </div>

</div>
@endsection

@push('custom-script')
    <script>
        $(function(){

            $('#select-all').on('click', function() {
                $('input:checkbox[name=permit][data-response=pending]').prop('checked', $(this).prop('checked'))
                $('input:checkbox[name=permit][data-response=""]').prop('checked', $(this).prop('checked'))
            })

            $('#select-action').on('change', function() {
                var acc_text = $(this).val() == 'accepted' ? "disetujui" : "ditolak";
                if($(this).val() == 'accepted' || $(this).val() == "rejected") {
                    Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: `Data izin akan ${acc_text}!`,
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        confirmButtonText: `Ya`,
                        confirmButtonColor: '#dc3545',
                    }).then(result => {
                        if (result.isConfirmed) {
                            var all_id = [];
                            $('input:checkbox[name=permit]:checked').each(function() {
                                all_id.push($(this).val())
                            })

                            $.ajax({
                                url: "",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    selected_id: all_id
                                },
                                success: function(res) {
                                    jquery_datatable.ajax.reload()
                                }
                            })
                            $(this).val("")
                        } else {
                            $(this).val("")
                            Swal.fire({
                                title: `Data izin batal ${acc_text}!`,
                                icon: 'info',
                            });
                        }
                    })
                }
            })

        })
    </script>
@endpush