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
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <form class="card" id="form" action="{{ route('permit.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        Tambah Data Izin
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <x-session-alert/>
                <div class="form-group mb-3">
                    <label for="student_id">Siswa</label>
                    <Select name="student_id" id="student_id" class="form-select choices @error('student_id') is-invalid @enderror" required>
                        <option value="" {{ !old('student_id') ? 'selected' : '' }}>-- pilih siswa --</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->full_name }}</option>
                        @endforeach
                    </Select>
                    @error('student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="reason">Alasan Izin</label>
                    <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" placeholder="Alasan izin" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('permit.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Tambah</button>
            </div>
        </form>
    </section>
    
    @push('custom-style')
        <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
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
            FilePond.create(document.getElementById("upload-image"), {
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
            $('#form').parsley()
        </script>
    @endpush

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
        <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
        <script>
            let choices = document.querySelectorAll(".choices");
            let initChoice;

            for (let i = 0; i < choices.length; i++) {
                if (choices[i].classList.contains("multiple-remove")) {
                    initChoice = new Choices(choices[i], {
                        delimiter: ",",
                        editItems: true,
                        maxItemCount: -1,
                        removeItemButton: true,
                    });
                } else {
                    initChoice = new Choices(choices[i]);
                }
            }
        </script>
        
        <script>
            $('#form').parsley()
        </script>
    @endpush

</div>
@endsection