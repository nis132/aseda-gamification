@extends('layouts.app')
@section('title', 'Badge Saya')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
/* ── HERO BANNER ─────────────────────────────────────── */
.badge-hero {
    background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);
    border-radius: var(--border-radius-xl);
    padding: 2rem 2.5rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.75rem;
}
.badge-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.badge-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}
.badge-hero .stat-box {
    background: rgba(255,255,255,0.15);
    border-radius: var(--border-radius-md);
    padding: 0.75rem 1.25rem;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    min-width: 100px;
}
.badge-hero .stat-box .num {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1;
}
.badge-hero .stat-box .lbl {
    font-size: 0.72rem;
    opacity: 0.8;
    margin-top: 2px;
}

/* ── BADGE CARDS ─────────────────────────────────────── */
.badge-card {
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
    background: var(--bg-card);
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1.75rem 1.25rem 1.5rem;
}
.badge-card.owned {
    border-color: transparent;
    box-shadow: var(--shadow-md);
}
.badge-card.owned:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
.badge-card.locked {
    opacity: 0.7;
    background: var(--bg-muted);
}
.badge-card.locked:hover {
    opacity: 0.85;
}

/* Ribbon "DIRAIH" */
.badge-ribbon {
    position: absolute;
    top: 12px; right: -22px;
    background: var(--clr-success);
    color: #fff;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 3px 28px;
    transform: rotate(35deg);
    box-shadow: 0 2px 6px rgba(16,185,129,0.35);
}

/* Badge image / icon */
.badge-img-wrap {
    width: 88px; height: 88px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1rem;
    position: relative;
    flex-shrink: 0;
}
.badge-img-wrap img {
    width: 72px; height: 72px;
    object-fit: contain;
    border-radius: 50%;
    display: block;
}
.badge-img-wrap .lock-overlay {
    position: absolute; inset: 0;
    border-radius: 50%;
    background: rgba(0,0,0,0.35);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.25rem;
}
.badge-count-pill {
    position: absolute;
    top: -4px; right: -4px;
    background: var(--clr-primary);
    color: #fff;
    width: 22px; height: 22px;
    border-radius: 50%;
    font-size: 0.68rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Progress bar */
.progress-slim {
    height: 5px;
    border-radius: 99px;
    background: var(--border-color);
    overflow: hidden;
    margin-top: 0.5rem;
}
.progress-slim .bar { height: 100%; border-radius: 99px; }

/* Section title */
.section-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--txt-tertiary);
    margin-bottom: 1rem;
}

/* ── OVERLAY POPUP ───────────────────────────────────── */
.badge-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.85);
    z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(6px);
}
.badge-overlay-inner {
    max-width: 380px; width: 90%;
    text-align: center;
}
.badge-overlay-img {
    width: 110px; height: 110px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 0 50px rgba(255,255,255,0.15);
}
.badge-overlay-img img {
    width: 90px; height: 90px;
    object-fit: contain; border-radius: 50%;
}
</style>
@endpush

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────── --}}
<div class="badge-hero mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:1;">
        <div>
            <div class="text-label mb-1" style="color:rgba(255,255,255,0.7);">Koleksi Badge</div>
            <h4 class="fw-bold mb-0" style="font-size:1.4rem;">Badge Saya</h4>
            <p class="mb-0 mt-1" style="font-size:0.82rem;opacity:0.8;">
                Kumpulkan badge dengan naik level dan kuasai mata pelajaran.
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="stat-box">
                <div class="num">{{ $ownedBadges->count() }}</div>
                <div class="lbl">Badge Diraih</div>
            </div>
            <div class="stat-box">
                <div class="num">{{ $levelSiswa }}</div>
                <div class="lbl">Level Saat Ini</div>
            </div>
        </div>
    </div>
</div>

{{-- ── BADGE YANG DIRAIH ─────────────────────────────────────── --}}
{{-- $badgeDiraih dan $badgeBelum di-pass dari BadgeController::index() --}}

@if($badgeDiraih->isNotEmpty())
<div class="section-label">
    <i class="fas fa-check-circle me-1" style="color:var(--clr-success);"></i>
    Badge Diraih ({{ $badgeDiraih->count() }})
