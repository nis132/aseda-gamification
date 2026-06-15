@extends('layouts.app')
@section('title', 'Detail Materi')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Materi</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Pratinjau materi pembelajaran.
        </p>
    </div>
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mt-3" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <div class="d-flex gap-2">
        <a href="{{ route('guru.materi') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>

        @if($materi->guru_id == auth()->id())
            {{-- Pemilik: bisa edit dan hapus --}}
            <a href="{{ route('guru.materi.edit', $materi) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt me-2"></i>Edit
            </a>
            <button type="button" class="btn btn-light"
                    onclick="showDeleteModal()"
                    style="color: var(--clr-danger);">
                <i class="fas fa-trash-alt me-2"></i>Hapus
            </button>
        @else
            {{-- Bukan pemilik: bisa kirim ke kelasnya sendiri --}}
            <button type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#kirimModal">
                <i class="fas fa-share me-2"></i>Kirim ke Kelas Saya
            </button>
        @endif
    </div>
</div>


<div class="row g-4">

    {{-- KONTEN UTAMA --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4 p-md-5">

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge"
                          style="background: var(--clr-primary-light); color: var(--clr-primary);">
                        <i class="fas fa-book me-1"></i>{{ $materi->mapel->nama_mapel ?? 'Umum' }}
                    </span>
                    <span class="badge"
                          style="background: #dbeafe; color: var(--clr-info);">
                        <i class="fas fa-users me-1"></i>{{ $materi->kelas->nama_kelas ?? 'Semua Kelas' }}
                    </span>
                </div>

                <h4 class="fw-bold mb-4" style="color: var(--txt-primary);">{{ $materi->judul }}</h4>

                <div class="p-4 rounded-2 mb-4"
                     style="background: var(--bg-muted); border: 1px solid var(--border-color); min-height: 180px;">
                    <div class="text-label mb-2">Deskripsi / Ringkasan Materi</div>
                    <div style="color: var(--txt-primary); line-height: 1.75; font-size: 0.9rem;">
                        {!! nl2br(e($materi->deskripsi)) !!}
                    </div>
                </div>

                @if($materi->file_url)
                <div class="d-flex align-items-center gap-3 p-3 rounded-2"
                     style="border: 1px solid var(--border-color); background: var(--bg-card);
                            transition: border-color var(--transition);">
                    <div class="icon-shape stat-icon-danger" style="width: 44px; height: 44px; font-size: 1.1rem;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="text-label">Dokumen Lampiran</div>
                        <div class="text-truncate fw-bold" style="font-size: 0.85rem; color: var(--txt-primary);">
                            {{ basename($materi->file_url) }}
                        </div>
                    </div>
                    <a href="{{ asset('storage/'.$materi->file_url) }}" target="_blank"
                       class="btn btn-primary btn-action flex-shrink-0">
                        <i class="fas fa-external-link-alt me-1"></i>Buka
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- SIDEBAR INFO --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <span class="fw-bold" style="font-size: 0.85rem;">Informasi Publikasi</span>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="text-label mb-1">Dibuat Pada</div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-calendar-alt" style="color: var(--clr-primary); font-size: 0.85rem;"></i>
                        <span class="fw-bold" style="font-size: 0.85rem; color: var(--txt-primary);">
                            {{ $materi->created_at->translatedFormat('d F Y') }}
                        </span>
                    </div>
                    <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-left: 1.4rem;">
                        Pukul {{ $materi->created_at->format('H:i') }} WIB
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-label mb-1">Terakhir Diperbarui</div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-history" style="color: var(--clr-success); font-size: 0.85rem;"></i>
                        <span class="fw-bold" style="font-size: 0.85rem; color: var(--txt-primary);">
                            {{ $materi->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <div class="p-3 rounded-2"
                     style="background: var(--clr-primary-light); font-size: 0.8rem; color: var(--clr-primary);">
                    <i class="fas fa-info-circle me-2"></i>
                    Materi ini dapat diakses oleh semua siswa di
                    <strong>{{ $materi->kelas->nama_kelas ?? 'Kelas Terpilih' }}</strong>.
                </div>
            </div>
        </div>

        <div class="card p-4 text-center"
             style="background: var(--sidebar-bg); border: none;">
            <i class="fas fa-lightbulb fa-2x mb-3" style="color: #fbbf24;"></i>
            <h6 class="fw-bold mb-2" style="color: #fff;">Tips Mengajar</h6>
            <p class="mb-0" style="font-size: 0.8rem; color: rgba(255,255,255,0.65);">
                Pastikan file PDF sudah memiliki judul yang jelas agar siswa lebih mudah mencarinya di perangkat mereka.
            </p>
        </div>
    </div>

</div>

{{-- MODAL KIRIM KE KELAS — hanya untuk bukan pemilik --}}
@if($materi->guru_id != auth()->id())
<div class="modal fade" id="kirimModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('guru.materi.kirim', $materi) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-share me-2" style="color: var(--clr-primary);"></i>
                        Kirim ke Kelas Saya
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="p-3 rounded-2 mb-3"
                         style="background: var(--clr-primary-light); font-size: 0.82rem; color: var(--clr-primary);">
                        <i class="fas fa-info-circle me-2"></i>
                        Salinan materi <strong>"{{ $materi->judul }}"</strong> akan dibuat
                        dan dikirimkan ke kelas yang kamu pilih. Materi asli tidak berubah.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Kelas Tujuan</label>
                        <select name="guru_mapel_kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach(
                                \App\Models\GuruMapelKelas::with('kelas')
                                    ->where('guru_id', auth()->id())
                                    ->where('mapel_id', $materi->mapel_id)
                                    ->get()
                                    ->unique('kelas_id')
                                as $gmk
                            )
                                <option value="{{ $gmk->id }}">
                                    {{ $gmk->kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hanya kelas yang kamu ampu untuk mata pelajaran ini yang ditampilkan.</div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-share me-2"></i>Kirim Materi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- MODAL HAPUS — hanya untuk pemilik --}}
@if($materi->guru_id == auth()->id())
<x-modal id="deleteMateriModal" title="Hapus Materi" type="danger" icon="fa-trash">
    <div class="text-center">
        <p class="mb-1" style="color: var(--txt-secondary);">Anda akan menghapus materi</p>
        <p class="fw-bold mb-0" style="font-size: 1rem; color: var(--txt-primary);">
            "{{ $materi->judul }}"
        </p>
        <p class="mt-2 mb-0 px-2 py-2 rounded-2"
           style="font-size: 0.8rem; background: #fee2e2; color: #991b1b;">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Tindakan ini tidak dapat dibatalkan.
        </p>
    </div>
    <x-slot:footer>
        <div class="d-flex justify-content-center gap-2 w-100">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <form action="{{ route('guru.materi.destroy', $materi) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                </button>
            </form>
        </div>
    </x-slot:footer>
</x-modal>
@endif

@endsection

@push('scripts')
<script>
function showDeleteModal() {
    new bootstrap.Modal(document.getElementById('deleteMateriModal')).show();
}
</script>
@endpush