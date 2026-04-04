@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row g-4">
    {{-- BAGIAN KIRI: INFORMASI UTAMA & STATISTIK --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg overflow-hidden mb-4 rounded-4">
            <div class="card-header bg-gradient-primary text-white p-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-1 shadow-sm me-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-graduate fa-3x text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0 text-white">{{ auth()->user()->name }}</h3>
                        <p class="mb-0 opacity-75 fs-6">Siswa Kelas {{ $kelas->nama_kelas ?? '-' }}</p>
                    </div>
                    <div class="ms-auto text-end">
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-medal me-1"></i> Level {{ $level }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light rounded-4 p-4 h-100 text-center hover-lift">
                            <div class="bg-success bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-check-double fa-2x text-success"></i>
                            </div>
                            <h2 class="fw-bold mb-0 text-dark">{{ $tantanganSelesai }}</h2>
                            <p class="text-muted small text-uppercase fw-bold mb-0">Tantangan Selesai</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light rounded-4 p-4 h-100 text-center hover-lift text-white" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important;">
                            <div class="bg-white bg-opacity-25 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-star fa-2x text-white"></i>
                            </div>
                            <h2 class="fw-bold mb-0 text-white">{{ number_format($totalPoin) }}</h2>
                            <p class="small text-uppercase fw-bold mb-0 opacity-90">Total XP Points</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PROGRES LEVEL --}}
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary mb-0"><i class="fas fa-stream me-2"></i>Roadmap Leveling</h5>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="row row-cols-2 row-cols-md-5 g-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="col text-center">
                            <div class="p-3 rounded-4 {{ $level >= $i ? 'bg-success text-white' : 'bg-light text-muted opacity-50' }} border shadow-sm position-relative">
                                @if($level >= $i)
                                    <i class="fas fa-check-circle position-absolute top-0 end-0 m-2 text-white small"></i>
                                @else
                                    <i class="fas fa-lock position-absolute top-0 end-0 m-2 text-muted small"></i>
                                @endif
                                <h6 class="fw-bold mb-1 small">LVL {{ $i }}</h6>
                                <i class="fas {{ $i == 5 ? 'fa-trophy' : 'fa-bolt' }} mb-0"></i>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: PERINGKAT & BADGES --}}
    <div class="col-lg-4">
        {{-- RANK CARD --}}
        <div class="card border-0 shadow-lg rounded-4 mb-4 bg-gradient-success text-white">
            <div class="card-body p-4 text-center">
                <h6 class="text-uppercase fw-bold opacity-75 small mb-3">Peringkat Kelas</h6>
                @if($rank)
                    <div class="display-3 fw-bold mb-0">#{{ $rank }}</div>
                    <p class="mb-0 fw-light">Di kelas {{ $kelas->nama_kelas ?? '-' }}</p>
                @else
                    <p class="mb-0">Belum Ada Peringkat</p>
                @endif
            </div>
        </div>

        {{-- BADGES CARD --}}
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary mb-0"><i class="fas fa-award me-2"></i>Koleksi Badge</h5>
            </div>
            <div class="card-body p-4 pt-2">
                @php
                    $badges = \App\Models\SiswaBadge::with('badge')
                                ->where('siswa_id', auth()->id())
                                ->get()
                                ->groupBy('badge_id');
                @endphp

                <div class="row g-3">
                    @forelse($badges as $badgeId => $group)
                        @php $badge = $group->first()->badge; @endphp
                        <div class="col-6 text-center">
                            <div class="card h-100 border-0 bg-light p-3 rounded-4 shadow-sm hover-lift">
                                <img src="{{ asset('storage/badges/' . $badge->icon) }}" 
                                     class="mx-auto mb-2 badge-icon" 
                                     style="width:50px; height:50px; object-fit:contain;"
                                     alt="Badge">
                                <p class="small fw-bold mb-1 text-dark lh-sm">{{ $badge->nama_badge }}</p>
                                <span class="badge bg-primary rounded-pill w-auto mx-auto small">x{{ $group->count() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-ghost fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted small mb-0">Belum ada badge yang diraih</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
    
    .card, .hover-lift {
        border-radius: 1.25rem !important;
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .badge-icon {
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
    }

    .display-3 {
        letter-spacing: -2px;
    }
</style>
@endpush