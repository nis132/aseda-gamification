@extends('layouts.app')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chalkboard me-2" style="color: var(--clr-primary); font-size: 1.1rem;"></i>
            Kelas {{ $kelas->nama_kelas }}
        </h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Informasi detail dan daftar siswa dalam kelas ini.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.kelas.edit', $kelas) }}" class="btn btn-light">
            <i class="fas fa-edit me-2"></i>Ubah
        </a>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-3">
    <div class="col-md-6 col-sm-6">
        <div class="card card-stat border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-primary">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="text-label">Total Siswa</div>
                </div>
                <div class="stat-number">{{ $siswa->count() }}</div>
                <small style="color: var(--txt-secondary); font-size: 0.78rem;">
                    Terdaftar di kelas ini
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-6">
        <div class="card card-stat border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="text-label">Dibuat</div>
                </div>
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--txt-primary);">
                    {{ $kelas->created_at->translatedFormat('d M Y') }}
                </div>
                <small style="color: var(--txt-secondary); font-size: 0.78rem;">
                    {{ $kelas->created_at->diffForHumans() }}
                </small>
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR SISWA --}}
<div class="card border-0">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="stat-icon stat-icon-primary"
                 style="width: 32px; height: 32px; font-size: 0.85rem; border-radius: 8px;">
                <i class="fas fa-users"></i>
            </div>
            <h6 class="mb-0 fw-bold">Daftar Siswa</h6>
        </div>
        <span class="badge" style="background: var(--clr-primary-light); color: var(--clr-primary); font-size: 0.75rem;">
            {{ $siswa->count() }} siswa
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Nama Siswa</th>
                        <th style="width: 180px;">Username</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                    <tr>
                        {{-- No --}}
                        <td class="ps-4">
                            <span style="font-size: 0.78rem; color: var(--txt-tertiary); font-weight: 600;">
                                {{ $index + 1 }}
                            </span>
                        </td>

                        {{-- Nama --}}
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                     style="width: 38px; height: 38px;
                                            background: var(--clr-primary-light);
                                            color: var(--clr-primary);
                                            font-size: 0.9rem;">
                                    {{ strtoupper(substr($s->nama, 0, 1)) }}
                                </div>
                                <div class="fw-bold" style="font-size: 0.875rem; color: var(--txt-primary);">
                                    {{ $s->nama }}
                                </div>
                            </div>
                        </td>

                        {{-- Username --}}
                        <td>
                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2"
                                  style="background: var(--bg-muted); border: 1px solid var(--border-color);
                                         font-size: 0.8rem; font-weight: 600; color: var(--txt-secondary);">
                                <i class="fas fa-at" style="font-size: 0.7rem;"></i>
                                {{ $s->username }}
                            </span>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h6>Belum ada siswa</h6>
                                <p>Belum ada siswa yang terdaftar di kelas ini.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Tambah Siswa
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

@endsection