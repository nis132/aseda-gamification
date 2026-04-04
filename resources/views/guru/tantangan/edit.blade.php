@extends('layouts.app')

@section('title', 'Edit Tantangan')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        {{-- BREADCRUMB / BACK BUTTON --}}
        <div class="mb-4">
            <a href="{{ route('guru.tantangan.index') }}" class="btn btn-light rounded-pill shadow-sm px-4 hover-lift">
                <i class="fas fa-arrow-left me-2 text-primary"></i>Kembali ke Daftar
            </a>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- HEADER DENGAN GRADIEN --}}
            <div class="card-header bg-gradient-primary p-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-edit fa-2x text-white"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-white mb-0">Edit Tantangan</h3>
                        <p class="text-white text-opacity-75 mb-0 small">Perbarui detail tugas atau materi tantangan Anda</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('guru.tantangan.update', $tantangan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        {{-- JUDUL TANTANGAN --}}
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Judul Tantangan</label>
                            <input type="text" name="judul" class="form-control form-control-lg rounded-3 @error('judul') is-invalid @enderror" 
                                   placeholder="Contoh: Kuis Logika Dasar" value="{{ old('judul', $tantangan->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Deskripsi / Instruksi</label>
                            <textarea name="deskripsi" rows="5" class="form-control rounded-3 @error('deskripsi') is-invalid @enderror" 
                                      placeholder="Berikan instruksi pengerjaan yang jelas...">{{ old('deskripsi', $tantangan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- MATA PELAJARAN --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Mata Pelajaran</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-book text-muted"></i></span>
                                <select name="mapel_id" class="form-select border-start-0 @error('mapel_id') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih Mapel</option>
                                    @foreach($mapelGuru as $m)
                                        <option value="{{ $m->id }}" {{ old('mapel_id', $tantangan->mapel_id) == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('mapel_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- KELAS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Target Kelas</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-users text-muted"></i></span>
                                <select name="kelas_id" class="form-select border-start-0 @error('kelas_id') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id', $tantangan->kelas_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kelas_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- TIPE SOAL --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Tipe Tantangan</label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="pg" {{ old('tipe', $tantangan->tipe) == 'pg' ? 'selected' : '' }}>Pilihan Ganda</option>
                                <option value="essay" {{ old('tipe', $tantangan->tipe) == 'essay' ? 'selected' : '' }}>Essay</option>
                                <option value="matching" {{ old('tipe', $tantangan->tipe) == 'matching' ? 'selected' : '' }}>Menjodohkan</option>
                            </select>
                            @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- BATAS WAKTU --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Batas Waktu</label>
                            <input type="datetime-local" name="batas_waktu" class="form-control @error('batas_waktu') is-invalid @enderror" 
                                   value="{{ old('batas_waktu', date('Y-m-d\TH:i', strtotime($tantangan->batas_waktu))) }}" required>
                            @error('batas_waktu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- HADIAH POIN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Reward Poin (XP)</label>
                            <div class="input-group">
                                <input type="number" name="poin" class="form-control @error('poin') is-invalid @enderror" 
                                       placeholder="10 - 1000" value="{{ old('poin', $tantangan->poin) }}" required>
                                <span class="input-group-text bg-warning text-dark fw-bold">XP</span>
                            </div>
                            @error('poin') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="my-4 opacity-50">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" onclick="window.history.back()">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow hover-lift">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; 
    }
    
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1.5px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush