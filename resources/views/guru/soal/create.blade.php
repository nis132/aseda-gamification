@extends('layouts.app')
@section('title', 'Buat Soal - ' . $tantangan->judul)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><i class="fas fa-feather me-2"></i>Buat Soal Baru</h3>
                            <p class="mb-0 opacity-75">Tantangan: <strong>{{ $tantangan->judul }}</strong></p>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark me-2">{{ $tantangan->mapel->nama_mapel }}</span>
                                <span class="badge bg-secondary">{{ $tantangan->kelas->nama_kelas }}</span>
                            </div>
                        </div>
                        <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-arrow-left me-2"></i>Lihat Soal
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('guru.soal.store', $tantangan) }}" novalidate>
                    @csrf
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- TIPE SOAL --}}
                        <div class="row mb-5">
                            <div class="col-lg-4">
                                <label class="form-label fw-bold fs-5 text-primary">Jenis Soal <span class="text-danger">*</span></label>
                                <select name="tipe" id="tipeSoal" class="form-select form-control-lg @error('tipe') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis Soal --</option>
                                    <option value="pg" {{ old('tipe') == 'pg' ? 'selected' : '' }}>Pilihan Ganda</option>
                                    <option value="essay" {{ old('tipe') == 'essay' ? 'selected' : '' }}>Essay</option>
                                    <option value="matching" {{ old('tipe') == 'matching' ? 'selected' : '' }}>Menjodohkan</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- PERTANYAAN --}}
                        <div class="row mb-5">
                            <div class="col-lg-12">
                                <label class="form-label fw-bold fs-5 text-primary">Pertanyaan <span class="text-danger">*</span></label>
                                <textarea name="pertanyaan" rows="4" class="form-control @error('pertanyaan') is-invalid @enderror" 
                                          placeholder="Tulis pertanyaan soal dengan jelas dan spesifik..." required>{{ old('pertanyaan') }}</textarea>
                                @error('pertanyaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- FORM PG --}}
                        <div id="formPG" class="tipe-form d-none mb-5">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-primary">Opsi A <span class="text-danger">*</span></label>
                                    <input type="text" name="opsi_a" class="form-control @error('opsi_a') is-invalid @enderror" 
                                           value="{{ old('opsi_a') }}" required>
                                    @error('opsi_a') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-primary">Opsi B <span class="text-danger">*</span></label>
                                    <input type="text" name="opsi_b" class="form-control @error('opsi_b') is-invalid @enderror" 
                                           value="{{ old('opsi_b') }}" required>
                                    @error('opsi_b') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-primary">Opsi C</label>
                                    <input type="text" name="opsi_c" class="form-control" value="{{ old('opsi_c') }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-primary">Opsi D</label>
                                    <input type="text" name="opsi_d" class="form-control" value="{{ old('opsi_d') }}">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-primary">Jawaban Benar <span class="text-danger">*</span></label>
                                    <select name="jawaban_benar" class="form-select @error('jawaban_benar') is-invalid @enderror" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="A" {{ old('jawaban_benar') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('jawaban_benar') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ old('jawaban_benar') == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ old('jawaban_benar') == 'D' ? 'selected' : '' }}>D</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- FORM ESSAY --}}
                        <div id="formEssay" class="tipe-form d-none mb-5">
                            <label class="form-label fw-bold text-primary">Jawaban Benar <span class="text-danger">*</span></label>
                            <textarea name="jawaban_benar" rows="4" class="form-control @error('jawaban_benar') is-invalid @enderror" 
                                      placeholder="Tulis jawaban yang benar (kata kunci utama)..." required>{{ old('jawaban_benar') }}</textarea>
                            @error('jawaban_benar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- FORM MATCHING DYNAMIC --}}
                        <div id="formMatching" class="tipe-form d-none mb-5">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Cocokkan KIRI → KANAN (min 2 pasang, max 6 pasang)
                            </div>
                            
                            <div class="row g-3 mb-4 fw-bold border-bottom pb-2">
                                <div class="col-md-5 text-primary">KOLOM KIRI</div>
                                <div class="col-md-5 text-success">KOLOM KANAN</div>
                                <div class="col-md-2 text-center">No</div>
                            </div>

                            <!-- Pair 1 (WAJIB) -->
                            <div class="matching-pair mb-4 p-3 border rounded bg-light" data-pair="1">
                                <div class="row align-items-end g-3">
                                    <div class="col-md-5">
                                        <input type="text" name="kiri_1" class="form-control @error('kiri_1') is-invalid @enderror" 
                                               value="{{ old('kiri_1') }}" placeholder="1. Jakarta" required>
                                        @error('kiri_1') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="kanan_1" class="form-control @error('kanan_1') is-invalid @enderror" 
                                               value="{{ old('kanan_1') }}" placeholder="DKI Jakarta" required>
                                        @error('kanan_1') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-success fs-6">1</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pairs 2-6 (DINAMIS) -->
                            @for($i = 2; $i <= 6; $i++)
                            <div class="matching-pair mb-4 p-3 border rounded bg-light d-none" data-pair="{{ $i }}">
                                <div class="row align-items-end g-3">
                                    <div class="col-md-5">
                                        <input type="text" name="kiri_{{ $i }}" class="form-control" 
                                               value="{{ old("kiri_$i") }}" placeholder="{{ $i }}. Bandung">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="kanan_{{ $i }}" class="form-control" 
                                               value="{{ old("kanan_$i") }}" placeholder="Jawa Barat">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-primary fs-6">{{ $i }}</span>
                                    </div>
                                </div>
                            </div>
                            @endfor

                            <div class="text-center">
                                <button type="button" id="addPairBtn" class="btn btn-outline-primary px-4">
                                    <i class="fas fa-plus me-2"></i>Tambah Pasangan
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="card-footer bg-transparent border-0 py-4">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" name="tambah_lagi" value="1" class="btn btn-outline-primary btn-lg px-5">
                                <i class="fas fa-plus me-2"></i>Tambah Lagi
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow-lg">
                                <i class="fas fa-save me-2"></i>Simpan & Selesai
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeSelect = document.getElementById('tipeSoal');
    const forms = document.querySelectorAll('.tipe-form');
    let pairCount = 1; // Global pair counter

    // Handle tipe change
    tipeSelect.addEventListener('change', function() {
        // Hide semua forms
        forms.forEach(form => {
            form.classList.add('d-none');
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = true;
                input.removeAttribute('required');
            });
        });
        
        // Show form yang dipilih
        const tipe = this.value;
        let targetForm = null;
        
        if (tipe === 'pg') targetForm = document.getElementById('formPG');
        else if (tipe === 'essay') targetForm = document.getElementById('formEssay');
        else if (tipe === 'matching') targetForm = document.getElementById('formMatching');
        
        if (targetForm) {
            targetForm.classList.remove('d-none');
            const inputs = targetForm.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = false;
            });
            
            // PG specific required
            if (tipe === 'pg') {
                document.querySelector('input[name="opsi_a"]').setAttribute('required', 'required');
                document.querySelector('input[name="opsi_b"]').setAttribute('required', 'required');
                document.querySelector('select[name="jawaban_benar"]').setAttribute('required', 'required');
            }
            // Matching specific required
            else if (tipe === 'matching') {
                // Reset pair count
                pairCount = 1;
                document.querySelectorAll('.matching-pair').forEach(pair => pair.classList.add('d-none'));
                document.querySelector('[data-pair="1"]').classList.remove('d-none');
                
                // Required untuk pair 1 & 2 minimum
                ['kiri_1', 'kanan_1'].forEach(name => {
                    const input = document.querySelector(`input[name="${name}"]`);
                    if (input) input.setAttribute('required', 'required');
                });
            }
        }
    });

    // Dynamic Matching Pairs
    document.getElementById('addPairBtn')?.addEventListener('click', function() {
        pairCount++;
        if (pairCount > 6) {
            alert('Maksimal 6 pasangan!');
            return;
        }
        document.querySelector(`[data-pair="${pairCount}"]`).classList.remove('d-none');
        
        // Auto-focus first input
        setTimeout(() => {
            const input = document.querySelector(`[data-pair="${pairCount}"] input[name="kiri_${pairCount}"]`);
            if (input) input.focus();
        }, 100);
    });

    // Load saved tipe on page load
    if (tipeSelect.value) {
        tipeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
