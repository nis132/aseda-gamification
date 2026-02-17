@extends('layouts.app')
@section('title', 'Buat Tantangan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-primary text-white py-4">
                <h3 class="mb-0"><i class="fas fa-plus me-2"></i>Buat Tantangan Baru</h3>
                <small>Pilih mapel & kelas yang diajar + jenis tantangan</small>
            </div>
            <form method="POST" action="{{ route('guru.tantangan.store') }}">
                @csrf
                <div class="card-body p-5">
                    <div class="row g-4">
                        <!-- Judul -->
                        <div class="col-lg-12">
                            <label class="form-label fw-bold fs-5 text-primary">📝 Judul Tantangan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                                   value="{{ old('judul') }}" placeholder="Contoh: Ulangan Matematika Bab 1" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-lg-12">
                            <label class="form-label fw-bold fs-5 text-primary">📄 Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" 
                                      placeholder="Jelaskan instruksi tantangan..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <!-- Mapel -->
                            <label class="form-label fw-bold fs-5 text-primary">📚 Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mapel_id" class="form-select form-control-lg @error('mapel_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Mapel yang Diajar --</option>
                                @foreach($mapelGuru as $mapel)
                                    <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <!-- Kelas -->
                            <label class="form-label fw-bold fs-5 text-primary">🏫 Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" class="form-select form-control-lg @error('kelas_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Tipe Tantangan -->
                        <div class="col-lg-4">
                            <label class="form-label fw-bold fs-5 text-primary">🎯 Jenis Tantangan <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select form-control-lg @error('tipe') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="pg" {{ old('tipe') == 'pg' ? 'selected' : '' }}>Pilihan Ganda (PG)</option>
                                <option value="matching" {{ old('tipe') == 'matching' ? 'selected' : '' }}>Menjodohkan</option>
                                <option value="essay" {{ old('tipe') == 'essay' ? 'selected' : '' }}>Uraian Singkat</option>
                            </select>
                            @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Poin -->
                        <div class="col-lg-4">
                            <label class="form-label fw-bold fs-5 text-primary">⭐ Total Poin <span class="text-danger">*</span></label>
                            <input type="number" name="poin" class="form-control form-control-lg @error('poin') is-invalid @enderror" 
                                   value="{{ old('poin', 100) }}" min="1" max="1000" required>
                            @error('poin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Batas Waktu -->
                        <div class="col-lg-4">
                            <label class="form-label fw-bold fs-5 text-primary">⏰ Batas Waktu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="batas_waktu" class="form-control form-control-lg @error('batas_waktu') is-invalid @enderror" 
                                   value="{{ old('batas_waktu') }}" required>
                            @error('batas_waktu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Siswa harus selesai sebelum waktu ini</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-4">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('guru.tantangan.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-lg">
                            <i class="fas fa-save me-2"></i>Simpan & Tambah Soal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
