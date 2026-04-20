@extends('layouts.app')

@section('title', 'Edit ' . $mapel->nama_mapel)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-warning text-dark py-3">
                <h3 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit {{ $mapel->nama_mapel }}
                </h3>
            </div>

            <form method="POST" action="{{ route('admin.mapel.update', $mapel) }}">
                @csrf @method('PUT')

                <div class="card-body p-4">
                    <div class="row g-3">

                        <!-- Nama Mapel -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Nama Mata Pelajaran</label>
                            <input type="text" name="nama_mapel"
                                class="form-control @error('nama_mapel') is-invalid @enderror"
                                value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>

                            @error('nama_mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 🔥 TAMBAHAN: GURU -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-primary">Guru Pengajar</label>
                        <select name="guru_id[]" multiple
                            class="form-select @error('guru_id') is-invalid @enderror">

                            @foreach($guru as $g)
                                <option value="{{ $g->id }}"
                                    {{ in_array($g->id, old('guru_id', $mapel->guru->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">Bisa pilih lebih dari satu guru (Ctrl + klik)</small>

                            @error('guru_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Guru bisa diganti atau dikosongkan</small>
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 py-3">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning px-4">
                            Update Mapel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection