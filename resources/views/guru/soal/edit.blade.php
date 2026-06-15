@extends('layouts.app')
@section('title', 'Edit Butir Soal')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Butir Soal</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Tantangan: <strong style="color: var(--clr-primary);">{{ $tantangan->judul }}</strong>
        </p>
    </div>
    <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header card-header-gradient">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-question-circle"></i>
                    <span class="fw-bold" style="font-size: 0.9rem;">Edit Butir Soal</span>
                </div>
            </div>

            <form method="POST" action="{{ route('guru.soal.update', [$tantangan, $soal]) }}">
                @csrf
                @method('PUT')

                <div class="card-body p-4">

                    {{-- TIPE --}}
                    <div class="mb-3">
                        <label class="form-label">Tipe Pertanyaan</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--clr-primary);">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            <select name="tipe" id="tipe" class="form-select">
                                <option value="pg"       {{ $soal->tipe == 'pg'       ? 'selected' : '' }}>Pilihan Ganda</option>
                                <option value="essay"    {{ $soal->tipe == 'essay'    ? 'selected' : '' }}>Esai (Uraian)</option>
                                <option value="matching" {{ $soal->tipe == 'matching' ? 'selected' : '' }}>Menjodohkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Isi Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="4"
                                  placeholder="Tuliskan pertanyaan di sini..." required>{{ $soal->pertanyaan }}</textarea>
                    </div>

                    <div style="border-top: 1px solid var(--border-color); margin-bottom: 1.25rem;"></div>

                    {{-- FORM PG --}}
                    <div id="pgForm" class="p-3 rounded-2 mb-3"
                         style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                        <div class="text-label mb-3">
                            <i class="fas fa-list-ul me-1"></i>Opsi Jawaban
                        </div>
                        <div class="row g-2 mb-3">
                            @foreach(['a','b','c','d'] as $opsi)
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text fw-bold"
                                          style="background: var(--bg-card); border-color: var(--border-color);
                                                 color: var(--txt-secondary); min-width: 40px; justify-content: center;">
                                        {{ strtoupper($opsi) }}
                                    </span>
                                    <input name="opsi_{{ $opsi }}" class="form-control"
                                           value="{{ $soal->{'opsi_'.$opsi} }}"
                                           placeholder="Opsi {{ strtoupper($opsi) }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div>
                            <label class="form-label">Kunci Jawaban Benar</label>
                            <select name="jawaban_pg" class="form-select"
                                    style="border-color: var(--clr-success); color: var(--clr-success); font-weight: 600;">
                                <option value="">-- Pilih Jawaban --</option>
                                @foreach(['A','B','C','D'] as $key)
                                    <option value="{{ $key }}"
                                        {{ $soal->jawaban_benar == $key ? 'selected' : '' }}>
                                        Jawaban {{ $key }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- FORM ESSAY --}}
                    <div id="essayForm" class="p-3 rounded-2 mb-3"
                         style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                        <div class="text-label mb-3">
                            <i class="fas fa-pen-nib me-1"></i>Referensi Kunci Jawaban
                        </div>
                        <textarea name="jawaban_benar" class="form-control" rows="3"
                                  placeholder="Masukkan poin-poin kunci jawaban...">{{ $soal->jawaban_benar }}</textarea>
                        <div class="mt-1" style="font-size: 0.76rem; color: var(--txt-tertiary); font-style: italic;">
                            *Tipe essay memerlukan koreksi manual oleh Guru.
                        </div>
                    </div>

                    {{-- FORM MATCHING --}}
                    <div id="matchingForm" class="p-3 rounded-2 mb-3"
                         style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                        <div class="text-label mb-3">
                            <i class="fas fa-random me-1"></i>Mode Menjodohkan
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item Sisi Kiri (pisahkan dengan koma)</label>
                            <input name="kiri" class="form-control"
                                   value="{{ implode(',', json_decode($soal->kiri_items ?? '[]')) }}"
                                   placeholder="Ayam, Sapi, Kambing">
                        </div>
                        <div>
                            <label class="form-label">Item Sisi Kanan (berurutan dengan sisi kiri)</label>
                            <input name="kanan" class="form-control"
                                   value="{{ implode(',', json_decode($soal->kanan_items ?? '[]')) }}"
                                   placeholder="Unggas, Mamalia, Herbivora">
                        </div>
                    </div>

                </div>

                <div class="card-body pt-0 px-4 pb-4">
                    <div class="d-flex justify-content-between align-items-center pt-3"
                         style="border-top: 1px solid var(--border-color);">
                        <span style="font-size: 0.78rem; color: var(--txt-tertiary);">
                            <i class="fas fa-info-circle me-1"></i>
                            Perubahan akan langsung terlihat oleh siswa.
                        </span>
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

@push('scripts')
<script>
const tipe     = document.getElementById('tipe');
const pgForm   = document.getElementById('pgForm');
const essayForm    = document.getElementById('essayForm');
const matchingForm = document.getElementById('matchingForm');

function toggleForm() {
    pgForm.style.display       = tipe.value === 'pg'       ? 'block' : 'none';
    essayForm.style.display    = tipe.value === 'essay'    ? 'block' : 'none';
    matchingForm.style.display = tipe.value === 'matching' ? 'block' : 'none';
}

tipe.addEventListener('change', toggleForm);
toggleForm();
</script>
@endpush