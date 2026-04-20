@extends('layouts.app')
@section('title', 'Kelola Materi')

@section('content')
<div class="row">
    <div class="col-12">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">
                    <i class="fas fa-book-open me-2"></i>Kelola Materi
                </h2>
                <p class="text-muted mb-0">Atur dan distribusikan bahan ajar untuk siswa</p>
            </div>

            <a href="{{ route('guru.materi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Materi
            </a>
        </div>

        {{-- STAT --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h4 class="mb-0">{{ $materis->total() }}</h4>
                    <small class="text-muted">Total Materi</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h4 class="mb-0">{{ $materis->where('file_materi', '!=', null)->count() }}</h4>
                    <small class="text-muted">Materi Berkas</small>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>Nama Materi</th>
                                <th>Mapel</th>
                                <th>Kelas</th>
                                <th>File</th>
                                <th>Tanggal</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($materis as $item)
                                <tr>

                                    {{-- NAMA --}}
                                    <td class="fw-semibold">
                                        {{ $item->judul }}
                                        <div class="small text-muted">
                                            {{ Str::limit($item->deskripsi, 50) }}
                                        </div>
                                    </td>

                                    {{-- MAPEL --}}
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $item->mapel->nama_mapel ?? 'Umum' }}
                                        </span>
                                    </td>

                                    {{-- KELAS --}}
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $item->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- FILE --}}
                                    <td>
                                        @if($item->file_url)
                                            <span class="badge bg-success">
                                                Ada File
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Tidak Ada
                                            </span>
                                        @endif
                                    </td>

                                    {{-- TANGGAL --}}
                                    <td>
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>

                                    {{-- AKSI --}}
                                    <td>
                                        <a href="{{ route('guru.materi.show', $item) }}"
                                           class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('guru.materi.edit', $item) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('guru.materi.destroy', $item) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Hapus materi?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        Belum ada materi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $materis->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection