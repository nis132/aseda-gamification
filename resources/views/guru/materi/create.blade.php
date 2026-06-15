@extends('layouts.app')
@section('title', 'Tambah Materi Baru')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Materi Baru</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Buat bahan ajar baru untuk siswa.
        </p>
    </div>
    <a href="{{ route('guru.materi') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-12">

        @if($errors->any())
        <div class="card mb-3" style="border-left: 4px solid var(--clr-danger); background: #fee2e2;">
            <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
                <i class="fas fa-exclamation-circle" style="color: var(--clr-danger);"></i>
                <span style="font-size: 0.85rem; color: #991b1b; font-weight: 600;">
                    Gagal menyimpan materi. Silakan periksa kembali formulir.
                </span>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header card-header-gradient">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span class="fw-bold" style="font-size: 0.9rem;">Formulir Materi Pembelajaran</span>
                </div>
            </div>

            <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">
                            Judul Materi <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--clr-primary);">
                                <i class="fas fa-heading"></i>
                            </span>
                            <input type="text" name="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}"
                                   placeholder="Contoh: Pengenalan Fotosintesis pada Tumbuhan">
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @unless($errors->has('judul'))
                            <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Buatlah judul yang spesifik dan menarik bagi siswa.
                            </div>
                        @endunless
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Kelas & Mata Pelajaran <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <select name="guru_mapel_kelas_id"
                                    class="form-select @error('guru_mapel_kelas_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih Kelas & Mata Pelajaran...</option>
                                @foreach($relasi as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('guru_mapel_kelas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->mapel->nama_mapel }} — {{ $item->kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_mapel_kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @unless($errors->has('guru_mapel_kelas_id'))
                            <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Pilih kombinasi kelas dan mata pelajaran yang Anda ajar.
                            </div>
                        @endunless
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Ringkasan Materi <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <textarea name="deskripsi" rows="5"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Tuliskan poin-poin utama atau ringkasan materi di sini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2 p-3 rounded-3"
     style="background: var(--bg-muted); border:1px solid var(--border-color);">

    <div class="d-flex align-items-center gap-2 mb-2">
        <i class="fab fa-markdown" style="color: var(--clr-primary);"></i>
        <span class="fw-bold" style="font-size:0.8rem;">
            Materi Mendukung Markdown
        </span>
    </div>

    <div style="font-size:0.75rem; color:var(--txt-secondary); line-height:1.7;">
        <div><code># Judul</code> → Judul besar</div>
        <div><code>## Subjudul</code> → Subjudul</div>
        <div><code>**tebal**</code> → <strong>tebal</strong></div>
        <div><code>*miring*</code> → <em>miring</em></div>
        <div><code>- poin</code> → List poin</div>
        <div><code>[Google](https://google.com)</code> → Link</div>
    </div>
</div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label d-flex align-items-center gap-2">
                            Dokumen Pendukung
                            <span class="badge fw-normal"
                                  style="background: var(--bg-muted); color: var(--txt-tertiary); font-size: 0.68rem;">
                                Opsional
                            </span>
                        </label>

                        {{-- Upload Zone --}}
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
                                     style="width: 52px; height: 52px; font-size: 1.2rem;">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="fw-bold" style="font-size: 0.88rem; color: var(--txt-primary);">
                                    Klik atau Seret Berkas ke Sini
                                </div>
                                <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                    Format: PDF, DOC, DOCX — Maksimal 5MB
                                </div>
                            </div>
                        </div>

                        @error('file_materi')
                            <div style="font-size: 0.78rem; color: var(--clr-danger); margin-top: 4px; font-weight: 600;">
                                {{ $message }}
                            </div>
                        @enderror

                        <div id="fileChosen" class="mt-2 d-none"
                             style="font-size: 0.78rem; font-weight: 600; color: var(--clr-primary);">
                            <i class="fas fa-file-check me-1"></i>File terpilih: <span id="fileName"></span>
                        </div>
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
                        <input type="url" name="video_url"
                               class="form-control @error('video_url') is-invalid @enderror"
                               placeholder="https://www.youtube.com/watch?v=..."
                               value="{{ old('video_url') }}">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div style="font-size: 0.75rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Tempel link YouTube — siswa bisa tonton langsung di halaman materi.
                            </div>
                        @enderror
                    </div>

                    {{-- LINK REFERENSI --}}
                    <div class="mb-2">
                        <label class="form-label d-flex align-items-center gap-2">
                            <i class="fas fa-link" style="color: var(--clr-primary);"></i> Link Referensi
                            <span class="badge fw-normal"
                                  style="background: var(--bg-muted); color: var(--txt-tertiary); font-size: 0.68rem;">
                                Opsional
                            </span>
                        </label>
                        <input type="url" name="link_referensi"
                               class="form-control @error('link_referensi') is-invalid @enderror"
                               placeholder="https://..."
                               value="{{ old('link_referensi') }}">
                        @error('link_referensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div style="font-size: 0.75rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Link ke sumber belajar tambahan (artikel, e-book, dll).
                            </div>
                        @enderror
                    </div>
                </div>
                {{-- BAB --}}
<div class="col-12 mt-2">
    <label class="form-label fw-bold">
        Bab <span style="color:var(--clr-danger);">*</span>
    </label>
    <p class="small mb-2" style="color:var(--txt-secondary);">
        Pilih bab materi ini. Siswa harus menyelesaikan bab sebelumnya untuk mengakses.
    </p>
    <div class="row g-2">
        @php
            $babs = [
                1 => ['label'=>'Bab 1','sub'=>'Pengantar','color'=>'#d1fae5','text'=>'#065f46','icon'=>'fa-book'],
                2 => ['label'=>'Bab 2','sub'=>'Dasar','color'=>'#dcfce7','text'=>'#166534','icon'=>'fa-book'],
                3 => ['label'=>'Bab 3','sub'=>'Pengembangan','color'=>'#dbeafe','text'=>'#0c2d48','icon'=>'fa-book'],
                4 => ['label'=>'Bab 4','sub'=>'Pendalaman','color'=>'#e0e7ff','text'=>'#3730a3','icon'=>'fa-book'],
                5 => ['label'=>'Bab 5','sub'=>'Integrasi','color'=>'#ede9fe','text'=>'#5b21b6','icon'=>'fa-book'],
                6 => ['label'=>'Bab 6','sub'=>'Aplikasi','color'=>'#fce7f3','text'=>'#9d174d','icon'=>'fa-book'],
                7 => ['label'=>'Bab 7','sub'=>'Analisis','color'=>'#fee2e2','text'=>'#7c2d12','icon'=>'fa-book'],
                8 => ['label'=>'Bab 8','sub'=>'Mastery','color'=>'#fef3c7','text'=>'#92400e','icon'=>'fa-crown'],
            ];
        @endphp
        @foreach($babs as $val => $cfg)
        <div class="col-6 col-md-3 col-xl">
            <label class="w-100" style="cursor:pointer;">
                <input type="radio" name="bab" value="{{ $val }}"
                       {{ old('bab', '1') == $val ? 'checked' : '' }}
                       class="d-none level-radio">
                <div class="level-card p-3 text-center rounded-3 border"
                     style="border-color:var(--border-color) !important; transition:all 0.2s;">
                    <div class="mb-1" style="font-size:1.1rem; color:{{ $cfg['text'] }};">
                        <i class="fas {{ $cfg['icon'] }}"></i>
                    </div>
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

                <div class="card-body pt-0 px-4 pb-4">
                    <div class="d-flex justify-content-between align-items-center pt-3"
                         style="border-top: 1px solid var(--border-color);">
                        <button type="reset" class="btn btn-light">Reset Formulir</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-rocket me-2"></i>Terbitkan Materi
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
const fileInput   = document.getElementById('fileInput');
const fileChosen  = document.getElementById('fileChosen');
const fileName    = document.getElementById('fileName');
const uploadZone  = document.getElementById('uploadZone');

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
<style>
.level-radio:checked + .level-card {
    border-color: var(--clr-primary) !important;
    background: var(--clr-primary-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(59,130,246,0.15);
}

.level-radio:checked + .level-card .fw-bold {
    color: var(--clr-primary);
}

.level-card:hover {
    border-color: var(--clr-primary) !important;
    transform: translateY(-2px);
}
</style>
@endpush