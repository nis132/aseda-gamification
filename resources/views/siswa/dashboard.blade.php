@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            Halo, {{ auth()->user()->nama }}! 👋
        </h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Selamat datang kembali di portal gamifikasi SMPN 2 Semen.
        </p>
    </div>
    <span class="badge" style="background: var(--bg-muted); color: var(--txt-secondary); border: 1px solid var(--border-color); font-weight: 500; font-size: 0.78rem;">
        <i class="fas fa-calendar me-1"></i>{{ now()->translatedFormat('d M Y') }}
    </span>
</div>

{{-- FILTER MAPEL GLOBAL --}}
<div class="card border-0 mb-4">
    <div class="card-body p-3">

        <form method="GET" action="{{ route('siswa.dashboard') }}">
            <div class="row align-items-center g-2">

                <div class="col-md-3">
                    <label class="fw-semibold mb-1"
                           style="font-size: 0.82rem; color: var(--txt-secondary);">
                        <i class="fas fa-filter me-1"></i>Filter Mata Pelajaran
                    </label>

                    <select name="mapel"
                            class="form-select"
                            onchange="this.form.submit()"
                            style="border-radius: 12px; font-size: 0.85rem;">

                        <option value="">
                            Semua Mata Pelajaran
                        </option>

                        @foreach($statsPerMapel as $stat)
                            <option value="{{ $stat['mapel_id'] }}"
                                {{ $selectedMapel == $stat['mapel_id'] ? 'selected' : '' }}>
                                {{ $stat['nama_mapel'] }}
                            </option>
                        @endforeach

                    </select>
                </div>

                @if($selectedMapel)
                <div class="col-md-auto">
                    <label class="d-block mb-1 opacity-0">Reset</label>

                    <a href="{{ route('siswa.dashboard') }}"
                       class="btn btn-outline-secondary"
                       style="border-radius: 12px;">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
                @endif

            </div>
        </form>

    </div>
</div>

