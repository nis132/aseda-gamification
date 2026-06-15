@extends('layouts.app')

@section('title', 'Edit ' . ucfirst($user->role))

@section('content')

@php
    $roleConfig = [
        'admin' => ['color' => 'var(--clr-danger)',   'bg' => '#fee2e2', 'txt' => '#991b1b', 'icon' => 'user-shield'],
        'guru'  => ['color' => 'var(--clr-success)',  'bg' => '#d1fae5', 'txt' => '#065f46', 'icon' => 'chalkboard-teacher'],
        'siswa' => ['color' => 'var(--clr-primary)',  'bg' => '#dbeafe', 'txt' => '#1e40af', 'icon' => 'user-graduate'],
    ][$user->role] ?? ['color' => 'var(--txt-secondary)', 'bg' => '#f1f5f9', 'txt' => '#475569', 'icon' => 'user'];
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Edit {{ ucfirst($user->role) }}</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Perbarui informasi akun pengguna.
        </p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-3">

    {{-- SIDE PANEL --}}
    <div class="col-lg-3 d-none d-lg-block">
        <div class="card border-0 h-100">
            {{-- Cover avatar --}}
            <div class="p-4 text-center"
                 style="background: {{ $roleConfig['bg'] }};
                        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white mx-auto mb-3"
                     style="width: 72px; height: 72px;
                            background: {{ $roleConfig['color'] }};
                            font-size: 1.6rem;">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $user->nama }}</h6>
                <span class="badge px-3 py-1 rounded-pill"
                      style="background: {{ $roleConfig['color'] }}; color: #fff; font-size: 0.7rem;">
                    <i class="fas fa-{{ $roleConfig['icon'] }} me-1"></i>{{ ucfirst($user->role) }}
                </span>
            </div>

            {{-- Info list --}}
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-2">

                    <div class="px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                        <div class="text-label mb-1">Username</div>
                        <div style="font-size: 0.82rem; font-weight: 600;">
                            <i class="fas fa-at me-1" style="color: var(--txt-tertiary);"></i>
                            {{ $user->username }}
                        </div>
                    </div>

                    @if($user->isSiswa() && $user->nis)
                    <div class="px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                        <div class="text-label mb-1">NIS</div>
                        <div style="font-size: 0.82rem; font-weight: 600;">{{ $user->nis }}</div>
                    </div>
                    @endif

                    @if($user->isGuru() && $user->nip)
                    <div class="px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                        <div class="text-label mb-1">NIP</div>
                        <div style="font-size: 0.82rem; font-weight: 600;">{{ $user->nip }}</div>
                    </div>
                    @endif

                    <div class="px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                        <div class="text-label mb-1">Terdaftar</div>
                        <div style="font-size: 0.82rem; font-weight: 600;">
                            {{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}
                        </div>
                    </div>

                    @if($user->isGuru())
                    <div class="px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                        <div class="text-label mb-2">Mengajar</div>
                        @forelse($user->mengajar as $gmk)
                            <div style="font-size: 0.78rem; font-weight: 600; margin-bottom: 4px;">
                                <i class="fas fa-book me-1" style="color: var(--clr-success);"></i>
                                {{ $gmk->mapel->nama_mapel ?? '-' }}
                                <span style="color: var(--txt-secondary);">/ {{ $gmk->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                        @empty
                            <span style="font-size: 0.78rem; color: var(--txt-tertiary);">Belum ada data.</span>
                        @endforelse
                    </div>
                    @endif

                    @if($user->isSiswa())
                    <div class="px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                        <div class="text-label mb-1">Kelas</div>
                        <div style="font-size: 0.82rem; font-weight: 600;">
                            {{ $user->kelas->first()?->nama_kelas ?? 'Belum ada kelas' }}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- MAIN FORM --}}
    <div class="col-lg-9">
        <div class="card border-0">

            {{-- Tab nav --}}
            <div class="card-header d-flex align-items-center gap-3">
                <ul class="nav nav-pills p-1 rounded-2 mb-0 flex-grow-1"
                    id="editTabs" role="tablist"
                    style="background: var(--bg-muted); width: fit-content;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active"
                                id="profile-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#profile"
                                type="button">
                            <i class="fas fa-user me-2"></i>Profil Dasar
                        </button>
                    </li>
                    @if($user->role !== 'admin')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                                id="role-spec-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#role-spec"
                                type="button">
                            <i class="fas fa-{{ $roleConfig['icon'] }} me-2"></i>
                            Data {{ ucfirst($user->role) }}
                        </button>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role" value="{{ $user->role }}">

                    <div class="tab-content">

                        {{-- TAB: PROFIL DASAR --}}
                        <div class="tab-pane fade show active" id="profile">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           value="{{ old('nama', $user->nama) }}"
                                           placeholder="Nama lengkap">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($user->isSiswa())
                                <div class="col-md-6">
                                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                                    <input type="text" name="nis"
                                           class="form-control @error('nis') is-invalid @enderror"
                                           value="{{ old('nis', $user->nis) }}"
                                           placeholder="Nomor Induk Siswa">
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                @if($user->isGuru())
                                <div class="col-md-6">
                                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" name="nip"
                                           class="form-control @error('nip') is-invalid @enderror"
                                           value="{{ old('nip', $user->nip) }}"
                                           placeholder="Nomor Induk Pegawai">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                <div class="{{ ($user->isSiswa() || $user->isGuru()) ? 'col-md-6' : 'col-12' }}">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary); font-size: 0.85rem;">
                                            <i class="fas fa-at"></i>
                                        </span>
                                        <input type="text" name="username"
                                               class="form-control @error('username') is-invalid @enderror"
                                               value="{{ old('username', $user->username) }}">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Divider password --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 my-1">
                                        <div style="flex: 1; height: 1px; background: var(--border-color);"></div>
                                        <span class="text-label">Ganti Password (opsional)</span>
                                        <div style="flex: 1; height: 1px; background: var(--border-color);"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password"
                                           class="form-control"
                                           placeholder="Kosongkan jika tidak diganti">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation"
                                           class="form-control"
                                           placeholder="Ulangi password baru">
                                </div>

                            </div>
                        </div>

                        {{-- TAB: DATA GURU --}}
                        @if($user->isGuru())
                        <div class="tab-pane fade" id="role-spec">
                            <p class="mb-3" style="font-size: 0.82rem; color: var(--txt-secondary);">
                                <i class="fas fa-info-circle me-1" style="color: var(--clr-info);"></i>
                                Kombinasi mata pelajaran dan kelas yang diajar oleh guru ini.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mata Pelajaran</label>
                                    <select name="mapel_id" class="form-select @error('mapel_id') is-invalid @enderror">
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($mapel as $m)
                                            <option value="{{ $m->id }}"
                                                {{ old('mapel_id', $user->mengajar->first()?->mapel_id) == $m->id ? 'selected' : '' }}>
                                                {{ $m->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mapel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kelas yang Diajar</label>
                                    <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}"
                                                {{ old('kelas_id', $user->mengajar->first()?->kelas_id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- TAB: DATA SISWA --}}
                        @if($user->isSiswa())
                        <div class="tab-pane fade" id="role-spec">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Kelas</label>
                                    <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}"
                                                {{ old('kelas_id', $user->kelas->first()?->id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Total Poin</label>
                                    <input type="number" name="total_poin"
                                           class="form-control"
                                           value="{{ old('total_poin', $user->total_poin) }}"
                                           min="0">
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>{{-- end tab-content --}}

                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex align-items-center gap-2 pt-4 mt-2"
                         style="border-top: 1px solid var(--border-color);">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-light px-4">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* Tab pills konsisten dengan layout */
.nav-pills .nav-link {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--txt-secondary);
    padding: 0.4rem 1rem;
    border-radius: var(--border-radius-sm);
    border: none;
    transition: all var(--transition);
}
.nav-pills .nav-link.active {
    background: var(--bg-card) !important;
    color: var(--txt-primary) !important;
    box-shadow: var(--shadow-sm);
}
</style>
@endpush