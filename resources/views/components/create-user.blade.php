<section class="section">
    <form class="card" id="{{ isset($formId) && $formId ? $formId : 'form' }}" action="{{ url($formAction) }}" method="{{ $formMethod != 'GET' ? 'POST' : 'GET' }}" enctype="multipart/form-data">
        @if($isEdit) @method('put') @endif
        @csrf
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
                <label for="photo">Foto</label>
                <div class="d-flex mt-3 gap-3 justify-content-stretch">
                    @if($isEdit)
                    <img src="{{ $dataUser->photo ? asset(Storage::url($dataUser->photo)) : asset('assets/compiled/jpg/0.webp') }}" alt="foto pengguna" class="rounded-4 mb-2" style="width: 200px; height: 200px; object-fit: cover;">
                    @endif
                    <div class="w-100 m-0 p-0" @if($isEdit) style="height: 200px; overflow-y: auto;" @endif>
                        <input type="file" id="upload-image" class="my-pond" name="photo"/>
                        @error('photo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email', ($dataUser ? $dataUser->user->email : '')) }}" required @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" placeholder="Nama Lengkap" value="{{ old('full_name', ($dataUser ? $dataUser->full_name : '')) }}" required @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if(!$isEdit)
                    <div class="row">
                        <div class="col-md-3">
                            <label for="password">Password <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9 position-relative">
                            <input type="password" name="password" id="password" pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{3,}$" minlength="8" data-parsley-pattern-message="password harus mengandung huruf besar, huruf kecil, dan angka" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required/>
                            <button type="button" class="btn-password">
                                <i class="bi bi-eye-fill text-muted"></i>
                            </button>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9 position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{3,}$" minlength="8" data-parsley-pattern-message="password harus mengandung huruf besar, huruf kecil, dan angka" data-parsley-equalto="#password" data-parsley-equalto-message="nilai tidak sama dengan password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Konfirmasi Password" required/>
                            <button type="button" class="btn-password">
                                <i class="bi bi-eye-fill text-muted"></i>
                            </button>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if($formFor != 'guru')
                    <div class="row">
                        <div class="col-md-3">
                            <label for="role_id">Role <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <Select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                @if(!$isEdit)
                                <option value="" {{ !old('role_id') ? 'selected' : '' }}>-- pilih jenis pengguna --</option>
                                @endif
                                @foreach($dataRole as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', ($dataUser ? $dataUser->user->roles[0]->id : '')) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </Select>
                            @error('role_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3">
                            <label for="nik">NIK <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror only-number" placeholder="NIK" value="{{ old('nik', ($dataUser ? $dataUser->nik : '')) }}" required @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('nik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if(isset($formFor) && $formFor == 'guru')
                    <div class="row">
                        <div class="col-md-3">
                            <label for="nip">NIP</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror only-number" placeholder="NIP" value="{{ old('nip', ($dataUser ? $dataUser->nip : '')) }}" @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('nip')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="nuptk">NUPTK</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="nuptk" id="nuptk" class="form-control @error('nuptk') is-invalid @enderror only-number" placeholder="NUPTK" value="{{ old('nuptk', ($dataUser ? $dataUser->nuptk : '')) }}" @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('nuptk')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="jenis_ptk">Jenis PTK</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="jenis_ptk" id="jenis_ptk" class="form-control @error('jenis_ptk') is-invalid @enderror" placeholder="Jenis PTK" value="{{ old('jenis_ptk', ($dataUser ? $dataUser->jenis_ptk : '')) }}" @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('jenis_ptk')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3">
                            <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <Select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif>
                                @if(!$isEdit)
                                <option value="" {{ !old('gender') ? 'selected' : '' }} disabled>-- pilih jenis kelamin --</option>
                                @endif
                                <option value="Laki-laki" {{ old('gender', ($dataUser ? $dataUser->gender : '')) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', ($dataUser ? $dataUser->gender : '')) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </Select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="phone_number">Nomor Telepon <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="phone_number" id="phone_number" class="form-control only-number @error('phone_number') is-invalid @enderror" placeholder="Nomor Telepon" value="{{ old('phone_number', ($dataUser ? $dataUser->phone_number : '')) }}" required @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="address">Alamat</label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat" value="{{ old('address', ($dataUser ? $dataUser->address : '')) }}" @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif />
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @php
                        $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                    @endphp
                    <div class="row">
                        <div class="col-md-3">
                            <label for="religion">Agama <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <select name="religion" id="religion" class="form-select @error('religion') is-invalid @enderror" required @if(isset($dataUser->is_dapodik) && $dataUser->is_dapodik ) readonly @endif>
                                @if(!$isEdit)
                                <option value="" {{ !old('religion') ? 'selected' : '' }} disabled>-- pilih agama --</option>
                                @endif
                                @foreach ($agamas as $agama)
                                <option value="{{ $agama }}" {{ old('religion', ($dataUser ? $dataUser->religion : '')) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                            @error('religion')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="bio">Bio</label>
                        </div>
                        <div class="form-group col-md-9">
                            <textarea type="text" name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" placeholder="Bio" >{{ old('bio', ($dataUser ? $dataUser->bio : '')) }}</textarea>
                            @error('bio')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 text-danger text-end">* tidak boleh kosong</div>
        </div>
        <div class="card-footer d-flex justify-content-between gap-2">
            <a href="{{ $backUrl }}" class="btn btn-secondary">&#10094; Kembali</a>
            <button type="submit" class="btn btn-success">{{ $isEdit ? 'Ubah' : 'Tambah'}}</button>
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
    <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.js') }}"></script>
    <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.extra.js') }}"></script>
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
        $(document).on('click', '.btn-password', (e) => {
            const input = e.currentTarget.parentNode.querySelector('input')
            const icon = e.currentTarget.querySelector('i')
            if(input.getAttribute('type') == 'password') {
                input.setAttribute('type', 'text')
                icon.classList.remove('bi-eye-fill')
                icon.classList.add('bi-eye-slash-fill')
            } else {
                input.setAttribute('type', 'password')
                icon.classList.remove('bi-eye-slash-fill')
                icon.classList.add('bi-eye-fill')
            }
        })
    </script>
    <script>
        let form_id = '{{ isset($formId) && $formId ? $formId : "form" }}'
        $('#'+form_id).parsley()
        
        $(document).on('input change mouseenter mouseleave focus', 'input', (e) => {
            let id = e.target.getAttribute('id')
            if(id) $('#'+id).parsley().validate()
        })
        $(document).on('input change mouseenter mouseleave focus', 'select', (e) => {
            let id = e.target.getAttribute('id')
            if(id) $('#'+id).parsley().validate()
        })
    </script>
@endpush