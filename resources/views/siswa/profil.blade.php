@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profil Card -->
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Profil Saya
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <i class="fas fa-user-graduate fa-4x text-primary"></i>
                            </div>
                            <h5 class="mb-1">{{ $profil->name }}</h5>
                            <p class="text-muted mb-2">NIS: {{ $profil->nis ?? '-' }}</p>
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                @php
                                    $kelas = $profil->kelas->first() ?? (object)['nama_kelas' => '-'];
                                @endphp
                                Kelas {{ $kelas->nama_kelas ?? '-' }}
                            </span>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-tasks fa-2x text-success mb-2"></i>
                                            <h3 class="mb-0">{{ $tantanganSelesai }}</h3>
                                            <small class="text-muted">Tantangan Selesai</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-coins fa-2x text-warning mb-2"></i>
                                            <h3 class="mb-0">{{ $leaderboard?->total_poin ?? 0 }} <small>XP</small></h3>
                                            <small class="text-muted">Total Poin</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Card -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Peringkat Kelas
                    </h6>
                </div>
                <div class="card-body">
                    @if($leaderboard && isset($leaderboard->rank) && $leaderboard->rank)
                        <div class="text-center mb-4">
                            <div class="position-relative">
                                <i class="fas fa-trophy fa-3x text-warning mb-2"></i>
                                <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-warning">
                                    #{{ $leaderboard->rank }}
                                </span>
                            </div>
                            <h5 class="text-success mb-0">Peringkat {{ $leaderboard->rank }}</h5>
                            <small class="text-muted">
                                Kelas {{ $kelas->nama_kelas ?? '-' }}
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-2x text-muted mb-2"></i>
                            <h6 class="text-muted">Belum masuk peringkat</h6>
                            <small>Selesaikan tantangan untuk bersaing!</small>
                        </div>
                    @endif
                    <hr>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ min((($leaderboard?->total_poin ?? 0) / 1000) * 100, 100) }}%"
                             aria-valuenow="{{ $leaderboard?->total_poin ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="1000">
                            {{ number_format($leaderboard?->total_poin ?? 0, 0, ',', '.') }} XP
                        </div>
                    </div>
                    <small class="text-muted">Target: 1000 XP untuk Top 3</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Level & Badge (sama seperti sebelumnya) -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-level-up-alt me-2"></i>Level Progres</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Level 1 <span class="badge bg-success rounded-pill">✅ Tercapai</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Level 2 <span class="badge bg-warning rounded-pill">{{ $tantanganSelesai >= 3 ? '✅' : '⏳' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Level 3 <span class="badge bg-secondary rounded-pill">{{ $tantanganSelesai >= 6 ? '✅' : '⏳' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Level 4 <span class="badge bg-secondary rounded-pill">{{ $tantanganSelesai >= 9 ? '✅' : '⏳' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Level 5 (Max) <span class="badge bg-danger rounded-pill">{{ $tantanganSelesai >= 12 ? '🏆' : '🔒' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-purple text-white">
                    <h6 class="mb-0"><i class="fas fa-medal me-2"></i>Badge Pencapaian</h6>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">Badge akan muncul saat kamu mencapainya!</p>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="badge-container p-3 rounded-3 bg-light">
                                <i class="fas fa-clock text-muted fa-2x mb-2"></i>
                                <small class="text-muted">Keaktifan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="badge-container p-3 rounded-3 bg-light">
                                <i class="fas fa-star text-warning fa-2x mb-2"></i>
                                <small class="text-muted">Quiz 80+</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="badge-container p-3 rounded-3 bg-light">
                                <i class="fas fa-crown text-primary fa-2x mb-2"></i>
                                <small class="text-muted">Top 3</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-end">
            <a href="{{ route('siswa.tantangan') }}" class="btn btn-lg btn-success">
                <i class="fas fa-rocket me-2"></i> Lanjut Tantangan
            </a>
            <a href="{{ route('siswa.leaderboard') }}" class="btn btn-lg btn-outline-primary ms-2">
                <i class="fas fa-trophy me-2"></i> Cek Leaderboard
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-purple { background-color: #6f42c1; }
.avatar-lg { width: 120px; height: 120px; }
.progress { border-radius: 10px; }
.badge-container { transition: all 0.3s ease; }
.badge-container:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
</style>
@endpush
