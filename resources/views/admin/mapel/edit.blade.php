@extends('layouts.app')

@section('title', 'Edit ' . $mapel->nama_mapel)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-warning text-dark py-4">
                <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Edit {{ $mapel->nama_mapel }}</h3>
            </div>
            <form method="POST" action="{{ route('admin.mapel.update', $mapel) }}">
                @csrf @method('PUT')
                <div class="card-body p-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" class="form-control form-control-lg @error('nama_mapel') is-invalid @enderror" 
                               value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                        @error('nama_mapel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-4">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                        <button type="submit" class="btn btn-warning btn-lg px-5">Update Mapel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
