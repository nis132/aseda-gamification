@extends('layouts.app')  <!-- ✅ WAJIB INI -->
@section('title', 'Tantangan ' . $tantangan->judul)

@section('content')
<!-- Header Tantangan -->
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h2 fw-bold mb-1">{{ $tantangan->judul }}</h1>
                <div class="d-flex align-items-center gap-3 text-muted small">
                    <span><i class="fas fa-book me-1"></i>{{ $tantangan->mapel->nama_mapel }}</span>
                    <span><i class="fas fa-users me-1"></i>Kelas {{ $tantangan->kelas->nama_kelas }}</span>
                    <span><i class="fas fa-clock me-1"></i>Batas: {{ $tantangan->batas_waktu->format('d M Y H:i') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('guru.tantangan.edit', $tantangan) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('guru.tantangan.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
<!-- Tabs: Soal | Progres Siswa -->
<div class="card shadow-lg border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-gradient-primary text-white p-0">
        <ul class="nav nav-tabs border-0 flex-nowrap overflow-auto mb-0" id="tantanganTabs" role="tablist">
            <li class="nav-item flex-grow-1" role="presentation">
                <button class="nav-link w-100 active border-end border-white border-opacity-25 rounded-0" 
                        id="soal-tab" data-bs-toggle="tab" data-bs-target="#soal" type="button">
                    <i class="fas fa-list-ol me-2"></i>Soal 
                    <span class="badge bg-white bg-opacity-20 ms-1">{{ $tantangan->soal->count() ?? 0 }}</span>
                </button>
            </li>
            <li class="nav-item flex-grow-1" role="presentation">
                <button class="nav-link w-100 border-end border-white border-opacity-25 rounded-0" 
                        id="progres-tab" data-bs-toggle="tab" data-bs-target="#progres" type="button">
                    <i class="fas fa-chart-bar me-2"></i>Progres
                    <span class="badge bg-white bg-opacity-20 ms-1">{{ $tantangan->nilaiTantangan->count() ?? 0 }}</span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content p-0" id="tantanganTabsContent">
        <!-- 🔥 TAB SOAL - PREMIUM DESIGN -->
        <div class="tab-pane fade show active p-4" id="soal" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-list-ol me-2 text-primary"></i>
                    Daftar Soal
                </h5>
                <a href="{{ route('guru.soal.create', $tantangan) }}" 
                   class="btn btn-success btn-lg px-4 shadow-sm hover-scale">
                    <i class="fas fa-plus me-2"></i>Tambah Soal
                </a>
            </div>

            @if($tantangan->soal->count() > 0)
                <div class="row g-4">
                    @foreach($tantangan->soal as $index => $soal)
                    <div class="col-xl-4 col-lg-6">
                        <div class="card h-100 border-0 shadow-sm hover-lift position-relative overflow-hidden">
                            <div class="card-header bg-gradient-light border-0 pt-4 pb-3 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary px-3 py-2 fw-bold fs-6">
                                        Soal {{ $index + 1 }}
                                    </span>
                                    <div class="dropdown dropstart">
                                        <button class="btn btn-sm btn-outline-secondary p-0" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu shadow-lg">
                                            <li>
                                                <form action="{{ route('guru.soal.destroy', $soal) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="dropdown-item text-danger" onclick="return confirm('Yakin hapus soal ini?')">
                                                        <i class="fas fa-trash me-2"></i>Hapus Soal
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4 pb-2">
                                <h6 class="fw-bold mb-3 text-truncate-2">{{ Str::limit($soal->pertanyaan, 80) }}</h6>
                                
                                @if($soal->opsi_a || $soal->opsi_b)
                                <div class="opsi-list small mb-3">
                                    @if($soal->opsi_a)
                                    <div class="opsi-item mb-2 p-2 bg-light rounded-2">
                                        <strong class="text-primary">A.</strong> {{ Str::limit($soal->opsi_a, 60) }}
                                    </div>
                                    @endif
                                    @if($soal->opsi_b)
                                    <div class="opsi-item mb-2 p-2 bg-light rounded-2">
                                        <strong class="text-primary">B.</strong> {{ Str::limit($soal->opsi_b, 60) }}
                                    </div>
                                    @endif
                                    @if($soal->opsi_c)
                                    <div class="opsi-item mb-2 p-2 bg-light rounded-2">
                                        <strong class="text-primary">C.</strong> {{ Str::limit($soal->opsi_c, 60) }}
                                    </div>
                                    @endif
                                    @if($soal->opsi_d)
                                    <div class="opsi-item mb-2 p-2 bg-light rounded-2">
                                        <strong class="text-primary">D.</strong> {{ Str::limit($soal->opsi_d, 60) }}
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @if($soal->jawaban_benar)
                            <div class="position-absolute bottom-0 end-0 start-0 bg-gradient-success text-white text-center py-2 fw-bold">
                                <i class="fas fa-lock me-1"></i> ✅ KUNCI: {{ $soal->jawaban_benar }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center py-10 bg-gradient-light rounded-4 border-dashed border-3 border-primary">
                    <div class="mb-5">
                        <div class="empty-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" 
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-question-circle fa-3x text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-3">Belum ada soal</h3>
                        <p class="text-muted lead mb-0">Mulai buat soal pertama untuk tantangan "{{ $tantangan->judul }}"</p>
                    </div>
                    <a href="{{ route('guru.soal.create', $tantangan) }}" class="btn btn-primary btn-lg px-5 py-3 shadow-lg">
                        <i class="fas fa-plus-circle me-2"></i>Mulai Buat Soal
                    </a>
                </div>
            @endif
        </div>

        <!-- 🔥 TAB PROGRES SISWA - PREMIUM CHARTS -->
        <div class="tab-pane fade p-4" id="progres" role="tabpanel">
            <div class="row mb-5">
                <div class="col-md-3">
                    <div class="card bg-gradient-primary text-white shadow-lg border-0 h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                            <div class="h2 mb-1 fw-bold">{{ $siswaCount ?? 0 }}</div>
                            <div class="h6 mb-0 opacity-90">Total Siswa</div>
                            <small class="opacity-75">Kelas {{ $tantangan->kelas->nama_kelas }}</small>
                        </div>
                    </div>
                </div>
<div class="row mb-5">
    <!-- Total Siswa -->
    <div class="col-md-3">
        <div class="card bg-gradient-primary text-white shadow-lg border-0 h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                <div class="h2 mb-1 fw-bold">{{ $siswaCount ?? 0 }}</div>
                <div class="h6 mb-0 opacity-90">Total Siswa</div>
                <small class="opacity-75">
                    Kelas {{ $tantangan->kelas->nama_kelas ?? '' }}
                </small>
            </div>
        </div>
    </div>
    
    <!-- Sudah Submit - ✅ FIXED -->
    <div class="col-md-3">
        <div class="card bg-gradient-success text-white shadow-lg border-0 h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-clipboard-check fa-3x mb-3 opacity-75"></i>
                <div class="h2 mb-1 fw-bold">{{ $tantangan->nilaiTantangan->count() }}</div>
                <div class="h6 mb-0 opacity-90">Sudah Submit</div>
                @php 
                    $submitCount = $tantangan->nilaiTantangan->count();
                    $totalSiswa = $siswaCount ?? 0;
                    $percentage = $totalSiswa > 0 ? round(($submitCount / $totalSiswa) * 100, 1) : 0;
                @endphp
                <small class="opacity-75">{{ $percentage }}%</small>
            </div>
        </div>
    </div>
    
    <!-- Progress Distribusi -->
    <div class="col-md-6">
        <div class="card shadow-lg border-0 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3">
                    <i class="fas fa-chart-pie me-2"></i>Distribusi Nilai
                </h6>
                @php 
                    $totalSubmit = max(1, $tantangan->nilaiTantangan->count());
                    $sangatBaik = $tantangan->nilaiTantangan->where('total_nilai', '>=', 80)->count();
                    $baik = $tantangan->nilaiTantangan->where('total_nilai', '>=', 60)->diff($tantangan->nilaiTantangan->where('total_nilai', '>=', 80))->count();
                    $perluBelajar = $tantangan->nilaiTantangan->where('total_nilai', '<', 60)->count();
                @endphp
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-success" 
                         style="width: {{ round(($sangatBaik / $totalSubmit) * 100, 1) }}%">
                        {{ $sangatBaik }} (Sangat Baik)
                    </div>
                    <div class="progress-bar bg-warning" 
                         style="width: {{ round(($baik / $totalSubmit) * 100, 1) }}%">
                        {{ $baik }} (Baik)
                    </div>
                    <div class="progress-bar bg-danger" 
                         style="width: {{ round(($perluBelajar / $totalSubmit) * 100, 1) }}%">
                        {{ $perluBelajar }} (Perlu Belajar)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- ✅ TABS CONTENT YANG KAMU PAKAI (paste kode premium disini) -->
        <!-- ... seluruh tabs soal + progres ... -->
    </div>
</div>
@endsection