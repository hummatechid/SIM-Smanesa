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
    <form action="#" method="post" class="card" id="form">
        <div class="card-header">
            <h4>Cetak Laporan</h4>
        </div>
        <div class="card-body">
            @csrf
            <div class="row">
                <div class="form-group mb-3 col-md-6">
                    <label for="type">Tipe Laporan</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="monthly">Bulanan</option>
                        <option value="yearly">Tahunan</option>
                        <option value="custom">Kustom Tanggal</option>
                    </select>
                </div>
                <div class="form-group mb-3 col-md" id="type_monthly">
                    <label for="month">Bulan</label>
                    <select name="month" id="month" class="form-select input-data" required>
                        @foreach($months as $month_id => $month)
                        <option value="{{ $month_id }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3 col-md" id="type_yearly">
                    <label for="year">Tahun</label>
                    <select name="year" id="year" class="form-select input-data" required>
                        @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3 col-md-6" id="type_custom_date" style="display: none;">
                    <label for="date">Tanggal</label>
                    <input type="text" name="date" id="date" class="form-control input-data">
                </div>
            </div>
            <div class="row">
                <div class="form-group mb-3 col-md">
                    <label for="data">Data yang Dicetak</label>
                    <select name="data" id="data" class="form-select" required>
                        <option value="all">Semua</option>
                        <option value="per_class">Per Kelas</option>
                        <option value="per_grade">Per Angkatan</option>
                    </select>
                </div>
                <div class="form-group mb-3 col-md-6" id="data_grade" style="display: none;">
                    <label for="grade">Angkatan</label>
                    <select name="grade" id="grade" class="form-select input-data">
                        @foreach($grades as $grade)
                        <option value="{{ $grade }}">{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3 col-md-6" id="data_class" style="display: none;">
                    <label for="class">Kelas</label>
                    <select name="class" id="class" class="form-select input-data">
                        @foreach($classes as $kls)
                        <option value="{{ $kls->nama_rombel }}">{{ $kls->nama_rombel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>
    
    @php
        // $data_column = ["name" => "Siswa", "violation" => "Pelanggaran", "score" => "Poin Pelanggaran", "date" => "Tanggal"];
        $data_column = [
            "name" => [
                "title" => "Siswa",
                "width" => "300px"
            ],
            "class" => "Kelas",
            "violation_score" => [
                "title" => "Poin Pelanggaran",
                "width" => "150px"
            ],
            "violation_total" => [
                "title" => "Jumlah Pelanggaran",
                "width" => "170px"
            ],
            "action" => "Aksi"
        ];
    @endphp
    <x-datatable
        card-title="Tabel Data Pelanggaran"
        data-url="{{ route('violation.get-report-datatables') }}"
        :table-columns="$data_column"
        default-order="1"
        arrange-order="asc"
        :custom-export-button="['csv', 'excel', 'pdf', 'print']"
        custom-export-title="Laporan Pelanggaran"
        :server-side="false"
        :info-table="false"
        :pagging-table="false"
        :is-report="true"
        :orderable="false"
        custom-export-settings="exportOptions: {columns: 'th:not(:last-child)'}"
    />
</div>

<div class="modal fade modal-lg" id="violation-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Data Pelanggaran <span id="student_name"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $detail_columns = [
                        'date' => "Tanggal",
                        'violation' => [
                            "title" => "Pelanggaran"
                        ], 'score' => 'Poin'
                    ];
                @endphp
                <x-datatable
                    table-id="violation_table"
                    card-title=""
                    data-url="{{ route('violation.student', 'student_id=student_id') }}"
                    :table-columns="$detail_columns"
                    default-order="1"
                    arrange-order="desc"
                />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-script')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/i18n/id.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/i18n/id.extra.js') }}"></script>
<script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/extensions/flatpickr/l10n/id.js') }}"></script>
<script>
    const choices = document.querySelectorAll('.choices');
    
    for (let i = 0; i < choices.length; i++) {
        new Choices(choices[i]);
    }
