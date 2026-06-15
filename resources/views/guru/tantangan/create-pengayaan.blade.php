@extends('layouts.app')
@section('title', 'Buat Pengayaan: ' . $tantangan->judul)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Buat Pengayaan</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Remedial untuk siswa yang melewatkan deadline
            <strong>{{ $tantangan->judul }}</strong>.
        </p>
    </div>
    <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">

    {{-- INFO: diwarisi dari tantangan induk --}}
    <div class="card mb-3" style="border-left: 4px solid var(--clr-info); background: var(--clr-info-light, #eff6ff);">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-start gap-3">
                <i class="fas fa-info-circle mt-1" style="color: var(--clr-info);"></i>
                <div style="font-size: 0.84rem; color: #1e40af;">
                    Mapel, kelas, bab, dan tingkat kesulitan <strong>diwarisi otomatis</strong>
                    dari tantangan asli. Kamu hanya perlu mengisi judul, instruksi,
                    deadline baru, dan poin pengayaan.
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="card mb-3" style="border-left: 4px solid var(--clr-danger); background: #fee2e2;">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
            <i class="fas fa-exclamation-circle" style="color: var(--clr-danger);"></i>
            <span style="font-size: 0.85rem; color: #991b1b; font-weight: 600;">
                Periksa kembali inputan di bawah.
            </span>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header card-header-gradient">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-redo" style="color: #fbbf24;"></i>
                <span class="fw-bold" style="font-size: 0.9rem;">Detail Pengayaan</span>
            </div>
        </div>

        <form method="POST" action="{{ route('guru.tantangan.pengayaan.store', $tantangan) }}">
            @csrf
            <div class="card-body p-4">

                {{-- JUDUL --}}
                <div class="mb-3">
                    <label class="form-label">
                        Judul Pengayaan <span style="color: var(--clr-danger);">*</span>
                    </label>
                    <input type="text" name="judul"
                           class="form-control @error('judul') is-invalid @enderror"
                           placeholder="Contoh: Pengayaan {{ $tantangan->judul }}"
                           value="{{ old('judul', 'Pengayaan — ' . $tantangan->judul) }}">
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-3">
                    <label class="form-label">
                        Instruksi Pengayaan <span style="color: var(--clr-danger);">*</span>
                    </label>
                    <textarea name="deskripsi" rows="4"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              placeholder="Jelaskan kepada siswa apa yang harus dikerjakan dalam pengayaan ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                            Sampaikan bahwa ini adalah kesempatan mengejar ketertinggalan.
                        </div>
                    @enderror
                </div>

                <div class="row g-3">

                    {{-- BATAS WAKTU --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Batas Waktu Pengayaan <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <input type="datetime-local" name="batas_waktu"
                               class="form-control @error('batas_waktu') is-invalid @enderror"
                               value="{{ old('batas_waktu') }}">
                        @error('batas_waktu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Deadline asli: {{ $tantangan->batas_waktu->format('d M Y, H:i') }}
                            </div>
                        @enderror
                    </div>

                    {{-- POIN --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Poin Pengayaan <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="poin" min="1" max="1000"
                                   class="form-control @error('poin') is-invalid @enderror"
                                   placeholder="Contoh: {{ round($tantangan->poin * 0.7) }}"
                                   value="{{ old('poin', round($tantangan->poin * 0.7)) }}">
                            <span class="input-group-text">XP</span>
                        </div>
                        @error('poin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Poin asli: {{ $tantangan->poin }} XP. Disarankan 70% dari poin asli.
                            </div>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- INFO WARISAN --}}
            <div class="card-body border-top px-4 py-3">
                <p class="mb-2" style="font-size: 0.8rem; color: var(--txt-secondary); font-weight: 600;">
                    Diwarisi dari tantangan asli:
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge" style="background: var(--clr-primary-light); color: var(--clr-primary);">
                        <i class="fas fa-book me-1"></i>{{ $tantangan->mapel->nama_mapel }}
                    </span>
                    <span class="badge" style="background: var(--clr-info-light, #eff6ff); color: #1e40af;">
                        <i class="fas fa-users me-1"></i>{{ $tantangan->kelas->nama_kelas ?? 'Kelas' }}
                    </span>
                    <span class="badge" style="background: #f3f4f6; color: #374151;">
                        <i class="fas fa-layer-group me-1"></i>BAB {{ $tantangan->bab }}
                    </span>
                    @php $diff = \App\Models\Tantangan::difficultyConfig()[$tantangan->difficulty] ?? null; @endphp
                    @if($diff)
                    <span class="badge" style="background: {{ $diff['color'] }}; color: {{ $diff['text'] }};">
                        <i class="fas {{ $diff['icon'] }} me-1"></i>{{ $diff['label'] }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2 p-4">
                <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-light">
                    Batal
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-redo me-2"></i>
                    Buat Pengayaan & Tambah Soal
                </button>
            </div>

        </form>
    </div>

</div>
</div>

@endsection