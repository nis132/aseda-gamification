@extends('layouts.app')
@section('title', 'Penilaian - ' . $tantangan->judul)

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            TUGAS : {{ strtoupper($tantangan->judul) }}
        </h3>

        <div class="d-flex gap-4 text-muted">
            <div>
                <strong>{{ $jawaban->count() }}</strong> Diserahkan
            </div>
        </div>
    </div>

    {{-- GRID SISWA --}}
    <div class="row g-4">

        @foreach($jawaban as $siswaId => $listJawaban)

            @php
                $total = 0;
                $count = 0;
                $belumFinal = false;

                foreach($listJawaban as $j){
                    if($j->nilai !== null){
                        $total += $j->nilai;
                        $count++;
                    }else{
                        $belumFinal = true;
                    }
                }

                $nilaiAkhir = $count > 0 ? round($total/$count,1) : 0;
            @endphp

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="card shadow-sm border-0 h-100 siswa-card"
                     style="cursor:pointer"
                     onclick="window.location='{{ route('guru.nilai.detail', [$tantangan->id, $siswaId]) }}'">

                    <div class="card-body text-center">

                        {{-- FOTO --}}
                        <div class="mb-3">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                 style="width:70px;height:70px;margin:auto;font-size:24px;">
                                {{ strtoupper(substr($listJawaban->first()->siswa->nama,1)) }}
                            </div>
                        </div>

                        {{-- NAMA --}}
                        <h6 class="fw-bold mb-2">
                            {{ $listJawaban->first()->siswa->nama }}
                        </h6>

                        {{-- STATUS --}}
                        @if($belumFinal)
                            <span class="badge bg-warning mb-2">
                                Menunggu Penilaian
                            </span>
                        @else
                            <span class="badge bg-success mb-2">
                                Dinilai
                            </span>
                        @endif

                        {{-- NILAI --}}
                        <div class="mt-2">
                            <h5 class="fw-bold text-primary">
                                {{ $nilaiAkhir }}%
                            </h5>
                        </div>

                    </div>
                </div>

            </div>

        @endforeach

    </div>

</div>

<style>
.siswa-card:hover{
    transform: translateY(-5px);
    transition: 0.2s;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>

@endsection