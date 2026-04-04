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
        <div class="card bg-gradient-warning text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-0">{{ $tantangans->where('batas_waktu', '>', now())->count() }}</h3>
                <small class="text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 1px;">Aktif</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-danger text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fas fa-exclamation-triangle fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-0">{{ $tantangans->where('batas_waktu', '<=', now())->count() }}</h3>
                <small class="text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 1px;">Tertunda</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-gradient-success text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fas fa-check-circle fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-0">
                    {{ $tantangans->filter(fn($t) => $t->nilaiTantangan && $t->nilaiTantangan->count() > 0)->count() }}
                </h3>
                <small class="text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 1px;">Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fas fa-tasks fa-2x mb-2 opacity-75"></i>
                <h3 class="fw-bold mb-0">{{ $tantangans->total() }}</h3>
                <small class="text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 1px;">Total</small>
            </div>
        </div>
    </div>
</div>

{{-- TANTANGAN LIST --}}
<div class="row g-4">
    @forelse($tantangans as $tantangan)
    <div class="col-xl-4 col-lg-6">
        <div class="card h-100 border-0 shadow-sm hover-lift overflow-hidden">
            <div class="card-body p-4">
                {{-- TOP SECTION: ICON & STATUS --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="fas fa-dice-d20 fa-lg"></i>
                    </div>
                    @if($tantangan->batas_waktu > now())
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                            <i class="fas fa-history me-1"></i> {{ $tantangan->batas_waktu->diffForHumans(null, true) }}
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2 shadow-sm">
                            <i class="fas fa-exclamation-circle me-1"></i> Terlambat
                        </span>
                    @endif
                </div>

                {{-- TITLE --}}
                <div class="mb-3">
                    <h5 class="fw-bold text-dark mb-1" style="line-height: 1.5;">{{ Str::limit($tantangan->judul, 45) }}</h5>
                    <small class="text-muted">ID: #T-{{ $tantangan->id }}</small>
                </div>

                {{-- INFO BOXES (Mencegah Teks Menumpuk) --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2 border rounded-3 bg-light" style="min-height: 65px;">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">Mata Pelajaran</small>
                            <div class="fw-bold small text-truncate">
                                <i class="fas fa-book text-primary me-1"></i> {{ $tantangan->mapel->nama ?? 'Umum' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded-3 bg-light" style="min-height: 65px;">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">Guru Pengampu</small>
                            <div class="fw-bold small text-truncate">
                                <i class="fas fa-user-tie text-info me-1"></i> {{ $tantangan->guru->nama ?? 'Guru' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- POIN & SOAL --}}
                <div class="d-flex gap-3 mb-3">
                    <div class="small"><i class="fas fa-star text-warning me-1"></i> <strong>{{ $tantangan->poin }}</strong> Poin</div>
                    <div class="small"><i class="fas fa-clipboard-list text-muted me-1"></i> <strong>{{ $tantangan->soal_count ?? 0 }}</strong> Soal</div>
                </div>

                {{-- PROGRESS --}}
                @if($tantangan->nilaiTantangan && $tantangan->nilaiTantangan->isNotEmpty())
                    <div class="pt-2 border-top">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-success fw-bold">Skor: {{ round($tantangan->nilaiTantangan->first()->total_nilai) }}%</span>
                            <span class="text-muted">+{{ $tantangan->nilaiTantangan->first()->poin_didapat }} Poin</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $tantangan->nilaiTantangan->first()->total_nilai }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                @if($tantangans->filter(fn($t) => $t->id == $tantangan->id && $t->nilaiTantangan->isNotEmpty())->count() > 0)
                    <button class="btn btn-outline-success w-100 disabled fw-bold">
                        <i class="fas fa-check-circle me-2"></i> Sudah Dikerjakan
                    </button>
                @elseif($tantangan->batas_waktu > now())
                    <a href="{{ route('siswa.tantangan.kerjakan', $tantangan) }}" class="btn btn-warning w-100 fw-bold shadow-sm">
                        <i class="fas fa-play me-2"></i> Kerjakan Sekarang
                    </a>
                @else
                    <button class="btn btn-secondary w-100 disabled fw-bold opacity-50">
                        <i class="fas fa-lock me-2"></i> Sudah Berakhir
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <img src="https://illustrations.popsy.co/white/surfing-duck.svg" style="width: 200px;" alt="Empty">
        <h5 class="text-muted mt-3">Belum ada tantangan aktif untukmu.</h5>
    </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if($tantangans->hasPages())
<div class="d-flex justify-content-center mt-5 pagination-sm">
    {!! $tantangans->appends(request()->query())->links('pagination::bootstrap-5') !!}
</div>
@endif

@endsection

@push('styles')
<style>
    .bg-gradient-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important; }
    .bg-gradient-danger { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%) !important; }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
    
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; }

    /* Fix teks menumpuk di pagination */
    .pagination { gap: 5px; }
    .page-link { border-radius: 8px !important; border: none; background: #fff; color: #667eea; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background: #667eea; color: white; }
</style>
@endpush