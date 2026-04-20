@extends('layouts.app')
@section('title', 'Tambah Materi Baru')

@section('content')
<div class="container-fluid py-3">

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <i class="fas fa-check-circle me-2 text-success"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 overflow-hidden">

                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="h3 fw-bold mb-1">
                                <i class="fas fa-book-open me-2"></i>
                                Materi Baru
                            </h2>
                            <p class="mb-0 opacity-75">Buat materi pelajaran untuk siswa</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-2 mt-md-0">
                            <a href="/guru/materi" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body p-4">

                        <div class="row g-3">

                            {{-- JUDUL --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Judul Materi <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="judul"
                                       class="form-control @error('judul') is-invalid @enderror"
                                       value="{{ old('judul') }}"
                                       placeholder="Contoh: Aljabar Linear Bab 1"
                                       required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- MAPEL --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mapel <span class="text-danger">*</span></label>
                                <select name="mapel_id" class="form-select @error('mapel_id') is-invalid @enderror" required>
                                    <option value="">Pilih Mapel</option>
                                    @foreach(\App\Models\Mapel::all() as $mapel)
                                        <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mapel_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- KELAS --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi"
                                          rows="4"
                                          class="form-control @error('deskripsi') is-invalid @enderror"
                                          placeholder="Jelaskan isi materi..."
                                          required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- FILE --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">File Materi (Opsional)</label>
                                <input type="file"
                                       name="file_materi"
                                       class="form-control @error('file_materi') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx">

                                <small class="text-muted">
                                    PDF, DOC, DOCX • Maksimal 5MB
                                </small>

                                @error('file_materi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 p-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/guru/materi" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Materi
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection