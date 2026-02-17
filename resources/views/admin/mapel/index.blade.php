@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold text-success mb-0">
        <i class="fas fa-book-open me-2"></i>Kelola Mata Pelajaran
    </h2>
    <a href="{{ route('admin.mapel.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i>Tambah Mapel
    </a>
</div>

<!-- Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.mapel.index') }}">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control form-control-lg" 
                       placeholder="Cari mata pelajaran..." value="{{ request('search') }}">
                <button class="btn btn-success" type="submit">Cari</button>
                <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-lg border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-success">
                    <tr>
                        <th width="50%">Nama Mapel</th>
                        <th width="20%">Guru Pengajar</th>
                        <th width="20%">Dibuat</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapel as $m)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold fs-4">{{ $m->nama_mapel }}</h5>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($m->guru && $m->guru->count() > 0)
                                @foreach($m->guru as $guru)
                                    <span class="badge bg-primary me-1 mb-1">
                                        {{ $guru->nama }}
                                    </span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Belum ada guru</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $m->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.mapel.edit', $m) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.mapel.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus {{ $m->nama_mapel }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5>Belum ada mata pelajaran</h5>
                            <a href="{{ route('admin.mapel.create') }}" class="btn btn-success">Tambah Mapel</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer py-3">
            {{ $mapel->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
