@extends('layouts.app')
@section('title', 'Kelola Materi')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-11">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
            <div class="mb-3 mb-md-0">
                <h1 class="display-6 fw-bold text-primary mb-1">
                    <i class="fas fa-book-open me-2"></i>Kelola Materi
                </h1>
                <p class="text-muted mb-0">Atur dan distribusikan bahan ajar untuk siswa Anda</p>
            </div>
            <a href="{{ route('guru.materi.create') }}" class="btn btn-primary btn-lg px-4 shadow-sm rounded-pill hover-lift">
                <i class="fas fa-plus-circle me-2"></i>Tambah Materi Baru
            </a>
        </div>

        <div class="row mb-5 g-4">
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                            <i class="fas fa-layer-group fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $materis->total() }}</h3>
                            <p class="mb-0 opacity-75">Total Koleksi Materi</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-0 shadow-sm text-white h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                            <i class="fas fa-file-pdf fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $materis->where('file_materi', '!=', null)->count() }}</h3>
                            <p class="mb-0 opacity-75">Materi Berkas File</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php $kelasTerpilih = session('kelas_terpilih', 'Semua Kelas'); @endphp

            @forelse($materis as $item)
            <div class="col-xl-4 col-lg-6">
                <div class="card h-100 border-0 shadow-sm hover-lift overflow-hidden">
                    {{-- Visual Indicator for Subject --}}
                    <div class="bg-primary opacity-10" style="height: 6px;"></div>
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-light rounded-3 p-2 text-primary">
                                <i class="fas {{ $item->file_url ? 'fa-file-pdf' : 'fa-align-left' }} fa-lg"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                    <li><a class="dropdown-item py-2" href="{{ route('guru.materi.show', $item) }}"><i class="fas fa-eye me-2 text-info"></i>Lihat Detail</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('guru.materi.edit', $item) }}"><i class="fas fa-edit me-2 text-warning"></i>Edit Konten</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('guru.materi.destroy', $item) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item py-2 text-danger" onclick="return confirm('Hapus materi ini?')">
                                                <i class="fas fa-trash me-2"></i>Hapus Materi
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-2 lh-sm">{{ Str::limit($item->judul, 60) }}</h5>
                        <p class="small text-muted mb-4">{{ Str::limit($item->deskripsi, 90) }}</p>
                        
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge bg-soft-info text-info">
                                <i class="fas fa-chalkboard me-1"></i>{{ $kelasTerpilih }}
                            </span>
                            <span class="badge bg-soft-primary text-primary">
                                <i class="fas fa-tag me-1"></i>{{ $item->mapel->nama ?? 'Umum' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <span class="small text-muted">
                                <i class="far fa-calendar-alt me-1"></i>{{ $item->created_at->format('d M Y') }}
                            </span>
                            @if($item->file_url)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                    <i class="fas fa-check-circle me-1"></i>Berkas Ok
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border-2 border-dashed border-light">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                        <i class="fas fa-book-open fa-3x text-muted opacity-50"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Belum ada materi terdaftar</h4>
                    <p class="text-muted mb-4">Mulai bagikan ilmu dengan membuat materi pelajaran pertama Anda.</p>
                    <a href="{{ route('guru.materi.create') }}" class="btn btn-primary px-5 py-3 rounded-pill shadow">
                        <i class="fas fa-plus me-2"></i>Buat Materi Sekarang
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $materis->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card, .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 1.25rem !important;
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    .bg-soft-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    .badge {
        font-weight: 600;
        padding: 0.5em 0.8em;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .display-6 {
        letter-spacing: -1px;
    }
</style>
@endpush