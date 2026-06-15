@extends('layouts.app')
@section('title', 'Leaderboard Kelas')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title">
            <i class="fas fa-trophy text-warning me-2"></i>Papan Peringkat
        </h4>
        <p class="small mb-0" style="color: var(--txt-secondary);">
            Kelas: <strong>{{ $namaKelas }}</strong>
        </p>
    </div>
    <a href="{{ route('guru.rekap.index') }}" class="btn btn-outline-primary rounded-pill px-4">
        <i class="fas fa-chart-bar me-2"></i>Rekap Nilai
    </a>
</div>

{{-- FILTER --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('guru.leaderboard') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Kelas</label>
                <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->kelas_id }}" {{ $kelasId == $k->kelas_id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Filter Mata Pelajaran</label>
                <select name="mapel" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $m)
                        <option value="{{ $m->mapel_id }}" {{ $mapelId == $m->mapel_id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                @if($mapelId)
                    <a href="{{ route('guru.leaderboard', ['kelas' => $kelasId]) }}"
                       class="btn btn-sm btn-light w-100">
                        <i class="fas fa-times me-1"></i>Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card mb-4 border-warning">
  <div class="card-header bg-warning-subtle fw-semibold">
    📋 Kunci Leaderboard Akhir Semester
  </div>
  <div class="card-body">

    @if($finalTerkunci)
      <div class="alert alert-success mb-3">
        ✅ Leaderboard periode <strong>{{ $finalTerkunci }}</strong> sudah dikunci.
        Siswa peringkat 1–3 dapat mengunduh sertifikat juara kelas.
      </div>

      {{-- Tabel snapshot final --}}
      <h6 class="mb-2">Peringkat Final — {{ $finalTerkunci }}</h6>
      <table class="table table-sm table-bordered mb-0">
        <thead class="table-light">
          <tr>
            <th>Rank</th><th>Nama</th><th>NIS</th>
            <th>Total Poin</th><th>Tantangan Selesai</th>
          </tr>
        </thead>
        <tbody>
          @foreach($finalLeaderboard as $row)
          <tr @class(['table-warning fw-bold' => $row->rank <= 3])>
            <td>
              @if($row->rank === 1) 🥇
              @elseif($row->rank === 2) 🥈
              @elseif($row->rank === 3) 🥉
              @else {{ $row->rank }}
              @endif
            </td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->nis }}</td>
            <td>{{ number_format($row->total_poin) }}</td>
            <td>{{ $row->jumlah_selesai }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p class="text-muted mb-3">
        Belum ada leaderboard yang dikunci. Kunci setelah UAS selesai agar ranking tidak berubah
        dan siswa peringkat 1–3 bisa mendapatkan sertifikat juara kelas.
      </p>
    @endif

    {{-- Form kunci baru (hanya tampil jika belum dikunci atau mau kunci periode lain) --}}
    <form method="POST" action="{{ route('leaderboard.kunci') }}"
          onsubmit="return confirm('Yakin mengunci leaderboard? Ranking saat ini akan disimpan permanen.')">
      @csrf
      <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
      <div class="input-group" style="max-width: 400px;">
        <input type="text" name="periode" class="form-control"
               placeholder="Contoh: 2025/2026 Genap" required
               value="{{ old('periode', date('Y') . '/' . (date('Y')+1) . ' Genap') }}">
        <button type="submit" class="btn btn-warning">
          🔒 Kunci Sekarang
        </button>
      </div>
      <small class="text-muted d-block mt-1">
        Pastikan semua siswa sudah selesai UAS sebelum mengunci.
      </small>
    </form>

  </div>
</div>

{{-- TOP 3 PODIUM --}}
@if($leaderboard->count() >= 1 && $leaderboard[0]->total_poin > 0)
<div class="card mb-4 overflow-hidden">
    <div class="card-body p-4" style="background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);">
        <div class="row align-items-end justify-content-center g-3">

            @if($leaderboard->count() >= 2)
            <div class="col-4 col-md-3 order-1">
                <div class="podium-card text-center p-3">
                    <div class="avatar-wrap mb-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($leaderboard[1]->nama) }}&background=E2E8F0&color=475569"
                             class="rounded-circle border border-3 border-white shadow-sm"
                             style="width:58px; height:58px;">
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
                             style="width:80px; height:80px;">
                        <div class="rank-pip" style="background: var(--clr-warning);">1</div>
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
                             style="width:58px; height:58px;">
                        <div class="rank-pip" style="background: #cd7f32;">3</div>
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

{{-- TABEL PERINGKAT --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span class="fw-bold" style="color:var(--txt-primary); font-size:0.9rem;">
            <i class="fas fa-list-ol me-2" style="color:var(--clr-primary);"></i>Semua Peringkat
        </span>
        <span class="badge" style="background:#d1fae5;color:#065f46;font-size:0.72rem;">
            <i class="fas fa-trophy me-1"></i>Hasil leaderboard dapat dijadikan nilai tambah
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:70px;">Rank</th>
                        <th>Siswa</th>
                        <th class="text-center" style="width:120px;">Tantangan Selesai</th>
                        <th class="text-center" style="width:110px;">Total XP</th>
                        <th class="text-center" style="width:130px;">Rata Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaderboard as $index => $item)
                    <tr>
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
                                <span class="fw-bold" style="color:var(--txt-tertiary);font-size:0.9rem;">
                                    #{{ $index + 1 }}
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&size=36&rounded=true"
                                     style="width:36px;height:36px;border-radius:var(--border-radius-sm);">
                                <div>
                                    <div class="fw-bold" style="color:var(--txt-primary);font-size:0.875rem;">
                                        {{ $item->nama }}
                                    </div>
                                    <div style="font-size:0.72rem;color:var(--txt-secondary);">
                                        NIS: {{ $item->nis ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="fw-bold" style="color:var(--txt-primary);">
                                {{ $item->jumlah_selesai }}
                            </span>
                            <div style="font-size:0.7rem;color:var(--txt-secondary);">selesai</div>
                        </td>

                        <td class="text-center">
                            <span class="fw-bold" style="color:var(--clr-primary);font-size:1rem;">
                                {{ number_format($item->total_poin) }}
                            </span>
                            <div style="font-size:0.7rem;color:var(--txt-secondary);">XP</div>
                        </td>

                        <td class="text-center">
                            @php
                                $menit = $item->rata_waktu > 0 ? round($item->rata_waktu / 60) : 0;
                            @endphp
                            <span class="fw-bold" style="color:var(--txt-primary);">
                                {{ $menit }}
                            </span>
                            <div style="font-size:0.7rem;color:var(--txt-secondary);">menit/tantangan</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h6>Belum ada data</h6>
                                <p>Belum ada siswa yang mengerjakan tantangan di kelas ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- INFO LUARAN --}}
<div class="card mt-3 p-3" style="background:var(--clr-primary-light);border-color:transparent;">
    <div class="d-flex gap-3 align-items-start">
        <i class="fas fa-info-circle mt-1" style="color:var(--clr-primary);"></i>
        <div style="font-size:0.82rem;color:var(--txt-secondary);">
            <strong style="color:var(--clr-primary);">Luaran Leaderboard:</strong>
            Data peringkat ini dapat Bapak/Ibu gunakan sebagai bahan pertimbangan
            nilai tambah pada penilaian tugas atau keaktifan sesuai kurikulum yang berlaku.
            Siswa peringkat atas menunjukkan tingkat partisipasi dan penguasaan materi yang lebih tinggi.
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
.crown { font-size: 1.4rem; line-height: 1; }
.avatar-wrap { position: relative; display: inline-block; }
.rank-pip {
    position: absolute; bottom: -4px; right: -4px;
    width: 22px; height: 22px;
    background: var(--clr-primary); color: #fff;
    border-radius: 50%; font-size: 0.7rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}
.xp-chip {
    display: inline-block;
    background: rgba(255,255,255,0.9); color: var(--clr-primary);
    padding: 2px 10px; border-radius: 20px;
    font-size: 0.75rem; font-weight: 700;
}
.xp-chip-gold { background: var(--clr-warning); color: #fff; }
</style>
@endpush