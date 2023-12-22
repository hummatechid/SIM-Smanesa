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
                    <div class="form-group mb-3">
                        <label for="{{ $key }}">{{ $data['title'] }} @if(in_array('required', $data['attr'])) <span class="text-danger">*</span>@endif</label>
                        @if($data['type'] == "select")
                        <select name="{{ $key }}" id="{{ $key }}" class="form-select"  @foreach($data['attr'] as $attr => $value) {{ $attr.'='.$value }} @endforeach>
                            @if(isset($data['first_option'])) <option value="" selected disabled>{{ $data['first_option'] }}</option> @endif
                            @foreach($data['options'] as $value => $title)
                            <option value="{{ $value }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        @elseif($data['type'] == "textarea")
                        <textarea name="{{ $key }}" id="{{ $key }}" class="form-control" placeholder="{{ $data['title'] }}" @foreach($data['attr'] as $attr => $value) {{ $attr.'='.$value }} @endforeach></textarea>
                        @else
                        <input type="{{ $data['type'] }}" class="form-control" name="{{ $key }}" placeholder="{{ $data['title'] }}" @foreach($data['attr'] as $attr => $value) {{ $attr.'='.$value }} @endforeach>
                        @endif
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                @elseif($dataAddType == "custom-btn")
                {!! $dataAddBtn !!}
                @elseif($dataAddUrl && $dataAddType == "modal")
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-new-data">+Tambah</button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <x-session-alert/>
            <div class="d-flex justify-content-start gap-3">
                @foreach ($withCustomGroups as $name => $props)
                <div class="form-group gap-1 d-flex justify-content-center align-items-center mb-3">
                    <div class="d-flex align-items-center h-100">
                        <label for="group_{{ $name }}">{{ $props['title'] }}:</label>
                    </div>
                    <select name="group_{{ $name }}" id="group_{{ $name }}" class="form-select">
                    @foreach ($props['options'] as $value => $title)
                        <option value="{{ $value }}">{{ $title }}</option>
                    @endforeach
                    </select>
                </div>
                @endforeach
            </div>
            {!! $multipleSelectAll !!}
            @if(isset($deleteOption))
            <div id="alert-delete"></div>
            @endif
            <div class="table-responsive datatable-minimal">
                <table class="table w-100" id="{{ $tableId }}">
                </table>
            </div>
        </div>
    </div>
</section>
{{-- E:DataTable --}}

@push("custom-style")
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <style>
        .parsley-errors-list {
            color: var(--bs-danger)
        }
        .parsley-error {
            border-color: var(--bs-danger)!important
        }
    </style>
@endpush

@push('custom-script')
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/datatables.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js "></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/i18n/id.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/i18n/id.extra.js') }}"></script>
<script>
    $('form').parsley()
    
    $(document).on('input change', 'input', (e) => {
        let id = e.target.getAttribute('id')
        if(id) $('#'+id).parsley().validate()
    })
</script>

<script>
    var customGroups = {}
    @foreach($withCustomGroups as $name => $props)
    customGroups["{{ $name }}"] = $('#group_{{ $name }}').val() ? $('#group_{{ $name }}').val() : null;
    @endforeach

    var params = "";
    Object.keys(customGroups).forEach((key) => {
        params += (`${key}=${customGroups[key]},`)
    })
    

    let {{ $tableId }} = $('#{{ $tableId }}').DataTable({
        processing: true,
        serverSide: "{{ $serverSide }}",
        paging: "{{ $paggingTable }}",
        searching: "{{ $searchableTable }}",
        orderClasses: false,
        deferRender: true,
        ajax: {
            url: "{{ url($dataUrl) }}?"+params,
            data: {
                _token: "{{ csrf_token() }}",
            }
        },
        order: [[{{ isset($defaultOrder) ? $defaultOrder : 1 }}, '{{ $arrangeOrder }}']],
        columns: [
            @if(!$withMultipleSelect)
            {
                data: "DT_RowIndex",
                title: "No",
                orderable: false,
                searchable: false
            },
            @endif
            @foreach($tableColumns as $column => $value)
                @if($column === "action" || $column === "selection")
                {
                    data: "{{ $column }}",
                    title: "{{ $value }}",
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

    @foreach ($withCustomGroups as $name => $props)
    $('#group_{{ $name }}').on('input change', () => {
        customGroups['{{ $name }}'] = $('#group_{{ $name }}').val()
        params = "";
        Object.keys(customGroups).forEach((key) => {
            params += (`${key}=${customGroups[key]},`)
        })

        {{ $tableId }}.ajax.reload()
    })
    @endforeach

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
                        let alert_msg = `<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"aria-label="Close"></button>
                        </div>`
                        $('#alert-delete').append(alert_msg)
                        window.setTimeout(function() {
                            $(".alert").fadeTo(1000, 0).slideUp(300, function(){
                                $(this).slideUp(300); 
                            });
                        }, 5000);
                        $('#{{ $tableId }}').DataTable().ajax.reload();
                    }
                });
            }
        })
    });
</Script>
@endif

@endpush