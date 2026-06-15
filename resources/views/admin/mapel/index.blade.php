@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Mata Pelajaran</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Manajemen kurikulum dan penetapan guru pengajar.
        </p>
    </div>
    <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Mapel
    </a>
</div>

{{-- SEARCH --}}
<div class="card border-0 mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.mapel.index') }}">
            <div class="row g-2">
                <div class="col">
                    <div class="d-flex align-items-center gap-2 px-3"
                         style="border: 1px solid var(--border-color); border-radius: var(--border-radius-sm); background: var(--bg-card);">
                        <i class="fas fa-search" style="color: var(--txt-tertiary); font-size: 0.85rem;"></i>
                        <input type="text" name="search"
                               class="form-control border-0 shadow-none px-0"
                               placeholder="Cari mata pelajaran (Matematika, IPA...)"
                               value="{{ request('search') }}"
                               style="background: transparent;">
                        @if(request('search'))
                            <a href="{{ route('admin.mapel.index') }}"
                               class="text-decoration-none flex-shrink-0"
                               style="color: var(--txt-tertiary);">
                                <i class="fas fa-times" style="font-size: 0.8rem;"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary h-100 px-4" type="submit">Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 35%;">Mata Pelajaran</th>
                        <th style="width: 40%;">Guru Pengajar</th>
                        <th class="text-center" style="width: 15%;">Terdaftar</th>
                        <th class="text-end pe-4" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapel as $m)
                    <tr>
                        {{-- MAPEL --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-shape stat-icon-success"
                                     style="width: 40px; height: 40px; border-radius: var(--border-radius-sm); font-size: 0.95rem;">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.9rem; color: var(--txt-primary);">
                                        {{ $m->nama_mapel }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--txt-tertiary);">
                                        ID: #MAPEL-{{ $m->id }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- GURU --}}
                        <td class="py-3">
                            @if($m->guruMapelKelas->count() > 0)
                                <div class="d-flex flex-column gap-1">
                                    @foreach($m->guruMapelKelas->take(3) as $rel)
                                        <div class="d-flex align-items-center gap-2 px-2 py-1 rounded-2"
                                             style="background: var(--bg-muted); border: 1px solid var(--border-color); max-width: 240px;">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                                 style="width: 24px; height: 24px;
                                                        background: var(--clr-primary-light);
                                                        color: var(--clr-primary);
                                                        font-size: 0.6rem; font-weight: 700;">
                                                {{ strtoupper(substr($rel->guru->nama ?? '?', 0, 1)) }}
                                            </div>
                                            <div style="min-width: 0;">
                                                <div class="fw-bold text-truncate"
                                                     style="font-size: 0.78rem; color: var(--txt-primary);">
                                                    {{ $rel->guru->nama ?? '-' }}
                                                </div>
                                                <div style="font-size: 0.7rem; color: var(--txt-tertiary);">
                                                    <i class="fas fa-school me-1"></i>
                                                    {{ $rel->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($m->guruMapelKelas->count() > 3)
                                        <span style="font-size: 0.72rem; color: var(--txt-tertiary); padding-left: 2px;">
                                            +{{ $m->guruMapelKelas->count() - 3 }} guru lainnya
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span style="font-size: 0.8rem; color: var(--txt-tertiary); font-style: italic;">
                                    Belum ada penetapan
                                </span>
                            @endif
                        </td>

                        {{-- TANGGAL --}}
                        <td class="text-center" style="font-size: 0.8rem; color: var(--txt-secondary);">
                            {{ $m->created_at->format('d M Y') }}
                        </td>

                        {{-- AKSI --}}
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.mapel.edit', $m) }}"
                                   class="btn btn-action btn-light"
                                   title="Edit">
                                    <i class="fas fa-pencil-alt" style="color: var(--clr-primary);"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-action btn-light"
                                        title="Hapus"
                                        onclick="confirmDeleteMapel('{{ route('admin.mapel.destroy', $m) }}', '{{ addslashes($m->nama_mapel) }}')">
                                    <i class="fas fa-trash-alt" style="color: var(--clr-danger);"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h6>Mata pelajaran tidak ditemukan</h6>
                                <p>
                                    @if(request('search'))
                                        Tidak ada mapel yang cocok dengan "{{ request('search') }}".
                                    @else
                                        Belum ada mata pelajaran yang ditambahkan.
                                    @endif
                                </p>
                                @if(request('search'))
                                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-refresh me-2"></i>Reset Pencarian
                                    </a>
                                @else
                                    <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-2"></i>Tambah Mapel
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($mapel->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top: 1px solid var(--border-color);">
            <div style="font-size: 0.82rem; color: var(--txt-secondary);">
                Menampilkan
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $mapel->firstItem() }}</span>
                –
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $mapel->lastItem() }}</span>
                dari
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $mapel->total() }}</span>
                mapel
            </div>
            {{ $mapel->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL HAPUS --}}
<x-modal id="modalHapusMapel" title="Hapus Mata Pelajaran" type="danger" icon="fa-trash">
    <div class="text-center">
        <p class="mb-1" style="color: var(--txt-secondary);">Anda akan menghapus mata pelajaran</p>
        <p class="fw-bold mb-0" style="font-size: 1rem; color: var(--txt-primary);" id="namaMapelHapus"></p>
        <p class="mt-2 mb-0 px-2 py-2 rounded-2"
           style="font-size: 0.8rem; background: #fee2e2; color: #991b1b;">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Tindakan ini tidak dapat dibatalkan.
        </p>
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-center gap-2 w-100">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <form id="formHapusMapel" method="POST">
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
function confirmDeleteMapel(url, nama) {
    document.getElementById('formHapusMapel').action = url;
    document.getElementById('namaMapelHapus').innerText = nama;
    new bootstrap.Modal(document.getElementById('modalHapusMapel')).show();
}
</script>
@endpush