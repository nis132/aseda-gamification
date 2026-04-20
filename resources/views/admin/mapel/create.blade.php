@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">
        
        {{-- HEADER --}}
        <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-plus me-2"></i>Tambah Mata Pelajaran
                </h5>
                <small class="opacity-75">Buat mapel sekaligus tentukan guru pengajar</small>
            </div>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.mapel.store') }}">
            @csrf

            <div class="card-body p-4">
                <div class="row g-3">

                    {{-- NAMA MAPEL --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Mata Pelajaran <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nama_mapel"
                               class="form-control @error('nama_mapel') is-invalid @enderror"
                               value="{{ old('nama_mapel') }}"
                               placeholder="Contoh: Matematika"
                               required>

                        @error('nama_mapel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- GURU --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Guru Pengajar
                        </label>
                        <select name="guru_id"
                                class="form-select @error('guru_id') is-invalid @enderror">

                            <option value="">-- Pilih Guru --</option>

                            @foreach($guru as $g)
                                <option value="{{ $g->id }}"
                                    {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>

                        @error('guru_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Bisa diubah nanti
                        </small>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-end gap-2">
                    
                    <a href="{{ route('admin.mapel.index') }}"
                       class="btn btn-light border">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>

                </div>
            </div>

        </form>
    </div>

</div>
@endsection