@extends('layouts.app')

@section('title', 'Detail Tantangan: ' . $tantangan->judul)

@section('content')

@php
    $publishAktif = $tantangan->publishKelas
        ->where('status', 'published');

    $isPublished = $publishAktif->count() > 0;
    $isOwner      = $tantangan->guru_id === auth()->id();
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">

    <div>
        <h1 class="page-title">{{ $tantangan->judul }}</h1>

        <p class="mb-0"
           style="color: var(--txt-secondary); font-size: 0.85rem;">
            {{ Str::limit($tantangan->deskripsi, 100) }}
        </p>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        <a href="{{ route('guru.tantangan.index') }}"
           class="btn btn-light">

            <i class="fas fa-arrow-left me-2"></i>
            Kembali

        </a>

        @php
            // Kelas yang diampu guru login untuk mapel ini
            $kelasDiampu = \App\Models\GuruMapelKelas::with('kelas')
                ->where('guru_id', auth()->id())
                ->where('mapel_id', $tantangan->mapel_id)
                ->get()
                ->unique('kelas_id');

            // Kelas yang sudah dipublish (semua guru)
            $kelasPublished = $publishAktif->pluck('kelas_id')->toArray();

            // Kelas yang diampu guru ini tapi BELUM dipublish
            $kelasBelumPublish = $kelasDiampu->filter(
                fn($gmk) => !in_array($gmk->kelas_id, $kelasPublished)
            );
        @endphp

        {{-- TOMBOL PUBLISH — muncul kalau masih ada kelas diampu yang belum dipublish --}}
        @if($kelasBelumPublish->count() > 0)
            <button type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#publishModal"
                    {{ $tantangan->soal->count() == 0 ? 'disabled' : '' }}>
                <i class="fas fa-paper-plane me-2"></i>
                Publish ke Kelas
            </button>
        @endif

        {{-- TOMBOL UNPUBLISH — hanya muncul untuk kelas yang guru ini ampu --}}
        @foreach($publishAktif as $publish)
            @php
                $bolehUnpublish = $kelasDiampu->contains('kelas_id', $publish->kelas_id);
            @endphp
            @if($bolehUnpublish)
                <button type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#unpublishModal{{ $publish->kelas_id }}">
                    <i class="fas fa-undo me-2"></i>
                    Unpublish {{ $publish->kelas->nama_kelas }}
                </button>
            @endif
        @endforeach

    </div>

</div>

{{-- WARNING --}}
@if($tantangan->soal->count() == 0)

<div class="mb-3 px-3 py-2 rounded-2 d-flex align-items-center gap-2"
     style="background:#fef3c7; color:#92400e; font-size:0.8rem;">

    <i class="fas fa-exclamation-triangle"></i>

    Tambahkan minimal satu soal sebelum publish.

</div>

@endif

{{-- INFO --}}
<div class="card mb-4">

    <div class="card-body py-3 px-4">

        <div class="d-flex flex-wrap gap-4">

            {{-- MAPEL --}}
            <div class="d-flex align-items-center gap-3">

                <div class="stat-icon stat-icon-primary"
                     style="width:36px; height:36px;">

                    <i class="fas fa-book"></i>

                </div>

                <div>

                    <div class="text-label">
                        Mata Pelajaran
                    </div>

                    <div class="fw-bold">
                        {{ $tantangan->mapel->nama_mapel }}
                    </div>

                </div>

            </div>

            {{-- KELAS --}}
            <div class="d-flex align-items-center gap-3"
                 style="border-left:1px solid var(--border-color); padding-left:1.5rem;">

                <div class="stat-icon stat-icon-info"
                     style="width:36px; height:36px;">

                    <i class="fas fa-users"></i>

                </div>

                <div>

                    <div class="text-label">
                        Dipublish Ke
                    </div>

                    @if($isPublished)

                        @foreach($publishAktif as $publish)

                            <div class="fw-bold">
                                {{ $publish->kelas->nama_kelas }}
                            </div>

                        @endforeach

                    @else

                        <span class="text-muted">
                            Belum dipublish
                        </span>

                    @endif

                </div>

            </div>

            {{-- STATUS --}}
            <div class="ms-auto">

                @if($isPublished)

                    <span class="badge"
                          style="background:#d1fae5; color:var(--clr-success);">

                        Published

                    </span>

                @else

                    <span class="badge"
                          style="background:#fef3c7; color:var(--clr-warning);">

                        Draft

                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

{{-- STAT --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">

        <div class="card card-stat p-3 text-center">

            <div class="stat-icon stat-icon-primary mx-auto mb-2">
                <i class="fas fa-tasks"></i>
            </div>

            <div class="stat-number">
                {{ $tantangan->soal->count() }}
            </div>

            <div class="text-label">
                Total Soal
            </div>

        </div>

    </div>

    <div class="col-6 col-md-3">

        <div class="card card-stat p-3 text-center">

            <div class="stat-icon stat-icon-info mx-auto mb-2">
                <i class="fas fa-calendar-alt"></i>
            </div>

            {{-- Jadi ini: --}}
            <div class="fw-bold">
                {{ $tantangan->batas_waktu ? $tantangan->batas_waktu->format('d M Y') : 'Tanpa Batas Waktu' }}
            </div>

            @if($tantangan->batas_waktu)
            <div class="text-label">
                {{ $tantangan->batas_waktu->format('H:i') }} WIB
            </div>
            @endif

        </div>

    </div>

    <div class="col-6 col-md-3">

        <div class="card card-stat p-3 text-center">

            <div class="stat-icon stat-icon-warning mx-auto mb-2">
                <i class="fas fa-trophy"></i>
            </div>

            <div class="stat-number">
                {{ $tantangan->poin }}
            </div>

            <div class="text-label">
                XP Reward
            </div>

        </div>

    </div>

    <div class="col-6 col-md-3">

        <div class="card card-stat p-3 text-center">

            <div class="stat-icon stat-icon-success mx-auto mb-2">
                <i class="fas fa-user-check"></i>
            </div>

            <div class="stat-number">
                {{ $siswaCount ?? 0 }}
            </div>

            <div class="text-label">
                Pengerja
            </div>

        </div>

    </div>

</div>

{{-- HEADER SOAL --}}
<div class="d-flex align-items-center justify-content-between mb-3">

    <h5 class="fw-bold mb-0">
        Preview Pertanyaan
    </h5>

    <div class="d-flex gap-2">

        <a href="{{ route('guru.nilai.index', $tantangan->id) }}"
           class="btn btn-light">

            <i class="fas fa-check-double me-2"></i>
            Penilaian

        </a>

        {{-- TAMBAH SOAL NONAKTIF SAAT PUBLISH --}}
        @if(!$isPublished && $isOwner)
            <a href="{{ route('guru.soal.create', $tantangan) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Tambah Soal
            </a>
        @endif

    </div>

</div>

{{-- LIST SOAL --}}
@if($tantangan->soal->count() == 0)

<div class="card">

    <div class="empty-state py-5 text-center">

        <div class="empty-state-icon mb-3">
            <i class="fas fa-question-circle fa-2x"></i>
        </div>

        <h6>Belum Ada Soal</h6>

        <p class="text-muted">
            Tambahkan soal terlebih dahulu.
        </p>

    </div>

</div>

@else

<div class="row g-3">

    @foreach($tantangan->soal as $index => $soal)

    <div class="col-lg-6">

        <div class="card h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <span class="badge"
                          style="background:var(--clr-primary-light); color:var(--clr-primary);">

                        #{{ $index + 1 }}
                        — {{ strtoupper($soal->tipe) }}

                    </span>

                    <div class="dropdown">

                        <button class="btn btn-light btn-action"
                                data-bs-toggle="dropdown">

                            <i class="fas fa-ellipsis-h"></i>

                        </button>
<ul class="dropdown-menu dropdown-menu-end">

    {{-- EDIT SOAL --}}
    @if($isPublished || !$isOwner)
        <li>
            <span class="dropdown-item text-muted">
                <i class="fas fa-lock me-2"></i>
                Edit Nonaktif
            </span>
        </li>
    @else
        <li>
            <a class="dropdown-item"
               href="{{ route('guru.soal.edit', [$tantangan, $soal]) }}">
                <i class="fas fa-pencil-alt me-2"></i>
                Edit Soal
            </a>
        </li>
    @endif

    <li><hr class="dropdown-divider"></li>

    {{-- HAPUS SOAL --}}
    @if($isPublished || !$isOwner)
        <li>
            <span class="dropdown-item text-muted">
                <i class="fas fa-lock me-2"></i>
                Hapus Nonaktif
            </span>
        </li>
    @else
        <li>
            <button type="button"
                    class="dropdown-item text-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#hapusSoalModal{{ $soal->id }}">
                <i class="fas fa-trash me-2"></i>
                Hapus Soal
            </button>
        </li>
    @endif

</ul>

                    </div>

                </div>

                <p class="fw-bold mb-3">
                    {{ $soal->pertanyaan }}
                </p>

            </div>

        </div>

    </div>

    @endforeach

</div>

@endif

{{-- SECTION PENGAYAAN --}}
@php $pengayaan = $tantangan->pengayaan; @endphp

<div class="d-flex align-items-center justify-content-between mb-3 mt-4">

    <div>
        <h5 class="fw-bold mb-0">Pengayaan Remedial</h5>
        <p class="mb-0" style="color:var(--txt-secondary); font-size:0.82rem;">
            Dikerjakan siswa yang melewatkan deadline tantangan ini.
        </p>
    </div>

    @if(!$pengayaan && $isOwner)
        <a href="{{ route('guru.tantangan.pengayaan.create', $tantangan) }}"
           class="btn btn-warning">
            <i class="fas fa-plus me-2"></i>
            Buat Pengayaan
        </a>
    @endif

</div>

@if($pengayaan)

    <div class="card mb-4">
        <div class="card-body p-4">

            <div class="d-flex align-items-start justify-content-between gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="stat-icon stat-icon-warning" style="width:40px; height:40px; flex-shrink:0;">
                        <i class="fas fa-redo"></i>
                    </div>

                    <div>
                        <div class="fw-bold">{{ $pengayaan->judul }}</div>
                        <div style="font-size:0.82rem; color:var(--txt-secondary);">
                            Deadline: {{ $pengayaan->batas_waktu->format('d M Y, H:i') }} WIB
                            &nbsp;·&nbsp;
                            {{ $pengayaan->poin }} XP
                            &nbsp;·&nbsp;
                            {{ $pengayaan->soal->count() }} soal
                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                    @if($pengayaan->status === 'published')
                        <span class="badge" style="background:#d1fae5; color:var(--clr-success);">Published</span>
                    @else
                        <span class="badge" style="background:#fef3c7; color:var(--clr-warning);">Draft</span>
                    @endif

                    <a href="{{ route('guru.tantangan.show', $pengayaan) }}"
                       class="btn btn-light btn-sm">
                        <i class="fas fa-eye me-1"></i>
                        Detail
                    </a>

                </div>

            </div>

        </div>
    </div>

@else

    <div class="card mb-4">
        <div class="empty-state py-4 text-center">
            <div class="empty-state-icon mb-2">
                <i class="fas fa-redo fa-2x" style="color:var(--txt-tertiary);"></i>
            </div>
            <p class="text-muted mb-0" style="font-size:0.85rem;">
                Belum ada pengayaan. Siswa yang melewatkan deadline tidak akan punya jalur remedial.
            </p>
        </div>
    </div>

@endif

{{-- MODAL PUBLISH --}}
<x-modal id="publishModal"
         title="Publish Tantangan"
         type="primary"
         icon="fa-paper-plane">

    <form action="{{ url('guru/tantangan/' . $tantangan->id . '/publish') }}"
          method="POST"
          id="publishForm">

        @csrf

        <div class="mb-3">

            <label class="form-label">
                Pilih Kelas
            </label>

            <select name="kelas_id"
                    class="form-select"
                    required>

                <option value="">-- Pilih Kelas --</option>

                @foreach($kelasBelumPublish as $gmk)
                    <option value="{{ $gmk->kelas_id }}">
                        {{ $gmk->kelas->nama_kelas }}
                    </option>
                @endforeach

            </select>

        </div>

    </form>

    <x-slot:footer>

        <button type="button"
                class="btn btn-light"
                data-bs-dismiss="modal">

            Batal

        </button>

        <button type="button"
                onclick="document.getElementById('publishForm').submit()"
                class="btn btn-primary">

            <i class="fas fa-paper-plane me-2"></i>
            Publish

        </button>

    </x-slot:footer>

</x-modal>

{{-- MODAL UNPUBLISH --}}
@foreach($publishAktif as $publish)

<x-modal id="unpublishModal{{ $publish->kelas_id }}"
         title="Batalkan Publish"
         type="danger"
         icon="fa-undo">

    <form action="{{ route('guru.tantangan.unpublish', [$tantangan->id, $publish->kelas_id]) }}"
          method="POST"
          id="unpublishForm{{ $publish->kelas_id }}">

        @csrf

        <div class="text-center">

            <p>

                Publish untuk kelas

                <strong>
                    {{ $publish->kelas->nama_kelas }}
                </strong>

                akan dibatalkan.

            </p>

        </div>

    </form>

    <x-slot:footer>

        <button type="button"
                class="btn btn-light"
                data-bs-dismiss="modal">

            Batal

        </button>

        <button type="button"
                class="btn btn-danger"
                onclick="document.getElementById('unpublishForm{{ $publish->kelas_id }}').submit()">

            <i class="fas fa-undo me-2"></i>
            Ya, Unpublish

        </button>

    </x-slot:footer>

</x-modal>

@endforeach
{{-- MODAL HAPUS SOAL --}}
@foreach($tantangan->soal as $soal)
<x-modal id="hapusSoalModal{{ $soal->id }}"
         title="Hapus Soal"
         type="danger"
         icon="fa-trash">

    <form action="{{ route('guru.soal.destroy', [$tantangan, $soal]) }}"
          method="POST"
          id="hapusSoalForm{{ $soal->id }}">
        @csrf
        @method('DELETE')

        <div class="text-center">
            <p>
                Yakin ingin menghapus soal
                <strong>#{{ $loop->iteration }}</strong>?
            </p>
            <p class="text-muted" style="font-size:0.85rem;">
                "{{ Str::limit($soal->pertanyaan, 80) }}"
            </p>
            <p class="text-danger" style="font-size:0.8rem;">
                Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

    </form>

    <x-slot:footer>
        <button type="button"
                class="btn btn-light"
                data-bs-dismiss="modal">
            Batal
        </button>
        <button type="button"
                class="btn btn-danger"
                onclick="document.getElementById('hapusSoalForm{{ $soal->id }}').submit()">
            <i class="fas fa-trash me-2"></i>
            Ya, Hapus
        </button>
    </x-slot:footer>

</x-modal>
@endforeach
@endsection