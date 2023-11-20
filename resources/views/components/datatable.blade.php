{{-- S:Datatable --}}
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    {{ $cardTitle }}
                </h5>
                @if(isset($dataAddUrl) && $dataAddUrl)
                <a href="{{ $dataAddUrl }}" class="btn btn-primary">+Tambah</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if(isset($usingAlert) && $usingAlert)
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @elseif (session('danger'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        {{ session('danger') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
            @endif
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
                    type: "DELETE",
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