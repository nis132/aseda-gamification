@extends('layouts.app')
@section('title', 'Edit Tantangan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Tantangan</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Memperbarui: <strong>{{ $tantangan->judul }}</strong>
        </p>
    </div>
    <a href="{{ route('guru.tantangan.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header card-header-gradient">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-magic"></i>
                    <span class="fw-bold" style="font-size: 0.9rem;">Modifikasi Tantangan</span>
                </div>
            </div>

            <form action="{{ route('guru.tantangan.update', $tantangan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body p-4">

                    <div class="mb-2 pb-2" style="border-bottom: 1px solid var(--border-color);">
                        <div class="text-label">Informasi Utama</div>
                    </div>

                    <div class="mt-3 mb-3">
                        <label class="form-label">Judul Misi</label>
                        <input type="text" name="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               placeholder="Contoh: Petualangan Aljabar Dasar"
                               value="{{ old('judul', $tantangan->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Instruksi Misi</label>
                        <textarea name="deskripsi" rows="4"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Jelaskan apa yang harus dicapai siswa...">{{ old('deskripsi', $tantangan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2 pb-2" style="border-bottom: 1px solid var(--border-color);">
                        <div class="text-label">Konfigurasi & Hadiah</div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran & Kelas</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--bg-muted); border-color: var(--border-color); color: var(--clr-primary);">
                                    <i class="fas fa-book"></i>
                                </span>
                                <select name="guru_mapel_kelas_id"
                                        class="form-select @error('guru_mapel_kelas_id') is-invalid @enderror">
                                    @foreach($relasi as $r)
                                        <option value="{{ $r->id }}"
                                            {{ ($tantangan->mapel_id == $r->mapel_id && $tantangan->kelas_id == $r->kelas_id) ? 'selected' : '' }}>
                                            {{ $r->mapel->nama_mapel }} - Kelas {{ $r->kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_mapel_kelas_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total XP Reward</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: #fef3c7; border-color: var(--border-color); color: var(--clr-warning);">
                                    <i class="fas fa-star"></i>
                                </span>
                                <input type="number" name="poin"
                                       class="form-control @error('poin') is-invalid @enderror"
                                       value="{{ old('poin', $tantangan->poin) }}" required>
                                @error('poin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Batas Akhir (Deadline)</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                    <i class="fas fa-hourglass-half"></i>
                                </span>
                                <input type="datetime-local" name="batas_waktu"
                                       class="form-control @error('batas_waktu') is-invalid @enderror"
                                       value="{{ old('batas_waktu', date('Y-m-d\TH:i', strtotime($tantangan->batas_waktu))) }}"
                                       required>
                                @error('batas_waktu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0 px-4 pb-4">
                    <div class="d-flex justify-content-between align-items-center pt-3"
                         style="border-top: 1px solid var(--border-color);">
                        <a href="{{ route('guru.tantangan.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection