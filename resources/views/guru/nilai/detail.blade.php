@extends('layouts.app')
@section('title', 'Detail Penilaian')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">
        Detail Penilaian - {{ $siswa->name }}
    </h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('guru.nilai.simpan', [$tantangan->id, $siswa->id]) }}">
        @csrf

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

                        <p>
                            <strong>Pertanyaan:</strong><br>
                            {!! $j->soal->pertanyaan !!}
                        </p>

                        <p>
                            <strong>Jawaban Siswa:</strong><br>
                            {{ $j->jawaban }}
                        </p>

                        {{-- AUTO --}}
                        @if(!$j->dinilai_manual)
                            <div class="alert alert-success py-2">
                                Nilai Otomatis: {{ $j->nilai }}
                            </div>
                        @else
                            {{-- MANUAL INPUT --}}
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    Nilai Essay:
                                </div>
                                <div class="col-md-3">
                                    <input type="number"
                                           name="nilai_{{ $j->id }}"
                                           value="{{ $j->nilai }}"
                                           class="form-control"
                                           min="0"
                                           max="100">
                                </div>
                            </div>
                        @endif

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
                        Nilai Sementara: {{ $rata }}%
                    </h5>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-success px-4">
                        Simpan Nilai
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection