@extends('layouts.app')
@section('title','Kerjakan Tantangan')

@section('content')
<div class="container">

    <h3>{{ $tantangan->judul }}</h3>

    <form method="POST" action="{{ route('siswa.tantangan.submit', $tantangan) }}">
        @csrf

@foreach($soals as $index => $soal)
<div class="soal-item mb-5 p-4 border rounded-4 shadow-sm">
    <h6 class="fw-bold mb-4">
        <span class="badge bg-primary fs-6 me-3">Soal {{ $index + 1 }}</span>
        {{ $soal->pertanyaan }}
    </h6>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-check-label fw-semibold">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="A" class="form-check-input" required>
                A. {{ $soal->pilihan_a }}
            </label>
        </div>
        <div class="col-md-6">
            <label class="form-check-label fw-semibold">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="B" class="form-check-input" required>
                B. {{ $soal->pilihan_b }}
            </label>
        </div>
        <div class="col-md-6">
            <label class="form-check-label fw-semibold">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="C" class="form-check-input" required>
                C. {{ $soal->pilihan_c }}
            </label>
        </div>
        <div class="col-md-6">
            <label class="form-check-label fw-semibold">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="D" class="form-check-input" required>
                D. {{ $soal->pilihan_d }}
            </label>
        </div>
    </div>
    
    {{-- DEBUG: Cek jawaban benar (HAPUS nanti) --}}
    <small class="text-muted mt-2 d-block">
        Kunci: <strong>{{ $soal->jawaban_benar }}</strong>
    </small>
</div>
@endforeach


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
@endsection
