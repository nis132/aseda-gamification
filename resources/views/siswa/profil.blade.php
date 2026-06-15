@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

@php
// 8 Level System - setiap level memerlukan 1 materi + 1 tantangan di bab tertentu
$nextLevelInfo = $user->getNextLevelRequirement();
$levelMeta = [
    1  => ['label' => 'Pemula Paripurna',      'icon' => 'fas fa-seedling',     'color' => '#4CAF50'],
    2  => ['label' => 'Pelajar Dasar',         'icon' => 'fas fa-sprout',       'color' => '#8BC34A'],
    3  => ['label' => 'Pelajar Maju',          'icon' => 'fas fa-leaf',         'color' => '#CDDC39'],
    4  => ['label' => 'Pandai',                'icon' => 'fas fa-lightbulb',    'color' => '#FFC107'],
    5  => ['label' => 'Pandai Luar Biasa',     'icon' => 'fas fa-star',         'color' => '#FF9800'],
    6  => ['label' => 'Cendekia',              'icon' => 'fas fa-brain',        'color' => '#FF5722'],
    7  => ['label' => 'Cendekia Unggul',       'icon' => 'fas fa-bolt',         'color' => '#E91E63'],
    8  => ['label' => 'Mahir Mastery',         'icon' => 'fas fa-fire',         'color' => '#9C27B0'],
];

$lm = $levelMeta[$level];
@endphp

{{-- PROFILE CARD --}}
<div class="card mb-4" style="overflow: hidden;">

    {{-- COVER --}}
    <div style="height: 100px; background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);"></div>

    <div class="card-body px-4 pb-4">
        <div class="row align-items-center">

            {{-- AVATAR + INFO --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4 flex-wrap">

                    <div style="margin-top: -64px;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                             style="width: 100px; height: 100px; font-size: 2.2rem;
                                    background: linear-gradient(135deg, var(--clr-primary), #7c3aed);
                                    border: 4px solid #fff; box-shadow: var(--shadow-sm);">
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h2 class="page-title mb-0">{{ $user->nama }}</h2>
                            <span class="badge rounded-pill px-3 py-2"
                                  style="background: var(--clr-primary-light); color: var(--clr-primary); font-size: 0.8rem;">
                                Level {{ $level }}
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--txt-secondary);">
                            <i class="fas fa-school me-2"></i>Kelas {{ $kelas->nama_kelas ?? '-' }}
                        </div>
                        @if($user->nis)
                        <div style="font-size: 0.85rem; color: var(--txt-secondary); margin-top: 0.2rem;">
                            <i class="fas fa-id-card me-2"></i>NIS {{ $user->nis }}
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- LEVEL CARD --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="p-3 rounded-3" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                    <div class="text-label mb-2">Status Level</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number" style="color: var(--clr-primary);">{{ $level }}</div>
                            <div class="fw-semibold" style="color: var(--clr-primary); font-size: 0.85rem;">{{ $lm['label'] }}</div>
                        </div>
                        <div class="stat-icon stat-icon-primary" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <i class="{{ $lm['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- STATISTIK --}}
<div class="row g-4 mb-4">

    <div class="col-lg-4 col-md-6">
        <div class="card card-stat">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-primary" style="width: 50px; height: 50px; font-size: 1.2rem;">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalPoin) }}</div>
                    <div class="text-label">Total XP</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card card-stat">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-info" style="width: 50px; height: 50px; font-size: 1.2rem;">
                    <i class="fas fa-tasks"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $tantanganSelesai }}</div>
                    <div class="text-label">Tantangan Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card card-stat">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-warning" style="width: 50px; height: 50px; font-size: 1.2rem;">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <div class="stat-number">#{{ $rank ?? '-' }}</div>
                    <div class="text-label">Rank Kelas</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- PROGRESS LEVEL --}}
