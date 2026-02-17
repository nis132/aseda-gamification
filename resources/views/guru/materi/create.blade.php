@extends('layouts.app')
@section('title', 'Tambah Materi Baru')

@section('content')
<div class="container-fluid py-4">
    {{-- FLASH MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-5" role="alert">
        <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-5" role="alert">
        <i class="fas fa-exclamation-triangle me-3 fs-4 text-danger"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-5 position-relative overflow-hidden">
                    <div class="bg-white bg-opacity-10 position-absolute top-0 start-0 w-100 h-100"></div>
                    <div class="position-relative">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="display-6 fw-bold mb-2">
                                    <i class="fas fa-book-open me-3"></i>
                                    Materi Baru
                                </h2>
                                <p class="lead mb-0 opacity-90">Buat materi pelajaran untuk siswa</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="/guru/materi" class="btn btn-outline-light btn-lg px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="card-body p-5">
                        <div class="row g-4">
                            {{-- JUDUL --}}
                            <div class="col-12">
                                <label class="form-label fw-bold fs-5 mb-3">📖 Judul Materi <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="judul" 
                                       class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                                       value="{{ old('judul') }}" 
                                       placeholder="Contoh: Aljabar Linear Bab 1" 
                                       required>
                                @error('judul')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label fw-bold">📚 Mapel <span class="text-danger">*</span></label>
                                <select name="mapel_id" class="form-select form-select-lg @error('mapel_id') is-invalid @enderror" required>
                                    <option value="">Pilih Mapel...</option>
                                    @foreach(\App\Models\Mapel::all() as $mapel)
                                        <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mapel_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
    <label class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
    <select name="kelas_id" class="form-select" required>
        <option value="">Pilih Kelas</option>
        @foreach($kelas as $k)
            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
            </option>
        @endforeach
    </select>
    @error('kelas_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>


                            {{-- DESKRIPSI --}}
                            <div class="col-12">
                                <label class="form-label fw-bold fs-5 mb-3">📝 Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" 
                                          rows="5" 
                                          class="form-control form-control-lg @error('deskripsi') is-invalid @enderror" 
                                          placeholder="Jelaskan isi materi secara singkat..." 
                                          required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- FILE MATERI --}}
                            <div class="col-12">
                                <label class="form-label fw-bold fs-5 mb-3">📎 File Materi (Opsional)</label>
                                <div class="border border-dashed border-3 border-secondary rounded-4 p-5 text-center hover-border-primary transition-all">
                                    <input type="file" 
                                           name="file_materi" 
                                           class="form-control form-control-lg @error('file_materi') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx">
                                    <div class="mt-3 small text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        PDF, DOC, DOCX • Maksimal 5MB
                                    </div>
                                </div>
                                @error('file_materi')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-gradient-light border-0 px-5 py-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <a href="/guru/materi" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5 py-3 shadow-lg">
                                <i class="fas fa-save me-2"></i>
                                Simpan Materi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
