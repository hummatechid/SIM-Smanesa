<div class="d-flex justify-content-center align-items-center gap-2">
    @if($item->permit_file)
    <button data-image="{{ asset(Storage::url($item->permit_file)) }}" class="btn btn-sm btn-primary btn-image" data-bs-toggle="modal" data-bs-target="#modal-image"><i class="bi bi-info-circle-fill"></i><span class="opacity-0">_</span>Lihat<span class="opacity-0">_</span>Izin</button>
    @endif
    @hasrole('superadmin')
    <button class="btn btn-sm btn-primary btn-change" data-data="{{ $item }}" data-student="{{ $student }}" data-bs-toggle="modal" data-bs-target="#modal-edit-data"><i class="bi bi-pencil-fill"></i><span class="opacity-0">_</span>Ubah</button>
    @endhasrole
</div>