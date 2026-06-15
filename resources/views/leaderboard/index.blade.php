@extends('layouts.app')

@section('title', 'Leaderboard Kelas')

@section('content')

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h4 class="page-title">
            <i class="fas fa-trophy text-warning me-2"></i>Papan Peringkat
        </h4>
        <p class="small mb-0" style="color: var(--txt-secondary);">
            Kelas: <strong>{{ $kelas->nama_kelas ?? '-' }}</strong>
        </p>
    </div>
</div>

{{-- OVERALL LEADERBOARD --}}

{{-- TOP 3 PODIUM --}}
@if($leaderboard->count() >= 1 && $leaderboard[0]->total_poin > 0)
<div class="card mb-4 overflow-hidden">
    <div class="card-body p-4" style="background:linear-gradient(135deg,var(--clr-primary) 0%,#7c3aed 100%);">
        <div class="row align-items-end justify-content-center g-3">

            @if($leaderboard->count() >= 2)
            <div class="col-4 col-md-3 order-1">
                <div class="podium-card text-center p-3">
                    <div class="avatar-wrap mb-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($leaderboard[1]->nama) }}&background=E2E8F0&color=475569"
                             class="rounded-circle border border-3 border-white shadow-sm"
                             style="width:58px;height:58px;">
                        <div class="rank-pip">2</div>
                    </div>
                    <div class="text-truncate small fw-bold text-white mb-1">{{ $leaderboard[1]->nama }}</div>
                    <div class="xp-chip">{{ number_format($leaderboard[1]->total_poin) }} XP</div>
                </div>
            </div>
            @endif

            <div class="col-4 col-md-3 order-2">
                <div class="podium-card podium-gold text-center p-3">
                    <div class="crown mb-1"><i class="fas fa-crown text-warning"></i></div>
                    <div class="avatar-wrap mb-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($leaderboard[0]->nama) }}&background=FEF3C7&color=D97706"
                             class="rounded-circle border border-4 border-warning shadow"
                             style="width:80px;height:80px;">
                        <div class="rank-pip" style="background:var(--clr-warning);">1</div>
                    </div>
                    <div class="text-truncate fw-bold text-white mb-1">{{ $leaderboard[0]->nama }}</div>
                    <div class="xp-chip xp-chip-gold">{{ number_format($leaderboard[0]->total_poin) }} XP</div>
                </div>
            </div>

            @if($leaderboard->count() >= 3)
            <div class="col-4 col-md-3 order-3">
                <div class="podium-card text-center p-3">
                    <div class="avatar-wrap mb-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($leaderboard[2]->nama) }}&background=FFEDD5&color=9A3412"
                             class="rounded-circle border border-3 border-white shadow-sm"
                             style="width:58px;height:58px;">
                        <div class="rank-pip" style="background:#cd7f32;">3</div>
                    </div>
                    <div class="text-truncate small fw-bold text-white mb-1">{{ $leaderboard[2]->nama }}</div>
                    <div class="xp-chip">{{ number_format($leaderboard[2]->total_poin) }} XP</div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endif

@if($sertifikatJuara)
<div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
  <span style="font-size:2rem">
    @if($sertifikatJuara->rank === 1) 🥇
    @elseif($sertifikatJuara->rank === 2) 🥈
    @else 🥉
    @endif
  </span>
  <div>
    <strong>Selamat! Kamu meraih Juara {{ $sertifikatJuara->rank }}</strong>
    pada periode <em>{{ $sertifikatJuara->periode }}</em>
    dengan {{ number_format($sertifikatJuara->total_poin) }} poin.
    <br>
    <a href="{{ route('leaderboard.sertifikat-juara', ['periode' => $sertifikatJuara->periode]) }}"
       class="btn btn-sm btn-warning mt-2">
      ⬇️ Download Sertifikat Juara Kelas
    </a>
  </div>
</div>
@endif

@if($finalTerkunci && !$sertifikatJuara)
<div class="alert alert-info mb-4">
  Leaderboard periode <strong>{{ $finalTerkunci }}</strong> sudah dikunci.
  Ranking kamu: <strong>#{{ collect($leaderboard)->firstWhere('id', auth()->id())->rank ?? '-' }}</strong>
</div>
@endif

{{-- RANK LIST DETAIL --}}
<div class="card">
    <div class="card-header">
        <span class="fw-bold" style="color:var(--txt-primary);font-size:0.9rem;">
            <i class="fas fa-list-ol me-2" style="color:var(--clr-primary);"></i>
            Semua Peringkat
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:90px;">Rank</th>
                        <th>Siswa</th>
                        <th class="text-center">Skor XP</th>
                        <th class="text-center">Selesai</th>
                        <th class="pe-4 text-end">Apresiasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaderboard as $index => $item)
                    <tr class="{{ auth()->id() == $item->id ? 'row-me' : '' }}">
                        <td class="ps-4">
                            @if($index === 0)
                                <span class="icon-shape" style="background:#fef3c7;color:#d97706;">
                                    <i class="fas fa-trophy"></i>
                                </span>
                            @elseif($index === 1)
                                <span class="icon-shape" style="background:#f1f5f9;color:#64748b;">
                                    <i class="fas fa-medal"></i>
                                </span>
                            @elseif($index === 2)
                                <span class="icon-shape" style="background:#fff7ed;color:#cd7f32;">
                                    <i class="fas fa-medal"></i>
                                </span>
                            @else
                                <span class="fw-bold" style="color:var(--txt-tertiary);font-size:0.9rem;">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&size=36&rounded=true"
                                     style="width:36px;height:36px;border-radius:var(--border-radius-sm);">
                                <div>
                                    <div class="fw-bold" style="color:var(--txt-primary);font-size:0.875rem;">{{ $item->nama }}</div>
                                    @if(auth()->id() == $item->id)
                                    <span class="badge" style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.62rem;">KAMU</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="color:var(--clr-primary);font-size:1rem;">{{ number_format($item->total_poin) }}</span>
                            <div class="text-label" style="line-height:1;">XP</div>
                        </td>
                        <td class="text-center" style="color:var(--txt-secondary);font-size:0.82rem;">
                            {{ $item->jumlah_selesai }} tantangan
                        </td>
                        <td class="pe-4 text-end">
                            @if($index === 0) <i class="fas fa-trophy text-warning"></i>
                            @elseif($index < 3) <i class="fas fa-star text-warning"></i>
                            @else <i class="far fa-thumbs-up" style="color:var(--txt-tertiary);"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-trophy"></i></div>
                                <h6>Belum ada peringkat</h6>
                                <p>Belum ada aktivitas skor di kelas ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.podium-card {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: var(--border-radius-lg);
    backdrop-filter: blur(6px);
    transition: transform var(--transition);
}
.podium-card:hover { transform: translateY(-4px); }
.podium-gold {
    transform: translateY(-16px);
    background: rgba(255,255,255,0.18);
    border-color: rgba(255,215,0,0.35);
}
.podium-gold:hover { transform: translateY(-20px); }
.crown { line-height: 1; }
.avatar-wrap { position: relative; display: inline-block; }
.rank-pip {
    position: absolute;
    bottom: -4px; right: -4px;
    width: 22px; height: 22px;
    background: var(--clr-primary);
    color: #fff;
    border-radius: 50%;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
}
.xp-chip {
    display: inline-block;
    background: rgba(255,255,255,0.9);
    color: var(--clr-primary);
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}
.xp-chip-gold { background: var(--clr-warning); color: #fff; }
.row-me { background-color: rgba(var(--clr-primary-rgb), 0.04) !important; }
</style>
@endpush