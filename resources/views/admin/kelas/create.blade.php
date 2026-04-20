@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-plus me-2"></i>Tambah Kelas
            </h5>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.kelas.store') }}">
            @csrf

            <div class="card-body p-4">

                <div class="row g-3">

                    {{-- NAMA KELAS --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Kelas
                        </label>

                        <input type="text"
                               name="nama_kelas"
                               class="form-control @error('nama_kelas') is-invalid @enderror"
                               value="{{ old('nama_kelas') }}"
                               placeholder="Contoh: 7A, 8B"
                               required>

                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Format: 7A, 8B, 9C (maks. 10 karakter)
                        </small>
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.kelas.index') }}"
                       class="btn btn-light border">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>

                </div>
            </div>

        </form>
    </div>

</div>
@endsection