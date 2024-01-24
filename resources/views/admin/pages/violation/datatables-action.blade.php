<div class="d-flex gap-2 justify-content-start align-items-center">
    <button type="button" class="btn btn-sm btn-primary btn-edit" data-data="{{ $item }}" data-bs-toggle="modal" data-bs-target="#modal-edit">
        <i class="bi bi-pencil-square" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Ubah" data-bs-placement="top"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Hapus" data-bs-placement="top">
        <i class="bi bi-trash"></i>
    </button>
</div>