@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Kelas</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Total <strong>{{ $kelas->total() }}</strong> kelas terdaftar dalam sistem.
        </p>
    </div>
    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Kelas
    </a>
</div>

{{-- SEARCH --}}
<div class="card border-0 mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.kelas.index') }}">
            <div class="row g-2">
                <div class="col">
                    <div class="d-flex align-items-center gap-2 px-3"
                         style="border: 1px solid var(--border-color); border-radius: var(--border-radius-sm); background: var(--bg-card);">
                        <i class="fas fa-search" style="color: var(--txt-tertiary); font-size: 0.85rem;"></i>
                        <input type="text" name="search"
                               class="form-control border-0 shadow-none px-0"
                               placeholder="Cari nama kelas (contoh: 7A, 9B...)"
                               value="{{ request('search') }}"
                               style="background: transparent;">
                        @if(request('search'))
                            <a href="{{ route('admin.kelas.index') }}"
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
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 45%;">Informasi Kelas</th>
                        <th class="text-center" style="width: 20%;">Jumlah Siswa</th>
                        <th class="text-center" style="width: 15%;">Dibuat</th>
                        <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $index => $k)
                    <tr>
                        {{-- NO --}}
                        <td class="ps-4">
                            <span style="font-size: 0.8rem; color: var(--txt-tertiary); font-weight: 600;">
                                {{ $kelas->firstItem() + $index }}
                            </span>
                        </td>

                        {{-- INFO KELAS --}}
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-shape stat-icon-primary"
                                     style="width: 40px; height: 40px; border-radius: var(--border-radius-sm); font-size: 0.95rem;">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                                <div>
                                    <a href="{{ route('admin.kelas.show', $k) }}"
                                       class="fw-bold text-decoration-none d-block"
                                       style="font-size: 1rem; color: var(--txt-primary); transition: color var(--transition);"
                                       onmouseover="this.style.color='var(--clr-primary)'"
                                       onmouseout="this.style.color='var(--txt-primary)'">
                                        {{ $k->nama_kelas }}
                                    </a>
                                    <span style="font-size: 0.75rem; color: var(--txt-tertiary);">
                                        ID: #{{ $k->id }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- SISWA COUNT --}}
                        <td class="text-center">
                            <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill"
                                  style="background: var(--clr-primary-light);
                                         color: var(--clr-primary);
                                         font-size: 0.78rem; font-weight: 700;">
                                <i class="fas fa-users" style="font-size: 0.7rem;"></i>
                                {{ $k->siswa_count ?? 0 }} Siswa
                            </span>
                        </td>

                        {{-- TANGGAL --}}
                        <td class="text-center" style="font-size: 0.8rem; color: var(--txt-secondary);">
                            {{ $k->created_at->format('d M Y') }}
                        </td>

                        {{-- AKSI --}}
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.kelas.show', $k) }}"
                                   class="btn btn-action btn-light"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye" style="color: var(--clr-info);"></i>
                                </a>
                                <a href="{{ route('admin.kelas.edit', $k) }}"
                                   class="btn btn-action btn-light"
                                   title="Ubah Kelas">
                                    <i class="fas fa-pencil-alt" style="color: var(--clr-primary);"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-action btn-light"
                                        title="Hapus Kelas"
                                        onclick="confirmDeleteKelas('{{ route('admin.kelas.destroy', $k) }}', '{{ addslashes($k->nama_kelas) }}')">
                                    <i class="fas fa-trash-alt" style="color: var(--clr-danger);"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                                <h6>Tidak ada kelas ditemukan</h6>
                                <p>
                                    @if(request('search'))
                                        Tidak ada kelas yang cocok dengan "{{ request('search') }}".
                                    @else
                                        Belum ada kelas yang ditambahkan.
                                    @endif
                                </p>
                                @if(request('search'))
                                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-refresh me-2"></i>Reset Pencarian
                                    </a>
                                @else
                                    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-2"></i>Tambah Kelas
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
        @if($kelas->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 px-4 py-3"
             style="border-top: 1px solid var(--border-color);">
            <div style="font-size: 0.82rem; color: var(--txt-secondary);">
                Menampilkan
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $kelas->firstItem() }}</span>
                –
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $kelas->lastItem() }}</span>
                dari
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $kelas->total() }}</span>
                kelas
            </div>
            {{ $kelas->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL HAPUS --}}
<x-modal id="modalHapusKelas" title="Hapus Kelas" type="danger" icon="fa-trash">
    <div class="text-center">
        <p class="mb-1" style="color: var(--txt-secondary);">Anda akan menghapus kelas</p>
        <p class="fw-bold mb-2" style="font-size: 1rem; color: var(--txt-primary);" id="namaKelasHapus"></p>
        <p class="mb-0 px-2 py-2 rounded-2" style="font-size: 0.8rem; background: #fee2e2; color: #991b1b;">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Tindakan ini berdampak pada data siswa di dalamnya dan tidak dapat dibatalkan.
        </p>
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-center gap-2 w-100">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <form id="formHapusKelas" method="POST">
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
function confirmDeleteKelas(url, nama) {
    document.getElementById('formHapusKelas').action = url;
    document.getElementById('namaKelasHapus').innerText = nama;
    new bootstrap.Modal(document.getElementById('modalHapusKelas')).show();
}
</script>
@endpush