<div class="row g-3 mb-4">

    {{-- TOTAL POIN --}}
    <div class="col-lg-3 col-md-6">
        <div class="card card-stat border-0 h-100"
             style="border-left: 3px solid var(--clr-warning) !important; border-radius: var(--border-radius-lg);">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-warning">
                        <i class="fas fa-crown"></i>
                    </div>

                    <span class="badge"
                          style="background: #fef3c7; color: #92400e; font-weight: 700;">
                        #{{ $rankKelas }}
                    </span>
                </div>

                <div class="stat-number mb-1">
                    {{ number_format($totalPoin) }}
                </div>

                <div class="text-label">Total Poin</div>

                <small style="color: var(--txt-secondary); font-size: 0.78rem;">
                    Ranking kelas
                </small>
            </div>
        </div>
    </div>

    {{-- TANTANGAN SELESAI --}}
    <div class="col-lg-3 col-md-6">
        <div class="card card-stat border-0 h-100"
             style="border-left: 3px solid var(--clr-primary) !important; border-radius: var(--border-radius-lg);">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-primary">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>

                <div class="stat-number mb-1">
                    {{ $totalTantanganSelesai }}<span style="font-size: 0.5em; color: var(--txt-secondary);">/{{ $totalTantanganTersedia }}</span>
                </div>

                <div class="text-label">Tantangan Selesai</div>

                <div style="margin-top: 0.75rem;">
                    <div class="progress" style="height: 6px; border-radius: 3px; background: var(--bg-muted); overflow: hidden;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $persenTantangan }}%; background: var(--clr-primary);"
                             aria-valuenow="{{ $persenTantangan }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <small style="color: var(--clr-primary); font-size: 0.75rem; font-weight: 600; display: inline-block; margin-top: 0.35rem;">
                        {{ $persenTantangan }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- MATERI SELESAI --}}
    <div class="col-lg-3 col-md-6">
        <div class="card card-stat border-0 h-100"
             style="border-left: 3px solid var(--clr-success) !important; border-radius: var(--border-radius-lg);">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-book-reader"></i>
                    </div>
                </div>

                <div class="stat-number mb-1">
                    {{ $totalMateriSelesai }}<span style="font-size: 0.5em; color: var(--txt-secondary);">/{{ $totalMateriTersedia }}</span>
                </div>

                <div class="text-label">Materi Selesai</div>

                <div style="margin-top: 0.75rem;">
                    <div class="progress" style="height: 6px; border-radius: 3px; background: var(--bg-muted); overflow: hidden;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $persenMateri }}%; background: var(--clr-success);"
                             aria-valuenow="{{ $persenMateri }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <small style="color: var(--clr-success); font-size: 0.75rem; font-weight: 600; display: inline-block; margin-top: 0.35rem;">
                        {{ $persenMateri }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- BELUM SELESAI --}}
    <div class="col-lg-3 col-md-6">
        <div class="card card-stat border-0 h-100"
             style="border-left: 3px solid var(--clr-danger) !important; border-radius: var(--border-radius-lg);">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-danger">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>

                <div class="stat-number mb-1">
                    {{ $totalBelumSelesai }}<span style="font-size: 0.5em; color: var(--txt-secondary);">/{{ $totalTantanganTersedia }}</span>
                </div>

                <div class="text-label">Belum Selesai</div>

                <div style="margin-top: 0.75rem;">
                    <div class="progress" style="height: 6px; border-radius: 3px; background: var(--bg-muted); overflow: hidden;">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-3">

    {{-- TANTANGAN TERBARU --}}
    <div class="col-lg-8">
        <div class="card border-0 h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon stat-icon-warning"
                         style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h6 class="mb-0 fw-bold">Tantangan Terbaru</h6>
                </div>
                <a href="{{ route('siswa.tantangan') }}"
                   class="btn btn-outline-primary btn-sm" style="border-radius: 99px; padding: 0.3rem 1rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($tantanganAktif->take(5) as $tantangan)
                            <tr>
                                <td class="ps-4 py-3" style="width: 60px;">
                                    <div class="icon-shape stat-icon-primary">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-truncate-2 mb-1" style="font-size: 0.875rem; max-width: 220px;">
                                        {{ $tantangan->judul }}
                                    </div>
                                    <div style="font-size: 0.78rem; color: var(--txt-secondary);">
                                        <i class="fas fa-book-open me-1"></i>{{ $tantangan->mapel->nama_mapel ?? '-' }}
                                        @if($tantangan->bab)
                                            <span class="mx-1">·</span>
                                            <i class="fas fa-layer-group me-1"></i>Bab {{ $tantangan->bab }}
                                        @endif
                                    </div>
                                </td>
                                <td style="width: 110px;">
                                    <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 0.7rem;">
                                        {{ $tantangan->soal_count ?? 0 }} Soal
                                    </span>
                                    <div style="font-size: 0.78rem; color: var(--clr-warning); font-weight: 600; margin-top: 3px;">
                                        +{{ $tantangan->poin }} Poin
                                    </div>
                                </td>
                                <td class="pe-4 text-end" style="width: 110px;">
                                    @if($tantangan->batas_waktu > now())
                                        @if($tantangan->nilaiTantangan->isEmpty())
                                            <a href="{{ route('siswa.tantangan.kerjakan', $tantangan) }}"
                                               class="btn btn-primary btn-action">
                                                <i class="fas fa-play me-1"></i>Kerjakan
                                            </a>
                                        @else
                                            <span class="badge" style="background: #d1fae5; color: #065f46; padding: 0.4em 0.75em;">
                                                <i class="fas fa-check me-1"></i>Selesai
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 0.4em 0.75em;">
                                            Terlambat
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <h6>Belum ada tantangan aktif</h6>
                                        <p>Cek kembali materi atau hubungi gurumu.</p>
                                        <a href="{{ route('siswa.materi') }}" class="btn btn-primary btn-sm">
                                            Lihat Materi
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR KANAN --}}
    <div class="col-lg-4 d-flex flex-column gap-3">

        <div class="card border-0">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="stat-icon stat-icon-success"
                     style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                    <i class="fas fa-link"></i>
                </div>
                <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">Akses Cepat</h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-4 text-center">
                        <a href="{{ route('siswa.materi') }}"
                           class="d-block p-2 text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-primary mx-auto mb-1"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-book"></i>
                            </div>
                            <span style="font-size: 0.72rem; font-weight: 600; color: var(--txt-primary);">Materi</span>
                        </a>
                    </div>
                    <div class="col-4 text-center">
                        <a href="{{ route('siswa.tantangan') }}"
                           class="d-block p-2 text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-warning mx-auto mb-1"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <span style="font-size: 0.72rem; font-weight: 600; color: var(--txt-primary);">Ujian</span>
                        </a>
                    </div>
                    <div class="col-4 text-center">
                        <a href="{{ route('leaderboard') }}"
                           class="d-block p-2 text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-info mx-auto mb-1"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <span style="font-size: 0.72rem; font-weight: 600; color: var(--txt-primary);">Skor</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body p-4 text-center">
                <div class="position-relative d-inline-block mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold shadow-sm"
                         style="width: 72px; height: 72px; font-size: 1.5rem;
                                background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <div class="position-absolute bottom-0 end-0 rounded-circle"
                         style="width: 16px; height: 16px; background: var(--clr-success); border: 2px solid white;"></div>
                </div>
                <h6 class="fw-bold mb-0">{{ auth()->user()->nama }}</h6>
                <small style="color: var(--txt-secondary);">
                    Kelas {{ auth()->user()->kelasIds()->first() ?? '-' }}
                </small>
                <div class="row g-2 my-3">
                    <div class="col-6">
                        <div class="p-2 rounded-2" style="background: var(--bg-muted);">
                            <div style="font-size: 0.72rem; color: var(--txt-secondary);">Poin</div>
                            <div style="font-size: 0.95rem; font-weight: 700; color: var(--txt-primary);">
                                {{ number_format($totalPoin) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2" style="background: var(--bg-muted);">
                            <div style="font-size: 0.72rem; color: var(--txt-secondary);">Ranking</div>
                            <div style="font-size: 0.95rem; font-weight: 700; color: var(--txt-primary);">
                                #{{ $rankKelas }}
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('siswa.profil') }}"
                   class="btn btn-outline-primary btn-sm w-100" style="border-radius: 99px;">
                    <i class="fas fa-user-edit me-2"></i>Lihat Profil Lengkap
                </a>
            </div>
        </div>

    </div>
