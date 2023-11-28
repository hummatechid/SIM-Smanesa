{{-- S:Datatable --}}
<section class="section">
    @if($dataAddUrl && $dataAddType == "modal")
    <div class="modal fade" id="modal-add-new-data" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ $dataAddUrl }}" class="modal-content" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(isset($dataAddSettings))
                    @foreach($dataAddSettings as $key => $data)
                    @if($data['type'] == "select")
                    <div class="form-group mb-3">
                        <label for="{{ $key }}">{{ $data['title'] }}</label>
                        <select name="{{ $key }}" id="{{ $key }}" class="form-select"  @foreach($data['attr'] as $attr => $value) {{ $attr.'='.$value }} @endforeach>
                            @if(isset($data['first_option'])) <option value="" selected disabled>{{ $data['first_option'] }}</option> @endif
                            @foreach($data['options'] as $value => $title)
                            <option value="{{ $value }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @elseif($data['type'] == "textarea")
                    <div class="form-group mb-3">
                        <label for="{{ $key }}">{{ $data['title'] }}</label>
                        <textarea name="{{ $key }}" id="{{ $key }}" class="form-control" placeholder="{{ $data['title'] }}" @foreach($data['attr'] as $attr => $value) {{ $attr.'='.$value }} @endforeach></textarea>
                    </div>
                    @else
                    <div class="form-group mb-3">
                        <label for="{{ $key }}">{{ $data['title'] }}</label>
                        <input type="{{ $data['type'] }}" class="form-control" name="{{ $key }}" placeholder="{{ $data['title'] }}" @foreach($data['attr'] as $attr => $value) {{ $attr.'='.$value }} @endforeach>
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    {{ $cardTitle }}
                </h5>
                @if($dataAddUrl && $dataAddType == "new_page")
                <a href="{{ $dataAddUrl }}" class="btn btn-primary">+Tambah</a>
                @elseif($dataAddUrl && $dataAddType == "modal")
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-new-data">+Tambah</button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <x-session-alert/>
            <div class="table-responsive datatable-minimal">
                <table class="table" id="{{ isset($tableId) && $tableId ? $tableId : 'table' }}">
                </table>
            </div>
        </div>
    </div>
</section>
{{-- E:DataTable --}}

@push("custom-style")
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css" rel="stylesheet">
@endpush

@push('custom-script')
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js "></script>

<script>
    const table_id = "{{ isset($tableId) && $tableId ? $tableId : 'table' }}"

    let jquery_datatable = $("#"+table_id).DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        orderClasses: false,
        deferRender: true,
        ajax: {
            url: "{{ url($dataUrl) }}",
        },
        order: [[{{ isset($defaultOrder) ? $defaultOrder : 1 }}, 'asc']],
        columns: [
            {
                data: "DT_RowIndex",
                title: "No",
                orderable: false,
                searchable: false
            },
            @foreach($tableColumns as $column => $value)
                @if($column === "action")
                {
                    data: "{{ $column }}",
                    title: "Aksi",
                    orderable: false,
                    searchable: false
                },
                @else
                {
                    data: "{{ $column }}",
                    title: "{{ $value }}"
                },
                @endif
            @endforeach
        ],
    });
</script>

@if(isset($deleteOption))
<Script>
    $('body').on('click', '.delete-data', function() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: `Ya`,
            confirmButtonColor: '#dc3545',
        }).then(result => {
            if (result.isConfirmed) {
                let id = $(this).attr('data-id');
                let urlDelete = "{{ url($deleteOption) }}"
                urlDelete = urlDelete.replace('deleted_id', id);
                console.log(urlDelete)

                $.ajax({
                    type: "POST",
                    url: urlDelete,
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        Swal.fire({
                            title: `${response.message}`,
                            icon: 'success',
                        });
                        $('#'+table_id).DataTable().ajax.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Data batal dihapus!',
                    icon: 'info',
                });
            }
        })
    });
</Script>
@endif

@endpush