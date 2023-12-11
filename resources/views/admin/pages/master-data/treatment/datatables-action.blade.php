<div class="d-flex gap-2">
    <button class="btn btn-sm btn-secondary edit-data" data-data="{{ $item }}" data-bs-toggle="modal" data-bs-target="#modal-edit-data">Ubah</button>
    <button class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}">Hapus</button>
</div>