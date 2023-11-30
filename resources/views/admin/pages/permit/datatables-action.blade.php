<div class="d-flex gap-2 justify-content-start align-items-center">
    @if($item->status == 'pending' || !$item->status)
    <div class="dropdown">
        <button class="btn btn-sm btn-success" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Konfirmasi
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
                <form action="{{ route('permit.update', $item->id) }}" method="post">
                    @method('patch')
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="dropdown-item">Tolak</button>
                </form>
            </li>
        </ul>
    </div>
    @endif
    <a href="{{ route('permit.show', $item->id) }}" class="btn btn-sm btn-primary" data-id="{{ $item->id }}">Detail</a>
    <button class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}">Hapus</button>
</div>