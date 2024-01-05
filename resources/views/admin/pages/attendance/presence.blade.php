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
        $data_column = ["student" => "Siswa", "present_at" => "Waktu Kehadiran", "status" => "Status", "action" => "Aksi"];
        if(auth()->user()->hasExactRoles('satpam')) $btn_add = '<div class="d-flex gap-3"><a href="'.route("scan.index").'" class="btn btn-primary">Scan Kehadiran</a></div>';
        else $btn_add = '<div class="d-flex gap-3"><a href="'.route("scan.index").'" class="btn btn-primary">Scan Kehadiran</a><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-permit">+ Tambah Izin</button></div>';
        $custom_group = [
                "date" => [
                    "title" => "Date",
                    "type" => "date",
                    "default" => date('Y-m-d')
                ]
            ]
    @endphp
    <x-datatable
        card-title="Tabel Data Kehadiran"
        data-url="{{ route('attendance.get-main-datatables') }}"
        :table-columns="$data_column"
        default-order="2"
        arrange-order="desc"
        data-add-type="custom-btn"
        :data-add-btn="$btn_add"
        :with-custom-groups="$custom_group"
    />

</div>

<div class="modal modal-lg fade" id="modal-add-permit">
    <div class="modal-dialog">
        <form action="{{ route('attendance.presence.create-permit') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h4 class="m-0">Tambah Siswa Izin</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="date" class="form-label">Tanggal Izin <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                    <select name="student_id" id="student_id" class="form-select choices" required>
                        <option value="" selected disabled>-- pilih siswa --</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }} | {{ $student->nisn }} | {{ $student->gender }} | {{ $student->nama_rombel }}</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-">
                    <label for="status" class="form-lael">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="" selected disabled>-- pilih status --</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="permit_file" class="form-label">File Izin <span class="text-danger">*</span></label>
                    <input type="file" id="permit_file" name="permit_file" class="add-image"/>
                    @error('permit_file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Tambah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
<div class="modal modal-lg fade" id="modal-edit-data">
    <div class="modal-dialog">
        <form id="form-edit" action="{{ route('attendance.update', 'updated_id') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h4 class="m-0">Ubah Kehadiran Siswa</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                    <input type="hidden" name="student_id" id="student_id">
                    <input type="text" id="student_name" class="form-control" readonly>
                    {{-- <select name="student_id" id="student_id" class="form-select choices" required>
                        <option value="" selected disabled>-- pilih siswa --</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }} | {{ $student->nisn }} | {{ $student->gender }} | {{ $student->nama_rombel }}</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror --}}
                </div>
                <div class="form-group mb-">
                    <label for="status" class="form-lael">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="hadir">Hadir</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                        <option value="alpa">Alpa</option>
                    </select>
                </div>
                <div class="form-group mb-3" id="edit-img-container">
                    <label for="permit_file" class="form-label">File Izin <span class="text-danger">*</span></label>
                    <input type="file" id="permit_file" name="permit_file" class="edit-image"/>
                    @error('permit_file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Tambah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('custom-script')
<script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>
<script src="{{ asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/filepond.js') }}"></script>
<script>
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
    )
    // Filepond: Image Preview
    FilePond.create(document.querySelector(".add-image"), {
        credits: null,
        allowImagePreview: true,
        allowImageFilter: false,
        allowImageExifOrientation: false,
        allowImageCrop: false,
        acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
        fileValidateTypeDetectType: (source, type) =>
            new Promise((resolve, reject) => {
            // Do custom type detection here and return with promise
            resolve(type)
            }),
        storeAsFile: true,
    })
    FilePond.create(document.querySelector(".edit-image"), {
        credits: null,
        allowImagePreview: true,
        allowImageFilter: false,
        allowImageExifOrientation: false,
        allowImageCrop: false,
        acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
        fileValidateTypeDetectType: (source, type) =>
            new Promise((resolve, reject) => {
            // Do custom type detection here and return with promise
            resolve(type)
            }),
        storeAsFile: true,
    })
</script>
<script>
    const choices = document.querySelectorAll('.choices');
    
    for (let i = 0; i < choices.length; i++) {
        new Choices(choices[i]);
    }
</script>
<script>
    $(document).on('click', '.btn-change', function() {
        const item = $(this).data('data');
        const student = $(this).data('student');
        let action = "{{ route('attendance.update', 'updated_id') }}";
        action = action.replace('updated_id', item.id)
        
        let date = new Date(item.present_at)
        let formated_date = date.toISOString().split('T')[0]
        let img = "{{ asset(Storage::url('')) }}";
        img = img+item['permit_file'];

        $('#form-edit').attr('action', action)
        $('#form-edit #student_id').val(student['id'])
        $('#form-edit #student_name').val(student['full_name'])
        $('#form-edit #date').val(formated_date)
        $('#form-edit #status option').each((index, el) => {
            if(item.status == el.value) el.setAttribute('selected', true)
            else el.removeAttribute('selected')
        })
        if($('#form-edit #status').val() == 'izin' || $('#form-edit #status').val() == 'sakit') $('#edit-img-container').show()
        else $('#edit-img-container').hide()
    })
    $(document).on('change input', '#form-edit #status', function(){
        if($('#form-edit #status').val() == 'izin' || $('#form-edit #status').val() == 'sakit') $('#edit-img-container').show()
        else $('#edit-img-container').hide()
    })
</script>
@endpush
@push('custom-style')
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
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
@endpush