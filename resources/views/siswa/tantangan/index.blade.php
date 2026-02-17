@extends('layouts.app')

@section('title', 'Tantangan Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold">
        <i class="fas fa-dice-d20 me-2 text-warning"></i> Tantangan Aktif
    </h2>
    <div class="text-muted">
        <i class="fas fa-clock me-1"></i> {{ now()->format('d M Y H:i') }}
    </div>
</div>

{{-- STATS HEADER --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-gradient-warning text-white border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-1">{{ $tantangans->where('batas_waktu', '>', now())->count() }}</h3>
                <small>Aktif</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-danger text-white border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-1">{{ $tantangans->where('batas_waktu', '<=', now())->count() }}</h3>
                <small>Tertunda</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-success text-white border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-1">
                    {{-- ✅ FIX: Gunakan whereHas di controller, di sini cek relation langsung --}}
                    {{ $tantangans->filter(fn($t) => $t->nilaiTantangan && $t->nilaiTantangan->count() > 0)->count() }}
                </h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-tasks fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-1">{{ $tantangans->total() }}</h3>
                <small>Total</small>
            </div>
        </div>
    </div>
</div>

{{-- TANTANGAN LIST --}}
<div class="row g-4">
    @forelse($tantangans as $tantangan)
    <div class="col-xl-4 col-lg-6">
        <div class="card h-100 border-0 shadow-sm hover-lift position-relative">
            {{-- STATUS BADGE --}}
            <div class="position-absolute top-0 end-0 m-3 z-2">
                @if($tantangan->batas_waktu > now())
                    <span class="badge bg-success px-3 py-2 fs-6 fw-bold shadow">
                        <i class="fas fa-clock me-1"></i>
                        {{ $tantangan->batas_waktu->shortRelativeDiffForHumans() }}
                    </span>
                @else
                    <span class="badge bg-danger px-3 py-2 fs-6 fw-bold shadow">
                        <i class="fas fa-clock me-1"></i> Terlambat
                    </span>
                @endif
            </div>

            <div class="card-body p-4 pt-5">
                {{-- HEADER --}}
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-warning bg-opacity-20 text-warning rounded-3 p-3 me-3">
                        <i class="fas fa-dice-d20 fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1">{{ Str::limit($tantangan->judul, 45) }}</h5>
                        <div class="small text-muted mb-1">
                            <i class="fas fa-book me-1"></i>
                            {{ $tantangan->mapel->nama ?? 'Mapel' }}
                        </div>
                        <div class="small text-muted">
                            <i class="fas fa-user me-1"></i>
                            {{ $tantangan->guru->nama ?? 'Guru' }}
                        </div>
                    </div>
                </div>

                {{-- DETAIL --}}
                <div class="mb-3">
                    <div class="row text-muted small g-1 mb-2">
                        <div class="col-6">
                            <i class="fas fa-star text-warning me-1"></i>
                            {{ $tantangan->poin }} Poin
                        </div>
                        <div class="col-6">
                            <i class="fas fa-question-circle text-info me-1"></i>
                            {{ $tantangan->soal_count ?? 0 }} Soal
                        </div>
                    </div>
                    @if($tantangan->deskripsi)
                    <p class="text-muted small mb-0" style="line-height: 1.4;">
                        {{ Str::limit($tantangan->deskripsi, 80) }}
                    </p>
                    @endif
                </div>

                {{-- ✅ FIX: PROGRESS - Pakai relation langsung, bukan whereHas --}}
                @if($tantangan->nilaiTantangan && $tantangan->nilaiTantangan->isNotEmpty())
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar bg-success" 
                         style="width: {{ round($tantangan->nilaiTantangan->first()->total_nilai ?? 0) }}%"></div>
                </div>
                <div class="d-flex justify-content-between small text-success fw-bold mb-3">
                    <span>✅ {{ round($tantangan->nilaiTantangan->first()->total_nilai ?? 0) }}%</span>
                    <span>+{{ $tantangan->nilaiTantangan->first()->poin_didapat ?? 0 }} Poin</span>
                </div>
                @endif
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="card-footer bg-transparent border-0 pt-0 px-4 pb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $tantangan->created_at->diffForHumans() }}
                    </small>
                    
                    {{-- ✅ FIX: Conditional buttons --}}
                    @if($tantangan->nilaiTantangan && $tantangan->nilaiTantangan->isNotEmpty())
                        <span class="badge bg-success px-4 py-2 fw-bold shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> Selesai
                        </span>
                    @elseif($tantangan->batas_waktu > now())
                        <a href="{{ route('siswa.tantangan.kerjakan', $tantangan) }}" 
                           class="btn btn-warning btn-sm px-4 fw-bold shadow-sm">
                            <i class="fas fa-play-circle me-1"></i> Kerjakan
                        </a>
                    @else
                        <span class="badge bg-secondary px-4 py-2 fw-bold shadow-sm">
                            <i class="fas fa-lock me-1"></i> Waktu Habis
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted empty-state">
            <i class="fas fa-dice-d20 fa-4x mb-4 opacity-50"></i>
            <h4 class="mb-3">Belum ada tantangan</h4>
            <p class="mb-4">Tunggu guru membuat tantangan baru untuk kelas Anda.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('siswa.materi') }}" class="btn btn-outline-primary">
                    <i class="fas fa-book me-2"></i> Materi
                </a>
                <a href="{{ route('siswa.leaderboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-trophy me-2"></i> Leaderboard
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if(method_exists($tantangans, 'hasPages') && $tantangans->hasPages())
<div class="d-flex justify-content-center mt-5">
    {{ $tantangans->appends(request()->query())->links() }}
</div>
@endif
@endsection

@push('styles')
<style>
.hover-lift:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.shadow { box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important; }
.bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important; }
.bg-gradient-danger { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%) !important; }
.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
</style>
@endpush
