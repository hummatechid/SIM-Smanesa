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
        $data_column = ["student" => "Siswa", "status" => "Status", "action" => "Aksi"];
    @endphp
    <x-datatable
        card-title="Tabel Data Izin / Sakit"
        data-url="{{ route('attendance.get-permit-datatables') }}"
        :table-columns="$data_column"
        default-order="2"
        arrange-order="desc"
        delete-option="attendance/presence/deleted_id?status=absent"
        data-add-type="custom-btn"
        data-add-btn='<div class="d-flex gap-3"><a href="{{ route("scan.index") }}" class="btn btn-primary">Scan Kehadiran</a><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-permit">+ Tambah izin</button></div>'
    />

</div>

<div class="modal modal-lg fade" id="modal-add-permit">
    <div class="modal-dialog">
        <form action="{{ route('attendance.presence.create-permit') }}" class="modal-content" method="POST">
            @csrf
            <div class="modal-header">
                <h4 class="m-0">Tambah Siswa Izin</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
                <div class="form-group mb-3">
                    <label for="permit_file" class="form-label">File Izin <span class="text-danger">*</span></label>
                    <input type="file" id="permit_file" name="permit_file"/>
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
    FilePond.create(document.getElementById("permit_file"), {
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