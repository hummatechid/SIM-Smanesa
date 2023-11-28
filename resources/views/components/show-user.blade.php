<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    {{ $cardTitle }}
                </h5>
            </div>
        </div>
        <div class="card-body">
            <x-session-alert/>
            <div class="form-group mb-3">
                <img src="{{ $dataUser->photo ? asset($dataUser->photo) : asset('assets/compiled/jpg/0.webp') }}" alt="foto pengguna" class="rounded-4 mb-2" style="width: 200px; height: 200px; object-fit: cover;">
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <div class="form-control">{{ $dataUser->user->email }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="role_id">Role</label>
                <div class="form-control">{{ $dataUser->user->roles[0]->name }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="nik">NIK</label>
                <div class="form-control">{{ $dataUser->nik }}</div>
            </div>
            @if(isset($formFor) && $formFor == 'guru')
            <div class="form-group mb-3">
                <label for="nip">NIP</label>
                <div class="form-control">{{ $dataUser->nip }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="nuptk">NUPTK</label>
                <div class="form-control">{{ $dataUser->nuptk }}</div>
            </div>
            <div class="form-group mb-">
                <label for="jenis_ptk">Jenis PTK</label>
                <div class="form-control">{{ $dataUser->jenis_ptk }}</div>
            </div>
            @endif
            <div class="form-group mb-3">
                <label for="full_name">Nama Lengkap</label>
                <div class="form-control">{{ $dataUser->full_name }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="gender">Jenis Kelamin</label>
                <div class="form-control">{{ $dataUser->gender }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="phone_number">Nomor Telepon</label>
                <div class="form-control">{{ $dataUser->phone_number }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="address">Alamat</label>
                <textarea class="form-control" readonly>{{ $dataUser->address }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="religion">Agama</label>
                <div class="form-control">{{ $dataUser->religion }}</div>
            </div>
            <div class="form-group mb-3">
                <label for="bio">Bio</label>
                <textarea class="form-control">{{ $dataUser->bio }}</textarea>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ $editUrl }}" class="btn btn-primary">Ubah</a>
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