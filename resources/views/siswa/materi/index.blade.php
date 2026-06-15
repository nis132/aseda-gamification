@extends('layouts.app')

@section('title', 'Materi Pelajaran')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-book-reader me-2" style="color: var(--clr-primary);"></i>
            Perpustakaan Materi
        </h1>
        <p style="color: var(--txt-secondary); font-size: 0.85rem; margin: 0;">
            Kelas {{ $kelasId ?? '-' }} &nbsp;·&nbsp;
            Menampilkan <strong>{{ $materis->count() }}</strong> dari {{ $materis->total() }} materi
        </p>
    </div>
    <div style="min-width: 220px;">
        <form method="GET" id="filterForm">
            <label class="form-label">Filter Mata Pelajaran</label>
            <select name="mapel" class="form-select" onchange="this.form.submit()">
                <option value="">— Semua Mapel —</option>
                @foreach($mapels as $mapel)
                    <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                        {{ $mapel->nama_mapel }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- RINGKASAN PROGRES --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-primary"><i class="fas fa-book"></i></div>
                <div>
                    <div class="stat-number">{{ $totalMateri }}</div>
                    <div class="text-label">Total Materi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-success"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-number">{{ $totalSelesai }}</div>
                    <div class="text-label">Sudah Selesai</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-warning"><i class="fas fa-hourglass-half"></i></div>
                <div>
                    <div class="stat-number">{{ $totalMateri - $totalSelesai }}</div>
                    <div class="text-label">Belum Selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PROGRESS BAR --}}
@if($totalMateri > 0)
<div class="mb-4">
    @php $persen = round(($totalSelesai / $totalMateri) * 100); @endphp
    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem; color: var(--txt-secondary);">
        <span>Progres Keseluruhan</span>
        <span class="fw-bold" style="color: var(--txt-primary);">{{ $persen }}%</span>
    </div>
    <div class="progress" style="height: 8px; border-radius: 99px;">
        <div class="progress-bar"
             style="width: {{ $persen }}%; background: var(--clr-success); border-radius: 99px; transition: width 0.6s ease;">
        </div>
    </div>
</div>
@endif

{{-- INFO LEVEL --}}
<div class="d-flex align-items-center gap-2 mb-3 px-1">
    <span class="badge px-3 py-2"
          style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.78rem;">
        <i class="fas fa-bolt me-1"></i>Level kamu: {{ $levelSiswa }}
    </span>
    <span style="font-size:0.78rem;color:var(--txt-secondary);">
        ✨ Semua materi terbuka untuk semua siswa. Pelajari sesuai kecepatan kamu sendiri!
    </span>
</div>

