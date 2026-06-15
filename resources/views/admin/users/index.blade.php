@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Pengguna</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Manajemen akun Admin, Guru, dan Siswa dalam satu panel.
        </p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah User
    </a>
</div>

{{-- FILTER & SEARCH --}}
<div class="card border-0 mb-3">
    <div class="card-body p-3">
        <div class="row g-2 align-items-center">

            {{-- Role Filter --}}
            <div class="col-lg-6">
                <div class="d-flex gap-2 flex-wrap">
                    @php
                        $filters = [
                            null     => ['label' => 'Semua',  'color' => 'primary'],
                            'admin'  => ['label' => 'Admin',  'color' => 'danger'],
                            'guru'   => ['label' => 'Guru',   'color' => 'success'],
                            'siswa'  => ['label' => 'Siswa',  'color' => 'info'],
                        ];
                    @endphp

                    @foreach($filters as $roleKey => $cfg)
                        @php
                            $isActive = request('role') === $roleKey;
                            $url = $roleKey
                                ? route('admin.users.index', ['role' => $roleKey])
                                : route('admin.users.index');
                        @endphp
                        <a href="{{ $url }}"
                           class="btn btn-sm {{ $isActive ? 'btn-'.$cfg['color'] : 'btn-light' }}"
                           style="{{ $isActive ? '' : 'border: 1px solid var(--border-color) !important;' }}">
                            {{ $cfg['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Search --}}
            <div class="col-lg-6">
                <form action="{{ route('admin.users.index') }}" method="GET">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <div class="input-group" style="border: 1px solid var(--border-color); border-radius: var(--border-radius-sm); overflow: hidden;">
                        <span class="input-group-text bg-transparent border-0" style="color: var(--txt-tertiary);">
                            <i class="fas fa-search" style="font-size: 0.85rem;"></i>
                        </span>
                        <input type="text" name="search"
                               class="form-control border-0 shadow-none"
                               placeholder="Cari nama atau username..."
                               value="{{ request('search') }}"
                               style="background: transparent;">
                        @if(request('search'))
                            <a href="{{ route('admin.users.index', array_filter(['role' => request('role')])) }}"
                               class="input-group-text bg-transparent border-0 text-decoration-none"
                               style="color: var(--txt-tertiary);">
                                <i class="fas fa-times" style="font-size: 0.8rem;"></i>
                            </a>
                        @endif
                        <button class="btn btn-primary border-0 px-3" type="submit" style="border-radius: 0 var(--border-radius-sm) var(--border-radius-sm) 0;">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- DATA TABLE --}}
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Identitas User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th class="text-center pe-4">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        {{-- IDENTITAS --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                {{-- Avatar inisial --}}
                                @php
                                    $avatarBg = match($user->role) {
                                        'admin' => '#fee2e2',
                                        'guru'  => '#d1fae5',
                                        default => '#dbeafe',
                                    };
                                    $avatarColor = match($user->role) {
                                        'admin' => '#991b1b',
                                        'guru'  => '#065f46',
                                        default => '#1e40af',
                                    };
                                @endphp
                                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold flex-shrink-0"
                                     style="width: 40px; height: 40px;
                                            background: {{ $avatarBg }};
                                            color: {{ $avatarColor }};
                                            font-size: 0.95rem;">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.875rem; color: var(--txt-primary);">
                                        {{ $user->nama }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--txt-secondary);">
                                        @if($user->isSiswa() && $user->nis)
                                            <i class="fas fa-id-card me-1"></i>NIS: {{ $user->nis }}
                                        @elseif($user->isGuru() && $user->nip)
                                            <i class="fas fa-id-badge me-1"></i>NIP: {{ $user->nip }}
                                        @else
                                            <i class="fas fa-hashtag me-1"></i>ID: {{ $user->id }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- USERNAME --}}
                        <td class="py-3">
                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2"
                                  style="background: var(--bg-muted); border: 1px solid var(--border-color);
                                         font-size: 0.8rem; font-weight: 600; color: var(--txt-secondary);">
                                <i class="fas fa-at" style="font-size: 0.7rem;"></i>
                                {{ $user->username }}
                            </span>
                        </td>

                        {{-- ROLE --}}
                        <td class="py-3">
                            @php
                                $roleConfig = [
                                    'admin' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => 'user-shield',         'label' => 'Admin'],
                                    'guru'  => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => 'chalkboard-teacher',  'label' => 'Guru'],
                                    'siswa' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => 'user-graduate',       'label' => 'Siswa'],
                                ][$user->role] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'user', 'label' => ucfirst($user->role)];
                            @endphp
                            <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill"
                                  style="background: {{ $roleConfig['bg'] }};
                                         color: {{ $roleConfig['color'] }};
                                         font-size: 0.75rem; font-weight: 700;">
                                <i class="fas fa-{{ $roleConfig['icon'] }}" style="font-size: 0.7rem;"></i>
                                {{ $roleConfig['label'] }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="py-3 text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-action btn-light"
                                   title="Edit">
                                    <i class="fas fa-pencil-alt" style="color: var(--clr-primary);"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-action btn-light"
                                        onclick="confirmDeleteUser('{{ route('admin.users.destroy', $user) }}', '{{ addslashes($user->nama) }}')"
                                        title="Hapus">
                                    <i class="fas fa-trash-alt" style="color: var(--clr-danger);"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h6>Tidak ada pengguna ditemukan</h6>
                                <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-refresh me-2"></i>Reset Filter
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($users->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 px-4 py-3"
             style="border-top: 1px solid var(--border-color);">
            <div style="font-size: 0.82rem; color: var(--txt-secondary);">
                Menampilkan
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $users->firstItem() }}</span>
                –
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $users->lastItem() }}</span>
                dari
                <span style="font-weight: 600; color: var(--txt-primary);">{{ $users->total() }}</span>
                pengguna
            </div>
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL HAPUS --}}
<x-modal id="modalHapusUser" title="Hapus Pengguna" type="danger" icon="fa-trash">
    <div class="text-center">
        <p class="mb-1" style="color: var(--txt-secondary);">
            Anda akan menghapus akun
        </p>
        <p class="fw-bold mb-0" style="font-size: 1rem; color: var(--txt-primary);" id="namaUserHapus"></p>
        <p class="mt-2 mb-0" style="font-size: 0.82rem; color: var(--txt-secondary);">
            Tindakan ini tidak dapat dibatalkan.
        </p>
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-center gap-2 w-100">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <form id="formHapusUser" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus
                </button>
            </form>
        </div>
    </x-slot:footer>
</x-modal>

@endsection

@push('styles')
<style>
/* Pagination custom */
.pagination { margin-bottom: 0; gap: 4px; }
.page-link {
    border-radius: var(--border-radius-sm) !important;
    border: 1px solid var(--border-color) !important;
    background: var(--bg-card);
    color: var(--txt-secondary);
    font-size: 0.82rem;
    font-weight: 600;
    padding: 0.35rem 0.7rem;
    transition: all var(--transition);
}
.page-link:hover {
    background: var(--clr-primary-light);
    color: var(--clr-primary);
    border-color: var(--clr-primary) !important;
}
.page-item.active .page-link {
    background: var(--clr-primary);
    border-color: var(--clr-primary) !important;
    color: #fff;
    box-shadow: 0 2px 8px rgba(var(--clr-primary-rgb), 0.35);
}
.page-item.disabled .page-link {
    background: var(--bg-muted);
    color: var(--txt-tertiary);
}
</style>
@endpush

@push('scripts')
<script>
function confirmDeleteUser(url, nama) {
    document.getElementById('formHapusUser').action = url;
    document.getElementById('namaUserHapus').innerText = nama;
    new bootstrap.Modal(document.getElementById('modalHapusUser')).show();
}
</script>
@endpush