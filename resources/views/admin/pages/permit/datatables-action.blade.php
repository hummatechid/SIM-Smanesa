<div class="d-flex gap-2 justify-content-start align-items-center">
    @hasrole(['pimpinan', 'superadmin'])
    @if($item->status == 'pending' || !$item->status)
    <div class="dropdown">
        <button class="btn btn-sm btn-success" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-check-square" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Konfirmasi" data-bs-placement="top"></i>
        </button>
        <ul class="dropdown-menu">
            <li>
                <form action="{{ route('permit.update', $item->id) }}" method="post">
                    @method('patch')
                    @csrf
                    <input type="hidden" name="status" value="accepted">
                    <button type="submit" class="dropdown-item">Izinkan</button>
                </form>
            </li>
            <li>
                <button type="button" class="dropdown-item btn-reject" data-bs-toggle="modal" data-bs-target="#reject-one-modal" data-id="{{ $item->id }}">Tolak</button>
            </li>
        </ul>
    </div>
    @endif
    @endhasrole
    <form action="{{ route('permit.print') }}" method="post">
        @csrf
        <input type="hidden" name="permit_id" value="{{ $item->id }}">
        <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Cetak" data-bs-placement="top">
            <i class="bi bi-printer"></i>
        </button>
    </form>
    @hasrole('superadmin')
    <button class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Hapus" data-bs-placement="top">
        <i class="bi bi-trash"></i>
    </button>
    @endhasrole
</div>