@extends('layouts.app')
@section('title', $tantangan->judul)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0"><i class="fas fa-tasks me-2"></i>{{ $tantangan->judul }}</h2>
                            <p class="mb-0 opacity-75">{{ $tantangan->deskripsi }}</p>
                            <div class="mt-2">
                                <span class="badge bg-info fs-6 me-2">{{ $tantangan->mapel->nama_mapel }} - {{ $tantangan->kelas->nama_kelas }}</span>
                                <span class="badge bg-secondary fs-6 me-2">{{ $tantangan->poin }} Poin</span>
                                <span class="badge bg-warning fs-6 me-2">{{ $tantangan->status == 'draft' ? 'Draft' : 'Published' }}</span>
                            </div>
                        </div>
{{-- PUBLISH SECTION --}}
                        <div class="mb-4">
                            @if($tantangan->status == 'published')
                                <span class="badge bg-success fs-5 px-3 py-2">
                                    <i class="fas fa-check-circle me-2"></i>Telah Dipublikasikan
                                </span>
                            @elseif($tantangan->soal->count() < 3)
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Minimal 3 soal untuk publikasikan ({{ $tantangan->soal->count() }}/3)
                                </div>
                            @else
                                {{-- ✅ FORM PUBLISH --}}
                                <form action="{{ url('guru/tantangan/' . $tantangan->id . '/publish') }}" 
                                    method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg px-4 shadow" 
                                            onclick="return confirm('Publikasikan tantangan?')">
                                        <i class="fas fa-globe me-2"></i>Publikasikan
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- STATS --}}
                    <div class="row mb-4 g-3">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-list fa-2x mb-2 opacity-75"></i>
                                    <div class="h5 mb-0">{{ $tantangan->soal->count() }}</div>
                                    <small>Soal</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                                    <div class="h5 mb-0">{{ $tantangan->batas_waktu->format('d/m H:i') }}</div>
                                    <small>Batas Waktu</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-coins fa-2x mb-2 opacity-75"></i>
                                    <div class="h5 mb-0">{{ $tantangan->poin }}</div>
                                    <small>Total Poin</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-2x mb-2 opacity-75"></i>
                                    <div class="h5 mb-0">{{ $siswaCount ?? 0 }}</div>
                                    <small>Siswa</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DAFTAR SOAL --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Daftar Soal 
                            <span class="badge bg-primary rounded-pill">{{ $tantangan->soal->count() }}</span>
                        </h5>
                        <a href="{{ route('guru.soal.create', $tantangan) }}" class="btn btn-success">
                            <i class="fas fa-file me-2"></i>Buat Soal Baru
                        </a>
                        <a href="{{ route('guru.nilai.index', $tantangan->id) }}" 
                        class="btn btn-primary">
                            <i class="fas fa-check-double me-2"></i>Lihat & Nilai Jawaban
                        </a>
                    </div>

                    @if($tantangan->soal->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-feather fa-4x text-muted mb-4 opacity-50"></i>
                            <h4 class="text-muted mb-3">Belum ada soal</h4>
                            <p class="text-muted mb-4">Tambahkan minimal 3 soal untuk publikasikan tantangan.</p>
                            <a href="{{ route('guru.soal.create', $tantangan) }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-file me-2"></i>Mulai Buat Soal
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($tantangan->soal as $soal)
                                <div class="col-xl-4 col-lg-6">
                                    <div class="card h-100 shadow-sm border-0 position-relative">
                                        {{-- HEADER --}}
                                        <div class="card-header bg-gradient-{{ $soal->tipe == 'pg' ? 'primary' : ($soal->tipe == 'matching' ? 'info' : 'warning') }} text-white py-3">
                                            <span class="badge rounded-pill px-3 py-2 fs-6">
                                                <i class="fas fa-{{ $soal->tipe == 'pg' ? 'list-ol' : ($soal->tipe == 'matching' ? 'link' : 'edit') }} me-1"></i>
                                                {{ strtoupper($soal->tipe) }}
                                            </span>
                                        </div>

                                        {{-- BODY --}}
                                        {{-- PREVIEW SOAL --}}
<div class="card-body">
    <h6 class="card-title fw-bold mb-3">{{ Str::limit($soal->pertanyaan, 70) }}</h6>
    
    @if($soal->opsi_a)
        <div class="options-preview small mb-3">
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-light text-dark me-2">A</span>
                {{ Str::limit($soal->opsi_a, 30) }}
            </div>
            @if($soal->opsi_b)
            <div class="d-flex align-items-center mb-1">
                <span class="badge bg-light text-dark me-2">B</span>
                {{ Str::limit($soal->opsi_b, 30) }}
            </div>
            @endif
        </div>
    @endif
    
    <div class="text-muted small fst-italic">
        Jawaban: <strong>{{ $soal->jawaban_benar }}</strong>
    </div>
</div>


                                        {{-- FOOTER --}}
                                        <div class="card-footer bg-transparent pt-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    {{ $soal->created_at->diffForHumans() }}
                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('guru.soal.destroy', [$tantangan, $soal]) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus soal?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
