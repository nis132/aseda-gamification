@extends('layouts.app')
@section('title', 'Edit Materi')

@php /** @var \App\Models\Materi $materi */ @endphp

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Materi</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Memperbarui: <strong>{{ $materi->judul }}</strong>
        </p>
    </div>
    <a href="{{ route('guru.materi') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-gradient">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span class="fw-bold" style="font-size: 0.9rem;">Ubah Detail Materi</span>
                </div>
            </div>

            <form action="{{ route('guru.materi.update', $materi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body p-4">

                    <div class="mb-3">
                        <label class="form-label">Judul Materi</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--clr-primary);">
                                <i class="fas fa-heading"></i>
                            </span>
                            <input type="text" name="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $materi->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran & Kelas</label>

                        @php
                            $currentRelasiId = old('guru_mapel_kelas_id',
                                $relasi->first(fn($r) =>
                                    $r->mapel_id == $materi->mapel_id &&
                                    $r->kelas_id == $materi->kelas_id
                                )?->id
                            );
                        @endphp

                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <select name="guru_mapel_kelas_id"
                                    class="form-select @error('guru_mapel_kelas_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Pilih Mata Pelajaran & Kelas --</option>
                                @foreach($relasi as $r)
                                    <option value="{{ $r->id }}"
                                        {{ $currentRelasiId == $r->id ? 'selected' : '' }}>
                                        {{ $r->mapel->nama_mapel ?? '-' }} — {{ $r->kelas->nama_kelas ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_mapel_kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($relasi->isEmpty())
                            <div class="mt-2 px-3 py-2 rounded-2"
                                 style="background: #fef3c7; font-size: 0.78rem; color: #92400e;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Anda belum memiliki relasi mengajar. Hubungi admin.
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Ringkasan</label>
                        <textarea name="deskripsi" rows="5"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  required>{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
{{-- VIDEO URL --}}
<div class="mb-4">
    <label class="form-label d-flex align-items-center gap-2">
        <i class="fab fa-youtube text-danger"></i> Link Video YouTube
        <span class="badge fw-normal"
              style="background: var(--bg-muted); color: var(--txt-tertiary); font-size: 0.68rem;">
            Opsional
        </span>
    </label>

    <input type="url"
           name="video_url"
           class="form-control @error('video_url') is-invalid @enderror"
           placeholder="https://www.youtube.com/watch?v=..."
           value="{{ old('video_url', $materi->video_url) }}">

    @error('video_url')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        <div style="font-size: 0.75rem; color: var(--txt-tertiary); margin-top: 4px;">
            Tempel link YouTube — siswa bisa tonton langsung di halaman materi.
        </div>
    @enderror
</div>

{{-- LINK REFERENSI --}}
<div class="mb-3">
    <label class="form-label d-flex align-items-center gap-2">
        <i class="fas fa-link" style="color: var(--clr-primary);"></i> Link Referensi
        <span class="badge fw-normal"
              style="background: var(--bg-muted); color: var(--txt-tertiary); font-size: 0.68rem;">
            Opsional
        </span>
    </label>

    <input type="url"
           name="link_referensi"
           class="form-control @error('link_referensi') is-invalid @enderror"
           placeholder="https://..."
           value="{{ old('link_referensi', $materi->link_referensi) }}">

    @error('link_referensi')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        <div style="font-size: 0.75rem; color: var(--txt-tertiary); margin-top: 4px;">
            Link ke sumber belajar tambahan (artikel, e-book, dll).
        </div>
    @enderror
</div>

                    {{-- FILE MANAGEMENT --}}
                    <div class="mb-1">
                        <label class="form-label">File Materi</label>

                        @if($materi->file_url)
                        <div class="d-flex align-items-center gap-3 p-3 rounded-2 mb-3"
                             style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                            <div class="icon-shape stat-icon-danger" style="width: 40px; height: 40px;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
                                            letter-spacing: 0.06em; color: var(--txt-tertiary);">
                                    File Saat Ini
                                </div>
                                <div class="text-truncate fw-bold" style="font-size: 0.83rem; color: var(--txt-primary);">
                                    {{ basename($materi->file_url) }}
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $materi->file_url) }}" target="_blank"
                               class="btn btn-action btn-light flex-shrink-0">
                                <i class="fas fa-external-link-alt" style="color: var(--clr-info);"></i>
                            </a>
                        </div>
                        @endif

                        <div class="position-relative rounded-2 text-center p-4"
                             style="border: 2px dashed var(--border-color); background: var(--bg-muted);
                                    transition: border-color var(--transition), background var(--transition);"
                             id="uploadZone">
                            <input type="file" name="file_materi"
                                   accept=".pdf,.doc,.docx"
                                   style="position: absolute; inset: 0; opacity: 0; cursor: pointer;"
                                   id="fileInput">
                            <div>
                                <div class="stat-icon stat-icon-primary mx-auto mb-2"
                                     style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="fw-bold" style="font-size: 0.85rem; color: var(--txt-primary);">
                                    {{ $materi->file_url ? 'Ganti File Materi' : 'Klik atau Seret Berkas ke Sini' }}
                                </div>
                                <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                    Format: PDF, DOC, DOCX — Maksimal 5MB
                                </div>
                            </div>
                        </div>

                        <div id="fileChosen" class="mt-2 d-none"
                             style="font-size: 0.78rem; font-weight: 600; color: var(--clr-primary);">
                            <i class="fas fa-file-signature me-1"></i>File baru siap diunggah: <span id="fileName"></span>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0 px-4 pb-4">

                    {{-- BAB --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Bab <span style="color:var(--clr-danger);">*</span>
                        </label>
                        <p class="small mb-2" style="color:var(--txt-secondary);">
                            Pilih bab materi ini. Siswa harus menyelesaikan bab sebelumnya untuk mengakses.
                        </p>
                        <div class="row g-2">
                            @php
                                $babs = [
                                    1 => ['label'=>'Bab 1','sub'=>'Pengantar','color'=>'#d1fae5','text'=>'#065f46'],
                                    2 => ['label'=>'Bab 2','sub'=>'Dasar','color'=>'#dcfce7','text'=>'#166534'],
                                    3 => ['label'=>'Bab 3','sub'=>'Pengembangan','color'=>'#dbeafe','text'=>'#0c2d48'],
                                    4 => ['label'=>'Bab 4','sub'=>'Pendalaman','color'=>'#e0e7ff','text'=>'#3730a3'],
                                    5 => ['label'=>'Bab 5','sub'=>'Integrasi','color'=>'#ede9fe','text'=>'#5b21b6'],
                                    6 => ['label'=>'Bab 6','sub'=>'Aplikasi','color'=>'#fce7f3','text'=>'#9d174d'],
                                    7 => ['label'=>'Bab 7','sub'=>'Analisis','color'=>'#fee2e2','text'=>'#7c2d12'],
                                    8 => ['label'=>'Bab 8','sub'=>'Mastery','color'=>'#fef3c7','text'=>'#92400e'],
                                ];
                                $currentBab = old('bab', $materi->bab ?? $materi->level_required ?? 1);
                            @endphp
                            @foreach($babs as $val => $cfg)
                            <div class="col-6 col-md-3">
                                <label class="w-100" style="cursor:pointer;">
                                    <input type="radio" name="bab" value="{{ $val }}"
                                           {{ $currentBab == $val ? 'checked' : '' }}
                                           class="d-none level-radio">
                                    <div class="level-card p-2 text-center rounded-3 border"
                                         style="border-color:var(--border-color) !important; transition:all 0.2s;">
                                        <div class="fw-bold" style="font-size:0.78rem; color:var(--txt-primary);">
                                            {{ $cfg['label'] }}
                                        </div>
                                        <div class="badge mt-1"
                                             style="background:{{ $cfg['color'] }};color:{{ $cfg['text'] }};font-size:0.62rem;">
                                            {{ $cfg['sub'] }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('bab')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3"
                         style="border-top: 1px solid var(--border-color);">
                        <a href="{{ route('guru.materi') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const fileInput  = document.getElementById('fileInput');
const fileChosen = document.getElementById('fileChosen');
const fileName   = document.getElementById('fileName');
const uploadZone = document.getElementById('uploadZone');

fileInput.addEventListener('change', function () {
    if (this.files.length > 0) {
        fileChosen.classList.remove('d-none');
        fileName.textContent = this.files[0].name;
        uploadZone.style.borderColor = 'var(--clr-primary)';
        uploadZone.style.background  = 'var(--clr-primary-light)';
    } else {
        fileChosen.classList.add('d-none');
        uploadZone.style.borderColor = 'var(--border-color)';
        uploadZone.style.background  = 'var(--bg-muted)';
    }
});
</script>
@endpush