@extends('layouts.app')
@section('title', $materi->judul . ' - Materi Pelajaran')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-md-8">
            <a href="{{ route('siswa.materi') }}" 
               class="btn btn-outline-secondary btn-lg px-4 mb-3 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Materi
            </a>
        </div>
        <div class="col-md-4 text-md-end">
            <h1 class="h2 fw-bold mb-2 text-primary">{{ $materi->judul }}</h1>
        </div>
    </div>

    {{-- Metadata --}}
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 small text-muted">
                <span class="badge bg-info fs-6 px-3 py-2">
                    <i class="fas fa-graduation-cap me-1"></i>
                    {{ $materi->kelas->nama_kelas ?? 'Semua Kelas' }}
                </span>
                <span class="badge bg-primary fs-6 px-3 py-2">
                    {{ $materi->mapel->nama_mapel ?? $materi->mapel->nama ?? 'Mata Pelajaran' }}
                </span>
                <span class="badge bg-light text-dark border fs-6 px-3 py-2">
                    <i class="fas fa-user me-1"></i>{{ $materi->guru->name ?? 'Guru' }}
                </span>
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $materi->created_at->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Konten Utama --}}
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="mb-0 fw-bold">
                        <i class="fas fa-book-open me-2"></i>Isi Materi
                    </h3>
                </div>
<div class="card-body p-5">
    <div class="materi-content lh-lg">
        {!! nl2br(e($materi->deskripsi)) !!}
    </div>

    {{-- ========================= --}}
    {{-- TOMBOL SELESAI --}}
    {{-- ========================= --}}
    <div class="mt-5">

        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(!$sudahSelesai)
            <form action="{{ route('siswa.materi.selesai', $materi->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg px-4 shadow">
                    ✅ Saya Sudah Mempelajari Materi
                </button>
            </form>
        @else
            <div class="alert alert-success d-flex align-items-center shadow-sm">
                🎉 <span class="ms-2">Materi sudah kamu selesaikan!</span>
            </div>
        @endif

    </div>
</div>
            </div>
        </div>

        {{-- File Download & Info --}}
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 h-100 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    @if($materi->file_url)
                        {{-- Download Button --}}
                        <div class="mb-4">
                            <a href="{{ Storage::url($materi->file_url) }}" 
                               class="btn btn-primary btn-lg w-100 shadow-lg mb-3" 
                               download>
                                <i class="fas fa-file-download me-2"></i>
                                Download Materi
                            </a>
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                    File PDF/DOC • Aman untuk diunduh
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">Tidak ada file terlampir</p>
                        </div>
                    @endif

                    {{-- Quick Actions --}}
                    <div class="list-group list-group-flush">
                        <a href="{{ route('siswa.materi') }}" 
                           class="list-group-item list-group-item-action px-3 border-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Lihat Semua Materi
                        </a>
                        @if($materi->file_url)
                        <a href="{{ Storage::url($materi->file_url) }}" 
                           class="list-group-item list-group-item-action px-3 border-0" download>
                            <i class="fas fa-cloud-download-alt me-2 text-success"></i>
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
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2c3e50;
}

.materi-content p {
    margin-bottom: 1.2rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hover-lift:hover {
    transform: translateY(-2px) !important;
}

.sticky-top {
    position: sticky;
    top: 20px;
}
</style>
@endpush