</script>
<script>
    $(window).on('load', () => {
        $(document).on('input change', '#type', () => {
            if($('#type').val() == 'yearly') {
                $('#type_yearly').show()
                $('#year').attr('required', 'required')
                $('#type_monthly').hide()
                $('#month').removeAttr('required')
                $('#type_custom_date').hide()
                $('#date').removeAttr('required')
            } else if($('#type').val() == 'monthly') {
                $('#type_yearly').show()
                $('#year').attr('required', 'required')
                $('#type_monthly').show()
                $('#month').attr('required', 'required')
                $('#type_custom_date').hide()
                $('#date').removeAttr('required')
            } else {
                $('#type_yearly').hide()
                $('#year').removeAttr('required')
                $('#type_monthly').hide()
                $('#month').removeAttr('required')
                $('#type_custom_date').show()
                $('#date').attr('required', 'required')
            }

            reloadNewUrl()
        })
        $(document).on('input change', '#data', () => {
            if($('#data').val() == 'per_class') {
                $('#data_class').show()
                $('#class').attr('required', 'required')
                $('#data_grade').hide()
                $('#grade').removeAttr('required')
            } else if($("#data").val() == 'per_grade') {
                $('#data_grade').show()
                $('#grade').attr('required', 'required')
                $('#data_class').hide()
                $('#class').removeAttr('required')
            } else {
                $('#data_grade').hide()
                $('#grade').removeAttr('required')
                $('#data_class').hide()
                $('#class').removeAttr('required')
            }

            reloadNewUrl()
        })
        $(document).on('input change', '.input-data', function() {
            reloadNewUrl()
        })
        reloadNewUrl()
        function reloadNewUrl()
        {
            let main_url = "{{ route('violation.get-report-datatables') }}"

            let params = "?type=" + $('#type').val()
            params += "&date=" + $('#date').val()
            params += "&month=" + $('#month').val()
            params += "&year=" + $('#year').val()
            params += "&data=" + $('#data').val()
            params += "&class=" + $('#class').val()
            params += "&grade=" + $('#grade').val()

            table.ajax.url(main_url+params).load()
        }
        $('#date').flatpickr({
            mode: 'range',
            maxDate: 'today',
            locale: 'id',
            defaultDate: 'today',
            onChange: function(selectedDates, dateStr, instance) {
                instance.element.value = dateStr.replace(' - ', ' s/d ')
                reloadNewUrl();
            }
        })


        $(document).on('click', '.btn-detail', function() {
            let data = $(this).data('data')
            let student_id = data.student_id
            let student_name = data.student.full_name

            let url = "{{ route('violation.student', 'student_id=siswa_id') }}"
            url = url.replace('siswa_id', student_id)

            console.log(url)

            $('#student_name').html(student_name)
            violation_table.ajax.url(url).load()
        })
    })
</script>
@endpush
@push('custom-style')
<link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/themes/dark.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/themes/light.css') }}"> --}}
<style>
    .modal-body .card-header {
        display: none;
    }
    .modal-body .card {
        margin-bottom: 0!important;
    }
    .choices__inner {
        padding: 3.5px 7.5px 3.75px;
        background: white;
        min-height: 22px;
    }

    .choices[data-type*=select-one] .choices__inner {
        padding-bottom: 2.5px;
    }
    .choices-multiple-input {
        position: relative
    }
    .choices-group .choices::after {
        display: none;
    }
    .choices {
        margin: 0
    }
    .del-choices {
        background-color: transparent;
        border: 0;
        position: absolute;
        top: 50%;
        right: 0px;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 1.5rem;
    }
    .choices-group .choices-multiple-input:only-child .del-choices {
        display: none
    }
    .parsley-errors-list {
        color: var(--bs-danger)
    }
    .parsley-error {
        border-color: var(--bs-danger)!important
    }
</style>
@endpush
