@extends('layouts.app')
@section('title', 'Review Jawaban')

@section('content')
<div class="container py-4">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-eye me-2"></i>Review Jawaban
            </h5>
        </div>

        <div class="card-body">

            <h5 class="fw-bold mb-4">{{ $tantangan->judul }}</h5>

            @foreach($tantangan->soal as $i => $soal)

                @php
                    $jawab = $jawaban->firstWhere('soal_id', $soal->id);
                @endphp

                <div class="mb-4 p-3 border rounded">

                    <h6 class="fw-bold">
                        {{ $i+1 }}. {{ $soal->pertanyaan }}
                    </h6>

                    {{-- PG --}}
                    @if($soal->tipe == 'pg')
                        <ul class="list-unstyled mt-2">

                            @foreach(['A','B','C','D'] as $opsi)
                                @php
                                    $field = 'opsi_' . strtolower($opsi);
                                    $isBenar = $soal->jawaban_benar == $opsi;
                                    $dipilih = $jawab->jawaban ?? null;
                                @endphp

                                <li class="
                                    p-2 rounded mb-1
                                    {{ $isBenar ? 'bg-success text-white' : '' }}
                                    {{ $dipilih == $opsi && !$isBenar ? 'bg-danger text-white' : '' }}
                                ">
                                    {{ $opsi }}. {{ $soal->$field }}
                                </li>
                            @endforeach

                        </ul>
                    @endif

                    {{-- ESSAY --}}
                    @if($soal->tipe == 'essay')
                        <div class="mt-2">
                            <strong>Jawaban kamu:</strong>
                            <div class="p-2 bg-light rounded">
                                {{ $jawab->jawaban ?? '-' }}
                            </div>

                            <small class="text-success">
                                Kunci: {{ $soal->jawaban_benar }}
                            </small>
                        </div>
                    @endif

                    {{-- MATCHING --}}
                    @if($soal->tipe == 'matching')
                        @php
                            $pairs = json_decode($soal->jawaban_benar, true);
                        @endphp

                        <ul class="mt-2">
                            @foreach($pairs as $pair)
                                <li>
                                    {{ $pair['kiri'] }} ➝ 
                                    <strong>{{ $pair['kanan'] }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </div>

            @endforeach

            <a href="{{ route('siswa.tantangan') }}" class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>

</div>
@endsection