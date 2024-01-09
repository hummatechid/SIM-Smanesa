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
                        @foreach($bread_crumbs as $title => $url)
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $title }}</a></li>
                        @endforeach
                        <li class="breadcrumb-item active" aria-current="page">{{ $now_page}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 order-md-1 mb-3">
                <div class="card mb-0">
                    <h4 class="card-body text-center m-0 p-4">
                        Detail Peserta Didik
                    </h4>
                </div>
            </div>
            <div class="col-md-4 order-md-2 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-4 d-flex justify-content-center">
                            <img src="{{ $student->image ? asset(Storage::url($student->image)) : asset('assets/compiled/jpg/0.webp') }}" alt="foto pengguna" class="rounded-4 mb-2" style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <div class="mb-3 text-center">
                            <div class="lead">{{ $student->full_name }}</div>
                            <div class="fw-bold">{{ $student->email ? $student->email : "-" }}</div>
                            <div class="fw-bold">{{ $student->nama_rombel ? $student->nama_rombel : '-' }} / {{ $student->nisn ? $student->nisn : '-' }}</div>
                        </div>
                        <div class="d-flex flex-column align-items-center gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#violation-modal">Poin Pelanggaran : {{ $student->score }}</button>
                            <div class="d-flex justify-content-center  gap-2">
                                <a href="{{ route('student.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 order-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="fw-bold">Data Peserta Didik</h5>
                    </div>
                    <div class="card-body">
                        <x-session-alert :is-swal="true" />
                        <table class="table mb-0 mt-3">
                            <tr>
                                <th>Email</th>
                                <td>{{ $student->email ? $student->email : "-" }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $student->nik ? $student->nik : "-" }}</td>
                            </tr>
                            <tr>
                                <th>NIPD</th>
                                <td>{{ $student->nipd ? $student->nipd : "-" }}</td>
                            </tr>
                            <tr>
                                <th>NISN</th>
                                <td>{{ $student->nisn ? $student->nisn : "-" }}</td>
                            </tr>
                            <tr>
                                <th>kelas</th>
                                <td>{{ $student->nama_rombel ? $student->nama_rombel : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ $student->gender ? $student->gender : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $student->phone_number ? $student->phone_number : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $student->address ? $student->address : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td>{{ $student->religion ? $student->religion : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Tempat / Tanggal Lahir</th>
                                <td>{{ $student->birth_location ? $student->birth_location : "-" }} / {{ $student->birth_date ? $student->birth_date : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Tinggi / Berat Badan</th>
                                <td>{{ $student->height ? $student->height." cm" : "-" }} / {{ $student->weight ? $student->weight." kg" : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Asal Sekolah</th>
                                <td>{{ $student->school_origin ? $student->school_origin : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Kebutuhan Khusus</th>
                                <td>{{ $student->kebutuhan_khusus ? $student->kebutuhan_khusus : "-" }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 order-md-3 col-md-8 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5>Data Orang Tua & Wali</h5>
                    </div>
                    <div class="card-body">
                        <x-session-alert :is-swal="true" />
                        <table class="table mb-0 mt-3">
                            <tr>
                                <th>Nama Ayah</th>
                                <td>{{ $student->father_name ? $student->father_name : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan Ayah</th>
                                <td>{{ $student->father_job ? $student->father_job : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Nama Ibu</th>
                                <td>{{ $student->mother_name ? $student->mother_name : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan Ibu</th>
                                <td>{{ $student->mother_job ? $student->mother_job : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Nama Wali</th>
                                <td>{{ $student->guardian_name ? $student->guardian_name : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan Wali</th>
                                <td>{{ $student->guardian_job ? $student->guardian_job : "-" }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<div class="modal fade modal-lg" id="violation-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Data Pelanggaran</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $data_columns = [
                        'date' => "Tanggal",
                        'violation' => [
                            "title" => "Pelanggaran",
                            "width" => "1000px"
                        ],
                        'score' => 'Poin'
                    ];
                @endphp
                <x-datatable
                    card-title=""
                    data-url="{{ route('violation.student', 'student_id='.$student->id) }}"
                    :table-columns="$data_columns"
                    default-order="1"
                    arrange-order="desc"
                />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-style')
    <style>
        .modal-body .card-header {
            display: none;
        }
        .modal-body .card {
            margin-bottom: 0!important;
        }
    </style>
@endpush