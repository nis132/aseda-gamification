@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-gradient-primary text-white shadow-lg border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tasks fa-2x opacity-75"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1">Total Tantangan</h5>
                        <h2 class="mb-0">{{ $tantanganCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-gradient-success text-white shadow-lg border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-book fa-2x opacity-75"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1">Mapel Diajar</h5>
                        <h2 class="mb-0">{{ $mapelCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tantangan -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card shadow-lg border-0 h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="mb-0"><i class="fas fa-clock me-2 text-primary"></i>Tantangan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($recentTantangan) && $recentTantangan->count() > 0)
                    @foreach($recentTantangan as $t)
                    <div class="border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $t->judul }}</h6>
                                <div class="text-muted small">
                                    <i class="fas fa-book me-1"></i>{{ $t->mapel->nama_mapel }}
                                    <i class="fas fa-users ms-3 me-1"></i>{{ $t->kelas->nama_kelas }}
                                </div>
                            </div>
                            <span class="badge bg-{{ $t->tipe == 'pg' ? 'primary' : ($t->tipe == 'essay' ? 'warning' : 'info') }}">
                                {{ strtoupper($t->tipe) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-tasks fa-3x mb-3 opacity-50"></i>
                        <h5>Belum ada tantangan</h5>
                        <a href="{{ route('guru.tantangan.create') }}" class="btn btn-primary">Buat Tantangan Pertama</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <a href="{{ route('guru.tantangan.index') }}" class="btn btn-success btn-lg px-4">
            <i class="fas fa-tasks me-2"></i>Kelola Semua Tantangan
        </a>
    </div>
</div>
@endsection
