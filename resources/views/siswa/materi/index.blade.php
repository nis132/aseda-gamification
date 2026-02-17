@extends('layouts.app')
@section('title', 'Materi Pelajaran - Siswa')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold mb-1 text-primary">
                        <i class="fas fa-book me-2"></i>Materi Pelajaran
                    </h1>
                    @if($kelasId)
                        <p class="text-muted mb-0">
                            Kelas {{ $kelasId }} • {{ $materis->total() }} materi tersedia
                        </p>
                    @else
                        <p class="text-muted mb-0">Semua materi dari semua guru</p>
                    @endif
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        {{ $materis->total() }} Materi
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Materi Cards --}}
    <div class="row g-4">
        @forelse($materis as $item)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100 shadow-sm border-0 hover-lift position-relative overflow-hidden">
                    {{-- File Badge --}}
                    @if($item->file_url)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-success rounded-pill shadow">
                            <i class="fas fa-file-pdf me-1"></i>PDF
                        </span>
                    @endif

                    <div class="card-body p-4 d-flex flex-column">
                        {{-- Action Dropdown --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title fw-bold text-dark mb-1 lh-sm">
                                    {{ Str::limit($item->judul, 55) }}
                                </h5>
                                <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 90) }}</p>
                            </div>
                            <div class="dropdown dropstart">
                                <button class="btn btn-sm p-1 text-muted" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v fs-6"></i>
                                </button>
                                <ul class="dropdown-menu shadow-sm">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('siswa.materi.show', $item) }}">
                                            <i class="fas fa-eye me-2 text-primary"></i>Baca Materi
                                        </a>
                                    </li>
                                    @if($item->file_url)
                                    <li>
                                        <a class="dropdown-item" href="{{ Storage::url($item->file_url) }}" download>
                                            <i class="fas fa-download me-2 text-success"></i>Download File
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        {{-- Badges --}}
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="badge bg-info">
                                {{ $item->kelas->nama_kelas ?? 'Semua Kelas' }}
                            </span>
                            <span class="badge bg-primary">
                                {{ $item->mapel->nama_mapel ?? $item->mapel->nama ?? 'Mapel' }}
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-user me-1"></i>{{ $item->guru->name ?? 'Guru' }}
                            </span>
                        </div>

                        {{-- Footer --}}
                        <div class="mt-auto">
                            <small class="text-muted d-flex align-items-center">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $item->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-10 bg-light rounded-4 border-dashed border-2">
                    <i class="fas fa-book-open fa-4x text-muted mb-4 opacity-75"></i>
                    <h3 class="h3 fw-bold text-muted mb-3">Belum ada materi</h3>
                    <p class="lead text-muted mb-4">
                        Guru belum mengunggah materi untuk kelas ini. 
                        Silakan cek lagi nanti.
                    </p>
                    <div class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Materi akan muncul setelah guru upload
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($materis->hasPages())
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            {{ $materis->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.card {
    transition: all 0.3s ease;
}
.badge {
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
