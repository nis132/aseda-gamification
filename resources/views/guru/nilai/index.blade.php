@extends('layouts.app')

@section('title', 'Penilaian - ' . $tantangan->judul)

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <a href="{{ route('guru.tantangan.show', $tantangan->id) }}"
           class="btn btn-light btn-action mb-2">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail
        </a>
        <h1 class="page-title">
            <i class="fas fa-user-check me-2" style="color: var(--clr-primary);"></i>
            Penilaian Tantangan
        </h1>
        <p style="color: var(--txt-secondary); font-size: 0.85rem; margin: 0;">
            {{ $tantangan->judul }}
        </p>
    </div>

    {{-- Counter siswa mengumpulkan --}}
    <div class="card card-stat" style="min-width: 140px; text-align: center;">
        <div class="card-body py-3 px-4">
            <div class="stat-number" style="color: var(--clr-primary);">{{ $jawaban->count() }}</div>
            <div class="text-label mt-1">Siswa Mengumpulkan</div>
        </div>
    </div>
</div>

@if($jawaban->isEmpty())

    <div class="card">
        <div class="card-body empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <h6>Belum Ada Jawaban Masuk</h6>
            <p>Belum ada siswa yang mengumpulkan tantangan ini.</p>
        </div>
    </div>

@else

@php
    $hasPg       = false;
    $hasMatching = false;
    $hasEssay    = false;
    foreach($tantangan->soal as $s) {
        if($s->tipe == 'pg')       $hasPg       = true;
        if($s->tipe == 'matching') $hasMatching = true;
        if($s->tipe == 'essay')    $hasEssay    = true;
    }

    // Hitung berapa siswa yang sudah bisa lihat review
    $reviewBukaCount = 0;
    $reviewTutupCount = 0;
    foreach($jawaban as $siswaId => $listJawaban) {
        $nilaiTantangan = \App\Models\NilaiTantangan::where('siswa_id', $siswaId)
            ->where('tantangan_id', $tantangan->id)->first();
        if ($nilaiTantangan && $nilaiTantangan->review_dibuka_pada) {
            $reviewBukaCount++;
        } else {
            $reviewTutupCount++;
        }
    }
@endphp

