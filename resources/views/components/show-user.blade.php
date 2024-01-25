<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h4 class="card-body text-center m-0 p-4">
                    {{ $cardTitle }}
                </h4>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-4 d-flex justify-content-center">
                        <img src="{{ $dataUser->photo ? asset(Storage::url($dataUser->photo)) : asset('assets/compiled/jpg/0.webp') }}" alt="foto pengguna" class="rounded-4 mb-2" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                    <div class="mb-3 text-center">
                        <div class="lead"><span class="text-primary" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Tersinkronisasi dengan dapodik" data-bs-placement="top"><i class="bi bi-patch-check-fill"></i></span> {{ $dataUser->full_name }}</div>
                        <div class="fw-bold">{{ $dataUser->user->email }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-2">
                        <div class="d-flex justify-content-center  gap-2">
                            <a href="{{ $backUrl }}" class="btn btn-secondary">Kembali</a>
                            @if(auth()->user()->hasRole('superadmin') || $dataUser->user->id === auth()->id())
                            <a href="{{ $editUrl }}" class="btn btn-primary">Ubah</a>
                            @endif
                        </div>
                        @if(auth()->user()->hasRole('superadmin') || $dataUser->user->id === auth()->id())
                        <a href="{{ $editPasswordUrl }}" class="btn btn-primary">Ubah Password</a>
                        @endif
                        @if(auth()->user()->hasRole('superadmin') && $formFor == 'guru' && !$dataUser->user->hasRole('pimpinan'))
                        <form action="{{ route('teacher.assign-role', $dataUser->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary">Jadikan Waka</button>
                        </form>
                        @elseif(auth()->user()->hasRole('superadmin') && $formFor == 'guru' && $dataUser->user->hasRole('pimpinan'))
                        <form action="{{ route('teacher.remove-role', $dataUser->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary">Hapus Waka</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-3">
            <div class="card h-100">
                @if($dataUser->bio)
                <div class="card-header bg-primary text-light">
                    <div class="card-title">Bio :</div>
                    <p class="fw-lighter lh-sm m-0">
                        {{ $dataUser->bio }}
                    </p>
                </div>
                @endif
                <div class="card-body">
                    <x-session-alert :is-swal="true" />
                    <table class="table mb-0 mt-3">
                        @if($formFor != 'guru')
                        <tr>
                            <th>Role</th>
                            <td>{{ $dataUser->user->roles[0]->name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>NIK</th>
                            <td>{{ $dataUser->nik }}</td>
                        </tr>
                        @if($formFor == 'guru')
                        <tr>
                            <th>NIP</th>
                            <td>{{ $dataUser->nip }}</td>
                        </tr>
                        <tr>
                            <th>NUPTK</th>
                            <td>{{ $dataUser->nuptk }}</td>
                        </tr>
                        <tr>
                            <th>Jenis PTK</th>
                            <td>{{ $dataUser->jenis_ptk }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $dataUser->gender }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $dataUser->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $dataUser->address }}</td>
                        </tr>
                        <tr>
                            <th>Agama</th>
                            <td>{{ $dataUser->religion }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
        let form_id = '{{ isset($formId) && $formId ? $formId : "form" }}'
        $('#'+form_id).parsley()
    </script>
@endpush