@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Admin</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Selamat datang kembali — kendalikan ekosistem belajar hari ini.
        </p>
    </div>
    <div class="text-end d-none d-md-block">
        <div class="fw-bold" style="font-size: 1rem;" id="current-time">--:--</div>
        <small style="color: var(--txt-secondary);">{{ now()->translatedFormat('l, d F Y') }}</small>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- STAT: Total Siswa --}}
    <div class="col-lg-4 col-md-6">
        <div class="card card-stat h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="badge" style="background: var(--clr-primary-light); color: var(--clr-primary);">
                        Real-time
                    </span>
                </div>
                <div class="stat-number mb-1">{{ number_format($stats['total_siswa']) }}</div>
                <div class="text-label">Total Siswa</div>
                <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                    <small style="color: var(--txt-secondary);">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; color: var(--clr-success);"></i>
                        Siswa terdaftar aktif
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- STAT: Total Guru --}}
    <div class="col-lg-4 col-md-6">
        <div class="card card-stat h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <span class="badge" style="background: #d1fae5; color: #065f46;">Aktif</span>
                </div>
                <div class="stat-number mb-1">{{ number_format($stats['total_guru']) }}</div>
                <div class="text-label">Total Guru</div>
                <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                    <small style="color: var(--txt-secondary);">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; color: var(--clr-success);"></i>
                        Tenaga pendidik aktif
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- STAT: Administrator --}}
    <div class="col-lg-4 col-md-12">
        <div class="card card-stat h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon stat-icon-danger">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="badge" style="background: #fee2e2; color: #991b1b;">Super Admin</span>
                </div>
                <div class="stat-number mb-1">{{ number_format($stats['total_admin']) }}</div>
                <div class="text-label">Administrator</div>
                <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                    <small style="color: var(--txt-secondary);">
                        <i class="fas fa-lock me-1" style="font-size: 0.75rem; color: var(--clr-danger);"></i>
                        Akses penuh sistem
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- NAVIGASI CEPAT --}}
    <div class="col-lg-8">
        <div class="card border-0 mb-3">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="stat-icon stat-icon-primary" style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                    <i class="fas fa-rocket"></i>
                </div>
                <h6 class="mb-0 fw-bold">Navigasi Cepat</h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('admin.users.index') }}"
                           class="d-block p-3 border rounded-3 text-decoration-none hover-lift text-center"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-primary mx-auto mb-2">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div class="fw-bold mb-0" style="font-size: 0.85rem; color: var(--txt-primary);">Kelola User</div>
                            <div style="font-size: 0.75rem; color: var(--txt-secondary);">Edit, Tambah, Hapus</div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.kelas.index') }}"
                           class="d-block p-3 border rounded-3 text-decoration-none hover-lift text-center"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-success mx-auto mb-2">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <div class="fw-bold mb-0" style="font-size: 0.85rem; color: var(--txt-primary);">Kelola Kelas</div>
                            <div style="font-size: 0.75rem; color: var(--txt-secondary);">Grup & Rombel</div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.mapel.index') }}"
                           class="d-block p-3 border rounded-3 text-decoration-none hover-lift text-center"
                           style="border-color: var(--border-color) !important;">
                            <div class="stat-icon stat-icon-info mx-auto mb-2">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="fw-bold mb-0" style="font-size: 0.85rem; color: var(--txt-primary);">Mata Pelajaran</div>
                            <div style="font-size: 0.75rem; color: var(--txt-secondary);">Kurikulum</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATUS GAMIFIKASI --}}
        <div class="card border-0">
            <div class="card-body p-3">
                <div class="text-label mb-3">Status Fitur Gamifikasi</div>
                <div class="d-flex flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-2"
                         style="background: #d1fae5; border: 1px solid #a7f3d0;">
                        <i class="fas fa-check-circle" style="color: var(--clr-success); font-size: 0.85rem;"></i>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #065f46;">Sistem XP</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-2"
                         style="background: #d1fae5; border: 1px solid #a7f3d0;">
                        <i class="fas fa-check-circle" style="color: var(--clr-success); font-size: 0.85rem;"></i>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #065f46;">Leaderboard</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-2"
                         style="background: #fef3c7; border: 1px solid #fde68a;">
                        <i class="fas fa-sync fa-spin" style="color: var(--clr-warning); font-size: 0.85rem;"></i>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #92400e;">PWA Sync</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-2"
                         style="background: #d1fae5; border: 1px solid #a7f3d0;">
                        <i class="fas fa-check-circle" style="color: var(--clr-success); font-size: 0.85rem;"></i>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #065f46;">Sistem Badge</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PROFIL ADMIN --}}
    <div class="col-lg-4">
        <div class="card border-0 h-100">
            {{-- Cover --}}
            <div class="p-4 text-center"
                 style="background: linear-gradient(135deg, var(--clr-primary-light) 0%, #e0e7ff 100%);
                        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&background=6366f1&color=fff&size=128"
                     alt="Avatar"
                     class="rounded-circle shadow-sm"
                     style="width: 80px; height: 80px; border: 3px solid #fff;">
            </div>

            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <h6 class="fw-bold mb-0">{{ auth()->user()->nama }}</h6>
                    <span class="text-label" style="color: var(--clr-primary);">Super Administrator</span>
                </div>

                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="d-flex justify-content-between align-items-center py-2 px-3 rounded-2"
                         style="background: var(--bg-muted);">
                        <span style="font-size: 0.8rem; color: var(--txt-secondary);">Username</span>
                        <span style="font-size: 0.82rem; font-weight: 600;">{{ auth()->user()->username }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 px-3 rounded-2"
                         style="background: var(--bg-muted);">
                        <span style="font-size: 0.8rem; color: var(--txt-secondary);">Akses Terakhir</span>
                        <span style="font-size: 0.82rem; font-weight: 600;">{{ now()->format('H:i') }} WIB</span>
                    </div>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">
                        <i class="fas fa-cog me-2"></i>Pengaturan Sistem
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTime() {
    const el = document.getElementById('current-time');
    if (el) el.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}
setInterval(updateTime, 1000);
updateTime();
</script>
@endpush