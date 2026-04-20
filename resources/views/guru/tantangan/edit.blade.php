@extends('layouts.app')

@section('title', 'Edit Tantangan')

@section('content')

<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">

            {{-- BACK BUTTON --}}
            <div class="mb-4">
                <a href="{{ route('guru.tantangan.index') }}" class="btn btn-light rounded-pill shadow-sm px-4">
                    <i class="fas fa-arrow-left me-2 text-primary"></i>Kembali ke Daftar
                </a>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="card-header bg-gradient-primary p-4 border-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="fas fa-edit fa-2x text-white"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-white mb-0">Edit Tantangan</h3>
                            <p class="text-white opacity-75 mb-0 small">
                                Perbarui detail tugas atau materi tantangan Anda
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5">

                    <form action="{{ route('guru.tantangan.update', $tantangan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">

                            {{-- JUDUL --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Judul Tantangan</label>
                                <input type="text" name="judul"
                                    class="form-control form-control-lg @error('judul') is-invalid @enderror"
                                    value="{{ old('judul', $tantangan->judul) }}"
                                    required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Deskripsi / Instruksi</label>
                                <textarea name="deskripsi" rows="5"
                                    class="form-control form-control-lg @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $tantangan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- MAPEL --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mata Pelajaran</label>
                                <select name="mapel_id" class="form-select form-select-lg @error('mapel_id') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih Mapel</option>
                                    @foreach($mapelGuru as $m)
                                        <option value="{{ $m->id }}"
                                            {{ old('mapel_id', $tantangan->mapel_id) == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- KELAS --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Target Kelas</label>
                                <select name="kelas_id" class="form-select form-select-lg @error('kelas_id') is-invalid @enderror" required>
                                    <option value="" disabled>Pilih Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kelas_id', $tantangan->kelas_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- WAKTU & POIN --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Batas Waktu</label>
                                <input type="datetime-local" name="batas_waktu"
                                    class="form-control @error('batas_waktu') is-invalid @enderror"
                                    value="{{ old('batas_waktu', date('Y-m-d\TH:i', strtotime($tantangan->batas_waktu))) }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Reward Poin (XP)</label>
                                <input type="number" name="poin"
                                    class="form-control form-control-lg @error('poin') is-invalid @enderror"
                                    value="{{ old('poin', $tantangan->poin) }}"
                                    required>
                            </div>

                            {{-- BUTTON --}}
                            <div class="col-12 mt-4">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('guru.tantangan.index') }}"
                                        class="btn btn-outline-secondary px-4">
                                        Batal
                                    </a>

                                    <button type="submit"
                                        class="btn btn-primary px-5 shadow">
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

</div>

@endsection

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.form-control, .form-select {
    padding: 12px 14px;
    border-radius: 12px;
}

.card {
    border-radius: 18px;
}
</style>