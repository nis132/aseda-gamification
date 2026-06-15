@extends('layouts.app')
@section('title', 'Misi & Tantangan')

@section('content')

{{-- ============================================================
     MODAL HASIL TANTANGAN
============================================================ --}}
@if(session('hasil'))
@php
    $hasil          = session('hasil');
    $isEssay        = $hasil['is_essay']   ?? false;
    $isPending      = $hasil['is_pending'] ?? false;
    $tampilMenunggu = $isEssay || $isPending;
@endphp
<div class="modal fade" id="modalHasil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content hasil-content shadow-lg border-0 overflow-hidden">
            <div class="modal-header d-block text-center border-0 p-0 position-relative">
                <div class="hasil-congrats">
                    @if($tampilMenunggu) MENUNGGU PENILAIAN
                    @elseif($hasil['nilai'] >= 80) PENCAPAIAN BARU!
                    @else TANTANGAN SELESAI
                    @endif
                </div>
                <button type="button" class="hasil-close-btn" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5 position-relative">
                <div class="text-center mb-4">
                    <div class="hasil-subtitle mb-2">Tantangan Selesai!</div>
                    <div class="display-3 fw-black text-white hasil-skor">
                        @if($tampilMenunggu)
                            <span style="font-size:18px;">@if($isPending){{ round($hasil['nilai']) }}*@else—@endif</span>
                        @else {{ round($hasil['nilai']) }}
                        @endif
                    </div>
                    <div class="text-white-50 small mt-1">
                        @if($isEssay) Menunggu Penilaian Guru
                        @elseif($isPending) Skor Sementara
                        @else Skor Akhir @endif
                    </div>
                </div>
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center hasil-poin-badge">
                        <i class="fas fa-coins text-warning fa-lg me-2"></i>
                        @if($tampilMenunggu) <span class="text-warning">Akan diberikan setelah guru menilai</span>
                        @else +{{ $hasil['poin'] }} Poin @endif
                    </div>
                </div>
                @if(!$tampilMenunggu && !empty($hasil['badges']) && count($hasil['badges']) > 0)
                <div class="mt-4 text-center">
                    <h6 class="fw-bold text-warning mb-3"><i class="fas fa-medal me-2"></i>Badge Baru!</h6>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        @foreach($hasil['badges'] as $badgeItem)
                        <div class="badge-achievement">
                            <img src="{{ asset('storage/badge/' . ($badgeItem->badge->icon ?? 'default.png')) }}"
                                 alt="{{ $badgeItem->badge->nama_badge }}" class="img-fluid badge-img mb-2">
                            <div class="badge-ribbon-text text-white fw-bold small">{{ $badgeItem->badge->nama_badge }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer d-grid p-0 border-0">
                {{-- Tombol kembali tetap membawa filter mapel yang aktif --}}
                <button type="button" class="btn btn-hasil-lanjut py-3 rounded-0" data-bs-dismiss="modal">
                    Lanjutkan Petualangan
                </button>
            </div>
        </div>
    </div>
</div>
<script>window.addEventListener('load',()=>{ new bootstrap.Modal(document.getElementById('modalHasil'),{backdrop:'static'}).show(); });</script>
@endif

{{-- ============================================================
     HEADER
============================================================ --}}
@php $activeMapel = request('mapel'); @endphp

<div class="quest-header mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h4 class="quest-title mb-1">
                <i class="fas fa-map me-2"></i>Peta Misi
            </h4>
            <p class="text-muted small mb-0">Selesaikan setiap task untuk membuka BAB berikutnya</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="level-badge-hero">
                <i class="fas fa-star me-1"></i>Level {{ $levelSiswa }}
            </div>
            {{-- Form filter — action eksplisit ke route tantangan agar tidak drift --}}
            <form method="GET" action="{{ route('siswa.tantangan') }}" class="d-flex gap-2 align-items-center">
                <select name="mapel" class="form-select form-select-sm border-0 shadow-sm"
                        style="border-radius:20px; font-size:0.8rem; min-width:160px;"
                        onchange="this.form.submit()">
                    <option value="">Semua Mapel</option>
                    @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}" {{ $activeMapel == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                @if($activeMapel)
                <a href="{{ route('siswa.tantangan') }}"
                   class="btn btn-sm btn-light rounded-pill px-3" style="font-size:0.78rem;">
                    <i class="fas fa-undo me-1"></i>Reset
                </a>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     QUEST MAP
============================================================ --}}
<div class="quest-map-wrapper">
@forelse($groupedByBab as $bab => $tantangans)
@php
    $progress    = $babProgress[$bab];
    $isBabLocked = $tantangans->first()->is_bab_locked ?? false;
    $babSelesai  = $progress['selesai_semua'];

    $tipeGrup = match($bab) {
        '__uts__' => 'uts',
        '__uas__' => 'uas',
        default   => 'reguler',
    };
    $babNum = ($tipeGrup === 'reguler' && $bab > 0) ? $bab : null;

    if ($isBabLocked)    $babState = 'locked';
    elseif ($babSelesai) $babState = 'done';
    else                 $babState = 'active';

    $babLabel = match($tipeGrup) {
        'uts'   => '📝 Ujian Tengah Semester',
        'uas'   => '🏆 Ujian Akhir Semester',
        default => 'BAB ' . $babNum,
    };

    $lockMsg = match($tipeGrup) {
        'uts'   => '🔒 Selesaikan BAB 1–4 dulu (butuh Level 4)',
        'uas'   => '🔒 Selesaikan semua BAB dulu (butuh Level 8)',
        default => '🔒 Selesaikan BAB ' . max(1, (int)$bab - 1) . ' dulu',
    };

    $babColors = [
        1 => ['from'=>'#f59e0b','to'=>'#ef4444','glow'=>'rgba(245,158,11,0.4)'],
        2 => ['from'=>'#10b981','to'=>'#059669','glow'=>'rgba(16,185,129,0.4)'],
        3 => ['from'=>'#3b82f6','to'=>'#6366f1','glow'=>'rgba(59,130,246,0.4)'],
        4 => ['from'=>'#8b5cf6','to'=>'#ec4899','glow'=>'rgba(139,92,246,0.4)'],
        5 => ['from'=>'#f97316','to'=>'#f59e0b','glow'=>'rgba(249,115,22,0.4)'],
        6 => ['from'=>'#06b6d4','to'=>'#3b82f6','glow'=>'rgba(6,182,212,0.4)'],
        7 => ['from'=>'#84cc16','to'=>'#10b981','glow'=>'rgba(132,204,22,0.4)'],
        8 => ['from'=>'#ec4899','to'=>'#8b5cf6','glow'=>'rgba(236,72,153,0.4)'],
    ];
    $specialColors = [
        'uts' => ['from'=>'#f59e0b','to'=>'#dc2626','glow'=>'rgba(245,158,11,0.35)'],
        'uas' => ['from'=>'#7c3aed','to'=>'#db2777','glow'=>'rgba(124,58,237,0.35)'],
    ];
    $clr = $specialColors[$tipeGrup] ?? ($babColors[$bab] ?? ['from'=>'#6366f1','to'=>'#8b5cf6','glow'=>'rgba(99,102,241,0.4)']);

    $qs = $activeMapel ? '?mapel=' . $activeMapel : '';
@endphp

