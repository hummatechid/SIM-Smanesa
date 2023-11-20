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
        <form class="card" id="form" action="{{ url('violation') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        Tambah Data Pelanggaran
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <x-session-alert/>
                <div class="form-group mb-3">
                    <label for="user_id">Siswa</label>
                    <select id="user_id" class="form-select choices" name="user_id" required>
                        <option value="" selected>-- pilih siswa --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{$user->full_name}} ({{ $user->nisn }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="violation_type_id">Pelanggaran</label>
                    <select id="violation_type_id" class="form-select choices" name="violation_type_id" required>
                        <option value="" selected>-- pilih pengguna --</option>
                        @foreach($violation_types as $violation)
                        <option value="{{ $violation->id }}">{{ $violation->name }}({{ $violation->score }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ url('violation') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Tambah</button>
            </div>
        </form>
    </section>
    
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