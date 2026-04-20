@extends('layouts.app')
@section('title', 'Kelola Tantangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold text-primary mb-0">
        <i class="fas fa-tasks me-2"></i>Tantangan & Tugas
    </h2>
    <a href="{{ route('guru.tantangan.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i>Buat Tantangan Baru
    </a>
</div>

<div class="card shadow-lg border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Judul</th>
                        <th>Mapel & Kelas</th>
                        <th>Poin</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tantangan as $t)
                    <tr>
                        <td>
                            <h6 class="mb-1 fw-bold">{{ $t->judul }}</h6>
                            <small class="text-muted">
                                {{ \Illuminate\Support\Str::limit($t->deskripsi, 50) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info me-1">
                                {{ $t->mapel->nama_mapel }}
                            </span>
                            <span class="badge bg-secondary me-1">
                                {{ $t->kelas->nama_kelas }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">
                                {{ $t->poin }} Poin
                            </strong>
                        </td>
                        <td>
                            <span class="badge {{ now()->lt($t->batas_waktu) ? 'bg-warning' : 'bg-danger' }}">
                                {{ $t->batas_waktu->format('d/m H:i') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $t->status == 'draft' ? 'warning' : 'success' }} px-3">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('guru.tantangan.show', $t) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('guru.tantangan.edit', $t) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('guru.tantangan.destroy', $t) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger" onclick="return confirm('Yakin hapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>Belum ada tantangan</h5>
                            <a href="{{ route('guru.tantangan.create') }}" class="btn btn-primary">
                                Buat Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Lebih Kecil --}}
        <div class="card-footer py-2 bg-light d-flex justify-content-center">
            <div class="small">
                {{ $tantangan->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</div>

{{-- Optional: CSS agar pagination lebih kecil --}}
<style>
.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.35rem 0.65rem;
    font-size: 0.875rem;
}
</style>

@endsection