<div class="bab-chapter {{ $babState }}" data-bab="{{ $bab }}">

    {{-- ===== BAB NODE ===== --}}
    <div class="chapter-node-row">
        <div class="chapter-connector-left"></div>
        <div class="chapter-node {{ $babState }}"
             style="--clr-from: {{ $clr['from'] }}; --clr-to: {{ $clr['to'] }}; --clr-glow: {{ $clr['glow'] }};">
            @if($babState === 'locked')        <i class="fas fa-lock fa-lg"></i>
            @elseif($babState === 'done')      <i class="fas fa-check-circle fa-lg"></i>
            @elseif($tipeGrup === 'uts')       <i class="fas fa-pen-nib fa-lg"></i>
            @elseif($tipeGrup === 'uas')       <i class="fas fa-graduation-cap fa-lg"></i>
            @elseif($babNum)                   <span class="chapter-num">{{ $babNum }}</span>
            @else                              <i class="fas fa-scroll fa-lg"></i>
            @endif
        </div>
        <div class="chapter-info {{ $babState }}">
            <div class="chapter-label">{{ $babLabel }}</div>
            @if(!$isBabLocked)
                <div class="chapter-progress-text">
                    {{ $progress['selesai'] }}/{{ $progress['total'] }} task selesai
                    @if($progress['nilai_rata'] > 0)
                        &nbsp;·&nbsp;
                        <span class="{{ $progress['nilai_rata'] >= 75 ? 'text-success fw-bold' : ($progress['nilai_rata'] >= 60 ? 'text-warning fw-bold' : 'text-danger fw-bold') }}">
                            Rata-rata: {{ $progress['nilai_rata'] }}
                        </span>
                    @endif
                </div>
                @if(($progress['total_poin'] ?? 0) > 0)
                <div class="chapter-poin-text">
                    <i class="fas fa-coins text-warning me-1"></i>
                    <span class="{{ ($progress['didapat_poin'] ?? 0) > 0 ? 'text-warning fw-bold' : 'text-muted' }}">
                        {{ $progress['didapat_poin'] ?? 0 }}
                    </span>
                    <span class="text-muted"> / {{ $progress['total_poin'] }} poin</span>
                </div>
                @endif
                <div class="chapter-progressbar">
                    <div class="chapter-progressbar-fill {{ $babState }}"
                         tyle="width: {{ $progress['nilai_rata'] }}%;
                                background: linear-gradient(90deg, {{ $clr['from'] }}, {{ $clr['to'] }});">
                    </div>
                </div>
            @else
                <div class="chapter-progress-text">{{ $lockMsg }}</div>
            @endif
        </div>
        @if($babSelesai)
        <div class="chapter-done-badge"><i class="fas fa-trophy me-1"></i>CLEAR!</div>
        @endif
        <div class="chapter-connector-right"></div>
    </div>

    {{-- ===== TASK NODES ===== --}}
    @if(!$isBabLocked)
    <div class="tasks-row">
        @foreach($tantangans as $idx => $tantangan)
        @php
            $sudahSelesai   = $tantangan->nilaiTantangan->isNotEmpty();
            $nilai          = $sudahSelesai ? $tantangan->nilaiTantangan->first() : null;
            $isExpired      = !is_null($tantangan->batas_waktu) && $tantangan->batas_waktu <= now();
            $isLocked       = $tantangan->is_locked || ($tantangan->is_level_locked ?? false);
            $bisaDikerjakan = !$isLocked && !$sudahSelesai && !$isExpired;

            if ($sudahSelesai)  $taskState = 'done';
            elseif ($isLocked)  $taskState = 'locked';
            elseif ($isExpired) $taskState = 'expired';
            else                $taskState = 'open';

            $urlKerjakan = route('siswa.tantangan.kerjakan', $tantangan) . $qs;
            $urlReview   = route('siswa.tantangan.review',   $tantangan->id) . $qs;
        @endphp

        @if($idx > 0)
        <div class="task-connector">
            <div class="connector-line {{ $taskState === 'done' ? 'done' : '' }}"></div>
        </div>
        @endif

        <div class="task-node-wrap">
            <div class="task-node {{ $taskState }}"
                 style="--clr-from: {{ $clr['from'] }}; --clr-to: {{ $clr['to'] }}; --clr-glow: {{ $clr['glow'] }};"
                 data-bs-toggle="tooltip" data-bs-placement="top"
                 title="{{ $tantangan->judul }}">
                @if($taskState === 'done')       <i class="fas fa-check"></i>
                @elseif($taskState === 'locked') <i class="fas fa-lock"></i>
                @elseif($taskState === 'expired')<i class="fas fa-hourglass-end"></i>
                @else                            <span class="task-num">{{ $tantangan->urutan ?? $idx + 1 }}</span>
                @endif
                @if($bisaDikerjakan)<div class="pulse-ring"></div>@endif
            </div>

            <div class="task-label {{ $taskState }}">
                <div class="task-title">{{ Str::limit($tantangan->judul, 35) }}</div>
                <div class="task-meta">
                    @if($taskState === 'done')
                        <span class="text-success fw-bold"><i class="fas fa-star me-1"></i>{{ round($nilai->total_nilai) }}%</span>
                        <span class="text-warning ms-2"><i class="fas fa-coins me-1"></i>+{{ $tantangan->poin_didapat_siswa }} poin</span>
                    @elseif($taskState === 'locked')
                        <span class="text-muted small"><i class="fas fa-lock me-1"></i>Selesaikan dulu</span>
                    @elseif($taskState === 'expired')
                        <span class="text-danger d-block"><i class="fas fa-hourglass-end me-1"></i>Lewat batas waktu</span>
                        @php
                            $babInt       = is_numeric($bab) ? (int)$bab : null;
                            $adaExpired   = ($progress['expired'] ?? 0) > 0;
                            $pengayaanBab = $babInt && $adaExpired
                                ? ($pengayaanPerBab[$babInt] ?? collect())
                                : collect();
                        @endphp
                    @else
                        <span class="text-success"><i class="far fa-clock me-1"></i>{{ $tantangan->batas_waktu ? $tantangan->batas_waktu->diffForHumans() : 'Tanpa batas' }}</span>
                        <span class="text-warning ms-2"><i class="fas fa-coins me-1"></i>{{ $tantangan->poin }} poin</span>
                    @endif
                </div>
                <div class="task-soal"><i class="fas fa-list-ol me-1"></i>{{ $tantangan->soal_count }} soal</div>
            </div>

            <div class="task-action">
                @if($taskState === 'done')
                    <a href="{{ $urlReview }}" class="btn-task review">
                        <i class="fas fa-eye me-1"></i>Review
                    </a>
                @elseif($taskState === 'locked')
                    <button class="btn-task locked" disabled><i class="fas fa-lock me-1"></i>Terkunci</button>
                @elseif($taskState === 'expired')
                    <button class="btn-task expired" disabled><i class="fas fa-times me-1"></i>Ditutup</button>
                @else
                    <a href="{{ $urlKerjakan }}" class="btn-task start">
                        <i class="fas fa-play me-1"></i>Mulai
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== PENGAYAAN — muncul jika ada hutang di bab ini ===== --}}
    @php
        $babInt       = is_numeric($bab) ? (int)$bab : null;
        $adaHutang    = $progress['ada_hutang'] ?? false;
        $pengayaanBab = $babInt && $adaHutang
            ? ($pengayaanPerBab[$babInt] ?? collect())
            : collect();
    @endphp

    @if($pengayaanBab->isNotEmpty())
    <div class="pengayaan-section">
        <div class="pengayaan-header">
            <div class="pengayaan-arrow">↓</div>
            <div class="pengayaan-header-text">
                <i class="fas fa-book-open me-1"></i>
                Pengayaan BAB {{ $babInt }}
                <span class="pengayaan-subtitle">Kerjakan untuk mengejar ketertinggalan</span>
            </div>
        </div>
        <div class="tasks-row pengayaan-row">
            @foreach($pengayaanBab as $idx => $p)
            @php
                $pSelesai    = $p->nilaiTantangan->isNotEmpty();
                $pNilai      = $pSelesai ? $p->nilaiTantangan->first() : null;
                $pState      = $pSelesai ? 'done' : 'open';
                $urlPKerjakan = route('siswa.tantangan.kerjakan', $p) . $qs;
                $urlPReview   = route('siswa.tantangan.review',   $p->id) . $qs;
            @endphp
            @if($idx > 0)
            <div class="task-connector"><div class="connector-line {{ $pState === 'done' ? 'done' : '' }}"></div></div>
            @endif
            <div class="task-node-wrap">
                <div class="task-node {{ $pState }} pengayaan"
                     style="--clr-from:#06b6d4; --clr-to:#3b82f6; --clr-glow:rgba(6,182,212,0.5);"
                     data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $p->judul }}">
                    @if($pState === 'done') <i class="fas fa-check"></i>
                    @else                   <i class="fas fa-book-open"></i>
                    @endif
                    @if($pState === 'open')<div class="pulse-ring" style="border-color:#06b6d4;"></div>@endif
                </div>
                <div class="task-label {{ $pState }} pengayaan">
                    <span class="pengayaan-tag">Pengayaan</span>
                    <div class="task-title">{{ Str::limit($p->judul, 35) }}</div>
                    <div class="task-meta">
                        @if($pState === 'done')
                            <span class="text-success fw-bold"><i class="fas fa-star me-1"></i>{{ round($pNilai->total_nilai) }}%</span>
                            <span class="text-warning ms-2"><i class="fas fa-coins me-1"></i>+{{ $p->poin_didapat_siswa }} poin</span>
                        @else
                            <span class="text-info"><i class="fas fa-infinity me-1"></i>Tanpa batas waktu</span>
                            <span class="text-warning ms-2"><i class="fas fa-coins me-1"></i>{{ $p->poin }} poin</span>
                        @endif
                    </div>
                    <div class="task-soal"><i class="fas fa-list-ol me-1"></i>{{ $p->soal_count }} soal</div>
                </div>
                <div class="task-action">
                    @if($pState === 'done')
                        <a href="{{ $urlPReview }}" class="btn-task review"><i class="fas fa-eye me-1"></i>Review</a>
                    @else
                        <a href="{{ $urlPKerjakan }}" class="btn-task pengayaan"><i class="fas fa-book-open me-1"></i>Kerjakan</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @else
    {{-- BAB terkunci: blur preview --}}
    <div class="tasks-row locked-preview">
        @foreach($tantangans->take(3) as $idx => $t)
        @if($idx > 0)<div class="task-connector"><div class="connector-line"></div></div>@endif
        <div class="task-node-wrap" style="opacity:0.3; filter:blur(2px); pointer-events:none;">
            <div class="task-node locked"><i class="fas fa-lock"></i></div>
            <div class="task-label locked">
                <div class="task-title">████████████</div>
                <div class="task-meta text-muted">??</div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Konektor ke BAB berikutnya --}}
    @if(!$loop->last)
    <div class="bab-down-connector {{ $babSelesai ? 'done' : ($isBabLocked ? 'locked' : 'pending') }}">
        <div class="down-line"></div>
        @if($babSelesai)
        <div class="down-arrow done"><i class="fas fa-chevron-down"></i></div>
        @endif
    </div>
    @endif

