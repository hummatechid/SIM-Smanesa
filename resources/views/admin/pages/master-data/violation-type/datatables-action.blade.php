<div class="d-flex gap-2">
    <button class="btn btn-sm btn-primary show-detail" data-data="{{ $item }}" data-bs-toggle="modal" data-bs-target="#modal-violation-detail">
        <i class="bi bi-list-ul" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Detail" data-bs-placement="top"></i>
    </button>
    @role('superadmin')
    <button class="btn btn-sm btn-secondary edit-data" data-data="{{ $item }}" data-bs-toggle="modal" data-bs-target="#modal-edit-data">
        <i class="bi bi-pencil-square" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Ubah" data-bs-placement="top"></i>
    </button>
    <button class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Hapus" data-bs-placement="top">
        <i class="bi bi-trash"></i>
    </button>
    @endrole
</div>

