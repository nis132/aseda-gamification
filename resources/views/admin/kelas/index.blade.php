@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold text-primary mb-0">
        <i class="fas fa-chalkboard me-2"></i>Kelola Kelas
    </h2>
    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i>Tambah Kelas
    </a>
</div>

<!-- Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.kelas.index') }}">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control form-control-lg" 
                       placeholder="Cari kelas (7A, 8B, dll)" value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Cari</button>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-lg border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="70%">Nama Kelas</th>
                        <th width="20%">Dibuat</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $k)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold fs-4">{{ $k->nama_kelas }}</h5>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $k->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin?')">
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
                        <td colspan="3" class="text-center py-5">
                            <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                            <h5>Belum ada kelas</h5>
                            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">Tambah Kelas</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer py-3">
            {{ $kelas->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