</div>
@empty
<div class="empty-quest">
    <div class="empty-icon"><i class="fas fa-scroll"></i></div>
    <h6>Tidak ada misi ditemukan</h6>
    <p class="text-muted small">Coba ubah filter atau tunggu misi baru dari guru.</p>
</div>
@endforelse
</div>

@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap');

.quest-title { font-family: 'Fredoka One', cursive; font-size: 1.8rem; color: var(--txt-primary); letter-spacing: 0.5px; }
.level-badge-hero { background: linear-gradient(135deg, #f59e0b, #ef4444); color: white; font-family: 'Fredoka One', cursive; font-size: 0.95rem; padding: 8px 18px; border-radius: 50px; box-shadow: 0 4px 15px rgba(245,158,11,0.4); letter-spacing: 0.5px; }
.quest-map-wrapper { display: flex; flex-direction: column; align-items: center; gap: 0; padding: 20px 0 60px; position: relative; }
.bab-chapter { width: 100%; max-width: 900px; display: flex; flex-direction: column; align-items: center; }
.chapter-node-row { display: flex; align-items: center; gap: 16px; width: 100%; margin-bottom: 24px; padding: 0 20px; }
.chapter-connector-left, .chapter-connector-right { flex: 1; height: 2px; background: linear-gradient(90deg, transparent, var(--border-color)); }
.chapter-connector-right { background: linear-gradient(90deg, var(--border-color), transparent); }
.chapter-node { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; color: white; position: relative; transition: all 0.3s ease; cursor: default; }
.chapter-node.active { background: linear-gradient(135deg, var(--clr-from), var(--clr-to)); box-shadow: 0 8px 24px var(--clr-glow), 0 0 0 4px rgba(255,255,255,0.8), 0 0 0 8px var(--clr-glow); animation: chapter-pulse 2.5s infinite; }
.chapter-node.done { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 8px 20px rgba(16,185,129,0.4); }
.chapter-node.locked { background: linear-gradient(135deg, #94a3b8, #64748b); box-shadow: none; opacity: 0.7; }
@keyframes chapter-pulse {
    0%, 100% { box-shadow: 0 8px 24px var(--clr-glow), 0 0 0 4px rgba(255,255,255,0.8), 0 0 0 8px var(--clr-glow); }
    50% { box-shadow: 0 8px 24px var(--clr-glow), 0 0 0 4px rgba(255,255,255,0.8), 0 0 0 14px transparent; }
}
.chapter-num { font-family: 'Fredoka One', cursive; font-size: 1.8rem; line-height: 1; }
.chapter-info { text-align: center; min-width: 160px; }
.chapter-label { font-family: 'Fredoka One', cursive; font-size: 1.1rem; color: var(--txt-primary); margin-bottom: 4px; }
.chapter-info.locked .chapter-label { color: var(--txt-tertiary); }
.chapter-info.done .chapter-label { color: #10b981; }
.chapter-progress-text { font-size: 0.72rem; color: var(--txt-secondary); margin-bottom: 4px; }
.chapter-poin-text { font-size: 0.7rem; margin-bottom: 5px; }
.chapter-progressbar { height: 6px; background: var(--border-color); border-radius: 99px; overflow: hidden; width: 140px; margin: 0 auto; }
.chapter-progressbar-fill { height: 100%; border-radius: 99px; transition: width 1s ease; }
.chapter-done-badge { background: linear-gradient(135deg, #f59e0b, #ef4444); color: white; font-size: 0.68rem; font-weight: 800; padding: 4px 12px; border-radius: 50px; letter-spacing: 0.5px; white-space: nowrap; box-shadow: 0 4px 12px rgba(245,158,11,0.4); }
.tasks-row { display: flex; align-items: flex-start; justify-content: center; gap: 0; width: 100%; padding: 0 16px; flex-wrap: wrap; position: relative; }
.task-connector { display: flex; align-items: center; padding-top: 36px; flex-shrink: 0; }
.connector-line { width: 32px; height: 3px; background: var(--border-color); border-radius: 2px; position: relative; overflow: hidden; }
.connector-line.done { background: linear-gradient(90deg, #10b981, #10b981); }
.connector-line::after { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent); animation: shimmer 2s infinite; }
@keyframes shimmer { to { left: 100%; } }
.task-node-wrap { display: flex; flex-direction: column; align-items: center; gap: 10px; width: 140px; flex-shrink: 0; position: relative; }
.task-node { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: white; position: relative; transition: all 0.3s ease; cursor: pointer; z-index: 2; }
.task-node.done { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 6px 20px rgba(16,185,129,0.5); }
.task-node.open { background: linear-gradient(135deg, var(--clr-from), var(--clr-to)); box-shadow: 0 6px 20px var(--clr-glow); }
.task-node.open:hover { transform: scale(1.1) translateY(-4px); box-shadow: 0 12px 28px var(--clr-glow); }
.task-node.locked { background: linear-gradient(135deg, #cbd5e1, #94a3b8); box-shadow: none; opacity: 0.6; cursor: not-allowed; }
.task-node.expired { background: linear-gradient(135deg, #fca5a5, #ef4444); box-shadow: 0 4px 12px rgba(239,68,68,0.3); opacity: 0.8; }
.task-node.pengayaan { background: linear-gradient(135deg, #06b6d4, #3b82f6); box-shadow: 0 6px 20px rgba(6,182,212,0.5); border: 3px dashed rgba(255,255,255,0.5); }
.task-node.pengayaan:hover { transform: scale(1.1) translateY(-4px); box-shadow: 0 12px 28px rgba(6,182,212,0.5); }
.task-num { font-family: 'Fredoka One', cursive; font-size: 1.5rem; line-height: 1; }
.pulse-ring { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; border-radius: 50%; border: 3px solid var(--clr-from, #6366f1); animation: pulse-expand 1.8s ease-out infinite; pointer-events: none; z-index: -1; }
@keyframes pulse-expand { 0% { transform: translate(-50%,-50%) scale(1); opacity: 0.8; } 100% { transform: translate(-50%,-50%) scale(1.8); opacity: 0; } }
.task-label { background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 10px 10px 8px; text-align: center; width: 100%; box-shadow: var(--shadow-xs); transition: all 0.2s; }
.task-label.done { border-color: #10b981; background: #f0fdf4; }
.task-label.expired { opacity: 1; border-color: #fca5a5; background: #fff5f5; }
.task-label.locked { opacity: 0.5; }
.task-label.pengayaan { border-color: #06b6d4; background: #ecfeff; }
.task-node-wrap:hover .task-label.open, .task-node-wrap:hover .task-label.pengayaan { box-shadow: var(--shadow-sm); transform: translateY(-2px); }
.task-title { font-size: 0.72rem; font-weight: 700; color: var(--txt-primary); line-height: 1.3; margin-bottom: 4px; }
.task-meta { font-size: 0.66rem; margin-bottom: 3px; }
.task-soal { font-size: 0.65rem; color: var(--txt-tertiary); }
.pengayaan-tag { display: inline-block; background: linear-gradient(135deg, #06b6d4, #3b82f6); color: white; font-size: 0.6rem; font-weight: 800; padding: 2px 8px; border-radius: 50px; margin-bottom: 4px; letter-spacing: 0.5px; }
.task-action { width: 100%; }
.btn-task { display: block; width: 100%; text-align: center; padding: 6px 8px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; letter-spacing: 0.3px; }
.btn-task.start { background: linear-gradient(135deg, var(--clr-from, #6366f1), var(--clr-to, #8b5cf6)); color: white; box-shadow: 0 4px 12px var(--clr-glow, rgba(99,102,241,0.4)); }
.btn-task.start:hover { transform: translateY(-1px); box-shadow: 0 6px 16px var(--clr-glow, rgba(99,102,241,0.5)); color: white; }
.btn-task.pengayaan { background: linear-gradient(135deg, #06b6d4, #3b82f6); color: white; box-shadow: 0 4px 12px rgba(6,182,212,0.4); }
.btn-task.pengayaan:hover { transform: translateY(-1px); color: white; }
.btn-task.review { background: #f0fdf4; color: #10b981; border: 1px solid #10b981; }
.btn-task.review:hover { background: #10b981; color: white; }
.btn-task.locked, .btn-task.expired { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
.bab-down-connector { display: flex; flex-direction: column; align-items: center; margin: 8px 0; gap: 0; }
.down-line { width: 3px; height: 40px; background: var(--border-color); border-radius: 2px; }
.bab-down-connector.done .down-line { background: linear-gradient(180deg, #10b981, #6366f1); }
.bab-down-connector.locked .down-line { background: #e2e8f0; opacity: 0.5; }
.down-arrow { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: white; animation: bounce-down 1.5s infinite; }
.down-arrow.done { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
@keyframes bounce-down { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(5px); } }
.empty-quest { text-align: center; padding: 60px 20px; color: var(--txt-secondary); }
.empty-icon { font-size: 3rem; margin-bottom: 16px; opacity: 0.3; }
.hasil-content { background: linear-gradient(145deg, #1d0e42, #392b6a); border-radius: 20px !important; }
.hasil-close-btn { position: absolute; top: 12px; right: 12px; width: 30px; height: 30px; background: rgba(255,255,255,0.1); border: none; border-radius: 50%; color: white; font-size: 1.1rem; cursor: pointer; }
.hasil-close-btn::before { content: '\2715'; }
.hasil-congrats { font-family: 'Fredoka One', cursive; font-size: 1.3rem; color: #ffd700; padding-top: 1.5rem; letter-spacing: 0.12em; }
.hasil-subtitle { color: rgba(255,255,255,0.8); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.1em; }
.fw-black { font-weight: 900; }
.hasil-skor { background: linear-gradient(to bottom, #ffffff, #a8cfff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.hasil-poin-badge { background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15); color: #fff; font-weight: 600; }
.btn-hasil-lanjut { background: linear-gradient(to right, #6366f1, #9333ea); color: #fff !important; font-size: 1rem; font-weight: 700; border: none; }
.pengayaan-section { width: 100%; max-width: 900px; display: flex; flex-direction: column; align-items: center; margin-top: 8px; margin-bottom: 4px; }
.pengayaan-header { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
.pengayaan-arrow { font-size: 1.4rem; color: #06b6d4; animation: bounce-down 1.5s infinite; }
.pengayaan-header-text { background: linear-gradient(135deg, rgba(6,182,212,0.12), rgba(59,130,246,0.12)); border: 1px dashed #06b6d4; border-radius: 50px; padding: 6px 18px; font-size: 0.82rem; font-weight: 700; color: #0891b2; display: flex; align-items: center; gap: 8px; }
.pengayaan-subtitle { font-weight: 400; font-size: 0.72rem; color: #64748b; }
.pengayaan-row { background: linear-gradient(135deg, rgba(6,182,212,0.05), rgba(59,130,246,0.05)); border: 1px dashed rgba(6,182,212,0.3); border-radius: 16px; padding: 16px 16px 12px; }

@media (max-width: 768px) {
    .tasks-row { flex-wrap: nowrap; overflow-x: auto; justify-content: flex-start; padding-bottom: 12px; gap: 0; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .task-node-wrap { width: 120px; }
    .connector-line { width: 20px; }
    .chapter-node-row { padding: 0 8px; }
    .chapter-connector-left, .chapter-connector-right { display: none; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endpush