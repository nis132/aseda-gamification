@extends('layouts.app')

@section('title', 'Tambah Kelas Baru')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Kelas Baru</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Pastikan nama kelas belum terdaftar sebelumnya.
        </p>
    </div>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">

        {{-- FORM CARD --}}
        <div class="card border-0">
            {{-- Accent header --}}
            <div class="card-header card-header-gradient d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-2"
                     style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); flex-shrink:0;">
                    <i class="fas fa-school" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.9rem; font-weight: 700;">Tambah Kelas</div>
                    <div style="font-size: 0.75rem; opacity: 0.75;">Isi nama kelas kemudian simpan</div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.kelas.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">
                            Nama Kelas <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                <i class="fas fa-tag" style="font-size: 0.85rem;"></i>
                            </span>
                            <input type="text"
                                   name="nama_kelas"
                                   class="form-control @error('nama_kelas') is-invalid @enderror"
                                   placeholder="Contoh: 7A, 8B, 9C"
                                   value="{{ old('nama_kelas') }}"
                                   maxlength="10"
                                   required
                                   autofocus>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @unless($errors->has('nama_kelas'))
                            <div style="font-size: 0.78rem; color: var(--txt-tertiary); margin-top: 0.4rem;">
                                <i class="fas fa-info-circle me-1"></i>
                                Gunakan format yang konsisten, maks. 10 karakter.
                            </div>
                        @endunless
                    </div>

                    {{-- Divider --}}
                    <div style="height: 1px; background: var(--border-color); margin-bottom: 1.25rem;"></div>

                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('admin.kelas.index') }}"
                           class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-check-circle me-2"></i>Simpan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TIPS --}}
        <div class="d-flex gap-3 align-items-start mt-3 p-3 rounded-3"
             style="background: var(--clr-primary-light); border-left: 3px solid var(--clr-primary);">
            <i class="fas fa-lightbulb mt-1" style="color: var(--clr-warning); font-size: 0.9rem; flex-shrink:0;"></i>
            <div>
                <div style="font-size: 0.82rem; font-weight: 700; color: var(--txt-primary); margin-bottom: 2px;">
                    Tips Cepat
                </div>
                <p class="mb-0" style="font-size: 0.8rem; color: var(--txt-secondary);">
                    Setelah kelas dibuat, tambahkan siswa melalui menu
                    <strong>Tambah User</strong> atau fitur <strong>Import Excel</strong>.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection