@extends('layouts.app')
@section('title', 'Review Jawaban - ' . $tantangan->judul)

@section('content')

{{-- HEADER NAVIGASI --}}
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('siswa.tantangan') }}"
           class="btn btn-light d-flex align-items-center justify-content-center"
           style="width:38px; height:38px; padding:0; border-radius: var(--border-radius-sm);">
            <i class="fas fa-arrow-left" style="font-size:0.85rem;"></i>
        </a>
        <div>
            <h4 class="page-title mb-0">Review Jawaban</h4>
            <p class="small mb-0" style="color: var(--txt-secondary);">{{ $tantangan->judul }}</p>
        </div>
    </div>
</div>

{{-- LOCK MESSAGE --}}
<div class="card border-0 shadow-sm" style="max-width: 500px; margin: 60px auto;">
    <div class="card-body p-5 text-center">
        
        {{-- ICON --}}
        <div style="font-size: 4rem; color: var(--clr-primary); margin-bottom: 1.5rem;">
            <i class="fas fa-lock"></i>
        </div>

        {{-- JUDUL --}}
        <h4 class="fw-bold mb-2" style="color: var(--txt-primary);">
            Review Belum Dibuka
        </h4>

        {{-- DESKRIPSI --}}
        <p style="color: var(--txt-secondary); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
            Guru belum membuka akses review hasil jawaban untuk tantangan ini. 
            Tunggu guru membuka review, kemudian Anda dapat melihat jawaban dan kunci jawaban.
        </p>

        {{-- INFO TAMBAHAN --}}
        <div class="p-3 rounded-3" style="background: var(--bg-muted); border-left: 4px solid var(--clr-info); margin-bottom: 1.5rem;">
            <small style="color: var(--txt-secondary);">
                <i class="fas fa-info-circle me-2" style="color: var(--clr-info);"></i>
                Pengecekan status review diperbarui secara real-time
            </small>
        </div>

        {{-- TOMBOL --}}
        <div class="d-flex gap-2">
            <a href="{{ route('siswa.tantangan') }}" class="btn btn-light flex-grow-1">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button type="button" class="btn btn-primary flex-grow-1" onclick="location.reload();">
                <i class="fas fa-redo me-2"></i>Refresh
            </button>
        </div>

    </div>
</div>

@endsection
