@extends('layouts.app')

@section('title', 'Edit Kelas ' . $kelas->nama_kelas)

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Ubah Kelas</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Memperbarui nama kelas <strong>{{ $kelas->nama_kelas }}</strong>.
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
            {{-- Header --}}
            <div class="card-header d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-warning"
                     style="width: 36px; height: 36px; border-radius: 8px; font-size: 0.9rem; flex-shrink:0;">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--txt-primary);">
                        Mode Edit
                    </div>
                    <div style="font-size: 0.75rem; color: var(--txt-secondary);">
                        Kelas <strong>{{ $kelas->nama_kelas }}</strong>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">
                            Nama Kelas Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--clr-warning);">
                                <i class="fas fa-chalkboard" style="font-size: 0.85rem;"></i>
                            </span>
                            <input type="text"
                                   name="nama_kelas"
                                   class="form-control @error('nama_kelas') is-invalid @enderror"
                                   value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                                   placeholder="Contoh: 7A, 8B, 9C"
                                   maxlength="10"
                                   required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-2 px-3 py-2 rounded-2"
                             style="background: var(--bg-muted); font-size: 0.78rem; color: var(--txt-secondary);">
                            <i class="fas fa-info-circle me-1" style="color: var(--clr-info);"></i>
                            Pastikan nama kelas tetap unik untuk menghindari kebingungan data.
                        </div>
                    </div>

                    {{-- Metadata --}}
                    <div class="d-flex gap-3 mb-4">
                        <div class="flex-fill px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                            <div class="text-label mb-1">Dibuat</div>
                            <div style="font-size: 0.82rem; font-weight: 600;">
                                {{ $kelas->created_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="flex-fill px-3 py-2 rounded-2" style="background: var(--bg-muted);">
                            <div class="text-label mb-1">Terakhir diubah</div>
                            <div style="font-size: 0.82rem; font-weight: 600;">
                                {{ $kelas->updated_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div style="height: 1px; background: var(--border-color); margin-bottom: 1.25rem;"></div>

                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection