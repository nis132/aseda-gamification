@extends('layouts.app')

@section('title', 'Tantangan Siswa')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-dice-d20 text-warning me-2"></i> Tantangan
        </h4>
        <small class="text-muted">
            {{ now()->format('d M Y H:i') }}
        </small>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="row g-2 mb-4">

        {{-- MAPEL --}}
        <div class="col-md-4">
            <select name="mapel" class="form-select">
                <option value="">Semua Mapel</option>
                @foreach($mapels as $mapel)
                    <option value="{{ $mapel->id }}" 
                        {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                        {{ $mapel->nama_mapel }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- STATUS --}}
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>

        {{-- BUTTON --}}
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('siswa.tantangan') }}" class="btn btn-light w-100">Reset</a>
        </div>

    </form>

    {{-- LIST --}}
    <div class="row g-3">
        @forelse($tantangans as $tantangan)
        <div class="col-lg-4 col-md-6">

            <div class="card h-100 border-0 shadow-sm hover-lift">

                <div class="card-body p-3">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="text-warning bg-warning bg-opacity-10 p-2 rounded">
                            <i class="fas fa-dice-d20"></i>
                        </div>

                        @if($tantangan->batas_waktu > now())
                            <span class="badge bg-success-subtle text-success small">
                                {{ $tantangan->batas_waktu->diffForHumans() }}
                            </span>
                        @else
                            <span class="badge bg-danger small">
                                Terlambat
                            </span>
                        @endif
                    </div>

                    {{-- TITLE --}}
                    <h6 class="fw-bold mb-1">
                        {{ Str::limit($tantangan->judul, 40) }}
                    </h6>

                    <small class="text-muted d-block mb-2">
                        ID: #T-{{ $tantangan->id }}
                    </small>

                    {{-- INFO --}}
                    <div class="mb-2 small">
                        <div>
                            <i class="fas fa-book text-primary me-1"></i>
                            {{ $tantangan->mapel->nama_mapel ?? '-' }}
                        </div>
                        <div>
                            <i class="fas fa-user text-muted me-1"></i>
                            {{ $tantangan->guru->nama ?? '-' }}
                        </div>
                    </div>

                    {{-- META --}}
                    <div class="d-flex justify-content-between small mb-2">
                        <span>
                            <i class="fas fa-star text-warning"></i>
                            {{ $tantangan->poin }}
                        </span>
                        <span>
                            <i class="fas fa-list"></i>
                            {{ $tantangan->soal_count }}
                        </span>
                    </div>

                    {{-- PROGRESS --}}
                    @if($tantangan->nilaiTantangan->isNotEmpty())
                        @php $nilai = $tantangan->nilaiTantangan->first(); @endphp

                        <div class="small text-success mb-1">
                            Skor: {{ round($nilai->total_nilai) }}%
                        </div>

                        <div class="progress mb-2" style="height:5px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ $nilai->total_nilai }}%">
                            </div>
                        </div>
                    @endif

                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 p-3 pt-0">

@if($tantangan->nilaiTantangan->isNotEmpty())
    <div class="d-flex gap-2">

        {{-- REVIEW --}}
        <a href="{{ route('siswa.tantangan.review', $tantangan->id) }}"
           class="btn btn-outline-primary w-100 btn-sm">
            <i class="fas fa-eye me-1"></i> Review
        </a>

    </div>

                    @elseif($tantangan->batas_waktu > now())
                        <a href="{{ route('siswa.tantangan.kerjakan', $tantangan) }}"
                           class="btn btn-warning w-100 btn-sm">
                            Kerjakan
                        </a>

                    @else
                        <button class="btn btn-secondary w-100 btn-sm" disabled>
                            Berakhir
                        </button>
                    @endif

                </div>

            </div>

        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h6 class="text-muted">Belum ada tantangan</h6>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($tantangans->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tantangans->appends(request()->query())->links() }}
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .pagination {
    font-size: 0.85rem;
}

.page-link {
    padding: 4px 10px;
    border-radius: 6px !important;
}

.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.hover-lift {
    transition: 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
}

.card {
    border-radius: 14px;
}

.progress {
    background-color: #e5e7eb;
    border-radius: 20px;
}
</style>
@endpush