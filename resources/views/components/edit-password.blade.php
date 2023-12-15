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
                        <div class="form-group col-md-9 position-relative">
                            <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Password Lama" required/>
                            <button type="button" class="btn-password">
                                <i class="bi bi-eye-fill text-muted"></i>
                            </button>
                            @error('old_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="password">Password Baru <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9 position-relative">
                            <input type="password" name="password" id="password" pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{3,}$" minlength="8" data-parsley-pattern-message="password harus mengandung huruf besar, huruf kecil, dan angka" class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru" required/>
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
                            <label for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group col-md-9 position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{3,}$" minlength="8" data-parsley-pattern-message="password harus mengandung huruf besar, huruf kecil, dan angka" data-parsley-equalto="#password" data-parsley-equalto-message="nilai tidak sama dengan password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Konfirmasi Password Baru" required/>
                            <button type="button" class="btn-password">
                                <i class="bi bi-eye-fill text-muted"></i>
                            </button>
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
    <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.js') }}"></script>
    <script src="{{ asset('assets/extensions/parsleyjs/i18n/id.extra.js') }}"></script>
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
    </script>
@endpush