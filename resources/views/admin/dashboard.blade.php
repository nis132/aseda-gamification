@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row g-4">
    {{-- STATS: TOTAL SISWA --}}
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-center p-4">
                <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1 display-5">{{ number_format($stats['total_siswa']) }}</h3>
                <p class="mb-0 fw-semibold fs-6">Total Siswa</p>
                <small class="opacity-75">Terdaftar di sistem</small>
            </div>
        </div>
    </div>

    {{-- STATS: TOTAL GURU --}}
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="card-body text-center p-4">
                <i class="fas fa-chalkboard-teacher fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1 display-5">{{ number_format($stats['total_guru']) }}</h3>
                <p class="mb-0 fw-semibold fs-6">Total Guru</p>
                <small class="opacity-75">Tenaga pendidik aktif</small>
            </div>
        </div>
    </div>

    {{-- STATS: TOTAL ADMIN --}}
    <div class="col-lg-4 col-md-12">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);">
            <div class="card-body text-center p-4">
                <i class="fas fa-user-shield fa-3x mb-3 opacity-75 text-danger"></i>
                <h3 class="fw-bold mb-1 display-5 text-danger">{{ number_format($stats['total_admin']) }}</h3>
                <p class="mb-0 fw-semibold fs-6 text-danger">Administrator</p>
                <small class="text-danger opacity-75">Pengelola sistem</small>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-primary text-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-th-large me-2"></i> Panel Kendali Cepat
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 rounded-4 border-2 shadow-sm hover-lift">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle mb-2">
                                <i class="fas fa-users-cog fa-2x text-primary"></i>
                            </div>
                            <span class="fw-bold fs-6">Kelola User</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 rounded-4 border-2 shadow-sm hover-lift">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle mb-2">
                                <i class="fas fa-chalkboard fa-2x text-success"></i>
                            </div>
                            <span class="fw-bold fs-6">Kelola Kelas</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 rounded-4 border-2 shadow-sm hover-lift">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle mb-2">
                                <i class="fas fa-book fa-2x text-info"></i>
                            </div>
                            <span class="fw-bold fs-6">Mata Pelajaran</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO SISTEM --}}
        <div class="card border-0 shadow-lg mt-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i> Status Fitur Gamifikasi
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 border-end">
                        <div class="p-3">
                            <i class="fas fa-check-circle text-success mb-2"></i>
                            <p class="mb-0 small text-muted">Sistem Poin</p>
                            <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                        </div>
                    </div>
                    <div class="col-md-4 border-end">
                        <div class="p-3">
                            <i class="fas fa-check-circle text-success mb-2"></i>
                            <p class="mb-0 small text-muted">Leaderboard</p>
                            <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <i class="fas fa-check-circle text-success mb-2"></i>
                            <p class="mb-0 small text-muted">Notifikasi PWA</p>
                            <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PROFIL ADMIN CARD --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-lg h-100 overflow-hidden">
            <div class="card-header bg-gradient-info text-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-user-shield me-2"></i> Administrator
                </h6>
            </div>
            <div class="card-body p-4 text-center">
                <div class="avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                    <i class="fas fa-user-tie fa-3x"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->nama }}</h5>
                <span class="badge bg-primary rounded-pill px-3 py-2 mb-4">Super Admin</span>
                
                <div class="list-group list-group-flush text-start">
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                        <span class="text-muted small"><i class="fas fa-id-badge me-2"></i>Username</span>
                        <span class="fw-bold">{{ auth()->user()->username }}</span>
                    </div>
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                        <span class="text-muted small"><i class="fas fa-calendar-alt me-2"></i>Login Terakhir</span>
                        <span class="fw-bold small">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="#" class="btn btn-primary w-100 rounded-3 shadow-sm">
                        <i class="fas fa-cog me-2"></i> Pengaturan Akun
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card, .hover-lift {
        border-radius: 20px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: none !important;
    }
    .stat-card:hover, .hover-lift:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important;
    }
    .bg-gradient-primary { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; 
    }
    .bg-gradient-info { 
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important; 
    }
    .display-5 { 
        font-size: 2.5rem; 
        font-weight: 800; 
        letter-spacing: -1px;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-info:hover {
        color: white !important;
    }
</style>
@endpush