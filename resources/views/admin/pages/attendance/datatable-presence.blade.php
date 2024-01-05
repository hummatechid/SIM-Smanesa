<div class="d-flex gap-2">
    @if($item->permit_file)
    <a href="{{ asset(Storage::url($item->permit_file)) }}" target="_blank" class="btn btn-sm btn-primary">Lihat Izin</a>
    @endif
    @hasrole('superadmin')
    <button class="btn btn-sm btn-primary btn-change" data-data="{{ $item }}" data-student="{{ $student }}" data-bs-toggle="modal" data-bs-target="#modal-edit-data">Ubah</button>
    @endhasrole
</div>