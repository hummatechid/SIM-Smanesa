<section class="section">
    <form class="card" id="{{ isset($formId) && $formId ? $formId : 'form' }}" action="{{ $formAction }}" method="{{ $formMethod != 'GET' ? 'POST' : 'GET' }}" enctype="multipart/form-data">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    {{ $cardTitle }}
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required />
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password_confirmation" name="password" id="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required />
            </div>
            <div class="form-group mb-3">
                <label for="role">Role</label>
                <Select name="role" id="role" class="form-select" required>
                    <option value="" {{ !old('role') ? 'selected' : '' }}>-- pilih jenis pengguna --</option>
                    @if(isset($formFor) && $formFor == "guru")
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    @else
                    <option value="satpam" {{ old('role') == 'satpam' ? 'selected' : '' }}>Satpam</option>
                    <option value="staf" {{ old('role') == 'staf' ? 'selected' : '' }}>Staf</option>
                    <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    @endif
                </Select>
            </div>
            <div class="form-group mb-3">
                <label for="nik">NIK</label>
                <input type="text" name="nik" id="nik" class="form-control only-number" placeholder="NIK" value="{{ old('nik') }}" required />
            </div>
            @if(isset($formFor) && $formFor == 'guru')
            <div class="form-group mb-3">
                <label for="nip">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" placeholder="NIP" value="{{ old('nip') }}" required />
            </div>
            <div class="form-group mb-3">
                <label for="nuptk">NUPTK</label>
                <input type="text" name="nuptk" id="nuptk" class="form-control" placeholder="NUPTK" value="{{ old('nuptk') }}" required />
            </div>
            @endif
            <div class="form-group mb-3">
                <label for="full_name">Nama Lengkap</label>
                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Nama Lengkap" value="{{ old('full_name') }}" required />
            </div>
            <div class="form-group mb-3">
                <label for="gender">Jenis Kelamin</label>
                <Select name="gender" id="gender" class="form-select" required>
                    <option value="" {{ !old('gender') ? 'selected' : '' }}>-- pilih jenis kelamin --</option>
                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </Select>
            </div>
            <div class="form-group mb-3">
                <label for="address">Alamat</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Alamat" value="{{ old('address') }}" required />
            </div>
            <div class="form-group mb-3">
                <label for="agama">Agama</label>
                <input type="text" name="agama" id="agama" class="form-control" placeholder="Agama" value="{{ old('agama') }}" required />
            </div>
            <div class="form-group mb-3">
                <label for="bio">Bio</label>
                <textarea type="text" name="bio" id="bio" class="form-control" placeholder="Bio" required>{{ old('bio') }}</textarea>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Tambah</button>
        </div>
    </form>
</section>

@push('custom-style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.min.css') }}">
@endpush
@push('custom-script')
    <script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond/filepond.min.js') }}"></script>
    <script>
        let form_id = '{{ isset($formId) && $formId ? $formId : "form" }}'
        $('#'+form_id).parsley()
    </script>
@endpush