<section class="section">
    <form class="card" id="{{ isset($formId) && $formId ? $formId : 'form' }}" action="{{ url($updateUrl) }}" method="POST" enctype="multipart/form-data">
        @method('patch')
        @csrf
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    {{ $cardTitle }}
                </h5>
            </div>
        </div>
        <div class="card-body">
            <x-session-alert :is-swal="true" />
            <div class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="old_password">Password Lama <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Password Lama" required/>
                            @error('old_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="password">Password Baru <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru" required/>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Konfirmasi Password Baru" required/>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 text-danger text-end">* tidak boleh kosong</div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Ubah Password</button>
        </div>
    </form>
</section>

@push('custom-style')
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
    <script>
        let form_id = '{{ isset($formId) && $formId ? $formId : "form" }}'
        $('#'+form_id).parsley()
    </script>
@endpush