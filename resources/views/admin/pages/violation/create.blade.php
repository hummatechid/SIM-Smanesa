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

    <section class="section">
        <form class="card" id="form" action="{{ route('violation.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        Tambah Data Pelanggaran
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <x-session-alert :is-swal="true"/>
                <div class="form-group mb-3">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <label>Siswa <span class="text-danger">*</span></label>
                    </div>
                    <div id="siswa_group" class="choices-group d-flex flex-column gap-2 mb-2">
                        <div class="siswa-input choices-multiple-input w-100" data-index="0">
                            <select id="student_id" class="form-select choices" name="student_id[]">
                                <option value="" selected>-- pilih siswa --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{$user->full_name}} | {{ $user->nisn }} | {{ $user->gender }} | {{ $user->nama_rombel }}</option>
                                @endforeach
                            </select>
                            <button type="button" data-index="0" class="btn-del-siswa del-choices text-danger">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="bg-transparent border-0 d-flex align-items-center gap-2" id="btn_add_siswa">
                        <div class="bg-success rounded-circle text-white fw-bold" style="padding: 0px 7px">+</div>
                        <div class="text-muted">tambah siswa</div>
                    </button>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="violation_type_id">Pelanggaran <span class="text-danger">*</span></label>
                    <div id="violation_group" class="choices-group d-flex flex-column gap-2 mb-2">
                        <div class="violation_input choices-multiple-input" data-index="0">
                            <select id="violation_type_id" class="form-select choices" name="violation_type_id[]">
                                <option value="" selected>-- pilih pelanggaran --</option>
                                @foreach($violation_types as $violation)
                                <option value="{{ $violation->id }}">{{ $violation->name }} ({{ $violation->score }} poin)</option>
                                @endforeach
                            </select>
                            <button type="button" data-index="0" class="btn-del-violation del-choices text-danger">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="bg-transparent border-0 d-flex align-items-center gap-2" id="btn_add_violation">
                        <div class="bg-success rounded-circle text-white fw-bold" style="padding: 0px 7px">+</div>
                        <div class="text-muted">tambah pelanggaran</div>
                    </button>
                    @error('violation_type_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 text-danger text-end">* tidak boleh kosong</div>
            </div>
            <div class="card-footer d-flex justify-content-between gap-2">
                <a href="{{ url('violation') }}" class="btn btn-secondary">&#10094; Kembali</a>
                <button type="submit" class="btn btn-success">Tambah</button>
            </div>
        </form>
    </section>
    
    @push('custom-style')
        <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}" />
        <style>
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
        </style>
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
        <script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
        <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.js') }}"></script>
        <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.extra.js') }}"></script>
        <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
        <script>
            function setToChoices(class_name) {
                const choices = document.querySelectorAll(class_name);
    
                for (let i = 0; i < choices.length; i++) {
                    new Choices(choices[i]);
                }
            }
            setToChoices(".choices")
        </script>
        
        <script>
            $('#form').parsley()
            $(document).on('input change', 'input', (e) => {
                let id = e.target.getAttribute('id')
                if(id) $('#'+id).parsley().validate()
            })
        </script>

        <script>

            const siswas = @json($users);
            const violations = @json($violation_types)

            var siswa_lists = `<option value="" disabled selected>-- pilih siswa --</option>`;
            siswas.forEach((siswa) => {
                siswa_lists += `<option value="${siswa.id}">${siswa.full_name} | ${siswa.nisn} | ${siswa.gender} | ${siswa.nama_rombel}</option>`;
            })

            var violation_lists = `<option value="" selected>-- pilih pelanggaran --</option>`;
            violations.forEach((violation) => {
                violation_lists += `<option value="${ violation.id }">${ violation.name } (${ violation.score } poin)</option>`
            })

            $(function() {
                $('#btn_add_siswa').on('click', function() {
                    var last_index = parseInt($('#siswa_group').children().last().attr('data-index'))
                    var new_index = last_index+1
                    
                    let new_select = `<select class="form-select choices-${new_index}" name="student_id[]">${siswa_lists}</select>`
                    let new_siswa_input = `<div class="siswa-input choices-multiple-input w-100" data-index="${new_index}">
                        ${new_select}
                        <button type="button" data-index="${new_index}" class="del-choices btn-del-siswa text-danger">&times;</button>
                        </div>`
                            
                    $('#siswa_group').append(new_siswa_input)
                    setToChoices('select.choices-'+new_index)
                })

                $('#btn_add_violation').on('click', function() {
                    var last_index = parseInt($('#violation_group').children().last().attr('data-index'))
                    var new_index = last_index+1
                    
                    let new_select = `<select class="form-select violation-choices-${new_index}" name="violation_type_id[]">${violation_lists}</select>`
                    let new_siswa_input = `<div class="violation_input choices-multiple-input" data-index="${new_index}">
                        ${new_select}
                        <button type="button" data-index="${new_index}" class="del-choices btn-del-violation text-danger">&times;</button>
                        </div>`
                            
                    $('#violation_group').append(new_siswa_input)
                    setToChoices('select.violation-choices-'+new_index)
                })
                
                $(document).on('click', '.btn-del-siswa', function() {
                    var data_index = $(this).attr('data-index')

                    $(`.siswa-input[data-index=${data_index}]`).remove()
                })

                $(document).on('click', '.btn-del-violation', function() {
                    var data_index = $(this).attr('data-index')

                    $(`.violation_input[data-index=${data_index}]`).remove()
                })
            })
        </script>
    @endpush

</div>
@endsection