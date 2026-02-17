@extends('layouts.app')
@section('title', 'Tambah Soal - ' . $tantangan->judul)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold">
        <i class="fas fa-question-circle me-2 text-primary"></i>
        Tambah Soal untuk "{{ $tantangan->judul }}"
    </h2>
    <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form method="POST" action="{{ route('guru.soal.store', $tantangan) }}">
    @csrf
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-info text-white py-4">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Soal Baru</h5>
        </div>
        <div class="card-body p-5">
            <!-- Jenis Soal -->
            <div class="mb-4">
                <label class="form-label fw-bold fs-6 text-primary mb-3">Jenis Soal <span class="text-danger">*</span></label>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="d-block p-4 border rounded-3 text-center cursor-pointer hover-shadow @error('tipe') border-danger @enderror">
                            <input type="radio" name="tipe" value="pg" class="d-none" {{ old('tipe') == 'pg' ? 'checked' : '' }} required>
                            <i class="fas fa-list fa-2x mb-2 d-block text-primary"></i>
                            <strong>Pilihan Ganda</strong>
                            <small class="d-block text-muted">4 opsi A-D</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="d-block p-4 border rounded-3 text-center cursor-pointer hover-shadow @error('tipe') border-danger @enderror">
                            <input type="radio" name="tipe" value="essay" class="d-none" {{ old('tipe') == 'essay' ? 'checked' : '' }} required>
                            <i class="fas fa-pen fa-2x mb-2 d-block text-warning"></i>
                            <strong>Uraian Singkat</strong>
                            <small class="d-block text-muted">Jawab bebas</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="d-block p-4 border rounded-3 text-center cursor-pointer hover-shadow @error('tipe') border-danger @enderror">
                            <input type="radio" name="tipe" value="matching" class="d-none" {{ old('tipe') == 'matching' ? 'checked' : '' }} required>
                            <i class="fas fa-exchange-alt fa-2x mb-2 d-block text-success"></i>
                            <strong>Menjodohkan</strong>
                            <small class="d-block text-muted">Cocokkan kolom</small>
                        </label>
                    </div>
                </div>
                @error('tipe') <div class="text-danger mt-2">{{ $message }}</div> @enderror
            </div>

            <!-- Pertanyaan -->
            <div class="mb-4">
                <label class="form-label fw-bold fs-6 text-primary">Pertanyaan <span class="text-danger">*</span></label>
                <textarea name="pertanyaan" class="form-control @error('pertanyaan') is-invalid @enderror" 
                          rows="4" placeholder="Tulis pertanyaan soal disini..." required>{{ old('pertanyaan') }}</textarea>
                @error('pertanyaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Opsi Jawaban (PG & Matching) -->
            <div id="opsi-container" style="display: none;">
                <label class="form-label fw-bold fs-6 text-primary">Pilihan Jawaban</label>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Opsi A <span class="text-danger">*</span></label>
                        <input type="text" name="opsi_a" class="form-control @error('opsi_a') is-invalid @enderror" 
                               value="{{ old('opsi_a') }}" placeholder="Jawaban A">
                        @error('opsi_a') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Opsi B <span class="text-danger">*</span></label>
                        <input type="text" name="opsi_b" class="form-control @error('opsi_b') is-invalid @enderror" 
                               value="{{ old('opsi_b') }}" placeholder="Jawaban B">
                        @error('opsi_b') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Opsi C</label>
                        <input type="text" name="opsi_c" class="form-control" value="{{ old('opsi_c') }}" placeholder="Jawaban C">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Opsi D</label>
                        <input type="text" name="opsi_d" class="form-control" value="{{ old('opsi_d') }}" placeholder="Jawaban D">
                    </div>
                </div>

                <!-- Jawaban Benar PG -->
                <div class="mb-4">
                    <label class="form-label fw-bold fs-6 text-success">✅ Kunci Jawaban <span class="text-danger">*</span></label>
                    <select name="jawaban_benar" class="form-select @error('jawaban_benar') is-invalid @enderror">
                        <option value="">-- Pilih Jawaban Benar --</option>
                        <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('jawaban_benar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-4">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-lg">
                    <i class="fas fa-plus me-2"></i>Tambah Soal & Lanjut
                </button>
            </div>
        </div>
    </div>
</form>

<style>
.hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important; transform: translateY(-2px); }
.cursor-pointer { cursor: pointer; }
</style>

<script>
document.querySelectorAll('input[name="tipe"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const container = document.getElementById('opsi-container');
        container.style.display = (this.value === 'pg' || this.value === 'matching') ? 'block' : 'none';
    });
});
</script>
@endsection
