@extends('layouts.app')
@section('title', 'Materi Pelajaran - Siswa')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fas fa-book me-2"></i>Materi Pelajaran
            </h4>
            <small class="text-muted">
                Kelas {{ $kelasId ?? '-' }} • {{ $materis->total() }} materi
            </small>
        </div>

        {{-- FILTER MAPEL --}}
        <div class="col-md-3 ms-auto">
            <form method="GET">
                <select name="mapel" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Mapel --</option>
                    @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}"
                            {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- LIST MATERI --}}
    <div class="row g-3">
        @forelse($materis as $item)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 hover-lift">

                    {{-- BADGE FILE --}}
                    @if($item->file_url)
                        <span class="position-absolute top-0 end-0 m-2 badge bg-success">
                            PDF
                        </span>
                    @endif

                    <div class="card-body p-3 d-flex flex-column">

                        {{-- JUDUL --}}
                        <h6 class="fw-bold mb-1">
                            {{ Str::limit($item->judul, 45) }}
                        </h6>

                        <p class="text-muted small mb-2">
                            {{ Str::limit($item->deskripsi, 70) }}
                        </p>

                        {{-- BADGE --}}
                        <div class="mb-2">
                            <span class="badge bg-info">
                                {{ $item->kelas->nama_kelas ?? '-' }}
                            </span>
                            <span class="badge bg-primary">
                                {{ $item->mapel->nama_mapel ?? '-' }}
                            </span>
                        </div>

                        {{-- GURU --}}
                        <small class="text-muted mb-2">
                            <i class="fas fa-user me-1"></i>
                            {{ $item->guru->name ?? '-' }}
                        </small>

                        {{-- FOOTER --}}
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                {{ $item->created_at->diffForHumans() }}
                            </small>

                            <a href="{{ route('siswa.materi.show', $item) }}"
                               class="btn btn-sm btn-primary">
                                Buka
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="text-center py-4 bg-light rounded">
                    <h6 class="text-muted">Belum ada materi</h6>
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3 d-flex justify-content-center">
        {{ $materis->links() }}
    </div>

</div>
@endsection

@push('styles')
<style>
.hover-lift {
    transition: 0.2s;
}
.hover-lift:hover {
    transform: translateY(-4px);
}
</style>
@endpush