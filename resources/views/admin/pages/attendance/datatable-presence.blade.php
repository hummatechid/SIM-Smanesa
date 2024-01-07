<div class="d-flex gap-2">
    @if($item->permit_file)
    <button data-image="{{ asset(Storage::url($item->permit_file)) }}" class="btn btn-sm btn-primary btn-image w-100" data-bs-toggle="modal" data-bs-target="#modal-image">Lihat Izin</button>
    @endif
    @hasrole('superadmin')
    <button class="btn btn-sm btn-primary btn-change w-100" data-data="{{ $item }}" data-student="{{ $student }}" data-bs-toggle="modal" data-bs-target="#modal-edit-data">Ubah</button>
    @endhasrole
</div>