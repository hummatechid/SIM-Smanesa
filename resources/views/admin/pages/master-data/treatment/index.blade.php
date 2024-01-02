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

    @php
        if(auth()->user()->hasRole('superadmin')) {
            $data_column = ["category" => "Kategori", "score" => "Poin","treatment" => "Tindakan" ,"action" => "Aksi"];

        } else {
            $data_column = ["category" => "Kategori", "score" => "Poin","treatment" => "Tindakan"];
        }
        $data_settings = [
            "category" => [
                "title" => "Kategori",
                "type" => "select",
                "first_option" => "-- pilih kategori pelanggaran --",
                "options" => [
                    "Pelanggaran ringan" => "Pelanggaran Ringan",
                    "Pelanggaran sedang" => "Pelanggaran Ringan",
                    "Pelanggaran berat" => "Pelanggaran Berat",
                ],
                "attr" => ["required" => "required"],
            ], "min_score" => [
                "title" => "Skor Minimum",
                "type" => "number",
                "attr" => ["required" => "required", "min"=>1, "max"=>999]
            ], "max_score" => [
                "title" => "Skor Maksimum",
                "type" => "number",
                "attr" => ["required" => "required", "min"=>1, "max"=>999]
            ],
            "action" => [
                "title" => "Tindakan",
                "type" => "text",
                "attr" => ["required" => "required"]
            ], 
        ];
    @endphp
    <x-datatable
        card-title="Tabel Tindak Lanjut"
        data-url="{{ route('treatment.get-main-datatables') }}"
        :table-columns="$data_column"
        delete-option="treatment/soft-delete/deleted_id"
        :default-order="2"
        data-add-url="{{ route('treatment.store') }}"
        data-add-type="modal"
        :data-add-settings="$data_settings"
    />

</div>

<div class="modal fade" id="modal-edit-data" tabindex="-1">
    <div class="modal-dialog">
        <form action="" class="modal-content" id="form-edit" method="POST">
            @csrf
            @method('put')
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="category" class="form-label">Kategori<span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="Pelanggaran ringan">Pelanggaran Ringan</option>
                        <option value="Pelanggaran sedang">Pelanggaran Sedang</option>
                        <option value="Pelanggaran berat">Pelanggaran Berat</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="min_score" class="form-label">Skor Minimum <span class="text-danger">*</span></label>
                    <input type="number" name="min_score" id="min_score" min="1" max="999" class="form-control" placeholder="Skor Minimum" required>
                </div>
                <div class="form-group mb-3">
                    <label for="max_score" class="form-label">Skor Maksimum <span class="text-danger">*</span></label>
                    <input type="number" name="max_score" id="max_score" min="1" max="999" class="form-control" placeholder="Skor Maximum" required>
                </div>
                <div class="form-group mb-3">
                    <label for="action" class="form-label">Tindakan <span class="text-danger">*</span></label>
                    <input type="text" name="action" id="action" class="form-control" placeholder="Pelanggaran" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ubah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('custom-script')
<script>
    $(document).on('click', '.edit-data', function() {
        var data = $(this).data('data')
        var base_edit_url = "{{ route('treatment.update', 'violation_id') }}"
        var real_url = base_edit_url.replace('violation_id', data.id)

        $('#form-edit').attr('action', real_url)
        $('#form-edit #min_score').val(data.min_score)
        $('#form-edit #max_score').val(data.max_score)
        $('#form-edit #action').val(data.action)

        $('#form-edit #category option').each((index, el) => {
            if(data.category == el.value) el.setAttribute('selected', true)
            else el.removeAttribute('selected')
        })
        
        $('#form-edit').parsley().validate()
    })
</script>
@endpush