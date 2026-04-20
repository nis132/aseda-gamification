@extends('layouts.app')
@section('title', 'Penilaian - ' . $tantangan->judul)

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">
            {{ $tantangan->judul }}
        </h4>
        <small class="text-muted">
            {{ $jawaban->count() }} siswa mengumpulkan
        </small>
    </div>

    {{-- EMPTY --}}
    @if($jawaban->isEmpty())
        <div class="text-center py-5">
            <h6 class="text-muted">Belum ada jawaban masuk</h6>
        </div>
    @else

    <div class="row g-3">

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
                $siswa = $listJawaban->first()->siswa;
            @endphp

            <div class="col-lg-3 col-md-4 col-6">

                <div class="card border-0 shadow-sm h-100 siswa-card"
                     onclick="window.location='{{ route('guru.nilai.detail', [$tantangan->id, $siswaId]) }}'">

                    <div class="card-body text-center p-3">

                        {{-- AVATAR --}}
                        <div class="mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                 style="width:50px;height:50px;font-size:18px;">
                                {{ strtoupper(substr($siswa->nama,0,1)) }}
                            </div>
                        </div>

                        {{-- NAMA --}}
                        <h6 class="fw-bold mb-1 small text-truncate">
                            {{ $siswa->nama }}
                        </h6>

                        {{-- STATUS --}}
                        @if($belumFinal)
                            <span class="badge bg-warning text-dark small mb-2">
                                Belum Dinilai
                            </span>
                        @else
                            <span class="badge bg-success small mb-2">
                                Selesai
                            </span>
                        @endif

                        {{-- NILAI --}}
                        <div>
                            <strong class="text-primary">
                                {{ $nilaiAkhir }}%
                            </strong>
                        </div>

                    </div>
                </div>

            </div>

        @endforeach

    </div>

    @endif

</div>

<style>
.siswa-card {
    cursor: pointer;
    transition: 0.2s;
}
.siswa-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
}
</style>

@endsection