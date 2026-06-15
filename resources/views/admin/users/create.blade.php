@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah User Baru</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Daftarkan akun Admin, Guru, atau Siswa secara manual maupun via import Excel.
        </p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

{{-- GLOBAL ERROR --}}
@if($errors->any())
<div class="card border-0 mb-3" style="border-left: 4px solid var(--clr-danger) !important;">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-start gap-3">
            <div class="stat-icon stat-icon-danger flex-shrink-0" style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div class="fw-bold mb-1" style="font-size: 0.875rem; color: var(--clr-danger);">
                    Ada kendala pada data yang Anda masukkan:
                </div>
                <ul class="mb-0 ps-3" style="font-size: 0.82rem; color: var(--txt-secondary);">
                    @foreach($errors->all() as $error)
                        @php
                            $pesan = $error;
                            if (str_contains($error, 'file field is required'))       $pesan = 'Anda belum memilih file untuk diunggah.';
                            if (str_contains($error, 'file must be a file of type'))  $pesan = 'Format file harus .xlsx atau .xls (Excel).';
                        @endphp
                        <li>{{ $pesan }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<div class="card border-0">

    {{-- TAB NAVIGATION --}}
    <div class="card-header p-0" style="background: var(--bg-muted); border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;">
        <ul class="nav nav-pills p-2 gap-1" id="userTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="admin-tab"
                        data-bs-toggle="tab" data-bs-target="#admin-pane" type="button">
                    <i class="fas fa-user-shield me-2"></i>Admin
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="guru-tab"
                        data-bs-toggle="tab" data-bs-target="#guru-pane" type="button">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Guru
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="siswa-tab"
                        data-bs-toggle="tab" data-bs-target="#siswa-pane" type="button">
                    <i class="fas fa-user-graduate me-2"></i>Siswa
                </button>
            </li>
        </ul>
    </div>

    {{-- IMPORT BANNER (berganti sesuai tab aktif) --}}
    <div style="background: var(--bg-muted); border-bottom: 1px solid var(--border-color); padding: 0.75rem 1.25rem;">

        {{-- Import Admin --}}
        <div id="import-admin" class="import-section">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="text-label me-2">Import Admin via Excel:</span>
                <a href="{{ route('admin.export.admin') }}"
                   class="btn btn-light btn-sm"
                   style="border: 1px solid var(--border-color) !important;">
                    <i class="fas fa-download me-1"></i>Unduh Template
                </a>
                <form action="{{ route('admin.import.admin') }}" method="POST"
                      enctype="multipart/form-data"
                      class="d-flex align-items-center gap-2 flex-grow-1" novalidate>
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls"
                           class="form-control form-control-sm @error('file') is-invalid @enderror"
                           style="max-width: 280px;">
                    <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                        <i class="fas fa-file-import me-1"></i>Import
                    </button>
                </form>
            </div>
        </div>

        {{-- Import Guru --}}
        <div id="import-guru" class="import-section d-none">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="text-label me-2">Import Guru via Excel:</span>
                <a href="{{ route('admin.export.guru') }}"
                   class="btn btn-light btn-sm"
                   style="border: 1px solid var(--border-color) !important;">
                    <i class="fas fa-download me-1"></i>Unduh Template
                </a>
                <form action="{{ route('admin.import.guru') }}" method="POST"
                      enctype="multipart/form-data"
                      class="d-flex align-items-center gap-2 flex-grow-1" novalidate>
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls"
                           class="form-control form-control-sm @error('file') is-invalid @enderror"
                           style="max-width: 280px;">
                    <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                        <i class="fas fa-file-import me-1"></i>Import
                    </button>
                </form>
            </div>
        </div>

        {{-- Import Siswa --}}
        <div id="import-siswa" class="import-section d-none">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="text-label me-2">Import Siswa via Excel:</span>
                <a href="{{ route('admin.export.siswa') }}"
                   class="btn btn-light btn-sm"
                   style="border: 1px solid var(--border-color) !important;">
                    <i class="fas fa-download me-1"></i>Unduh Template
                </a>
                <form action="{{ route('admin.import.siswa') }}" method="POST"
                      enctype="multipart/form-data"
                      class="d-flex align-items-center gap-2 flex-grow-1" novalidate>
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls"
                           class="form-control form-control-sm @error('file') is-invalid @enderror"
                           style="max-width: 280px;">
                    <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                        <i class="fas fa-file-import me-1"></i>Import
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- FORM AREA --}}
    <div class="card-body p-4">
        <div class="tab-content">

            {{-- ==========================
                 TAB: ADMIN
            ========================== --}}
            <div class="tab-pane fade show active" id="admin-pane">
                <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Admin <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('role') == 'admin' ? old('nama') : '' }}"
                                   placeholder="Nama lengkap">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                    <i class="fas fa-at" style="font-size: 0.85rem;"></i>
                                </span>
                                <input type="text" name="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('role') == 'admin' ? old('username') : '' }}"
                                       placeholder="username.admin">
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Akun Admin
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ==========================
                 TAB: GURU
            ========================== --}}
            <div class="tab-pane fade" id="guru-pane">
                <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="role" value="guru">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Guru <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('role') == 'guru' ? old('nama') : '' }}"
                                   placeholder="Nama lengkap guru">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nip"
                                   class="form-control @error('nip') is-invalid @enderror"
                                   value="{{ old('role') == 'guru' ? old('nip') : '' }}"
                                   placeholder="Nomor Induk Pegawai">
                            @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                    <i class="fas fa-at" style="font-size: 0.85rem;"></i>
                                </span>
                                <input type="text" name="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('role') == 'guru' ? old('username') : '' }}"
                                       placeholder="username.guru">
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Akun Guru
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ==========================
                 TAB: SISWA
            ========================== --}}
            <div class="tab-pane fade" id="siswa-pane">
                <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="role" value="siswa">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('role') == 'siswa' ? old('nama') : '' }}"
                                   placeholder="Nama lengkap siswa">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis"
                                   class="form-control @error('nis') is-invalid @enderror"
                                   value="{{ old('role') == 'siswa' ? old('nis') : '' }}"
                                   placeholder="Nomor Induk Siswa">
                            @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                    <i class="fas fa-at" style="font-size: 0.85rem;"></i>
                                </span>
                                <input type="text" name="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('role') == 'siswa' ? old('username') : '' }}"
                                       placeholder="username.siswa">
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Poin Awal (XP)</label>
                            <input type="number" name="total_poin" class="form-control"
                                   value="{{ old('total_poin', 0) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Level Awal</label>
                            <input type="number" name="level" class="form-control"
                                   value="{{ old('level', 1) }}" min="1">
                        </div>
                        <div class="col-md-4">
                            {{-- spacer --}}
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-rocket me-2"></i>Simpan Akun Siswa
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Tab pills di halaman create */
#userTabs .nav-link {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--txt-secondary);
    padding: 0.45rem 1.1rem;
    border-radius: var(--border-radius-sm);
    border: none;
    transition: all var(--transition);
}
#userTabs .nav-link.active {
    background: var(--bg-card) !important;
    color: var(--txt-primary) !important;
    box-shadow: var(--shadow-sm);
}
#userTabs #admin-tab.active { color: var(--clr-danger) !important; }
#userTabs #guru-tab.active  { color: var(--clr-success) !important; }
#userTabs #siswa-tab.active { color: var(--clr-primary) !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const importMap = {
        'admin-tab':  document.getElementById('import-admin'),
        'guru-tab':   document.getElementById('import-guru'),
        'siswa-tab':  document.getElementById('import-siswa'),
    };

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', e => {
            Object.values(importMap).forEach(el => el?.classList.add('d-none'));
            importMap[e.target.id]?.classList.remove('d-none');
        });
    });

    // Restore tab saat ada validation error
    const oldRole = "{{ old('role') }}";
    if (oldRole) {
        document.getElementById(oldRole + '-tab')?.click();
    }
});
</script>
@endpush