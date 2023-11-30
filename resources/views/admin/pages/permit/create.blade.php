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
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <label>Siswa</label>
                        <button type="button" class="btn btn-sm btn-secondary" id="btn_add_siswa">+ tambah</button>
                    </div>
                    <div id="siswa_group" class="d-flex flex-column gap-2">
                        <div class="siswa-input d-flex gap-2 w-100" data-index="0">
                            <div class="w-100">
                                <select id="student_id" class="form-select choices" name="student_id[]">
                                    <option value="" selected>-- pilih siswa --</option>
                                    @foreach($students as $user)
                                    <option value="{{ $user->id }}">{{$user->full_name}} ({{ $user->nisn }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger btn-del-siswa" data-index="0">Hapus</button>
                        </div>
                    </div>
                    @error('user_id')
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
            function setToChoices(class_name) {
                let choices = document.querySelectorAll(class_name);
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
            }
            setToChoices('.choices')
        </script>
        
        <script>
            $('#form').parsley()
        </script>
        
        <script>

            const siswas = @json($students);
            var siswa_lists = `<option value="" disabled selected>-- pilih siswa --</option>`;
            siswas.forEach((siswa) => {
                siswa_lists += `<option value="${siswa.id}">${siswa.full_name} (${siswa.nisn})</option>`;
            })

            $(function() {
                $('#btn_add_siswa').on('click', function() {
                    if($('#siswa_group').children().length == 0) var last_index = -1;
                    else var last_index = parseInt($('#siswa_group').children().last().attr('data-index'))

                    let new_siswa_input = `
                        <div class="siswa-input d-flex gap-2 w-100" data-index="${last_index+1}">
                            <div class="w-100">
                                <select id="student_id" class="form-select choices${last_index+1}" name="student_id[]">${siswa_lists}</select>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger btn-del-siswa" data-index="${last_index+1}">Hapus</button>
                        </div>`

                    $('#siswa_group').append(new_siswa_input)
                    setToChoices(`.choices${last_index+1}`)
                })
                
                $(document).on('click', '.btn-del-siswa', function() {
                    var data_index = $(this).attr('data-index')

                    $(`.siswa-input[data-index=${data_index}]`).remove()
                })
            })
        </script>
    @endpush

</div>
@endsection