</div>


@endsection

@push('styles')
<style>
.btn-mapel-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 99px;
    border: 1px solid var(--border-color);
    background: transparent;
    color: var(--txt-secondary);
    font-size: 0.78rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    line-height: 1.4;
}
.btn-mapel-chip:hover {
    border-color: var(--clr-primary);
    color: var(--txt-primary);
    background: var(--bg-muted);
}
.btn-mapel-chip.active {
    background: var(--clr-primary);
    border-color: var(--clr-primary);
    color: #fff;
}
.btn-mapel-chip.active .chip-count {
    opacity: 0.8;
}
.chip-count {
    font-size: 0.72rem;
    opacity: 0.6;
}
.chip-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.btn-mapel-chip.active .chip-dot {
    background: rgba(255,255,255,0.8) !important;
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    const group   = document.getElementById('mapelFilterGroup');
    const cards   = document.querySelectorAll('.mapel-card-col');
    const buttons = group.querySelectorAll('.btn-mapel-chip');

    function applyFilter(mapelId) {
        cards.forEach(function (col) {
            col.style.display = (mapelId === 'all' || col.dataset.mapel === String(mapelId)) ? '' : 'none';
        });
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            buttons.forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            applyFilter(this.dataset.mapel);
        });
    });
})();
</script>
@endpush