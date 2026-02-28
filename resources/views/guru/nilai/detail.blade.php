@extends('layouts.app')
@section('title', 'Detail Penilaian')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">
        Detail Penilaian - {{ $siswa->nama }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">

            @php
                $total = 0;
                $count = 0;
            @endphp

            @foreach($jawaban as $j)
                <div class="mb-4 border-bottom pb-3">

                    <h6 class="fw-bold">
                        Soal {{ $loop->iteration }}
                    </h6>

                    <p class="mb-1">
                        <strong>Pertanyaan:</strong><br>
                        {!! $j->soal->pertanyaan !!}
                    </p>

                    <p class="mb-1">
                        <strong>Jawaban Siswa:</strong>
                        {{ $j->jawaban }}
                    </p>

                    <p class="mb-1">
                        <strong>Nilai:</strong>
                        {{ $j->nilai ?? 0 }}
                    </p>

                </div>

                @php
                    $total += $j->nilai ?? 0;
                    $count++;
                @endphp
            @endforeach

            @php
                $rata = $count > 0 ? round($total/$count,1) : 0;
            @endphp

            <div class="mt-4">
                <h5 class="fw-bold text-primary">
                    Nilai Akhir: {{ $rata }}%
                </h5>
            </div>

        </div>
    </div>

</div>
@endsection