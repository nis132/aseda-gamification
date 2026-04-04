@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold text-dark mb-0">
        <i class="fas fa-users me-2 text-primary"></i>Kelola User
    </h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i>Tambah User
    </a>
</div>
<div class="mb-3">
    <div class="btn-group" role="group">
        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline-secondary {{ request('role') == null ? 'active' : '' }}">
            Semua
        </a>

        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="btn btn-outline-danger {{ request('role') == 'admin' ? 'active' : '' }}">
            Admin
        </a>

        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}"
           class="btn btn-outline-success {{ request('role') == 'guru' ? 'active' : '' }}">
            Guru
        </a>

        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}"
           class="btn btn-outline-primary {{ request('role') == 'siswa' ? 'active' : '' }}">
            Siswa
        </a>
    </div>
</div>
<div class="card shadow-lg border-0 rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light sticky-top">
                    <tr>
                        <th class="border-0">Nama</th>
                        <th class="border-0">Username</th>
                        <th class="border-0">Role</th>
                        <th class="border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="hover-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'guru' ? 'success' : 'primary') }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    <i class="fas fa-user text-white fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $user->nama }}</h6>
                                    <small class="text-muted">ID: #{{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded fw-medium">{{ $user->username }}</code>
                        </td>
                        <td>
                            <span class="badge fs-6 px-3 py-2 fw-semibold bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'guru' ? 'success' : 'primary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin hapus {{ $user->nama }}? Data tidak bisa dikembalikan.')"
                                      style="margin-left: -1px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger border-left-0" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-5">
                                <i class="fas fa-users fa-4x text-muted mb-4 opacity-50"></i>
                                <h4 class="text-muted mb-3">Belum ada pengguna</h4>
                                <p class="text-muted mb-4">Mulai dengan menambah user pertama</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-user-plus me-2"></i>Tambah User Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
<div class="d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
    </div>

    <div>
        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
        @endif
    </div>
</div>

<style>
.hover-row:hover { background-color: rgba(0,123,255,.03); }
.avatar-sm { width: 45px; height: 45px; }
.fs-6 { font-size: 0.875rem !important; }
</style>
@endsection
