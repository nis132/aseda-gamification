@extends('layouts.app')
@section('title', 'Hasil Tantangan')

@section('content')
<div class="container py-4">

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-chart-bar me-2"></i>Hasil Tantangan
            </h5>
        </div>

        <div class="card-body text-center">

            <h4 class="fw-bold mb-2">{{ $tantangan->judul }}</h4>

            <h1 class="display-4 text-success fw-bold">
                {{ round($nilai->total_nilai) }}%
            </h1>

            <p class="text-muted">Skor kamu</p>

            <a href="{{ route('siswa.tantangan') }}" class="btn btn-primary mt-3">
                Kembali
            </a>

        </div>
    </div>

</div>
@endsection