{{-- SECTION KONTROL REVIEW --}}
<div class="card mb-4" style="border-left: 4px solid var(--clr-info); background: #f0f7ff;">
    <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 class="mb-0 fw-bold" style="color: var(--clr-info);">
                <i class="fas fa-book-open me-2"></i>Status Review
            </h6>
            <small style="color: var(--txt-secondary);">
                {{ $reviewBukaCount }} siswa dapat melihat · {{ $reviewTutupCount }} siswa belum dapat melihat
            </small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if($reviewTutupCount > 0)
                <form method="POST" action="{{ route('guru.review.buka', $tantangan->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-unlock me-1"></i>Buka Review Semua
                    </button>
                </form>
            @endif
            @if($reviewBukaCount > 0)
                <form method="POST" action="{{ route('guru.review.tutup', $tantangan->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-lock me-1"></i>Tutup Review Semua
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="card">

    {{-- CARD HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 class="fw-bold mb-0" style="color: var(--txt-primary);">Rekap Penilaian</h6>
            <small style="color: var(--txt-secondary);">
                @if($hasEssay)
                    PG &amp; Matching otomatis · Essay dinilai manual
                @else
                    Semua soal dinilai otomatis
                @endif
            </small>
        </div>
        <div class="input-group" style="width: 220px;">
            <span class="input-group-text"
                  style="background: var(--bg-muted); border: 1px solid var(--border-color); border-right: none;">
                <i class="fas fa-search" style="font-size: 0.8rem; color: var(--txt-tertiary);"></i>
            </span>
            <input type="text" id="searchSiswa" class="form-control" placeholder="Cari siswa..."
                   style="border-left: none;">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">No</th>
                    <th>Siswa</th>
                    @if($hasPg)       <th class="text-center">Pilihan Ganda</th> @endif
                    @if($hasMatching) <th class="text-center">Matching</th>      @endif
                    @if($hasEssay)    <th class="text-center">Essay</th>         @endif
                    <th class="text-center">Nilai Akhir</th>
                    <th class="text-center">Predikat</th>
                    <th class="text-center">Review</th>
                    <th class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($jawaban as $siswaId => $listJawaban)
                @php
                    $siswa        = $listJawaban->first()->siswa;
                    $pg           = $listJawaban->filter(fn($j) => $j->soal->tipe == 'pg');
                    $matching     = $listJawaban->filter(fn($j) => $j->soal->tipe == 'matching');
                    $essay        = $listJawaban->filter(fn($j) => $j->soal->tipe == 'essay');
                    $nilaiPg      = $pg->count()       ? round($pg->avg('nilai'), 1)       : null;
                    $nilaiMatching= $matching->count() ? round($matching->avg('nilai'), 1) : null;
                    $nilaiEssay   = $essay->count()    ? round($essay->avg('nilai'), 1)    : null;

                    $totalNilai = 0; $totalBobot = 0;
                    if($nilaiPg       !== null) { $totalNilai += $nilaiPg;       $totalBobot++; }
                    if($nilaiMatching !== null) { $totalNilai += $nilaiMatching; $totalBobot++; }
                    if($nilaiEssay    !== null) { $totalNilai += $nilaiEssay;    $totalBobot++; }
                    $nilaiAkhir = $totalBobot > 0 ? round($totalNilai / $totalBobot, 1) : 0;
                    $essayBelumDinilai = $essay->whereNull('nilai')->count();
                @endphp
                <tr class="siswa-row">

                    <td class="ps-4" style="color: var(--txt-secondary); font-size: 0.82rem;">{{ $loop->iteration }}</td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-shape"
                                 style="background: var(--clr-primary-light); color: var(--clr-primary); font-weight: 700; font-size: 0.8rem; border-radius: 50%;">
                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold nama-siswa" style="font-size: 0.875rem; color: var(--txt-primary);">{{ $siswa->nama }}</div>
                                <small style="color: var(--txt-secondary);">{{ $siswa->nis ?? '-' }}</small>
                            </div>
                        </div>
                    </td>

                    @if($hasPg)
                    <td class="text-center">
                        @if($nilaiPg !== null)
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-success);">{{ $nilaiPg }}</span>
                        @else
                            <span style="color: var(--txt-tertiary);">—</span>
                        @endif
                    </td>
                    @endif

                    @if($hasMatching)
                    <td class="text-center">
                        @if($nilaiMatching !== null)
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-info);">{{ $nilaiMatching }}</span>
                        @else
                            <span style="color: var(--txt-tertiary);">—</span>
                        @endif
                    </td>
                    @endif

                    @if($hasEssay)
                    <td class="text-center">
                        @if($essay->count() > 0)
                            @if($essayBelumDinilai > 0)
                                <a href="{{ route('guru.nilai.detail', [$tantangan->id, $siswaId]) }}"
                                   class="btn btn-action"
                                   style="background: var(--clr-warning); color: #fff;">
                                    <i class="fas fa-pen me-1"></i>Nilai
                                </a>
                            @else
                                <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-success);">{{ $nilaiEssay }}</span>
                            @endif
                        @else
                            <span style="color: var(--txt-tertiary);">—</span>
                        @endif
                    </td>
                    @endif

                    <td class="text-center">
                        <span class="fw-bold" style="font-size: 1rem;
                            color: {{ $nilaiAkhir >= 75 ? 'var(--clr-success)' : ($nilaiAkhir >= 60 ? 'var(--clr-warning)' : 'var(--clr-danger)') }};">
                            {{ $nilaiAkhir }}
                        </span>
                    </td>

                    <td class="text-center">
                        @if($nilaiAkhir >= 85)
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-success);">Sangat Baik</span>
                        @elseif($nilaiAkhir >= 75)
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-primary);">Baik</span>
                        @elseif($nilaiAkhir >= 60)
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-warning); color: #fff;">Cukup</span>
                        @else
                            <span class="badge rounded-pill px-3 py-2" style="background: var(--clr-danger);">Perlu Bimbingan</span>
                        @endif
                    </td>

                    {{-- KOLOM REVIEW STATUS --}}
                    <td class="text-center">
                        @php
                            $nilaiTantangan = \App\Models\NilaiTantangan::where('siswa_id', $siswaId)
                                ->where('tantangan_id', $tantangan->id)->first();
                            $reviewDibuka = $nilaiTantangan && $nilaiTantangan->review_dibuka_pada;
                        @endphp
                        
                        @if($reviewDibuka)
                            <span class="badge rounded-pill px-2 py-2" style="background: #d1fae5; color: var(--clr-success); font-weight: 600;">
                                <i class="fas fa-unlock-alt me-1"></i>Dibuka
                            </span>
                            <form method="POST" action="{{ route('guru.review.tutup', $tantangan->id) }}" class="d-inline mt-1">
                                @csrf
                                <input type="hidden" name="siswa_id" value="{{ $siswaId }}">
                                <button type="submit" class="btn btn-xs btn-light" style="font-size: 0.7rem;">
                                    <i class="fas fa-lock me-1"></i>Tutup
                                </button>
                            </form>
                        @else
                            <span class="badge rounded-pill px-2 py-2" style="background: #fee2e2; color: var(--clr-danger); font-weight: 600;">
                                <i class="fas fa-lock me-1"></i>Tertutup
                            </span>
                            <form method="POST" action="{{ route('guru.review.buka', $tantangan->id) }}" class="d-inline mt-1">
                                @csrf
                                <input type="hidden" name="siswa_id" value="{{ $siswaId }}">
                                <button type="submit" class="btn btn-xs btn-light" style="font-size: 0.7rem;">
                                    <i class="fas fa-unlock-alt me-1"></i>Buka
                                </button>
                            </form>
                        @endif
                    </td>

                    <td class="text-center pe-4">
                        <a href="{{ route('guru.nilai.detail', [$tantangan->id, $siswaId]) }}"
                           class="btn btn-outline-primary btn-action">
                            <i class="fas fa-eye me-1"></i>Detail
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- FOOTER LEGENDA --}}
    <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--border-color); display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
        <span class="text-label me-1">Predikat:</span>
        <span class="badge px-3 py-2" style="background: var(--clr-success);">85–100 Sangat Baik</span>
        <span class="badge px-3 py-2" style="background: var(--clr-primary);">75–84 Baik</span>
        <span class="badge px-3 py-2" style="background: var(--clr-warning); color: #fff;">60–74 Cukup</span>
        <span class="badge px-3 py-2" style="background: var(--clr-danger);">&lt; 60 Perlu Bimbingan</span>
    </div>

</div>

@endif

@push('scripts')
<script>
document.getElementById('searchSiswa')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.siswa-row').forEach(row => {
        const nama = row.querySelector('.nama-siswa')?.textContent.toLowerCase() ?? '';
        row.style.display = nama.includes(q) ? '' : 'none';
    });
});
</script>
@endpush

@endsection