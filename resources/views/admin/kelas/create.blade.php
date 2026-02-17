@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white py-4">
                <h3 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Kelas</h3>
            </div>
            <form method="POST" action="{{ route('admin.kelas.store') }}">
                @csrf
                <div class="card-body p-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control form-control-lg @error('nama_kelas') is-invalid @enderror" 
                               value="{{ old('nama_kelas') }}" placeholder="Contoh: 7A, 8B, 9C" required>
                        @error('nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan nama kelas seperti 7A, 8B, 9C (maksimal 10 karakter)</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-4">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                        <button type="submit" class="btn btn-primary btn-lg px-5">Simpan Kelas</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
