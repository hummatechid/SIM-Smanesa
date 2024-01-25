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

    @php
        $data_column = ["name" => "Pelanggaran", "score" => "Poin", "action" => "Aksi"];
        $data_settings = [
            "name" => [
                "title" => "Pelanggaran",
                "type" => "textarea",
                "attr" => ["required" => "required"]
            ], "score" => [
                "title" => "Poin",
                "type" => "number",
                "attr" => ["required" => "required", "min"=>1]
            ]
        ];
    @endphp
    @hasrole('superadmin')
    <x-datatable
        card-title="Tabel Jenis Pelanggaran"
        data-url="{{ route('violation-type.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="violation-type/soft-delete/deleted_id"
        data-add-url="{{ route('violation-type.store') }}"
        data-add-type="modal"
        :data-add-settings="$data_settings"
        default-order="2"
    />
    @else
    <x-datatable
        card-title="Tabel Jenis Pelanggaran"
        data-url="{{ route('violation-type.get-main-datatables') }}"
        :table-columns="$data_column"
    />
    @endhasrole

</div>

<div class="modal modal-lg fade" id="modal-violation-detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h4 class="m-0">Detail Jenis Pelanggaran</h4>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-2 mb-2">
                    <div class="form-group">
                        <label for="year">Tahun :</label>
                        <select name="year" id="year" class="form-select choices">
                            @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month">Bulan :</label>
                        <select name="month" id="month" class="form-select choices">
                            <option value="" selected>-- Pilih Bulan --</option>
                            @foreach($months as $month_num => $month)
                            <option value="{{ $month_num }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="violation-charts"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-data" tabindex="-1">
    <div class="modal-dialog">
        <form action="" class="modal-content" id="form-edit" method="POST">
            @csrf
            @method('put')
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Pelanggaran <span class="text-danger">*</span></label>
                    <textarea name="name" id="name" rows="5" class="form-control" placeholder="Pelanggaran" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="score" class="form-label">Poin <span class="text-danger">*</span></label>
                    <input type="number" name="score" id="score" min="1" max="999" class="form-control" placeholder="Pelanggaran" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ubah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('custom-script')
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script>
    var violation_id;
    var options = {
        chart: {
            height: 250,
            type: 'area'
        },
        stroke: {
            curve: 'smooth'
        },
        series: [],
        xaxis: {
            categories: []
        },
        noData: {
            text: 'menunggu...'
        },
        dataLabels: {
            enabled: false
        }
    }

    var chart = new ApexCharts(document.querySelector("#violation-charts"), options);
    chart.render();

    $(document).on('click', '.show-detail', function() {
        var violation = $(this).data('data')
        violation_id = violation.id
        updateDataDetail()
    });

    $('#year').on('change', updateDataDetail)
    $('#month').on('change', updateDataDetail)

    function updateDataDetail() {
        $.ajax({
            url: `{{ route('violation.stats') }}?violation_type_id=${violation_id}&year=${$('#year').val()}&month=${$('#month').val()}`,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: (rows) => {
                chart.updateOptions({
                    series: [{
                        name: 'total',
                        data: Object.values(rows.data)
                    }],
                    xaxis: {
                        categories: Object.keys(rows.data)
                    }
                })
            }
        })

    }

    $(document).on('click', '.edit-data', function() {
        var data = $(this).data('data')
        var base_edit_url = "{{ route('violation-type.update', 'violation_id') }}"
        var real_url = base_edit_url.replace('violation_id', data.id)

        $('#form-edit').attr('action', real_url)
        $('#form-edit #name').val(data.name)
        $('#form-edit #score').val(data.score)

        $('#form-edit').parsley().validate()
    })
</script>
@endpush