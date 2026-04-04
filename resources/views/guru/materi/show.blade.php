@extends('layouts.app')
@section('title', 'Detail Materi')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-3">{{ $materi->judul }}</h3>

            <div class="mb-3">
                <span class="badge bg-primary">
                    Kelas: {{ $materi->kelas->nama_kelas ?? '-' }}
                </span>
                <span class="badge bg-success">
                    Mapel: {{ $materi->mapel->nama_mapel ?? '-' }}
                </span>
            </div>

            <p class="text-muted">{{ $materi->deskripsi }}</p>

            @if($materi->file_url)
                <div class="mt-4">
                    <a href="{{ asset('storage/'.$materi->file_url) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf me-2"></i>Lihat / Download File
                    </a>
                </div>
            @endif

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('guru.materi.edit', $materi) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>

                <form action="{{ route('guru.materi.destroy', $materi) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger" onclick="return confirm('Yakin hapus?')">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>

                <a href="{{ route('guru.materi') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>
</div>
@endsection