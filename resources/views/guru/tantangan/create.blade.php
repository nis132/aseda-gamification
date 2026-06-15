@extends('layouts.app')
@section('title', 'Buat Tantangan Baru')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Buat Tantangan Baru</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Rancang tugas berbasis gamifikasi untuk siswa.
        </p>
    </div>
    <a href="{{ route('guru.tantangan.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">

        @if($errors->any())
        <div class="card mb-3" style="border-left: 4px solid var(--clr-danger); background: #fee2e2;">
            <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
                <i class="fas fa-exclamation-circle" style="color: var(--clr-danger);"></i>
                <span style="font-size: 0.85rem; color: #991b1b; font-weight: 600;">
                    Gagal membuat tantangan. Periksa kembali inputan Anda.
                </span>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header card-header-gradient">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-trophy" style="color: #fbbf24;"></i>
                    <span class="fw-bold" style="font-size: 0.9rem;">Detail Utama Tantangan</span>
                </div>
            </div>

            <form method="POST" action="{{ route('guru.tantangan.store') }}">
                @csrf
                <div class="card-body p-4">

                    {{-- JUDUL --}}
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Tantangan / Tugas <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <input type="text" name="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               placeholder="Contoh: Misi Penjelajah Aljabar"
                               value="{{ old('judul') }}">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div style="font-size: 0.76rem; color: var(--txt-tertiary); margin-top: 4px;">
                                Gunakan nama yang menarik untuk meningkatkan antusiasme siswa.
                            </div>
                        @enderror
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="mb-3">
                        <label class="form-label">
                            Instruksi Misi <span style="color: var(--clr-danger);">*</span>
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Jelaskan apa yang harus dikerjakan siswa dalam tantangan ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">

                        {{-- KELAS & MAPEL --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Target Kelas & Mapel <span style="color: var(--clr-danger);">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--bg-muted); border-color: var(--border-color); color: var(--txt-tertiary);">
                                    <i class="fas fa-users"></i>
                                </span>
                                <select name="guru_mapel_kelas_id"
                                        class="form-select @error('guru_mapel_kelas_id') is-invalid @enderror"
                                        required>
                                    <option value="">Pilih Mapel & Kelas</option>
                                    @foreach($relasi as $r)
                                        @if($r->kelas)
                                            <option value="{{ $r->id }}"
                                                {{ old('guru_mapel_kelas_id') == $r->id ? 'selected' : '' }}>
                                                {{ $r->mapel->nama_mapel }} - {{ $r->kelas->nama_kelas }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('guru_mapel_kelas_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- POIN --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Reward Poin (XP) <span style="color: var(--clr-danger);">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: #fef3c7; border-color: var(--border-color); color: var(--clr-warning);">
                                    <i class="fas fa-star"></i>
                                </span>
                                <input type="number" name="poin"
                                       class="form-control @error('poin') is-invalid @enderror"
                                       placeholder="Misal: 100"
                                       value="{{ old('poin') }}">
                                @error('poin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- BAB (8 BAB SYSTEM) --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                BAB / CHAPTER <span style="color:var(--clr-danger);">*</span>
                            </label>
                            <p class="small mb-2" style="color:var(--txt-secondary);">
                                <i class="fas fa-info-circle me-1"></i>
                                Tentukan tantangan ini masuk ke BAB mana dari 8 BAB. Setiap bab memiliki 3 task (soal).
                            </p>
                            <div class="row g-2 mb-2">
                                @php
                                    $babOptions = [];
                                    for ($i = 1; $i <= 8; $i++) {
                                        $babOptions[] = 'BAB ' . $i;
                                    }
                                @endphp
                                @foreach($babOptions as $babVal)
                                <div class="col-6 col-md-3">
                                    <label class="w-100" style="cursor:pointer;">
                                        <input type="radio" name="bab" value="{{ $babVal }}"
                                               {{ old('bab') == $babVal ? 'checked' : '' }}
                                               class="d-none bab-radio">
                                        <div class="bab-card p-2 text-center rounded-3 border fw-bold"
                                             style="border-color:var(--border-color) !important;
                                                    font-size:0.80rem; color:var(--txt-primary);
                                                    transition:all 0.2s; background:#fff;">
                                            <i class="fas fa-book-open me-1" style="font-size:0.70rem;"></i>
                                            {{ $babVal }}
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            {{-- Input manual --}}
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:0.78rem;color:var(--txt-secondary);">Atau ketik manual:</span>
                                <input type="text" id="bab_manual"
                                       class="form-control form-control-sm"
                                       placeholder="Contoh: BAB 9"
                                       style="max-width:160px;">
                            </div>
                            @error('bab')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DIFFICULTY / CHAPTER LEVEL --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                Level Kesulitan (Chapter) <span style="color: var(--clr-danger);">*</span>
                            </label>
                            <p class="small mb-2" style="color:var(--txt-secondary);">
                                <i class="fas fa-lightbulb me-1"></i>
                                Pilih chapter sesuai tingkat kesulitan. Siswa harus mencapai level tersebut untuk mengerjakan.
                            </p>
                            <div class="row g-2">
                                @php
                                    $difficulties = [
                                        'chapter_1'  => ['label'=>'Bab 1 - Pengantar', 'sub'=>'Level 1', 'color'=>'#d1fae5','text'=>'#065f46'],
                                        'chapter_2'  => ['label'=>'Bab 2 - Dasar', 'sub'=>'Level 2', 'color'=>'#dcfce7','text'=>'#166534'],
                                        'chapter_3'  => ['label'=>'Bab 3 - Pengembangan', 'sub'=>'Level 3', 'color'=>'#dbeafe','text'=>'#0c2d48'],
                                        'chapter_4'  => ['label'=>'Bab 4 - Pendalaman', 'sub'=>'Level 4', 'color'=>'#e0e7ff','text'=>'#3730a3'],
                                        'chapter_5'  => ['label'=>'Bab 5 - Integrasi', 'sub'=>'Level 5', 'color'=>'#ede9fe','text'=>'#5b21b6'],
                                        'chapter_6'  => ['label'=>'Bab 6 - Aplikasi', 'sub'=>'Level 6', 'color'=>'#fce7f3','text'=>'#9d174d'],
                                        'chapter_7'  => ['label'=>'Bab 7 - Analisis', 'sub'=>'Level 7', 'color'=>'#fee2e2','text'=>'#7c2d12'],
                                        'chapter_8'  => ['label'=>'Bab 8 - Mastery', 'sub'=>'Level 8', 'color'=>'#fef3c7','text'=>'#92400e'],
                                    ];
                                @endphp
                                @foreach($difficulties as $val => $cfg)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <label class="difficulty-option w-100" style="cursor:pointer;">
                                        <input type="radio" name="difficulty" value="{{ $val }}"
                                               {{ old('difficulty', 'chapter_1') === $val ? 'checked' : '' }}
                                               class="d-none difficulty-radio">
                                        <div class="difficulty-card p-2 text-center rounded-3 border"
                                             style="border-color: var(--border-color) !important; transition: all 0.2s;">
                                            <div class="fw-bold" style="font-size:0.80rem; color:var(--txt-primary);">
                                                {{ $cfg['label'] }}
                                            </div>
                                            <div class="badge mt-1" style="background:{{ $cfg['color'] }}; color:{{ $cfg['text'] }}; font-size:0.65rem;">
                                                {{ $cfg['sub'] }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('difficulty')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BATAS WAKTU --}}
                        <div class="col-12">
                            <label class="form-label">
                                Batas Waktu Misi <span style="color: var(--clr-danger);">*</span>
                            </label>
                            <div class="p-3 rounded-2"
                                 style="border: 2px dashed var(--border-color); background: var(--bg-muted);">
                                <div class="row align-items-center g-2">
                                    <div class="col-md-4 d-flex align-items-center gap-2">
                                        <i class="fas fa-hourglass-half" style="color: var(--clr-primary);"></i>
                                        <span class="fw-bold" style="font-size: 0.85rem; color: var(--txt-primary);">Deadline Misi</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="datetime-local" name="batas_waktu"
                                               class="form-control @error('batas_waktu') is-invalid @enderror"
                                               value="{{ old('batas_waktu') }}">
                                        @error('batas_waktu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0 px-4 pb-4">
                    <div class="d-flex justify-content-between align-items-center pt-3"
                         style="border-top: 1px solid var(--border-color);">
                        <a href="{{ route('guru.tantangan.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            Lanjut: Tambah Soal <i class="fas fa-chevron-right ms-2" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card mt-3 p-3" style="background: var(--clr-primary-light); border-color: transparent;">
            <div class="d-flex gap-3 align-items-start">
                <i class="fas fa-info-circle mt-1" style="color: var(--clr-primary);"></i>
                <div>
                    <div class="fw-bold" style="font-size: 0.83rem; color: var(--clr-primary);">Langkah Berikutnya</div>
                    <div style="font-size: 0.8rem; color: var(--txt-secondary);">
                        Setelah menyimpan detail ini, Anda akan diarahkan ke halaman pembuatan soal.
                        Semakin banyak soal, semakin beragam penilaian dan nilai tidak otomatis 100.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.bab-radio:checked + .bab-card {
    border-color: var(--clr-primary) !important;
    background: var(--clr-primary-light) !important;
    color: var(--clr-primary) !important;
    box-shadow: 0 0 0 2px var(--clr-primary);
}
.bab-card:hover { border-color: var(--clr-primary) !important; }
.difficulty-radio:checked + .difficulty-card {
    border-color: var(--clr-primary) !important;
    background: var(--clr-primary-light);
    box-shadow: 0 0 0 2px var(--clr-primary);
}
.difficulty-card:hover {
    border-color: var(--clr-primary) !important;
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
// Input manual BAB → override radio
const babManual = document.getElementById('bab_manual');
babManual.addEventListener('input', function () {
    if (this.value.trim()) {
        document.querySelectorAll('.bab-radio').forEach(r => r.checked = false);
        let hidden = document.getElementById('bab_hidden');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.id   = 'bab_hidden';
            hidden.name = 'bab';
            this.closest('form').appendChild(hidden);
        }
        hidden.value = this.value.trim();
    }
});

// Klik radio → kosongkan manual
document.querySelectorAll('.bab-radio').forEach(r => {
    r.addEventListener('change', function () {
        babManual.value = '';
        const h = document.getElementById('bab_hidden');
        if (h) h.remove();
    });
});
</script>
@endpush