@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

{{-- WELCOME BANNER --}}
<div class="card border-0 mb-4 overflow-hidden"
     style="background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-md-9">
                <p class="mb-1" style="color: rgba(255,255,255,0.65); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                    Selamat datang
                </p>
                <h2 class="fw-bold mb-1 text-white">{{ auth()->user()->nama }}</h2>
                <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.875rem;">
                    Siap menginspirasi siswa hari ini? Kelola tantangan dan materi di sini.
                </p>
            </div>
            <div class="col-md-3 text-md-end">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
                     style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                    <i class="fas fa-calendar-alt" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;"></i>
                    <span style="color: rgba(255,255,255,0.9); font-size: 0.8rem; font-weight: 600;">
                        {{ now()->translatedFormat('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="card card-stat border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-primary">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="text-label">Total Tantangan</div>
                </div>
                <div class="stat-number">{{ number_format($tantanganCount ?? 0) }}</div>
                <small style="color: var(--clr-success); font-weight: 600; font-size: 0.78rem;">
                    <i class="fas fa-plus me-1"></i>Misi aktif
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card card-stat border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="text-label">Mata Pelajaran</div>
                </div>
                <div class="stat-number">{{ number_format($mapelCount ?? 0) }}</div>
                <small style="color: var(--clr-primary); font-weight: 600; font-size: 0.78rem;">
                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Aktif diajar
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card card-stat border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-warning">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="text-label">Materi</div>
                </div>
                <div class="stat-number">{{ $materiCount ?? 0 }}</div>
                <small style="color: var(--clr-warning); font-weight: 600; font-size: 0.78rem;">
                    <i class="fas fa-book-open me-1"></i>Materi
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- AKTIVITAS TERBARU --}}
    <div class="col-lg-8">
        <div class="card border-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon stat-icon-primary"
                         style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                        <i class="fas fa-history"></i>
                    </div>
                    <h6 class="mb-0 fw-bold">Aktivitas Terbaru</h6>
                </div>
                <a href="{{ route('guru.tantangan.index') }}"
                   class="btn btn-outline-primary btn-sm" style="border-radius: 99px; padding: 0.3rem 1rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($recentTantangan ?? [] as $t)
                            <tr>
                                <td class="ps-4 py-3" style="width: 56px;">
                                    <div class="icon-shape {{ $t->tipe == 'pg' ? 'stat-icon-primary' : 'stat-icon-warning' }}">
                                        <i class="fas {{ $t->tipe == 'pg' ? 'fa-list-ul' : 'fa-pen-fancy' }}"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold" style="font-size: 0.875rem; color: var(--txt-primary);">
                                        {{ Str::limit($t->judul, 38) }}
                                    </div>
                                    <div style="font-size: 0.78rem; color: var(--txt-secondary);">
                                        {{ $t->mapel->nama_mapel ?? '-' }}
                                        <span class="mx-1">·</span>
                                        {{ $t->kelas->nama_kelas ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center" style="width: 90px;">
                                    @if($t->tipe == 'pg')
                                        <span class="badge" style="background: var(--clr-primary-light); color: var(--clr-primary);">PG</span>
                                    @else
                                        <span class="badge" style="background: #fef3c7; color: #92400e;">Esai</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end" style="width: 100px;">
                                    <a href="{{ route('guru.tantangan.show', $t->id) }}"
                                       class="btn btn-light btn-action">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <h6>Belum ada tantangan</h6>
                                        <p>Mulai dengan membuat tantangan pertama untuk siswa.</p>
                                        <a href="{{ route('guru.tantangan.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Buat Tantangan
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
        {{-- PROFIL --}}
        <div class="card border-0">
            <div class="text-center p-4"
                 style="background: var(--clr-primary-light);
                        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=6366f1&color=fff&size=128"
                     class="rounded-circle mb-2"
                     style="width: 72px; height: 72px; border: 3px solid #fff; box-shadow: var(--shadow-sm);">
                <h6 class="fw-bold mb-0">{{ auth()->user()->nama }}</h6>
                <small style="color: var(--txt-secondary);">NIP. {{ auth()->user()->username }}</small>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 rounded-2"
                         style="background: var(--bg-muted);">
                        <span style="font-size: 0.8rem; color: var(--txt-secondary);">Jabatan</span>
                        <span class="badge" style="background: #d1fae5; color: #065f46;">Guru Aktif</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 rounded-2"
                         style="background: var(--bg-muted);">
                        <span style="font-size: 0.8rem; color: var(--txt-secondary);">Aktivitas</span>
                        <span style="font-size: 0.82rem; font-weight: 600;">Hari Ini</span>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-outline-primary btn-sm" style="border-radius: 99px;">
                        <i class="fas fa-user-edit me-2"></i>Edit Profil
                    </button>
                </div>
            </div>
        </div>

        {{-- AKSES CEPAT --}}
        <div class="card border-0">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">Akses Cepat</h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('guru.rekap.index') }}"
                           class="d-block p-3 text-center text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-danger mx-auto mb-2"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: var(--txt-primary);">
                                Rekap Nilai
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('guru.tantangan.create') }}"
                           class="d-block p-3 text-center text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-primary mx-auto mb-2"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: var(--txt-primary);">
                                Tantangan
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/guru/materi"
                           class="d-block p-3 text-center text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-success mx-auto mb-2"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: var(--txt-primary);">
                                Materi
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="/guru/tantangan"
                           class="d-block p-3 text-center text-decoration-none border rounded-2 hover-lift"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-warning mx-auto mb-2"
                                 style="width:36px; height:36px; font-size:0.9rem; border-radius:8px;">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: var(--txt-primary);">
                                Semua Misi
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection