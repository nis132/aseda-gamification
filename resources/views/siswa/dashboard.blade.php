@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row g-4">
    {{-- TOTAL POIN CARD --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="card-body text-center position-relative p-4">
                <div class="rank-badge bg-warning text-dark px-3 py-1 rounded-pill position-absolute top-0 end-0 me-3 mt-3 shadow">
                    #{{ $rankKelas }}
                </div>
                <i class="fas fa-crown fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1 display-5">{{ number_format($totalPoin) }}</h3>
                <p class="mb-1 fw-semibold fs-6">Total Poin</p>
                <small class="opacity-75">Kelas {{ auth()->user()->kelasIds()->first() ?? '-' }}</small>
            </div>
        </div>
    </div>

    {{-- TANTANGAN AKTIF --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-primary text-white h-100 border-0 shadow-lg">
            <div class="card-body text-center p-4">
                <i class="fas fa-tasks fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1">{{ $tantanganAktif->where('batas_waktu', '>', now())->count() }}</h3>
                <p class="mb-1 fw-semibold fs-6">Tantangan Aktif</p>
                <small class="opacity-75">Buruan kerjakan!</small>
            </div>
        </div>
    </div>

    {{-- ✅ FIX: TANTANGAN SELESAI - whereHas → filter --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-warning text-white h-100 border-0 shadow-lg">
            <div class="card-body text-center p-4">
                <i class="fas fa-star fa-3x mb-3 opacity-75"></i>
                {{-- ✅ FIXED: Collection method untuk relation --}}
                <h3 class="fw-bold mb-1">
                    {{ $tantanganAktif->filter(function($tantangan) {
                        return $tantangan->nilaiTantangan && $tantangan->nilaiTantangan->count() > 0;
                    })->count() }}
                </h3>
                <p class="mb-1 fw-semibold fs-6">Selesai</p>
                <small class="opacity-75">Kerja bagus! ⭐</small>
            </div>
        </div>
    </div>

    {{-- TANTANGAN TERLAMBAT --}}
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-danger text-white h-100 border-0 shadow-lg">
            <div class="card-body text-center p-4">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1">{{ $tantanganAktif->where('batas_waktu', '<=', now())->count() }}</h3>
                <p class="mb-1 fw-semibold fs-6">Terlambat</p>
                <small class="opacity-75">Jangan tertinggal!</small>
            </div>
        </div>
    </div>

    {{-- TANTANGAN TERBARU --}}
    <div class="col-12">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient-primary text-white border-0">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-bolt me-2"></i> Tantangan Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                @forelse($tantanganAktif->take(5) as $tantangan)
                <div class="p-4 border-bottom hover-row">
                    <div class="row align-items-center g-3">
                        <div class="col-md-2 col-3">
                            <div class="bg-primary bg-opacity-20 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                                <i class="fas fa-rocket fa-lg"></i>
                            </div>
                        </div>
                        <div class="col-md-7 col-6">
                            <h6 class="mb-1 fw-bold">{{ Str::limit($tantangan->judul, 50) }}</h6>
                            <div class="row text-muted small g-1 mb-2">
                                <div class="col-6">
                                    <i class="fas fa-book me-1"></i>
                                    {{ $tantangan->mapel->nama ?? '-' }}
                                </div>
                                <div class="col-6">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $tantangan->guru->nama ?? '-' }}
                                </div>
                            </div>
                            <div class="mb-2">
                                <span class="badge bg-info me-1">{{ $tantangan->soal_count ?? 0 }} Soal</span>
                                <span class="badge bg-warning text-dark">+{{ $tantangan->poin }} Poin</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-3 text-end">
                            @if($tantangan->batas_waktu > now())
                            <a href="{{ route('siswa.tantangan.kerjakan', $tantangan) }}" 
                               class="btn btn-sm btn-primary px-3">
                                <i class="fas fa-play me-1"></i> Kerjakan
                            </a>
                            @else
                            <span class="badge bg-danger px-3 py-2 fs-6">Terlambat</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">Belum ada tantangan aktif</h5>
                    <p class="text-muted mb-0">Periksa lagi nanti!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- QUICK LINKS --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-success text-white border-0">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('siswa.materi') }}" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 rounded-3 border-0 shadow-sm hover-lift">
                            <i class="fas fa-book fa-2x mb-2 text-primary"></i>
                            <span class="fw-bold fs-6">Materi</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('siswa.tantangan') }}" class="btn btn-warning btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 rounded-3 border-0 shadow-sm hover-lift">
                            <i class="fas fa-tasks fa-2x mb-2 text-dark"></i>
                            <span class="fw-bold fs-6 text-dark">Tantangan</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('siswa.leaderboard') }}" class="btn btn-outline-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 rounded-3 border-0 shadow-sm hover-lift">
                            <i class="fas fa-trophy fa-2x mb-2 text-info"></i>
                            <span class="fw-bold fs-6">Leaderboard</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PROFIL --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient-info text-white border-0">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-user-circle me-2"></i> Profil Saya
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-primary bg-opacity-20 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ auth()->user()->nama }}</h5>
                    <p class="text-muted mb-0">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 border-bottom py-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-chalkboard me-1"></i>Kelas</span>
                            <span class="fw-bold text-primary">{{ auth()->user()->kelasIds()->first() ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="list-group-item px-0 border-bottom py-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-star me-1"></i>Total Poin</span>
                            <span class="fw-bold text-warning fs-5">{{ number_format($totalPoin) }}</span>
                        </div>
                    </div>
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-trophy me-1"></i>Ranking Kelas</span>
                            <span class="fw-bold text-success fs-6">#{{ $rankKelas }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('siswa.profil') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit me-2"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card, .hover-lift {
    border-radius: 20px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    overflow: hidden;
}
.stat-card:hover, .hover-lift:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 25px 50px rgba(0,0,0,0.2) !important;
}
.rank-badge {
    font-size: 0.85rem;
    font-weight: 700;
}
.hover-row:hover {
    background: linear-gradient(90deg, rgba(0,123,255,0.08), rgba(0,123,255,0.15)) !important;
    border-radius: 12px !important;
    transform: translateX(8px) !important;
    transition: all 0.3s ease !important;
}
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
.bg-gradient-info { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important; }
.avatar-lg { font-size: 2.5rem; }
.display-5 { font-size: 2.5rem; font-weight: 700; }
</style>
@endpush
