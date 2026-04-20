@extends('layouts.app')
@section('title', $materi->judul . ' - Materi Pelajaran')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('siswa.materi') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>

        <h5 class="fw-bold text-primary mb-0">
            {{ $materi->judul }}
        </h5>
    </div>

    {{-- STATUS SELESAI (PINDAH KE ATAS) --}}
    @if($sudahSelesai)
        <div class="alert alert-success py-2 mb-3">
            Materi sudah kamu selesaikan!
        </div>
    @endif

    {{-- META --}}
    <div class="mb-3 small text-muted d-flex flex-wrap gap-2">
        <span class="badge bg-info">
            {{ $materi->kelas->nama_kelas ?? '-' }}
        </span>
        <span class="badge bg-primary">
            {{ $materi->mapel->nama_mapel ?? '-' }}
        </span>
        <span class="badge bg-light text-dark border">
            {{ $materi->guru->name ?? '-' }}
        </span>
        <span class="badge bg-secondary">
            {{ $materi->created_at->format('d M Y') }}
        </span>
    </div>

    <div class="row g-3">

        {{-- KONTEN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-gradient-primary text-white py-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-book-open me-1"></i>Isi Materi
                    </h6>
                </div>

                <div class="card-body p-3">

                    {{-- ISI --}}
                    <div class="materi-content mb-3">
                        {!! nl2br(e($materi->deskripsi)) !!}
                    </div>

                    {{-- TOMBOL SELESAI (FIX POSISI DI BAWAH) --}}
                    @if(!$sudahSelesai)
                        <form action="{{ route('siswa.materi.selesai', $materi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                Tandai Sudah Dipelajari
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:15px;">

                <div class="card-body p-3">

                    {{-- DOWNLOAD --}}
                    @if($materi->file_url)
                        <a href="{{ Storage::url($materi->file_url) }}"
                           class="btn btn-primary w-100 mb-2"
                           download>
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                        <small class="text-muted d-block text-center mb-3">
                            PDF / DOC
                        </small>
                    @else
                        <div class="text-center py-3">
                            <small class="text-muted">Tidak ada file</small>
                        </div>
                    @endif

                    {{-- QUICK MENU --}}
                    <div class="list-group list-group-flush">
                        <a href="{{ route('siswa.materi') }}"
                           class="list-group-item list-group-item-action px-2 py-2 border-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Semua Materi
                        </a>

                        @if($materi->file_url)
                        <a href="{{ Storage::url($materi->file_url) }}"
                           class="list-group-item list-group-item-action px-2 py-2 border-0"
                           download>
                            <i class="fas fa-download me-2 text-success"></i>
                            Download Lagi
                        </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.materi-content {
    font-size: 0.95rem;
    line-height: 1.6;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
}
</style>
@endpush