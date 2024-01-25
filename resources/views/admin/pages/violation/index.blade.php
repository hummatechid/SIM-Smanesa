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
        if(auth()->user()->hasRole('superadmin')) {
            $data_column = ["name" => "Pelanggar", "violation" => "Pelanggaran", "score" => "Poin", "date" => 'Tanggal', "user_created" => "Dibuat Oleh", "user_updated" => "Diubah Oleh" , 'action' => 'Aksi'];
        } else {
            $data_column = ["name" => "Pelanggar", "violation" => "Pelanggaran", "score" => "Poin", "date" => 'Tanggal', "user_created" => "Dibuat Oleh", "user_updated" => "Diubah Oleh" ];
        }
    @endphp
    <x-datatable
        card-title="Tabel Data Pelanggaran"
        data-url="{{ route('violation.get-main-datatables') }}"
        :table-columns="$data_column"
        default-order="4"
        arrange-order="desc"
        data-add-url="{{ url('violation/create') }}"
        delete-option="{{ route('violation.soft-destroy', 'deleted_id') }}"
    />

    <div class="modal modal-lg fade" id="modal-edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-edit" method="post" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Data Pelanggaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="student_id" class="form-label">Siswa</label>
                        <select name="student_id" id="student_id" class="choices">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{$user->full_name}} | {{ $user->nisn }} | {{ $user->gender }} | {{ $user->nama_rombel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="violation_type_id" class="form-label">Siswa</label>
                        <select name="violation_type_id" id="violation_type_id" class="choices">
                            @foreach($violation_types as $violation)
                                <option value="{{ $violation->id }}">{{ $violation->name }} ({{ $violation->score }} poin)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ubah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

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
    </style>
@endpush
@push('custom-script')
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        const student_sel = document.getElementById('student_id');
        const violation_type_sel = document.getElementById('violation_type_id');
        
        const choices_student = new Choices(student_sel);
        const choices_vioation_type = new Choices(violation_type_sel);
        

        $(document).on('click', '.btn-edit', function() {
            let data = $(this).data('data')

            let url = "{{ route('violation.update', 'updated_id') }}";
            url = url.replace('updated_id', data.id)
            $('form#form-edit').attr('action', url)

            $('#student_id').each((index, el) => {
                choices_student.setChoiceByValue(data.student_id);
            })
            $('#violation_type_id').each((index, el) => {
                choices_vioation_type.setChoiceByValue(data.violation_type_id)
            })
        })
    </script>
@endpush