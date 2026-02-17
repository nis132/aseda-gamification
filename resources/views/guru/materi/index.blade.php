@extends('layouts.app')
@section('title', 'Kelola Materi')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h2 fw-bold mb-1">📚 Kelola Materi</h1>
                <p class="text-muted mb-0">Materi pelajaran untuk semua kelas Anda</p>
            </div>
            <a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i>Tambah Materi
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-5 g-3">
            <div class="col-md-3">
                <div class="card bg-gradient-primary text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-book fa-3x mb-3 opacity-75"></i>
                        <div class="h2 mb-1 fw-bold">{{ $materis->total() }}</div>
                        <div class="h6 mb-0 opacity-90">Total Materi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-gradient-success text-white shadow-lg border-0 h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-download fa-3x mb-3 opacity-75"></i>
                        <div class="h2 mb-1 fw-bold">{{ $materis->where('file_materi', '!=', null)->count() }}</div>
                        <div class="h6 mb-0 opacity-90">File Tersedia</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materi Cards -->
        <div class="row g-4">
            @php $kelasTerpilih = session('kelas_terpilih', 'Semua Kelas'); @endphp

            @forelse($materis as $item)
            <div class="col-xl-4 col-lg-6">
                <div class="card h-100 border-0 shadow-sm hover-lift position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold text-primary mb-1">{{ Str::limit($item->judul, 50) }}</h6>
                            <div class="dropdown dropstart">
                                <button class="btn btn-sm btn-outline-secondary p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu shadow-lg">
                                    <li><a class="dropdown-item" href="{{ route('guru.materi.show', $item) }}"><i class="fas fa-eye me-2"></i>Lihat</a></li>
                                    <li><a class="dropdown-item" href="{{ route('guru.materi.edit', $item) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                    <li>
                                        <form action="{{ route('guru.materi.destroy', $item) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger" onclick="return confirm('Yakin hapus materi ini?')">
                                                <i class="fas fa-trash me-2"></i>Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="small text-muted mb-3">{{ Str::limit($item->deskripsi, 100) }}</p>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-info me-2">
                                {{ $kelasTerpilih }}
                            </span>
                            <span class="badge bg-primary me-2">
                                {{ $item->mapel->nama ?? 'Mapel' }}
                            </span>
                            @if($item->file_url)
                            <span class="badge bg-success">
                                <i class="fas fa-file-pdf me-1"></i>File
                            </span>
                            @else
                            <span class="badge bg-secondary">Tanpa File</span>
                            @endif
                        </div>

                        
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $item->created_at->format('d M Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-12 bg-gradient-light rounded-4 border-dashed border-2 border-primary">
                    <i class="fas fa-book fa-5x text-muted mb-4 opacity-50"></i>
                    <h3 class="fw-bold text-muted mb-3">Belum ada materi</h3>
                    <p class="text-muted lead mb-5">Buat materi pelajaran pertama Anda</p>
                    <a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-lg px-5 py-3 shadow-lg">
                        <i class="fas fa-plus-circle me-2"></i>Mulai Buat Materi
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $materis->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
