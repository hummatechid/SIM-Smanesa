<div class="d-flex gap-2 justify-content-start align-items-center">
    <button type="button" class="btn btn-sm btn-primary btn-edit" data-data="{{ $item }}" data-bs-toggle="modal" data-bs-target="#modal-edit">Ubah</button>
    <button type="button" class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}">Hapus</button>
</div>