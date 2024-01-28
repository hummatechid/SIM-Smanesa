@extends('admin.layouts.app') @section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="d-flex justify-content-between">
                <h3>{{ $page_title }}</h3>
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        @foreach ($bread_crumbs as $title => $url)
                            <li class="breadcrumb-item">
                                <a href="{{ $url }}">{{ $title }}</a>
                            </li>
                        @endforeach
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $now_page }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <form action="{{ route('change-ip') }}" method="POST" class="card">
            @csrf
            <div class="card-header">
                <h5 class="card-title">
                    Ubah IP Publik
                </h5>
            </div>
            <div class="card-body">
                <label for="ip_public">IP Publik</label>
                <input type="text" class="form-control" placeholder="118.99.112.0" name="ip_public" required pattern="/^(?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$/" data-parsley-pattern-message="format dns salah (contoh: 118.99.112.0)"/>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Ubah</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>
@endsection

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
        $('form').parsley()

        $(document).on('input change mouseenter mouseleave focus', 'input', function() {
            $(this).parsley().validate()
        })
        $(document).on('click', 'button', function() {
            setTimeout(() => {
                $('input').each( function(index, item) {
                    $(this).parsley().validate()
                })
            }, 10);
        })
    </script>
@endpush