@extends('layouts.app')
@section('title', 'Kelola Materi')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Materi</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Atur pustaka bahan ajar untuk menunjang pembelajaran siswa.
        </p>
    </div>
    <a href="{{ route('guru.materi.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Materi
    </a>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-primary">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $materis->total() }}</div>
                    <div class="text-label">Total Koleksi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-success">
                    <i class="fas fa-cloud-download-alt"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $materis->getCollection()->where('file_url', '!=', null)->count() }}</div>
                    <div class="text-label">File Terlampir</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 35%;">Detail Materi</th>
                        <th style="width: 15%;">Mata Pelajaran</th>
                        <th style="width: 12%;">Kelas</th>
                        <th class="text-center" style="width: 12%;">Berkas</th>
                        <th style="width: 14%;">Waktu Rilis</th>
                        <th class="text-end pe-4" style="width: 12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materis as $item)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-shape {{ $item->file_url ? 'stat-icon-danger' : 'stat-icon-info' }}"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas {{ $item->file_url ? 'fa-file-pdf' : 'fa-file-alt' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.9rem; color: var(--txt-primary);">
                                        {{ $item->judul }}
                                    </div>
                                    <div class="text-truncate" style="font-size: 0.75rem; color: var(--txt-tertiary); max-width: 240px;">
                                        {{ $item->deskripsi ?? 'Tidak ada deskripsi' }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: var(--txt-tertiary);" class="mt-1">
                                        <i class="fas fa-user-edit me-1"></i>
                                        {{ $item->guru->nama ?? '-' }}
                                        @if($item->guru_id == auth()->id())
                                            <span class="badge ms-1"
                                                  style="background: #d1fae5; color: var(--clr-success); font-size: 0.65rem;">
                                                Milik Saya
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge"
                                  style="background: var(--clr-primary-light); color: var(--clr-primary);">
                                {{ $item->mapel->nama_mapel ?? 'Umum' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge"
                                  style="background: #dbeafe; color: var(--clr-info);">
                                {{ $item->kelas->nama_kelas ?? 'Semua' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->file_url)
                                <span class="badge" style="background: #d1fae5; color: var(--clr-success);">
                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                </span>
                            @else
                                <span class="badge" style="background: var(--bg-muted); color: var(--txt-tertiary);">
                                    <i class="fas fa-minus-circle me-1"></i>Teks Saja
                                </span>
                            @endif
                        </td>
                        <td style="font-size: 0.8rem;">
                            <div style="font-weight: 600; color: var(--txt-primary);">{{ $item->created_at->format('d M Y') }}</div>
                            <div style="color: var(--txt-tertiary);">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('guru.materi.show', $item) }}"
                                   class="btn btn-action btn-light" title="Lihat">
                                    <i class="fas fa-eye" style="color: var(--clr-info);"></i>
                                </a>
                                {{-- Edit dan hapus hanya untuk pemilik materi --}}
                                @if($item->guru_id == auth()->id())
                                <a href="{{ route('guru.materi.edit', $item) }}"
                                   class="btn btn-action btn-light" title="Edit">
                                    <i class="fas fa-pencil-alt" style="color: var(--clr-primary);"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-action btn-light" title="Hapus"
                                        onclick="showDeleteModal('{{ addslashes($item->judul) }}', '{{ route('guru.materi.destroy', $item) }}')">
                                    <i class="fas fa-trash-alt" style="color: var(--clr-danger);"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h6>Belum ada materi pembelajaran</h6>
                                <p>Materi yang Anda buat akan muncul di sini.</p>
                                <a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Tambah Materi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($materis->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top: 1px solid var(--border-color);">
            <div style="font-size: 0.82rem; color: var(--txt-secondary);">
                Menampilkan
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $materis->firstItem() }}</span>
                –
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $materis->lastItem() }}</span>
                dari
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $materis->total() }}</span>
                materi
            </div>
            {{ $materis->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL HAPUS --}}
<x-modal id="confirmDeleteModal" title="Hapus Materi" type="danger" icon="fa-trash">
    <div class="text-center">
        <p class="mb-1" style="color: var(--txt-secondary);">Anda akan menghapus materi</p>
        <p class="fw-bold mb-0" style="font-size: 1rem; color: var(--txt-primary);" id="materiJudul"></p>
        <p class="mt-2 mb-0 px-2 py-2 rounded-2"
           style="font-size: 0.8rem; background: #fee2e2; color: #991b1b;">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Tindakan ini tidak dapat dibatalkan.
        </p>
    </div>
    <x-slot:footer>
        <div class="d-flex justify-content-center gap-2 w-100">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                </button>
            </form>
        </div>
    </x-slot:footer>
</x-modal>

@endsection

@push('styles')
<style>
.pagination { margin-bottom: 0; gap: 4px; }
.page-link {
    border-radius: var(--border-radius-sm) !important;
    border: 1px solid var(--border-color) !important;
    background: var(--bg-card);
    color: var(--txt-secondary);
    font-size: 0.82rem;
    font-weight: 600;
    padding: 0.35rem 0.7rem;
    transition: all var(--transition);
}
.page-link:hover {
    background: var(--clr-primary-light);
    color: var(--clr-primary);
    border-color: var(--clr-primary) !important;
}
.page-item.active .page-link {
    background: var(--clr-primary);
    border-color: var(--clr-primary) !important;
    color: #fff;
    box-shadow: 0 2px 8px rgba(var(--clr-primary-rgb), 0.35);
}
.page-item.disabled .page-link { background: var(--bg-muted); color: var(--txt-tertiary); }
</style>
@endpush

@push('scripts')
<script>
function showDeleteModal(judul, actionUrl) {
    document.getElementById('materiJudul').innerText = judul;
    document.getElementById('deleteForm').action = actionUrl;
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
}
</script>
@endpush