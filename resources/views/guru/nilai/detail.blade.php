@extends('layouts.app')
@section('title', 'Detail Penilaian')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                {{ $siswa->name }}
            </h4>
            <small class="text-muted">
                Penilaian Essay & Jawaban
            </small>
        </div>

        <a href="{{ route('guru.nilai.index', $tantangan->id) }}" 
           class="btn btn-outline-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('guru.nilai.simpan', [$tantangan->id, $siswa->id]) }}">
        @csrf

        <div class="card shadow-sm border-0">
            <div class="card-body">

                @php
                    $total = 0;
                    $count = 0;
                @endphp

                @foreach($jawaban as $j)

                <div class="row align-items-start py-3 border-bottom">

                    {{-- SOAL --}}
                    <div class="col-md-5">
                        <small class="text-muted">Soal {{ $loop->iteration }}</small>
                        <div class="fw-semibold">
                            {!! $j->soal->pertanyaan !!}
                        </div>
                    </div>

                    {{-- JAWABAN --}}
                    <div class="col-md-4">
                        <small class="text-muted">Jawaban</small>
                        <div class="bg-light p-2 rounded small">
                            {{ $j->jawaban }}
                        </div>
                    </div>

                    {{-- NILAI --}}
                    <div class="col-md-3">

                        @if(!$j->dinilai_manual)
                            <span class="badge bg-success">
                                {{ $j->nilai }}
                            </span>
                        @else
                            <input type="number"
                                   name="nilai_{{ $j->id }}"
                                   value="{{ $j->nilai }}"
                                   class="form-control nilai-input"
                                   min="0"
                                   max="100"
                                   placeholder="Nilai">
                        @endif

                    </div>

                </div>

                @php
                    $total += $j->nilai ?? 0;
                    $count++;
                @endphp

                @endforeach

                {{-- TOTAL --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h5 class="fw-bold text-primary mb-0">
                        Nilai: <span id="totalNilai">{{ $count > 0 ? round($total/$count,1) : 0 }}</span>%
                    </h5>

                    <button class="btn btn-success px-4">
                        Simpan
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>

{{-- AUTO HITUNG NILAI --}}
<script>
document.querySelectorAll('.nilai-input').forEach(input => {
    input.addEventListener('input', hitung);
});

function hitung(){
    let total = 0;
    let count = 0;

    document.querySelectorAll('.nilai-input').forEach(el => {
        if(el.value !== ''){
            total += parseFloat(el.value);
            count++;
        }
    });

    let rata = count > 0 ? (total / count).toFixed(1) : 0;
    document.getElementById('totalNilai').innerText = rata;
}
</script>

@endsection