<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 class="fw-bold mb-0" style="color: var(--txt-primary);">
                <i class="fas fa-chart-line me-2" style="color: var(--clr-primary);"></i>Progress Level
            </h6>
            <small style="color: var(--txt-secondary);">
                @if($level < 8)
                    <i class="fas fa-info-circle me-1"></i>
                    Selesaikan <strong>{{ $nextLevelInfo['materiNeeded'] }} Materi</strong> & 
                    <strong>{{ $nextLevelInfo['tantanganNeeded'] }} Tantangan</strong> untuk naik ke Level {{ $nextLevelInfo['nextLevel'] }}
                @else
                    <i class="fas fa-star me-1"></i>Level Maksimal - Anda telah mencapai puncak! 
                @endif
            </small>
        </div>

        {{-- STEP INDICATOR (8 LEVEL) --}}
        <div class="d-flex gap-1 flex-wrap" style="max-width: 220px;">
            @for($i = 1; $i <= 8; $i++)
            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                 style="width: 32px; height: 32px; font-size: 0.75rem;
                        {{ $i <= $level
                            ? 'background: ' . ($levelMeta[$i]['color'] ?? '#4CAF50') . '; color: #fff;'
                            : 'background: var(--bg-muted); color: var(--txt-tertiary); border: 1px solid var(--border-color);' }}
                        transition: all 0.3s ease;
                        cursor: default;"
                 title="Level {{ $i }}">
                {{ $i }}
            </div>
            @endfor
        </div>
    </div>

    <div class="card-body p-4">

        {{-- CURRENT LEVEL INFO --}}
        @if($level < 8)
        <div class="alert alert-info mb-4" style="border-radius: 12px; border-left: 4px solid var(--clr-primary);">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fas fa-lightbulb fa-2x"></i>
                </div>
                <div class="col">
                    <strong>🚀 Cara Naik Level (SULIT):</strong><br>
                    <small>Selesaikan <strong>minimal 3 tantangan</strong> di setiap bab untuk naik ke level berikutnya! Semakin banyak tantangan = semakin kuat! 💪</small>
                </div>
            </div>
        </div>
        @endif

        {{-- TANTANGAN PROGRESS (Yang penting untuk level up) --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold" style="font-size: 0.875rem; color: var(--txt-primary);">
                    <i class="fas fa-gamepad me-2" style="color: #FF9800;"></i>Tantangan Selesai (Untuk Level Up)
                </div>
                <div class="fw-bold" style="font-size: 0.875rem; color: #FF9800;">
                    {{ $nextLevelInfo['tantanganSelesai'] }} / 3 (minimal)
                </div>
            </div>
            <div class="progress rounded-pill" style="height: 14px; background: #FFE0B2;">
                <div class="progress-bar rounded-pill"
                     style="width: {{ $nextLevelInfo['tantanganSelesai'] >= 3 ? 100 : (($nextLevelInfo['tantanganSelesai'] / 3) * 100) }}%; background: #FF9800; transition: width 0.4s ease;">
                </div>
            </div>
            @if($nextLevelInfo['tantanganSelesai'] < 3)
                <small class="text-muted d-block mt-1">
                    ⏳ Kurang {{ 3 - $nextLevelInfo['tantanganSelesai'] }} tantangan lagi untuk naik ke level {{ $nextLevelInfo['nextLevel'] }}!
                </small>
            @else
                <small class="text-success d-block mt-1">
                    ✅ Siap naik level! Selesaikan tantangan bab berikutnya untuk lanjut.
                </small>
            @endif
        </div>

        {{-- LEVEL DESCRIPTION --}}
        <div style="padding: 12px 16px; background: var(--bg-muted); border-radius: 8px; border-left: 4px solid var(--clr-primary);">
            <div class="fw-bold mb-1" style="color: var(--clr-primary);">
                <i class="{{ $lm['icon'] }} me-2"></i>Level {{ $level }} - {{ $lm['label'] }}
            </div>
            <small style="color: var(--txt-secondary);">
                @if($level == 1)
                    Anda baru memulai perjalanan belajar. Selesaikan tantangan untuk naik level! 🚀
                @elseif($level == 6)
                    Luar biasa! Anda sudah mencapai pertengahan jalan menuju kesuksesan akademik. 🌟
                @elseif($level == 8)
                    Anda adalah Legend Master! Pencapaian tertinggi telah diraih. 👑
                @else
                    Terus semangat dalam belajar! Level {{ $level }} - pertahankan momentum ini! 💪
                @endif
            </small>
        </div>

    </div>
</div>

@endsection