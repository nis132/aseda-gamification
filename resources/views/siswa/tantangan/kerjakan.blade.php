@extends('layouts.app')
@section('title', 'Mengerjakan Tantangan - ' . $tantangan->judul)

@section('content')
@if(session('newBadges'))
    @include('badge.popup', ['newBadges' => session('newBadges')])
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- HEADER INFO --}}
        <div class="card mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3 gap-3">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--txt-primary);">{{ $tantangan->judul }}</h5>
                        <span class="badge"
                              style="background: var(--clr-primary-light); color: var(--clr-primary); font-size:0.72rem;">
                            {{ $tantangan->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                        </span>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold text-warning mb-0" style="font-size:1.1rem;">
                            <i class="fas fa-star me-1"></i>{{ $tantangan->poin }} Poin
                        </div>
                        <small style="color: var(--txt-secondary);">Target Skor</small>
                    </div>
                </div>

                {{-- PROGRESS BAR --}}
                <div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="fw-bold" style="color: var(--txt-secondary);">Progress Pengerjaan</span>
                        <span class="fw-bold" style="color: var(--clr-primary);" id="progressText">
                            {{ $soals->count() > 0 ? '1 / ' . $soals->count() : '0 / 0' }}
                        </span>
                    </div>
                    <div class="progress" style="height:10px; border-radius: var(--border-radius-sm);">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                             id="progressBar"
                             role="progressbar"
                             style="width: {{ $soals->count() > 0 ? 100 / $soals->count() : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EMPTY STATE --}}
        @if($soals->count() == 0)
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h6>Belum ada soal</h6>
                        <p>Tantangan ini belum memiliki soal.</p>
                    </div>
                </div>
            </div>
        @endif

        @if($soals->count() > 0)
        <form method="POST" id="formTantangan" action="{{ route('siswa.tantangan.submit', $tantangan) }}">
            @csrf

            {{-- SOAL CONTAINER --}}
            <div id="soalContainer">
                @foreach($soals as $index => $soal)
                <div class="soal-slide" data-index="{{ $index }}" style="{{ $index == 0 ? '' : 'display:none;' }}">

                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">

                            {{-- NOMOR SOAL --}}
                            <div class="d-flex align-items-center mb-4">
                                <div class="soal-number me-3">{{ $index + 1 }}</div>
                                <h5 class="fw-bold mb-0" style="color: var(--txt-primary);">Pertanyaan</h5>
                            </div>

                            {{-- TEKS PERTANYAAN --}}
                            <div class="mb-5 fs-5" style="color: var(--txt-primary); line-height: 1.7;">
                                {{ $soal->pertanyaan }}
                            </div>

                            {{-- OPSI JAWABAN --}}
                            <div class="options-container">

                                {{-- PILIHAN GANDA --}}
                                @if($soal->tipe === 'pg')
                                    @foreach(['a','b','c','d'] as $opsi)
                                        @php $field = "opsi_$opsi"; @endphp
                                        @if($soal->$field)
                                        <div class="option-item mb-3">
                                            <input type="radio" class="btn-check"
                                                   name="jawaban[{{ $soal->id }}]"
                                                   id="q{{ $soal->id }}{{ $opsi }}"
                                                   value="{{ strtoupper($opsi) }}"
                                                   autocomplete="off">
                                            <label class="btn btn-outline-light text-dark w-100 text-start p-3 border d-flex align-items-center"
                                                   style="border-radius: var(--border-radius-md) !important;"
                                                   for="q{{ $soal->id }}{{ $opsi }}">
                                                <span class="option-label me-3">{{ strtoupper($opsi) }}</span>
                                                <span class="option-text">{{ $soal->$field }}</span>
                                            </label>
                                        </div>
                                        @endif
                                    @endforeach

                                {{-- ESSAY --}}
                                @elseif($soal->tipe === 'essay')
                                    <div class="form-floating">
                                        <textarea name="jawaban[{{ $soal->id }}]"
                                                  class="form-control"
                                                  placeholder="Ketik jawabanmu di sini..."
                                                  id="essay{{ $soal->id }}"
                                                  style="height:150px; border-radius: var(--border-radius-md);"></textarea>
                                        <label for="essay{{ $soal->id }}"
                                               style="color: var(--txt-tertiary);">Tuliskan jawaban lengkap...</label>
                                    </div>

                                {{-- MATCHING --}}
                                @elseif($soal->tipe === 'matching')
                                    <div class="alert border-0 rounded-3 mb-4 small"
                                         style="background: var(--clr-primary-light); color: var(--clr-primary);">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Klik item kiri lalu pilih pasangan di kanan.
                                    </div>

                                    @php
                                        $kiri  = json_decode($soal->kiri_items  ?? '[]', true);
                                        $kanan = json_decode($soal->kanan_items ?? '[]', true);
                                        $kananIndexed = collect($kanan)
                                            ->map(fn($item, $i) => ['text' => $item, 'index' => $i])
                                            ->shuffle();
                                    @endphp

                                    <div class="matching-container position-relative">
                                        <div class="row">
                                            <div class="col-md-6">
                                                @foreach($kiri as $i => $item)
                                                    <div class="match-item kiri" data-id="kiri-{{ $i }}">
                                                        {{ $i + 1 }}. {{ $item }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6">
                                                @foreach($kananIndexed as $i => $item)
                                                    <div class="match-item kanan" data-id="kanan-{{ $item['index'] }}">
                                                        {{ chr(65 + $i) }}. {{ $item['text'] }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <svg class="lineCanvas"></svg>
                                        <input type="hidden" name="jawaban[{{ $soal->id }}]" class="hasilJawaban">
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- NAVIGASI --}}
            <div class="d-flex justify-content-between mt-4 align-items-center">
                <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" id="prevBtn">
                    <i class="fas fa-chevron-left me-2"></i>Sebelumnya
                </button>

                <span class="d-none d-md-block small" style="color: var(--txt-tertiary);">
                    <i class="fas fa-keyboard me-1"></i>Gunakan tombol untuk berpindah soal
                </span>

                <button type="button" class="btn btn-primary px-5 rounded-pill fw-bold" id="nextBtn">
                    Berikutnya <i class="fas fa-chevron-right ms-2"></i>
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

{{-- MODAL KONFIRMASI SUBMIT — pakai <x-modal> --}}
<x-modal id="modalSubmit" title="Kirim Jawaban?" type="primary" icon="fa-paper-plane">
    <div class="text-center">
        <div class="mb-4">
            <div class="stat-icon stat-icon-primary mx-auto" style="width:64px; height:64px; font-size:1.6rem;">
                <i class="fas fa-paper-plane"></i>
            </div>
        </div>
        <p class="mb-1" style="color: var(--txt-primary);">Pastikan semua jawaban sudah terisi dengan benar.</p>
        <p class="small mb-0" style="color: var(--txt-secondary);">Jawaban tidak dapat diubah setelah dikirim.</p>
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-center w-100 gap-2">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                Cek Kembali
            </button>
            <button class="btn btn-primary rounded-pill px-4 fw-bold" id="btnSubmitFinal">
                <i class="fas fa-paper-plane me-2"></i>Ya, Kirim
            </button>
        </div>
    </x-slot:footer>
</x-modal>

{{-- MODAL VALIDASI — pakai <x-modal> --}}
<x-modal id="modalValidasi" title="Jawaban Belum Lengkap" type="danger" icon="fa-exclamation-triangle">
    <p id="textValidasi" class="mb-0" style="color: var(--txt-primary);"></p>

    <x-slot:footer>
        <div class="d-flex justify-content-center w-100">
            <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                Oke, Saya Periksa
            </button>
        </div>
    </x-slot:footer>
</x-modal>

@endsection

@push('styles')
<style>
.soal-number {
    width: 40px;
    height: 40px;
    background: var(--clr-primary);
    color: #fff;
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(var(--clr-primary-rgb), 0.3);
}

/* Opsi pilihan ganda */
.option-item input:checked + label {
    background-color: var(--clr-primary-light) !important;
    border-color: var(--clr-primary) !important;
    color: var(--clr-primary) !important;
    box-shadow: 0 4px 12px rgba(var(--clr-primary-rgb), 0.12) !important;
}

.option-label {
    width: 34px;
    height: 34px;
    background: var(--bg-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius-sm);
    font-weight: 700;
    color: var(--clr-primary);
    flex-shrink: 0;
    transition: all var(--transition);
}

.option-item input:checked + label .option-label {
    background: var(--clr-primary);
    color: #fff;
}

/* Matching */
.matching-container { position: relative; }

.lineCanvas {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 1;
}

.match-item {
    position: relative;
    z-index: 2;
    padding: 12px;
    background: var(--bg-muted);
    border-radius: var(--border-radius-sm);
    margin-bottom: 10px;
    cursor: pointer;
    border: 1px solid var(--border-color);
    transition: all var(--transition);
    font-size: 0.875rem;
    color: var(--txt-primary);
}

.match-item:hover { background: var(--clr-primary-light); border-color: var(--clr-primary); }
.match-item.active { background: var(--clr-primary); color: #fff; border-color: var(--clr-primary-dark); }
</style>
@endpush

@push('scripts')
<script>
/* ---- Navigasi soal ---- */
let current = 0;
const slides    = document.querySelectorAll('.soal-slide');
const total     = slides.length;
const progressBar  = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');
const prevBtn   = document.getElementById('prevBtn');
const nextBtn   = document.getElementById('nextBtn');

if (total === 0) {
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'none';
}

function showSlide(index) {
    slides.forEach(s => s.style.display = 'none');
    slides[index].style.display = 'block';

    const pct = ((index + 1) / total) * 100;
    if (progressBar)  progressBar.style.width  = pct + '%';
    if (progressText) progressText.innerText   = (index + 1) + ' / ' + total;

    if (prevBtn) prevBtn.disabled = index === 0;

    const isLast = index === total - 1;
    if (nextBtn) {
        nextBtn.innerHTML = isLast
            ? 'Kirim Jawaban <i class="fas fa-paper-plane ms-2"></i>'
            : 'Berikutnya <i class="fas fa-chevron-right ms-2"></i>';
        nextBtn.classList.toggle('btn-success', isLast);
        nextBtn.classList.toggle('btn-primary', !isLast);
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function getBelumDijawab() {
    let belum = [];
    slides.forEach((slide, i) => {
        let answered = false;
        const radios   = slide.querySelectorAll('input[type="radio"]');
        const textarea = slide.querySelector('textarea');
        const hidden   = slide.querySelector('.hasilJawaban');

        if (radios.length > 0)   radios.forEach(r => { if (r.checked) answered = true; });
        if (textarea && textarea.value.trim()) answered = true;
        if (hidden   && hidden.value.trim())   answered = true;

        if (!answered) belum.push(i + 1);
    });
    return belum;
}

function showValidasi(belum) {
    document.getElementById('textValidasi').innerText =
        'Masih ada soal yang belum dijawab: No ' + belum.join(', ');
    new bootstrap.Modal(document.getElementById('modalValidasi')).show();
    current = belum[0] - 1;
    showSlide(current);
}

if (nextBtn) nextBtn.addEventListener('click', () => {
    if (current < total - 1) {
        current++;
        showSlide(current);
    } else {
        const belum = getBelumDijawab();
        if (belum.length > 0) { showValidasi(belum); return; }
        new bootstrap.Modal(document.getElementById('modalSubmit')).show();
    }
});

if (prevBtn) prevBtn.addEventListener('click', () => {
    if (current > 0) { current--; showSlide(current); }
});

const btnFinal = document.getElementById('btnSubmitFinal');
if (btnFinal) btnFinal.addEventListener('click', function () {
    const belum = getBelumDijawab();
    if (belum.length > 0) { showValidasi(belum); return; }

    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    document.getElementById('formTantangan').submit();
});

if (total > 0) showSlide(0);

/* ---- Matching / menjodohkan ---- */
document.querySelectorAll('.matching-container').forEach(container => {
    let selectedKiri = null;
    let pairs  = {};
    const svg        = container.querySelector('.lineCanvas');
    const input      = container.querySelector('.hasilJawaban');
    const kiriItems  = container.querySelectorAll('.kiri');
    const kananItems = container.querySelectorAll('.kanan');

    kiriItems.forEach(el => {
        el.addEventListener('click', () => {
            selectedKiri = el;
            kiriItems.forEach(k => k.classList.remove('active'));
            el.classList.add('active');
        });
    });

    kananItems.forEach(el => {
        el.addEventListener('click', () => {
            if (!selectedKiri) return;
            pairs[selectedKiri.dataset.id] = el.dataset.id;
            drawLines();
            selectedKiri.classList.remove('active');
            selectedKiri = null;
            saveJawaban();
        });
    });

    function drawLines() {
        svg.innerHTML = '';
        Object.keys(pairs).forEach(kiriKey => {
            const kiriEl  = container.querySelector(`[data-id="${kiriKey}"]`);
            const kananEl = container.querySelector(`[data-id="${pairs[kiriKey]}"]`);
            if (!kiriEl || !kananEl) return;

            const r1 = kiriEl.getBoundingClientRect();
            const r2 = kananEl.getBoundingClientRect();
            const pr = container.getBoundingClientRect();

            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', r1.right  - pr.left);
            line.setAttribute('y1', r1.top + r1.height / 2 - pr.top);
            line.setAttribute('x2', r2.left   - pr.left);
            line.setAttribute('y2', r2.top + r2.height / 2 - pr.top);
            line.setAttribute('stroke', 'var(--clr-primary)');
            line.setAttribute('stroke-width', '2');
            svg.appendChild(line);
        });
    }

    function saveJawaban() {
        input.value = Object.keys(pairs)
            .map(k => `${parseInt(k.split('-')[1]) + 1}-${pairs[k].split('-')[1]}`)
            .join(',');
    }
});
</script>
@endpush