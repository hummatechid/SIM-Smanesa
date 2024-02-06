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
                        @foreach($bread_crumbs as $title => $url)
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $title }}</a></li>
                        @endforeach
                        <li class="breadcrumb-item active" aria-current="page">{{ $now_page}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasExactRoles('satpam'))
        @php
            $data_column = [
                "date" => "Waktu",
                "student" => "Siswa",
                "reason" => "Alasan",
                "status" => "Status",
            ];
        @endphp
        <x-datatable
            card-title="Tabel Izin"
            data-url="{{ route('permit.get-main-datatables',).'?status=accepted' }}"
            :table-columns="$data_column"
            default-order="1"
            arrange-order="desc"
        />
    @elseif(auth()->user()->hasRole(['guru', 'staf']))
        @php
            $data_column = [
                "date" => "Waktu",
                "student" => "Siswa",
                "reason" => "Alasan",
                "status" => "Status",
            ];
        @endphp
        <x-datatable
            card-title="Tabel Izin"
            data-url="{{ route('permit.get-main-datatables') }}"
            :table-columns="$data_column"
            default-order="1"
            arrange-order="desc"
        />
    @else
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
                        // "back" => "Telah Kembali"
                    ]
                ]
            ]
        @endphp
        <x-datatable
            card-title="Tabel Izin"
            data-url="{{ route('permit.get-main-datatables') }}"
            default-order="1"
            arrange-order="desc"
            :table-columns="$data_column"
            delete-option="permit/soft-delete/deleted_id"
            data-add-url="{{ url('permit/create') }}"
            :with-multiple-select="true"
            multiple-select-all='
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="">
                    <input type="checkbox" name="select-all" id="select-all" class="me-2">
                    <label for="select-all">pilih semua data pending</label>
                </div>
                <div class="">
                    <select name="select-action" id="select-action" class="form-select">
                        <option value="">Pilih Aksi</option>
                        <option value="accepted">Setujui</option>
                        <option value="rejected">Tolak</option>
                    </select>
                </div>
            </div>'
            :with-custom-groups="$custom_group"
        />
    @endif

</div>

<div class="modal fade" tabindex="-1" id="reject-one-modal">
    <div class="modal-dialog">
        <form class="modal-content" action="" method="post">
            <div class="modal-header">
                <h4 class="modal-title">
                    Tolak Izin
                </h4>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @method('patch')
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="form-group">
                    <label for="notes" id="form-labe">Catatan alasan <span class="text-danger">*</span></label>
                    <textarea name="notes" id="notes" rows="5" class="form-control" placeholder="Alasan Penolakan"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Kirim</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('custom-script')
    <script>
        $(window).on('load', function(){

            $('#select-all').on('click', function() {
                $('input:checkbox[name=permit][data-response=pending]').prop('checked', $(this).prop('checked'))
                $('input:checkbox[name=permit][data-response=""]').prop('checked', $(this).prop('checked'))
            })

            $('#select-action').on('change', function() {
                var acc_text = $(this).val() == 'accepted' ? "disetujui" : "ditolak";
                var notes = null;
                if($(this).val() == 'rejected') {
                    Swal.fire({
                        title: "Berikan alasan penolakan",
                        input: "textarea",
                        inputAttributes: {
                            required: true
                        },
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        confirmButtonText: "Kirim",
                        confirmButtonColor: '#dc3545',
                    }).then((result) => {
                        if(result.isConfirmed) {
                            notes = result.value
                            sendAcception($(this).val())
                        }
                    })
                } else sendAcception($(this).val())

                function sendAcception(status) {
                    if(status == 'accepted' || (status == "rejected" && notes !== null)) {
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
                                    url: "{{ route('permit.updateManyData') }}",
                                    method: "PATCH",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        status: status,
                                        selected_id: all_id,
                                        notes: notes
                                    },
                                    success: function(res) {
                                        jquery_datatable.ajax.reload()
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: `Data izin batal ${acc_text}!`,
                                    icon: 'info',
                                });
                            }
                        })
                    }
                }
            })

            $(document).on('change input', 'input:checkbox[name=permit][data-response=pending]', function() {
                checkIsAllCheck()
            })
            $(document).on('change input', 'input:checkbox[name=permit][data-response=""]', function() {
                checkIsAllCheck()
            })

            function checkIsAllCheck() {
                let pending1 = $('input:checkbox[name=permit][data-response=pending]');
                let pending2 = $('input:checkbox[name=permit][data-response=""]');
                let count_not_checked = 0;

                pending1.each(function (index, item) {
                    !item.checked ? count_not_checked++ : ''
                })
                pending1.each(function (index, item) {
                    !item.checked ? count_not_checked++ : ''
                })

                if(count_not_checked > 0) $('#select-all').prop('checked', false)
                else $('#select-all').prop('checked', true)
            }

            $(document).on('click', '.btn-reject', function() {
                let id = $(this).data('id')
                let action_url = "{{ route('permit.update', 'selected_id') }}"
                action_url = action_url.replace('selected_id', id)
                $('#reject-one-modal form').attr('action', action_url)
            })

        })
    </script>
@endpush