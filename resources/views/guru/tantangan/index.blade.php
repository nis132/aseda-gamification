@extends('layouts.app')
@section('title', 'Kelola Tantangan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Tantangan</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Kelola tugas berbasis gamifikasi untuk memotivasi belajar siswa.
        </p>
    </div>

    <a href="{{ route('guru.tantangan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Buat Tantangan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th class="ps-4" style="width: 30%;">Tantangan</th>
                        <th style="width: 18%;">Mapel</th>
                        <th style="width: 20%;">Dipublish Ke</th>
                        <th style="width: 12%;">Reward</th>
                        <th style="width: 15%;">Deadline</th>
                        <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($tantangan as $t)

                    <tr>

                        {{-- TANTANGAN --}}
                        <td class="ps-4 py-3">

                            <div class="fw-bold"
                                 style="font-size: 0.9rem; color: var(--txt-primary);">
                                {{ $t->judul }}
                            </div>

                            <div class="text-truncate"
                                 style="font-size: 0.75rem; color: var(--txt-tertiary); max-width: 250px;">
                                {{ Str::limit($t->deskripsi, 60) }}
                            </div>

                            <div style="font-size: 0.72rem; color: var(--txt-tertiary);" class="mt-1">
                                <i class="fas fa-user-edit me-1"></i>
                                Dibuat oleh:
                                <span class="fw-semibold">
                                    {{ $t->guru->nama }}
                                </span>

                                @if($t->guru_id == auth()->id())
                                    <span class="badge ms-1"
                                          style="background: #d1fae5; color: var(--clr-success); font-size: 0.65rem;">
                                        Milik Saya
                                    </span>
                                @endif
                            </div>

                        </td>

                        {{-- MAPEL --}}
                        <td class="py-3">

                            <span class="badge"
                                  style="background: var(--clr-primary-light); color: var(--clr-primary);">
                                <i class="fas fa-book me-1"></i>
                                {{ $t->mapel->nama_mapel }}
                            </span>

                        </td>

                        {{-- PUBLISH KELAS --}}
                        <td class="py-3">

                            <div class="d-flex flex-wrap gap-1">

                                @forelse($t->publishKelas as $publish)

                                    <span class="badge"
                                          style="background: #dbeafe; color: var(--clr-info);">

                                        <i class="fas fa-users me-1"></i>

                                        {{ $publish->kelas->nama_kelas }}

                                    </span>

                                @empty

                                    <span style="font-size: 0.75rem; color: var(--txt-tertiary);">
                                        Belum dipublish
                                    </span>

                                @endforelse

                            </div>

                        </td>

                        {{-- REWARD --}}
                        <td class="py-3">

                            <div class="d-flex align-items-center gap-2">

                                <div class="stat-icon stat-icon-warning"
                                     style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    <i class="fas fa-star"></i>
                                </div>

                                <span class="fw-bold"
                                      style="color: var(--txt-primary);">
                                    {{ $t->poin }}
                                    <span style="font-weight: 400; color: var(--txt-tertiary); font-size: 0.78rem;">
                                        XP
                                    </span>
                                </span>

                            </div>

                        </td>

                        {{-- DEADLINE --}}
                        <td class="py-3">

                            @php
                                $isOverdue = $t->batas_waktu && now()->gt($t->batas_waktu);
                            @endphp

                            <div class="fw-bold"
                                 style="font-size: 0.82rem;
                                 color: {{ $isOverdue ? 'var(--clr-danger)' : 'var(--txt-primary)' }};">

                                <i class="far fa-clock me-1"></i>

                                {{ $t->batas_waktu ? $t->batas_waktu->format('d M, H:i') : 'Tanpa batas' }}

                            </div>

                            @if($isOverdue)

                                <span class="badge"
                                      style="background: #fee2e2; color: var(--clr-danger); font-size: 0.65rem;">
                                    Waktu Habis
                                </span>

                            @else

                                <div style="font-size: 0.72rem; color: var(--txt-tertiary);">
                                    {{ $t->batas_waktu ? $t->batas_waktu->diffForHumans() : 'Tanpa batas' }}
                                </div>

                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td class="text-end pe-4 py-3">

                            <div class="d-flex justify-content-end gap-2">

                                {{-- LIHAT --}}
                                <a href="{{ route('guru.tantangan.show', $t) }}"
                                   class="btn btn-action btn-light"
                                   title="Lihat">

                                    <i class="fas fa-eye"
                                       style="color: var(--clr-info);"></i>

                                </a>

                                {{-- EDIT HANYA PEMILIK --}}
{{-- EDIT HANYA PEMILIK & BELUM PUBLISH --}}
@if($t->guru_id == auth()->id())
    @if($t->status === 'published')
        <span class="btn btn-action btn-light"
              title="Tidak bisa diedit — tantangan sudah dipublish"
              style="opacity: 0.45; cursor: not-allowed;">
            <i class="fas fa-pencil-alt" style="color: var(--clr-primary);"></i>
        </span>
    @else
        <a href="{{ route('guru.tantangan.edit', $t) }}"
           class="btn btn-action btn-light"
           title="Edit">
            <i class="fas fa-pencil-alt" style="color: var(--clr-primary);"></i>
        </a>
    @endif
@endif

                                {{-- PUBLISH — muncul untuk semua guru yang mengampu mapel ini --}}
                                @php
                                    $kelasPublishedIds2 = $t->publishKelas->pluck('kelas_id')->toArray();
                                    $adaKelasBisaPublish = \App\Models\GuruMapelKelas::where('guru_id', auth()->id())
                                        ->where('mapel_id', $t->mapel_id)
                                        ->get()
                                        ->unique('kelas_id')
                                        ->filter(fn($gmk) => !in_array($gmk->kelas_id, $kelasPublishedIds2))
                                        ->count() > 0;
                                @endphp

                                @if($adaKelasBisaPublish && $t->soal_count > 0)
                                <button type="button"
                                        class="btn btn-action btn-light"
                                        title="Publish ke Kelas"
                                        data-bs-toggle="modal"
                                        data-bs-target="#publishModal{{ $t->id }}">
                                    <i class="fas fa-paper-plane"
                                       style="color: var(--clr-success);"></i>
                                </button>
                                @endif

                                {{-- HAPUS HANYA PEMILIK --}}
                                @if($t->guru_id == auth()->id())

                                <button type="button"
                                        class="btn btn-action btn-light"
                                        title="Hapus"
                                        onclick="prepareDelete('{{ $t->id }}', '{{ addslashes($t->judul) }}')">

                                    <i class="fas fa-trash-alt"
                                       style="color: var(--clr-danger);"></i>

                                </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                    {{-- MODAL PUBLISH --}}
                    <div class="modal fade"
                         id="publishModal{{ $t->id }}"
                         tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 shadow">

                                <form action="{{ url('guru/tantangan/' . $t->id . '/publish') }}"
                                      method="POST">

                                    @csrf

                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">
                                            Publish Tantangan
                                        </h5>

                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label class="form-label">
                                                Pilih Kelas
                                            </label>

                                            @php
                                                $kelasPublishedIds = $t->publishKelas->pluck('kelas_id')->toArray();
                                                $kelasBisaPublish = App\Models\GuruMapelKelas::with('kelas')
                                                    ->where('guru_id', auth()->id())
                                                    ->where('mapel_id', $t->mapel_id)
                                                    ->get()
                                                    ->unique('kelas_id')
                                                    ->filter(fn($gmk) => !in_array($gmk->kelas_id, $kelasPublishedIds));
                                            @endphp

                                            <select name="kelas_id"
                                                    class="form-select"
                                                    required>

                                                <option value="">
                                                    -- Pilih Kelas --
                                                </option>

                                                @foreach($kelasBisaPublish as $gmk)
                                                <option value="{{ $gmk->kelas_id }}">
                                                    {{ $gmk->kelas->nama_kelas }}
                                                </option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="p-3 rounded-3"
                                             style="background: var(--bg-muted);">

                                            <div style="font-size: 0.82rem; color: var(--txt-secondary);">

                                                <i class="fas fa-info-circle me-1"></i>

                                                Tantangan ini akan dipublish
                                                ke kelas yang dipilih tanpa
                                                mengubah data asli tantangan.

                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer border-0">

                                        <button type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal">

                                            Batal

                                        </button>

                                        <button type="submit"
                                                class="btn btn-primary">

                                            <i class="fas fa-paper-plane me-2"></i>
                                            Publish

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                    @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <div class="empty-state-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>

                                <h6>Belum ada tantangan</h6>

                                <p>
                                    Mulai buat tantangan untuk meningkatkan
                                    semangat belajar siswa.
                                </p>

                                <a href="{{ route('guru.tantangan.create') }}"
                                   class="btn btn-primary btn-sm">

                                    <i class="fas fa-plus me-2"></i>
                                    Buat Tantangan

                                </a>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if($tantangan->hasPages())

        <div class="d-flex justify-content-between align-items-center px-4 py-3"
             style="border-top: 1px solid var(--border-color);">

            <div style="font-size: 0.82rem; color: var(--txt-secondary);">

                Menampilkan

                <span style="font-weight: 600; color: var(--txt-primary);">
                    {{ $tantangan->firstItem() }}
                </span>

                –

                <span style="font-weight: 600; color: var(--txt-primary);">
                    {{ $tantangan->lastItem() }}
                </span>

                dari

                <span style="font-weight: 600; color: var(--txt-primary);">
                    {{ $tantangan->total() }}
                </span>

                tantangan

            </div>

            {{ $tantangan->links('pagination::bootstrap-5') }}

        </div>

        @endif

    </div>
</div>

{{-- DELETE MODAL --}}
<x-modal id="deleteModal"
         title="Hapus Tantangan"
         type="danger"
         icon="fa-trash">

    <div class="text-center">

        <p class="mb-1" style="color: var(--txt-secondary);">
            Anda akan menghapus tantangan
        </p>

        <p class="fw-bold mb-0"
           style="font-size: 1rem; color: var(--txt-primary);"
           id="deleteTantanganTitle"></p>

        <p class="mt-2 mb-0 px-2 py-2 rounded-2"
           style="font-size: 0.8rem; background: #fee2e2; color: #991b1b;">

            <i class="fas fa-exclamation-triangle me-1"></i>

            Semua progres siswa pada tugas ini
            akan ikut terhapus permanen.

        </p>

    </div>

    <x-slot:footer>

        <div class="d-flex justify-content-center gap-2 w-100">

            <button type="button"
                    class="btn btn-light px-4"
                    data-bs-dismiss="modal">

                Batal

            </button>

            <form id="deleteFormAction" method="POST">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-danger px-4">

                    <i class="fas fa-trash me-2"></i>
                    Ya, Hapus

                </button>

            </form>

        </div>

    </x-slot:footer>

</x-modal>

@endsection

@push('scripts')

<script>
function prepareDelete(id, judul)
{
    document.getElementById('deleteTantanganTitle').innerText = judul;

    document.getElementById('deleteFormAction').action =
        `/guru/tantangan/${id}`;

    new bootstrap.Modal(
        document.getElementById('deleteModal')
    ).show();
}
</script>

@endpush