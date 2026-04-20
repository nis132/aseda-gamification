@extends('layouts.app')
@section('title','Kerjakan Tantangan - ' . $tantangan->judul)

@section('content')

<div class="container py-4">

    {{-- PROGRESS --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between small mb-1">
            <span>Progress</span>
            <span id="progressText">1/{{ $soals->count() }}</span>
        </div>
        <div class="progress" style="height:8px;">
            <div class="progress-bar bg-primary" id="progressBar"
                style="width: {{ 100 / $soals->count() }}%">
            </div>
        </div>
    </div>

    <form method="POST" id="formTantangan" action="{{ route('siswa.tantangan.submit', $tantangan) }}">
        @csrf

        {{-- SLIDE CONTAINER --}}
        <div id="soalContainer">

            @foreach($soals as $index => $soal)
            <div class="soal-slide" data-index="{{ $index }}" style="{{ $index == 0 ? '' : 'display:none;' }}">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Soal {{ $index + 1 }}
                        </h5>

                        <p class="mb-4">{{ $soal->pertanyaan }}</p>

                        {{-- ============================= --}}
                        {{-- PILIHAN GANDA --}}
                        {{-- ============================= --}}
                        @if($soal->tipe === 'pg')
                            @foreach(['a','b','c','d'] as $opsi)
                                @php $field = "opsi_$opsi"; @endphp
                                @if($soal->$field)
                                    <label class="d-block border rounded p-2 mb-2">
                                        <input type="radio"
                                            name="jawaban[{{ $soal->id }}]"
                                            value="{{ strtoupper($opsi) }}">
                                        {{ strtoupper($opsi) }}. {{ $soal->$field }}
                                    </label>
                                @endif
                            @endforeach

                        {{-- ============================= --}}
                        {{-- ESSAY --}}
                        {{-- ============================= --}}
                        @elseif($soal->tipe === 'essay')
                            <textarea name="jawaban[{{ $soal->id }}]"
                                class="form-control"
                                rows="4"
                                placeholder="Tulis jawaban..."></textarea>

                        {{-- ============================= --}}
                        {{-- MATCHING --}}
                        {{-- ============================= --}}
                        @elseif($soal->tipe === 'matching')
                            @php
                                $kiri = json_decode($soal->kiri_items ?? '[]', true);
                                $kanan = json_decode($soal->kanan_items ?? '[]', true);
                                $shuffled = collect($kanan)->shuffle()->values();
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    @foreach($kiri as $i => $item)
                                        <div class="p-2 border mb-2"
                                            draggable="true"
                                            data-left="{{ $i }}"
                                            data-soal="{{ $soal->id }}">
                                            {{ $item }}
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-md-6">
                                    @foreach($shuffled as $i => $item)
                                        <div class="p-2 border mb-2 text-center"
                                            data-right="{{ array_search($item, $kanan) }}"
                                            data-soal="{{ $soal->id }}">
                                            {{ $item }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div id="result-{{ $soal->id }}"></div>
                        @endif

                    </div>
                </div>

            </div>
            @endforeach

        </div>

        {{-- NAVIGATION --}}
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary" id="prevBtn">Sebelumnya</button>
            <button type="button" class="btn btn-primary" id="nextBtn">Berikutnya</button>
        </div>

    </form>
</div>

{{-- MODAL SUBMIT --}}
<div class="modal fade" id="modalSubmit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <h5>Yakin mau submit?</h5>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success" id="btnSubmitFinal">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
let current = 0;
const slides = document.querySelectorAll('.soal-slide');
const total = slides.length;

const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

function showSlide(index) {
    slides.forEach(s => s.style.display = 'none');
    slides[index].style.display = 'block';

    const percent = ((index + 1) / total) * 100;
    progressBar.style.width = percent + '%';
    progressText.innerText = (index + 1) + '/' + total;

    prevBtn.disabled = index === 0;
    nextBtn.innerText = index === total - 1 ? 'Submit' : 'Berikutnya';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

nextBtn.addEventListener('click', () => {
    if (current < total - 1) {
        current++;
        showSlide(current);
    } else {
        new bootstrap.Modal(document.getElementById('modalSubmit')).show();
    }
});

prevBtn.addEventListener('click', () => {
    if (current > 0) {
        current--;
        showSlide(current);
    }
});

document.getElementById('btnSubmitFinal').addEventListener('click', function() {
    document.getElementById('formTantangan').submit();
});

showSlide(0);
</script>

@endsection