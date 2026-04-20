@extends('layouts.app')
@section('title', $tantangan->judul)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 overflow-hidden">
                {{-- HEADER DENGAN GRADIENT BARU --}}
                <div class="card-header bg-primary py-4 text-white">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="mb-3 mb-md-0">
                            <h2 class="fw-bold mb-1"><i class="fas fa-tasks me-2"></i>{{ $tantangan->judul }}</h2>
                            <p class="mb-0 opacity-75">{{ Str::limit($tantangan->deskripsi, 100) }}</p>
                            <div class="mt-3">
                                <span class="badge bg-white text-primary px-3 py-2 me-2">
                                    <i class="fas fa-book-reader me-1"></i> {{ $tantangan->mapel->nama_mapel }}
                                </span>
                                <span class="badge bg-white text-primary px-3 py-2 me-2">
                                    <i class="fas fa-door-open me-1"></i> Kelas {{ $tantangan->kelas->nama_kelas }}
                                </span>
                                <span class="badge {{ $tantangan->status == 'published' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                                    <i class="fas {{ $tantangan->status == 'published' ? 'fa-check-circle' : 'fa-edit' }} me-1"></i>
                                    {{ ucfirst($tantangan->status) }}
                                </span>
                            </div>
                        </div>

{{-- ACTION BUTTON --}}
<div>
    @if($tantangan->status !== 'published')
        <button type="button" class="btn btn-light btn-lg fw-bold px-4 text-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#publishModal">
            <i class="fas fa-paper-plane me-2"></i>Publikasikan
        </button>
    @else
        <form action="{{ url('guru/tantangan/' . $tantangan->id . '/unpublish') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-lg fw-bold px-4 shadow-sm" onclick="return confirm('Tarik kembali tantangan ini dari siswa?')">
                <i class="fas fa-undo me-2"></i>Unpublish
            </button>
        </form>
    @endif
</div>

<div class="modal fade" id="publishModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Pilih Kelas Tujuan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('guru/tantangan/' . $tantangan->id . '/publish') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted mb-3">Tantangan ini akan dimunculkan pada dashboard siswa di kelas:</p>
                    <select name="kelas_id" class="form-select form-select-lg border-primary" required>
                        @foreach(App\Models\Kelas::all() as $k)
                            <option value="{{ $k->id }}" {{ $tantangan->kelas_id == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Konfirmasi & Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>
                    </div>
                </div>

                <div class="card-body p-4 bg-light">
                    {{-- ALERT MESSAGES --}}
                    {{-- STATS GRID --}}
                    <div class="row g-3 mb-5">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="text-primary mb-2"><i class="fas fa-file-alt fa-2x"></i></div>
                                    <h4 class="fw-bold mb-0">{{ $tantangan->soal->count() }}</h4>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Total Soal</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4 text-truncate">
                                    <div class="text-info mb-2"><i class="fas fa-hourglass-half fa-2x"></i></div>
                                    <h4 class="fw-bold mb-0" style="font-size: 1.1rem;">{{ $tantangan->batas_waktu->format('d/m H:i') }}</h4>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Deadline</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="text-warning mb-2"><i class="fas fa-star fa-2x"></i></div>
                                    <h4 class="fw-bold mb-0">{{ $tantangan->poin }}</h4>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Max Poin</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <div class="text-success mb-2"><i class="fas fa-user-graduate fa-2x"></i></div>
                                    <h4 class="fw-bold mb-0">{{ $siswaCount ?? 0 }}</h4>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Siswa</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION DAFTAR SOAL --}}
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 gap-3">
                        <h4 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-layer-group text-primary me-2"></i>Daftar Pertanyaan
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('guru.nilai.index', $tantangan->id) }}" class="btn btn-outline-primary fw-bold">
                                <i class="fas fa-tasks me-2"></i>Nilai Jawaban
                            </a>
                            <a href="{{ route('guru.soal.create', $tantangan) }}" class="btn btn-primary fw-bold px-4">
                                <i class="fas fa-plus-circle me-2"></i>Tambah Soal
                            </a>
                        </div>
                    </div>

                    @if($tantangan->soal->count() == 0)
                        <div class="card border-0 shadow-sm py-5 text-center bg-white rounded-4">
                            <div class="py-4">
                                <i class="fas fa-file-signature fa-4x text-light mb-3"></i>
                                <h5 class="text-muted">Belum ada pertanyaan yang dibuat.</h5>
                                <p class="text-muted px-4">Klik tombol "Tambah Soal" untuk mulai menyusun tantangan ini.</p>
                            </div>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($tantangan->soal as $index => $soal)
                                <div class="col-xl-4 col-lg-6">
                                    <div class="card h-100 border-0 shadow-sm hover-lift">
                                        <div class="p-3 pb-0 d-flex justify-content-between">
                                            <span class="badge bg-light text-primary border border-primary border-opacity-10 px-3 py-2">
                                                #{{ $index + 1 }} - {{ strtoupper($soal->tipe) }}
                                            </span>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
<ul class="dropdown-menu dropdown-menu-end shadow border-0">

    {{-- EDIT --}}
    <li>
        <a class="dropdown-item" href="{{ route('guru.soal.edit', [$tantangan, $soal]) }}">
            <i class="fas fa-edit me-2 text-info"></i>Edit
        </a>
    </li>

    <li><hr class="dropdown-divider"></li>

    {{-- DELETE --}}
    <li>
        <form action="{{ route('guru.soal.destroy', [$tantangan, $soal]) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="dropdown-item text-danger"
                onclick="return confirm('Hapus soal ini?')">
                <i class="fas fa-trash me-2"></i>Hapus
            </button>
        </form>
    </li>

</ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="fw-bold text-dark mb-3" style="line-height: 1.6;">
                                                {{ Str::limit($soal->pertanyaan, 100) }}
                                            </h6>
                                            
                                            @if($soal->tipe == 'pg')
                                                <div class="small text-muted mb-3 bg-light p-2 rounded">
                                                    <div class="mb-1"><i class="fas fa-check-circle text-success me-1"></i> A: {{ Str::limit($soal->opsi_a, 40) }}</div>
                                                    <div><i class="fas fa-circle text-light me-1 border rounded-circle"></i> B: {{ Str::limit($soal->opsi_b, 40) }}</div>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-auto pt-2 border-top">
                                                <small class="text-muted">Jawaban Benar:</small>
                                                <div class="fw-bold text-primary">{{ $soal->jawaban_benar }}</div>
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

@push('styles')
<style>
    .hover-lift { transition: all 0.25s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.08) !important; }
    .bg-primary { background: #667eea !important; }
    .btn-primary { background-color: #667eea; border-color: #667eea; }
    .btn-primary:hover { background-color: #5a6fd6; }
    .card { border-radius: 12px; }
</style>
@endpush