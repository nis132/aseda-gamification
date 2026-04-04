@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white py-4">
                <h3 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Mapel + Tugas Guru</h3>
                <small class="opacity-75">Buat mapel sekaligus tentukan guru pengajar</small>
            </div>
            <form method="POST" action="{{ route('admin.mapel.store') }}">
                @csrf
                <div class="card-body p-5">
                    <div class="row g-4">
                        <!-- Nama Mapel -->
                        <div class="col-lg-12 mb-4">
                            <label class="form-label fw-bold fs-5 text-success"> Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" name="nama_mapel" class="form-control form-control-lg @error('nama_mapel') is-invalid @enderror" 
                                   value="{{ old('nama_mapel') }}" placeholder="Contoh: Matematika, IPA, Bahasa Indonesia" required>
                            @error('nama_mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Guru Pengajar -->
                        <div class="col-lg-12 mb-4">
                            <label class="form-label fw-bold fs-5 text-primary"> Guru Pengajar</label>
                            <select name="guru_id" class="form-select form-control-lg @error('guru_id') is-invalid @enderror">
                                <option value="">-- Pilih Guru (Opsional) --</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Guru bisa diubah nanti di halaman index</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-4">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-lg">
                            <i class="fas fa-save me-2"></i>Simpan Mapel + Guru
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
