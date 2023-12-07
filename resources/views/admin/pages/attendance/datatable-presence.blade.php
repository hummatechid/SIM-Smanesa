<div class="d-flex gap-2">
    @if($item->permit_file)
    <a href="{{ asset(Storage::url($item->permit_file)) }}" target="_blank" class="btn btn-sm btn-primary">Lihat Gambar</a>
    @endif
    <button class="btn btn-sm btn-danger delete-data" data-id="{{ $item->id }}">Hapus Izin</button>
</div>