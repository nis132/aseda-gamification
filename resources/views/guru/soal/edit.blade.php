@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow">
        <div class="card-header bg-warning">
            <h5>Edit Soal</h5>
        </div>

        <form method="POST" action="{{ route('guru.soal.update', [$tantangan, $soal]) }}">
            @csrf @method('PUT')

            <div class="card-body">

                <div class="mb-3">
                    <label>Tipe</label>
                    <select name="tipe" class="form-select" id="tipe">
                        <option value="pg" {{ $soal->tipe == 'pg' ? 'selected' : '' }}>PG</option>
                        <option value="essay" {{ $soal->tipe == 'essay' ? 'selected' : '' }}>Essay</option>
                        <option value="matching" {{ $soal->tipe == 'matching' ? 'selected' : '' }}>Matching</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Pertanyaan</label>
                    <textarea name="pertanyaan" class="form-control">{{ $soal->pertanyaan }}</textarea>
                </div>

<div id="pgForm">
    <input name="opsi_a" class="form-control mb-2" value="{{ $soal->opsi_a }}" placeholder="Opsi A">
    <input name="opsi_b" class="form-control mb-2" value="{{ $soal->opsi_b }}" placeholder="Opsi B">
    <input name="opsi_c" class="form-control mb-2" value="{{ $soal->opsi_c }}" placeholder="Opsi C">
    <input name="opsi_d" class="form-control mb-2" value="{{ $soal->opsi_d }}" placeholder="Opsi D">

    {{-- 🔥 DROPDOWN JAWABAN --}}
    <select name="jawaban_pg" class="form-select">
        <option value="">-- Pilih Jawaban Benar --</option>
        <option value="A" {{ $soal->jawaban_benar == 'A' ? 'selected' : '' }}>A</option>
        <option value="B" {{ $soal->jawaban_benar == 'B' ? 'selected' : '' }}>B</option>
        <option value="C" {{ $soal->jawaban_benar == 'C' ? 'selected' : '' }}>C</option>
        <option value="D" {{ $soal->jawaban_benar == 'D' ? 'selected' : '' }}>D</option>
    </select>
</div>

                {{-- Essay --}}
                <div id="essayForm">
                    <textarea name="jawaban_benar" class="form-control">{{ $soal->jawaban_benar }}</textarea>
                </div>

                {{-- Matching --}}
                <div id="matchingForm">
                    <input name="kiri" class="form-control mb-2"
                        value="{{ implode(',', json_decode($soal->kiri_items ?? '[]')) }}">
                    
                    <input name="kanan" class="form-control"
                        value="{{ implode(',', json_decode($soal->kanan_items ?? '[]')) }}">
                </div>

            </div>

            <div class="card-footer text-end">
                <button class="btn btn-warning">Update</button>
            </div>

        </form>
    </div>

</div>

<script>
const tipe = document.getElementById('tipe');
const pg = document.getElementById('pgForm');
const essay = document.getElementById('essayForm');
const matching = document.getElementById('matchingForm');

function toggleForm() {
    pg.style.display = 'none';
    essay.style.display = 'none';
    matching.style.display = 'none';

    if (tipe.value === 'pg') pg.style.display = 'block';
    if (tipe.value === 'essay') essay.style.display = 'block';
    if (tipe.value === 'matching') matching.style.display = 'block';
}

tipe.addEventListener('change', toggleForm);
toggleForm();
</script>

@endsection