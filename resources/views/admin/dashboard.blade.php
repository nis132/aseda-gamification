@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="display-5 fw-bold text-primary">
            <i class="fas fa-tachometer-alt me-3"></i>
            Dashboard Admin
        </h1>
        <p class="lead text-muted">Kelola sistem gamifikasi SMPN 2 Semen</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-primary h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-primary mb-1">{{ $stats['total_siswa'] }}</h3>
                        <p class="text-muted mb-0">Total Siswa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-success h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-3 me-3">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-success mb-1">{{ $stats['total_guru'] }}</h3>
                        <p class="text-muted mb-0">Total Guru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-warning h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle p-3 me-3">
                        <i class="fas fa-user-shield fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-warning mb-1">{{ $stats['total_admin'] }}</h3>
                        <p class="text-muted mb-0">Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Sistem
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-star fa-3x text-warning mb-2"></i>
                        <h4 class="fw-bold text-primary">Gamifikasi Aktif</h4>
                        <p class="text-muted">Poin, Badge, Leaderboard</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-book fa-3x text-success mb-2"></i>
                        <h4 class="fw-bold text-success">Materi Terstruktur</h4>
                        <p class="text-muted">Per Mata Pelajaran & Kelas</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="fas fa-gamepad fa-3x text-info mb-2"></i>
                        <h4 class="fw-bold text-info">Tantangan Beragam</h4>
                        <p class="text-muted">PG, Essay, Matching</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
