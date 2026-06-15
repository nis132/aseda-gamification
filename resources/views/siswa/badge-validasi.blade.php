@extends('layouts.app')

@section('title', 'Badge Saya')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     HERO BANNER
══════════════════════════════════════════════════════════════ --}}
<div class="card mb-4" style="overflow:hidden;">
    <div style="height:72px;background:linear-gradient(135deg,var(--clr-primary) 0%,#7c3aed 100%);"></div>
    <div class="card-body px-4 pb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            {{-- Avatar + judul --}}
            <div class="d-flex align-items-center gap-3">
                <div style="margin-top:-48px;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                         style="width:80px;height:80px;
                                background:linear-gradient(135deg,var(--clr-primary),#7c3aed);
                                border:4px solid var(--bg-card,#fff);
                                box-shadow:var(--shadow-sm);
                                font-size:1.75rem;color:#fff;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <div>
                    <h2 class="page-title mb-0">Badge Saya</h2>
                    <p class="mb-0 mt-1" style="font-size:0.82rem;color:var(--txt-secondary);">
                        Kumpulkan badge dengan naik level &amp; kuasai mata pelajaran
                    </p>
                </div>
            </div>

            {{-- Stat chips --}}
            <div class="d-flex gap-2 flex-wrap">
                <div class="card card-stat text-center" style="min-width:100px;">
                    <div class="card-body py-2 px-3">
                        <div class="stat-number" style="color:var(--clr-primary);font-size:1.5rem;">
                            {{ $badgeDimiliki->groupBy('badge_id')->count() }}
                        </div>
                        <div class="text-label">Badge Diraih</div>
                    </div>
                </div>
                <div class="card card-stat text-center" style="min-width:100px;">
                    <div class="card-body py-2 px-3">
                        <div class="stat-number" style="color:var(--clr-warning);font-size:1.5rem;">
                            {{ $levelSiswa }}
                        </div>
                        <div class="text-label">Level Saat Ini</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     BADGE YANG DIRAIH
══════════════════════════════════════════════════════════════ --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
                <i class="fas fa-award me-2" style="color:var(--clr-warning);"></i>Badge yang Diraih
            </h6>
            <small style="color:var(--txt-secondary);">Badge yang berhasil kamu kumpulkan</small>
        </div>
        <span class="badge rounded-pill px-3 py-2"
              style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.78rem;">
            {{ $badgeDimiliki->groupBy('badge_id')->count() }} Badge
        </span>
    </div>

    <div class="card-body p-4">
        @if($badgeDimiliki->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-lock"></i></div>
                <h6>Belum Ada Badge</h6>
                <p>Selesaikan tantangan untuk naik level dan mendapatkan badge pertamamu.</p>
            </div>
        @else
        <div class="row g-3">
            @foreach($badgeDimiliki->groupBy('badge_id') as $badgeId => $group)
            @php
                $badge      = $group->first()->badge;
                $style      = $badge
                    ? $badge->styleConfig()
                    : ['bg'=>'#f1f5f9','text'=>'#64748b','icon'=>'fa-medal','label'=>'Badge'];
                $tglDiraih  = \Carbon\Carbon::parse($group->first()->diterima_pada)->isoFormat('D MMM YYYY');
            @endphp
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card hover-lift h-100" style="overflow:hidden;text-align:center;border-top:3px solid {{ $style['text'] }};">

                    {{-- Bagian atas berwarna --}}
                    <div class="position-relative py-4" style="background:{{ $style['bg'] }};">

                        {{-- Badge duplikat --}}
                        @if($group->count() > 1)
                        <span class="position-absolute top-0 end-0 m-2 px-2 py-1 rounded-pill"
                              style="background:var(--clr-primary);color:#fff;font-size:0.65rem;font-weight:700;">
                            ×{{ $group->count() }}
                        </span>
                        @endif

                        {{-- Gambar badge --}}
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle"
                             style="width:80px;height:80px;background:#fff;box-shadow:var(--shadow-sm);overflow:hidden;">
                            <img src="{{ asset('storage/images/' . ($badge->icon ?? '')) }}"
                                 alt="{{ $badge->nama_badge ?? 'Badge' }}"
                                 style="width:56px;height:56px;object-fit:contain;"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <i class="fas {{ $style['icon'] }}"
                               style="display:none;font-size:1.75rem;color:{{ $style['text'] }};"></i>
                        </div>

                        {{-- Label level --}}
                        <div class="mt-2">
                            <span class="badge rounded-pill"
                                  style="background:{{ $style['text'] }}20;color:{{ $style['text'] }};font-size:0.65rem;font-weight:600;">
                                {{ $style['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Body teks --}}
                    <div class="card-body px-3 py-3 d-flex flex-column">
                        <h6 class="fw-bold mb-1" style="font-size:0.875rem;color:var(--txt-primary);">
                            {{ $badge->nama_badge ?? '-' }}
                        </h6>
                        <p style="font-size:0.75rem;color:var(--txt-secondary);line-height:1.5;flex-grow:1;margin-bottom:0.75rem;">
                            {{ $badge->deskripsi ?? '-' }}
                        </p>

                        {{-- Tanggal diraih --}}
                        <div class="mb-2">
                            <span style="font-size:0.7rem;color:var(--txt-tertiary);">
                                <i class="fas fa-calendar-check me-1" style="color:var(--clr-success);"></i>
                                {{ $tglDiraih }}
                            </span>
                        </div>

                        {{-- Tombol sertifikat --}}
                        @if($badge && $badge->ada_sertifikat)
                        <a href="{{ route('badge.sertifikat', $badge->id) }}"
                           class="btn btn-warning btn-sm rounded-pill fw-bold w-100"
                           style="font-size:0.72rem;">
                            <i class="fas fa-certificate me-1"></i>Lihat Sertifikat
                        </a>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     TARGET BADGE BERIKUTNYA
══════════════════════════════════════════════════════════════ --}}
@php
    $allBadgesLevel = \App\Models\Badge::where('tipe_syarat', 'level')->orderBy('level_required')->get();
    $ownedIds       = $badgeDimiliki->pluck('badge_id')->unique()->toArray();
    $badgeTarget    = $allBadgesLevel->first(fn($b) => !in_array($b->id, $ownedIds));

    $syaratMap = [
        2 => ['tantangan'=>3],
        3 => ['tantangan'=>6],
        4 => ['tantangan'=>9],
        5 => ['tantangan'=>12],
    ];
    $tanSelesai = \App\Models\NilaiTantangan::where('siswa_id', auth()->id())->count();
@endphp

@if($badgeTarget)
@php
    $styleTarget = $badgeTarget->styleConfig();
    $nextLevel   = $badgeTarget->level_required;
    $pct         = min(100, $nextLevel > 0 ? round(($levelSiswa / $nextLevel) * 100) : 0);
    $syaratNext  = $syaratMap[$nextLevel] ?? null;
@endphp
<div class="card mb-4">
    <div class="card-header">
        <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
            <i class="fas fa-bullseye me-2" style="color:var(--clr-primary);"></i>Target Badge Berikutnya
        </h6>
    </div>
    <div class="card-body p-4">

        {{-- Badge target —— gambar + info + progress --}}
        <div class="d-flex align-items-center gap-4 flex-wrap mb-4">

            {{-- Gambar greyscale --}}
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                 style="width:76px;height:76px;
                        background:{{ $styleTarget['bg'] }};
                        border:3px solid {{ $styleTarget['text'] }}33;
                        overflow:hidden;
                        filter:grayscale(0.5);opacity:0.75;">
                <img src="{{ asset('storage/images/' . $badgeTarget->icon) }}"
                     alt="{{ $badgeTarget->nama_badge }}"
                     style="width:52px;height:52px;object-fit:contain;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <i class="fas {{ $styleTarget['icon'] }}"
                   style="display:none;font-size:1.75rem;color:{{ $styleTarget['text'] }};"></i>
            </div>

            <div class="flex-grow-1" style="min-width:180px;">
                <div class="d-flex justify-content-between align-items-start mb-1 flex-wrap gap-1">
                    <div>
                        <span class="fw-bold" style="font-size:0.95rem;color:var(--txt-primary);">
                            {{ $badgeTarget->nama_badge }}
                        </span>
                        <span class="badge ms-2 rounded-pill"
                              style="background:{{ $styleTarget['bg'] }};color:{{ $styleTarget['text'] }};font-size:0.65rem;">
                            {{ $styleTarget['label'] }}
                        </span>
                    </div>
                    <span style="font-size:0.78rem;color:var(--txt-secondary);white-space:nowrap;">
                        Level {{ $levelSiswa }} / {{ $nextLevel }}
                    </span>
                </div>
                <p style="font-size:0.78rem;color:var(--txt-secondary);margin-bottom:0.6rem;line-height:1.5;">
                    {{ $badgeTarget->deskripsi }}
                </p>
                <div class="progress rounded-pill" style="height:10px;background:var(--bg-muted);">
                    <div class="progress-bar rounded-pill"
                         style="width:{{ $pct }}%;background:{{ $styleTarget['text'] }};transition:width 0.5s;">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:0.7rem;color:var(--txt-tertiary);">
                    <span>Level saat ini: <strong style="color:var(--txt-primary);">{{ $levelSiswa }}</strong></span>
                    <span>Target: <strong style="color:var(--txt-primary);">Level {{ $nextLevel }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Syarat detail --}}
        @if($syaratNext)
        <div class="p-3 rounded-3" style="background:var(--bg-muted);border:1px solid var(--border-color);">
            <div class="fw-bold mb-3" style="font-size:0.8rem;color:var(--txt-primary);">
                <i class="fas fa-tasks me-2" style="color:var(--clr-primary);"></i>
                Syarat Naik ke Level {{ $nextLevel }}
            </div>
<div class="row g-3">
    <div class="col-12">
        <div class="d-flex align-items-center gap-2 mb-1">
            <div class="stat-icon stat-icon-warning" style="width:32px;height:32px;font-size:0.85rem;flex-shrink:0;">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="flex-grow-1">
                <div style="font-size:0.78rem;color:var(--txt-secondary);">
                    Tantangan Selesai
                </div>
                <div style="font-size:0.82rem;font-weight:700;color:var(--txt-primary);">
                    {{ $tanSelesai }}
                    <span style="font-weight:400;color:var(--txt-tertiary);">/ {{ $syaratNext['tantangan'] }}</span>
                </div>
            </div>
            @if($tanSelesai >= $syaratNext['tantangan'])
            <i class="fas fa-check-circle" style="color:var(--clr-success);font-size:1.1rem;"></i>
            @endif
        </div>
        <div class="progress rounded-pill" style="height:6px;background:var(--border-color);">
            <div class="progress-bar bg-warning rounded-pill"
                 style="width:{{ min(100, $syaratNext['tantangan']>0 ? round(($tanSelesai/$syaratNext['tantangan'])*100) : 0) }}%;transition:width 0.5s;">
            </div>
        </div>
        <div class="mt-2" style="font-size:0.72rem;color:var(--txt-tertiary);">
            Selesaikan minimal 3 tantangan per BAB untuk naik level
        </div>
    </div>
</div>
        </div>
        @else
        <div class="p-3 rounded-3 text-center" style="background:#d1fae5;border:1px solid #6ee7b7;">
            <i class="fas fa-check-circle me-2" style="color:var(--clr-success);font-size:1.1rem;"></i>
            <span style="font-size:0.85rem;font-weight:600;color:#065f46;">Kamu sudah di level tertinggi!</span>
        </div>
        @endif

    </div>
</div>
@endif

@php
    $badgePenguasa  = \App\Models\Badge::where('tipe_syarat', 'semua_mapel')->first();
    $stylePenguasa  = $badgePenguasa ? $badgePenguasa->styleConfig() : ['bg'=>'#f1f5f9','text'=>'#64748b','icon'=>'fa-map-marked-alt','label'=>'Spesial'];
    $penguasaDiraih = $badgePenguasa
        ? $badgeDimiliki->where('badge_id', $badgePenguasa->id)->count()
        : 0;
    $totalMapel     = count($progressMapel);
    $mapelSelesai   = collect($progressMapel)->filter(fn($pm) => $pm['total'] > 0 && $pm['selesai'] >= $pm['total'])->count();
@endphp

@if($badgePenguasa)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
                <i class="fas fa-map-marked-alt me-2" style="color:var(--clr-primary);"></i>Badge Penguasa Mapel
            </h6>
            <small style="color:var(--txt-secondary);">Selesaikan SEMUA tantangan di satu mata pelajaran untuk meraihnya</small>
        </div>

        {{-- Status badge penguasa: diraih / belum --}}
        @if($penguasaDiraih)
        <span class="badge rounded-pill px-3 py-2"
              style="background:#d1fae5;color:#065f46;font-size:0.75rem;">
            <i class="fas fa-check-circle me-1"></i>Diraih
        </span>
        @else
        <span class="badge rounded-pill px-3 py-2"
              style="background:#fef3c7;color:#92400e;font-size:0.75rem;">
            <i class="fas fa-lock me-1"></i>Belum Diraih
        </span>
        @endif
    </div>

    <div class="card-body p-4">

        {{-- Preview badge penguasa --}}
        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3"
             style="background:{{ $stylePenguasa['bg'] }};border:1px solid {{ $stylePenguasa['text'] }}20;">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                 style="width:64px;height:64px;background:#fff;box-shadow:var(--shadow-sm);overflow:hidden;
                        {{ !$penguasaDiraih ? 'filter:grayscale(0.5);opacity:0.6;' : '' }}">
                <img src="{{ asset('storage/images/' . $badgePenguasa->icon) }}"
                     alt="{{ $badgePenguasa->nama_badge }}"
                     style="width:44px;height:44px;object-fit:contain;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <i class="fas {{ $stylePenguasa['icon'] }}"
                   style="display:none;font-size:1.5rem;color:{{ $stylePenguasa['text'] }};"></i>
            </div>
            <div>
                <div class="fw-bold" style="font-size:0.9rem;color:{{ $stylePenguasa['text'] }};">
                    {{ $badgePenguasa->nama_badge }}
                </div>
                <div style="font-size:0.78rem;color:var(--txt-secondary);margin-top:2px;">
                    {{ $badgePenguasa->deskripsi }}
                </div>
                <div class="mt-1" style="font-size:0.72rem;color:var(--txt-tertiary);">
                    {{ $mapelSelesai }} dari {{ $totalMapel }} mata pelajaran selesai
                </div>
            </div>
        </div>

        {{-- Progress per mapel --}}
        @forelse(collect($progressMapel) as $pm)
        @php
            $isDone = $pm['total'] > 0 && $pm['selesai'] >= $pm['total'];
            $pctMapel = $pm['total'] > 0 ? min(100, round(($pm['selesai']/$pm['total'])*100)) : 0;
        @endphp
        <div class="mb-3 p-3 rounded-3"
             style="border:1px solid {{ $isDone ? '#6ee7b7' : 'var(--border-color)' }};
                    background:{{ $isDone ? '#f0fdf4' : 'var(--bg-muted)' }};">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                <div class="d-flex align-items-center gap-2">
                    @if($isDone)
                    <i class="fas fa-check-circle" style="color:var(--clr-success);"></i>
                    @else
                    <i class="fas fa-book" style="color:var(--clr-primary);"></i>
                    @endif
                    <span class="fw-semibold" style="font-size:0.875rem;color:var(--txt-primary);">
                        {{ $pm['nama_mapel'] }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:0.75rem;color:var(--txt-secondary);">
                        {{ $pm['selesai'] }} / {{ $pm['total'] }} tantangan
                    </span>
                    @if($isDone)
                    <span class="badge rounded-pill"
                          style="background:#d1fae5;color:#065f46;font-size:0.65rem;padding:3px 8px;">
                        Selesai ✓
                    </span>
                    @else
                    <span style="font-size:0.72rem;font-weight:600;color:var(--clr-primary);">
                        {{ $pctMapel }}%
                    </span>
                    @endif
                </div>
            </div>
            <div class="progress rounded-pill" style="height:7px;background:var(--border-color);">
                <div class="progress-bar rounded-pill"
                     style="width:{{ $pctMapel }}%;
                            background:{{ $isDone ? 'var(--clr-success)' : 'var(--clr-primary)' }};
                            transition:width 0.5s;">
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:2rem 0;">
            <div class="empty-state-icon"><i class="fas fa-book-open"></i></div>
            <p>Belum ada data mata pelajaran.</p>
        </div>
        @endforelse

    </div>
</div>
@endif

@endsection