{{-- LIST MATERI --}}
<div class="row g-4">
    @forelse($materis as $item)
    @php
        $sudahSelesai  = in_array($item->id, $selesaiIds);
        $bab           = $item->bab_display ?? $item->bab ?? $item->level_required ?? 1;
        $babColors = [
            1=>['bg'=>'#d1fae5','text'=>'#065f46'], 2=>['bg'=>'#dcfce7','text'=>'#166534'],
            3=>['bg'=>'#dbeafe','text'=>'#0c2d48'], 4=>['bg'=>'#e0e7ff','text'=>'#3730a3'],
            5=>['bg'=>'#ede9fe','text'=>'#5b21b6'], 6=>['bg'=>'#fce7f3','text'=>'#9d174d'],
            7=>['bg'=>'#fee2e2','text'=>'#7c2d12'], 8=>['bg'=>'#fef3c7','text'=>'#92400e'],
        ];
        $lc = $babColors[$bab] ?? $babColors[1];
    @endphp
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card h-100 hover-lift"
             style="overflow:hidden; {{ $sudahSelesai ? 'border:1.5px solid var(--clr-success);' : '' }}">

            {{-- TOP ACCENT --}}
            <div style="height:4px; width:100%;
                background:{{ $sudahSelesai ? 'var(--clr-success)' : 'var(--clr-primary)' }};"></div>

            {{-- BADGE STATUS & BAB --}}
            <div class="position-absolute d-flex gap-1" style="top:16px; right:16px; flex-wrap:wrap; justify-content:flex-end;">
                {{-- Badge bab --}}
                <span class="badge" style="background:{{ $lc['bg'] }};color:{{ $lc['text'] }};font-size:0.65rem;">
                    <i class="fas fa-book me-1"></i>Bab {{ $bab }}
                </span>
                @if($sudahSelesai)
                    <span class="badge" style="background:#d1fae5;color:var(--clr-success);font-size:0.65rem;">
                        <i class="fas fa-check-circle me-1"></i>Selesai
                    </span>
                @else
                    <span class="badge" style="background:#fef3c7;color:#92400e;font-size:0.65rem;">
                        <i class="far fa-clock me-1"></i>Belum
                    </span>
                @endif
                @if($item->file_url)
                    <span class="badge" style="background:#fee2e2;color:var(--clr-danger);font-size:0.65rem;">
                        <i class="far fa-file-pdf me-1"></i>PDF
                    </span>
                @endif
            </div>

            <div class="card-body p-4 d-flex flex-column">

                {{-- MAPEL LABEL --}}
                <div class="mb-2">
                    <span class="text-label" style="color:var(--clr-primary);">
                        {{ $item->mapel->nama_mapel ?? 'Umum' }}
                    </span>
                </div>

                {{-- JUDUL --}}
                <h6 class="fw-bold mb-2" style="color:var(--txt-primary);line-height:1.45;">
                    {{ Str::limit($item->judul, 45) }}
                </h6>

                {{-- DESKRIPSI --}}
                <p class="flex-grow-1 mb-4" style="font-size:0.83rem;color:var(--txt-secondary);">
                    {{ Str::limit(strip_tags($item->deskripsi), 85) }}
                </p>

                <hr style="border-color:var(--border-color);margin-bottom:0.85rem;">

                {{-- GURU & KELAS --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="icon-shape"
                         style="background:var(--bg-muted);color:var(--txt-secondary);border-radius:50%;">
                        <i class="fas fa-user-tie" style="font-size:0.8rem;"></i>
                    </div>
                    <div style="overflow:hidden;">
                        <div class="fw-semibold text-truncate" style="font-size:0.83rem;color:var(--txt-primary);">
                            {{ $item->guru->nama ?? 'Guru Pengampu' }}
                        </div>
                        <div style="font-size:0.75rem;color:var(--txt-secondary);">
                            Kelas {{ $item->kelas->nama_kelas ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <span style="font-size:0.78rem;color:var(--txt-tertiary);">
                        <i class="far fa-clock me-1"></i>{{ $item->created_at->diffForHumans() }}
                    </span>
                    <a href="{{ route('siswa.materi.show', $item) }}"
                       class="btn btn-action {{ $sudahSelesai ? 'btn-light' : 'btn-primary' }}">
                            {{ $sudahSelesai ? 'Baca Lagi' : 'Pelajari' }}
                            <i class="fas fa-arrow-right ms-1" style="font-size:0.75rem;"></i>
                        </a>
                </div>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body empty-state">
                <div class="empty-state-icon"><i class="fas fa-book-open"></i></div>
                <h6>Belum Ada Materi</h6>
                <p>Materi pelajaran untuk kategori ini belum tersedia.</p>
                <a href="{{ route('siswa.materi') }}" class="btn btn-outline-primary">
                    <i class="fas fa-sync me-2"></i>Refresh Halaman
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if($materis->hasPages())
<div class="mt-5 d-flex justify-content-center">
    {{ $materis->links('pagination::bootstrap-5') }}
</div>
@endif

@push('styles')
<style>
.materi-locked {
    filter: grayscale(35%);
    border: 1px dashed #ced4da !important;
}
.materi-locked:hover { transform: none !important; box-shadow: none !important; }
.pagination { gap: 4px; }
.page-item .page-link {
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--border-color);
    color: var(--clr-primary);
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.45rem 0.9rem;
    transition: all var(--transition);
    box-shadow: none;
}
.page-item .page-link:hover { background: var(--clr-primary-light); border-color: var(--clr-primary); }
.page-item.active .page-link { background: var(--clr-primary); border-color: var(--clr-primary); color: #fff; }
.page-item.disabled .page-link { color: var(--txt-tertiary); }
</style>
@endpush

@endsection