</div>
<div class="row g-3 mb-4">
    @foreach($badgeDiraih as $badge)
    @php
        $group  = $ownedBadges->get($badge->id);
        $isNew  = $group->contains('is_new', true);
        $jumlah = $group->count();
        $style  = $badge->styleConfig();
    @endphp
    <div class="col-6 col-md-4 col-lg-3">
        <div class="badge-card owned" style="border-top: 3px solid {{ $style['text'] }};">
            <div class="badge-ribbon">Diraih</div>

            <div class="badge-img-wrap" style="background:{{ $style['bg'] }};border:3px solid {{ $style['text'] }}20;">
                <img src="{{ asset('storage/images/' . $badge->icon) }}"
                     alt="{{ $badge->nama_badge }}"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                
                <i class="fas {{ $style['icon'] }}" style="display:none;font-size:2rem;color:{{ $style['text'] }};"></i>

                @if($jumlah > 1)
                <span class="badge-count-pill">{{ $jumlah }}</span>
                @endif
            </div>

            <h6 class="fw-bold mb-1" style="font-size:0.875rem;color:var(--txt-primary);">
                {{ $badge->nama_badge }}
            </h6>
            <p style="font-size:0.72rem;color:var(--txt-secondary);line-height:1.5;margin-bottom:0.75rem;">
                {{ $badge->deskripsi }}
            </p>

            <span class="badge rounded-pill"
                  style="background:{{ $style['bg'] }};color:{{ $style['text'] }};font-size:0.65rem;">
                {{ $style['label'] }}
            </span>

            @if($badge->ada_sertifikat)
            <div class="mt-2 w-100">
                <a href="{{ route('badge.sertifikat', $badge->id) }}"
                   class="btn btn-warning btn-sm rounded-pill w-100 fw-bold"
                   style="font-size:0.72rem;">
                    <i class="fas fa-certificate me-1"></i>Lihat Sertifikat
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- POPUP badge baru --}}
    @if($isNew)
    <div id="overlay-{{ $badge->id }}" class="badge-overlay">
        <div class="badge-overlay-inner animate__animated animate__jackInTheBox">
            <div style="font-size:1.4rem;color:#ffd700;font-weight:900;
                        text-shadow:0 0 20px rgba(255,215,0,0.6);margin-bottom:6px;">
                🎉 BADGE BARU!
            </div>
            <p class="text-white mb-3" style="font-size:0.9rem;">Selamat, kamu mendapatkan badge</p>
            <div class="badge-overlay-img" style="background:{{ $style['bg'] }};">
                <img src="{{ asset('storage/images/' . $badge->icon) }}"
                     alt="{{ $badge->nama_badge }}"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <i class="fas {{ $style['icon'] }}" style="display:none;font-size:2.5rem;color:{{ $style['text'] }};"></i>
            </div>
            <h3 class="text-white fw-bold mb-1">{{ $badge->nama_badge }}</h3>
            <p class="text-white mb-4" style="font-size:0.82rem;opacity:0.75;">{{ $badge->deskripsi }}</p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                @if($badge->ada_sertifikat)
                <a href="{{ route('badge.sertifikat', $badge->id) }}"
                   class="btn btn-warning px-4 fw-bold rounded-pill">
                    <i class="fas fa-certificate me-2"></i>Ambil Sertifikat
                </a>
                @endif
                <button onclick="closeOverlay('{{ $badge->id }}')"
                        class="btn btn-outline-light px-4 fw-bold rounded-pill">
                    Mantap! 
                </button>
            </div>
        </div>
    </div>
    @php
        \App\Models\SiswaBadge::where('siswa_id', auth()->id())
            ->where('badge_id', $badge->id)
            ->update(['is_new' => false]);
    @endphp
    @endif

    @endforeach
</div>
@else
<div class="card mb-4 p-4 text-center" style="border:2px dashed var(--border-color);background:var(--bg-muted);">
    <div style="font-size:2.5rem;margin-bottom:0.5rem;">🏅</div>
    <p class="fw-bold mb-1" style="color:var(--txt-primary);">Belum ada badge diraih</p>
    <p class="mb-0" style="font-size:0.82rem;color:var(--txt-secondary);">
        Selesaikan tantangan untuk naik level dan dapatkan badge pertamamu!
    </p>
</div>
@endif

{{-- ── BADGE YANG BELUM DIRAIH ──────────────────────────────── --}}
@if($badgeBelum->isNotEmpty())
<div class="section-label mt-2">
    <i class="fas fa-lock me-1"></i>
    Badge Belum Diraih ({{ $badgeBelum->count() }})
</div>
<div class="row g-3">
    @foreach($badgeBelum as $badge)
    @php
        $style = $badge->styleConfig();
        $sudahLewat = $badge->tipe_syarat === 'level' && $levelSiswa >= $badge->level_required;
        // Jika level sudah lewat tapi badge belum diraih → ini bug, tapi tampilkan info
        $pct = $badge->tipe_syarat === 'level'
            ? min(99, round(($levelSiswa / $badge->level_required) * 100))
            : 0;
    @endphp
    <div class="col-6 col-md-4 col-lg-3">
        <div class="badge-card locked">
            <div class="badge-img-wrap" style="background:var(--bg-muted);border:3px solid var(--border-color);">
                <img src="{{ asset('storage/images/' . $badge->icon) }}"
                     alt="{{ $badge->nama_badge }}"
                     style="filter:grayscale(1);opacity:0.5;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <i class="fas {{ $style['icon'] }}" style="display:none;font-size:2rem;color:var(--txt-tertiary);"></i>
                <div class="lock-overlay"><i class="fas fa-lock"></i></div>
            </div>

            <h6 class="fw-bold mb-1" style="font-size:0.875rem;color:var(--txt-tertiary);">
                {{ $badge->nama_badge }}
            </h6>
            <p style="font-size:0.72rem;color:var(--txt-tertiary);line-height:1.5;margin-bottom:0.75rem;">
                {{ $badge->deskripsi }}
            </p>

            @if($badge->tipe_syarat === 'level')
                @if($sudahLewat)
                
                <span class="badge rounded-pill"
                      style="background:#fef3c7;color:#92400e;font-size:0.65rem;">
                    <i class="fas fa-clock me-1"></i>Segera diproses...
                </span>
                @else
                <div class="w-100">
                    <div class="d-flex justify-content-between" style="font-size:0.65rem;color:var(--txt-tertiary);">
                        <span>Level {{ $levelSiswa }}</span>
                        <span>Target: Level {{ $badge->level_required }}</span>
                    </div>
                    <div class="progress-slim">
                        <div class="bar" style="width:{{ $pct }}%;background:{{ $style['text'] }};"></div>
                    </div>
                </div>
                @endif
            @else
            <span class="badge rounded-pill"
                  style="background:var(--bg-muted);color:var(--txt-tertiary);font-size:0.65rem;">
                <i class="fas fa-lock me-1"></i>Belum Diraih
            </span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

<script>
function closeOverlay(id) {
    const el = document.getElementById('overlay-' + id);
    el.classList.add('animate__animated','animate__fadeOut');
    setTimeout(() => el.remove(), 500);
}
</script>
@endsection