@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="row g-4">
    {{-- STATS: TOTAL TANTANGAN --}}
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-center p-4">
                <i class="fas fa-tasks fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1 display-5">{{ number_format($tantanganCount ?? 0) }}</h3>
                <p class="mb-0 fw-semibold fs-6">Total Tantangan</p>
                <small class="opacity-75">Tantangan yang telah dibuat</small>
            </div>
        </div>
    </div>

    {{-- STATS: MAPEL DIAJAR --}}
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="card-body text-center p-4">
                <i class="fas fa-book fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-1 display-5">{{ number_format($mapelCount ?? 0) }}</h3>
                <p class="mb-0 fw-semibold fs-6">Mata Pelajaran</p>
                <small class="opacity-75">Aktif di semester ini</small>
            </div>
        </div>
    </div>

    {{-- STATS: TOTAL MATERI (Opsional/Tambahan) --}}
    <div class="col-lg-4 col-md-12">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <div class="card-body text-center p-4">
                <i class="fas fa-file-alt fa-3x mb-3 opacity-75 text-danger"></i>
                <h3 class="fw-bold mb-1 display-5 text-danger">{{ $materiCount ?? 0 }}</h3>
                <p class="mb-0 fw-semibold fs-6 text-danger">Total Materi</p>
                <small class="text-danger opacity-75">Materi edukasi terunggah</small>
            </div>
        </div>
    </div>

    {{-- TANTANGAN TERBARU --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient-primary text-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i> Tantangan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($recentTantangan) && $recentTantangan->count() > 0)
                    @foreach($recentTantangan as $t)
                    <div class="p-4 border-bottom hover-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                                    <i class="fas {{ $t->tipe == 'pg' ? 'fa-list-ul' : 'fa-pen-fancy' }}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ Str::limit($t->judul, 40) }}</h6>
                                    <div class="text-muted small">
                                        <span class="me-3"><i class="fas fa-book me-1"></i>{{ $t->mapel->nama_mapel ?? '-' }}</span>
                                        <span><i class="fas fa-users me-1"></i>{{ $t->kelas->nama_kelas ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill bg-{{ $t->tipe == 'pg' ? 'primary' : ($t->tipe == 'essay' ? 'warning' : 'info') }} mb-2">
                                    {{ strtoupper($t->tipe) }}
                                </span>
                                <br>
                                <a href="{{ route('guru.tantangan.show', $t->id) }}" class="btn btn-sm btn-light border">Detail</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                        <h5>Belum Ada Tantangan</h5>
                        <p class="small">Mulai buat tantangan untuk siswa Anda!</p>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white border-0 text-center py-3">
                <a href="{{ route('guru.tantangan.index') }}" class="text-primary fw-bold text-decoration-none">Lihat Semua Tantangan <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    {{-- PROFIL GURU SIDE --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient-info text-white border-0 py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2"></i> Identitas Guru</h6>
            </div>
            <div class="card-body p-4 text-center">
                <div class="avatar-lg bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                    <i class="fas fa-chalkboard-teacher fa-3x"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->nama }}</h5>
                <p class="text-muted small mb-4">NIP. {{ auth()->user()->username }}</p>

                <div class="list-group list-group-flush text-start">
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                        <span class="text-muted small">Status</span>
                        <span class="badge bg-success-soft text-success fw-bold">Tenaga Pendidik</span>
                    </div>
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Tugas</span>
                        <span class="fw-bold small text-primary">{{ $mapelCount ?? 0 }} Mata Pelajaran</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-outline-info w-100 rounded-3">
                        <i class="fas fa-user-edit me-2"></i> Update Profil
                    </button>
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
    .hover-row:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transition: 0.2s;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
    .bg-gradient-info { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%) !important; }
    .display-5 { font-size: 2.5rem; font-weight: 800; }
    .bg-success-soft { background-color: rgba(56, 239, 125, 0.15); }
    .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-warning:hover, .btn-outline-info:hover {
        color: white !important;
    }
</style>
@endpush