@extends('layouts.app')
@section('title', 'Edit Mapel: ' . $mapel->nama_mapel)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Mata Pelajaran</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Memperbarui kurikulum <strong>{{ $mapel->nama_mapel }}</strong>
        </p>
    </div>
    <a href="{{ route('admin.mapel.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- ERROR SUMMARY --}}
        @if($errors->any())
        <div class="alert alert-danger d-flex gap-3 align-items-start mb-4 rounded-3" role="alert">
            <i class="fas fa-exclamation-circle mt-1" style="font-size: 1.1rem; flex-shrink:0;"></i>
            <div>
                <div class="fw-bold mb-1">Terdapat kesalahan pada formulir:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li style="font-size: 0.875rem;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="card overflow-hidden">
            <div class="row g-0">

                {{-- PANEL KIRI --}}
                <div class="col-md-4 d-flex align-items-start justify-content-center p-4 text-white"
                     style="background: var(--sidebar-bg);">
                    <div class="w-100">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                 style="width: 72px; height: 72px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Mode Ubah</h5>
                            <p class="small mb-0" style="color: rgba(255,255,255,0.65);">
                                {{ $mapel->nama_mapel }}
                            </p>
                        </div>

                        <div style="border-top: 1px solid rgba(255,255,255,0.12); padding-top: 1rem;">
                            <p class="mb-2"
                               style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em;
                                      text-transform: uppercase; color: rgba(255,255,255,0.4);">
                                Relasi Saat Ini
                            </p>

                            @forelse($mapel->guruMapelKelas as $gmk)
                                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.65); margin-bottom: 4px;">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $gmk->guru->nama ?? '-' }}
                                    <span style="opacity:.4; margin: 0 4px;">→</span>
                                    <i class="fas fa-door-open me-1"></i>
                                    {{ $gmk->kelas->nama_kelas ?? '-' }}
                                </div>
                            @empty
                                <p style="font-size: 0.78rem; color: rgba(255,255,255,0.35);">
                                    Belum ada relasi.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- PANEL KANAN --}}
                <div class="col-md-8">
                    <div class="p-4 p-md-5">

                        <div class="mb-4">
                            <h5 class="fw-bold mb-1" style="color: var(--txt-primary);">Informasi Mapel</h5>
                            <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.83rem;">
                                Perbarui nama mapel serta relasi guru dan kelas.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('admin.mapel.update', $mapel) }}" id="formMapel">
                            @csrf
                            @method('PUT')

                            {{-- Nama Mapel --}}
                            <div class="mb-4">
                                <label class="form-label">
                                    Nama Mata Pelajaran <span style="color: var(--clr-danger);">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="background: var(--bg-muted); border-color: var(--border-color); color: var(--clr-primary);">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <input type="text"
                                           name="nama_mapel"
                                           class="form-control @error('nama_mapel') is-invalid @enderror"
                                           value="{{ old('nama_mapel', $mapel->nama_mapel) }}"
                                           required>
                                    @error('nama_mapel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Relasi Guru & Kelas — checkbox accordion --}}
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Pengaturan Guru & Kelas</span>
                                    <span class="badge fw-normal"
                                          style="background: var(--clr-primary-light); color: var(--clr-primary); font-size: 0.68rem;">
                                        Multi Relasi
                                    </span>
                                </label>

                                @if($guru->isEmpty())
                                    <div class="rounded-3 p-3 text-center"
                                         style="background: var(--bg-muted); color: var(--txt-secondary); font-size: 0.85rem;">
                                        <i class="fas fa-user-slash me-2"></i>Belum ada data guru.
                                    </div>
                                @else
                                    <div class="border rounded-3 overflow-hidden">
                                        @foreach($guru as $index => $g)

                                        @php
                                            $selectedKelas = old(
                                                'pairs.' . $g->id,
                                                $mapel->guruMapelKelas
                                                    ->where('guru_id', $g->id)
                                                    ->pluck('kelas_id')
                                                    ->toArray()
                                            );
                                            $jumlahDipilih = count($selectedKelas);
                                        @endphp

                                        <div class="guru-row {{ $index > 0 ? 'border-top' : '' }}"
                                             style="{{ $index % 2 === 0 ? 'background: var(--bg-muted);' : '' }}">

                                            {{-- Header guru --}}
                                            <button type="button"
                                                    class="btn w-100 d-flex align-items-center justify-content-between px-3 py-3 guru-toggle"
                                                    data-target="guru-{{ $g->id }}"
                                                    style="background: transparent; border: none; text-align: left;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                                         style="width: 30px; height: 30px; background: var(--clr-primary-light);
                                                                color: var(--clr-primary); font-size: 0.75rem;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--txt-primary);">
                                                        {{ $g->nama }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="kelas-counter badge"
                                                          id="counter-{{ $g->id }}"
                                                          style="background: {{ $jumlahDipilih > 0 ? 'var(--clr-primary)' : 'var(--clr-primary-light)' }};
                                                                 color: {{ $jumlahDipilih > 0 ? '#fff' : 'var(--clr-primary)' }};
                                                                 font-size: 0.68rem; font-weight: 600;">
                                                        {{ $jumlahDipilih }} kelas
                                                    </span>
                                                    <i class="fas fa-chevron-down toggle-icon"
                                                       style="font-size: 0.75rem; color: var(--txt-tertiary);
                                                              transition: transform 0.2s;
                                                              {{ $jumlahDipilih > 0 ? 'transform: rotate(180deg);' : '' }}"></i>
                                                </div>
                                            </button>

                                            {{-- Daftar kelas (checkbox) — terbuka otomatis jika sudah ada pilihan --}}
                                            <div class="kelas-panel px-3 pb-3"
                                                 id="guru-{{ $g->id }}"
                                                 style="display: {{ $jumlahDipilih > 0 ? 'block' : 'none' }};">
                                                <div class="row g-2">
                                                    @foreach($kelas as $k)
                                                    <div class="col-6 col-md-4">
                                                        <label class="kelas-checkbox-label d-flex align-items-center gap-2 px-2 py-2 rounded-2"
                                                               style="cursor: pointer; border: 1px solid var(--border-color);
                                                                      font-size: 0.8rem; transition: all 0.15s;
                                                                      {{ in_array($k->id, $selectedKelas) ? 'background: var(--clr-primary-light); border-color: var(--clr-primary);' : '' }}">
                                                            <input type="checkbox"
                                                                   name="pairs[{{ $g->id }}][]"
                                                                   value="{{ $k->id }}"
                                                                   class="kelas-cb"
                                                                   data-guru="{{ $g->id }}"
                                                                   style="width: 14px; height: 14px; flex-shrink: 0;"
                                                                   {{ in_array($k->id, $selectedKelas) ? 'checked' : '' }}>
                                                            <span style="color: {{ in_array($k->id, $selectedKelas) ? 'var(--clr-primary)' : 'var(--txt-primary)' }};
                                                                         font-weight: {{ in_array($k->id, $selectedKelas) ? '600' : '400' }};
                                                                         line-height: 1.2;">
                                                                {{ $k->nama_kelas }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                @if($kelas->isEmpty())
                                                    <p style="font-size: 0.8rem; color: var(--txt-tertiary);" class="mb-0 mt-1">
                                                        <i class="fas fa-info-circle me-1"></i>Belum ada kelas tersedia.
                                                    </p>
                                                @endif
                                            </div>

                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-2" style="font-size: 0.75rem; color: var(--txt-tertiary);">
                                        <i class="fas fa-info-circle me-1" style="color: var(--clr-info);"></i>
                                        Klik nama guru untuk memilih kelas. Guru tanpa kelas dipilih tidak akan disimpan.
                                    </div>
                                @endif
                            </div>

                            {{-- Peringatan replace relasi --}}
                            <div class="px-3 py-2 rounded-2 mb-4 d-flex align-items-start gap-2"
                                 style="background: #fef3c7; font-size: 0.78rem; color: #92400e;">
                                <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
                                <span>Menyimpan akan mengganti seluruh relasi guru-kelas sebelumnya.</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2"
                                 style="border-top: 1px solid var(--border-color);">
                                <a href="{{ route('admin.mapel.index') }}" class="btn btn-light">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.kelas-checkbox-label:hover {
    background: var(--clr-primary-light);
    border-color: var(--clr-primary) !important;
}
.kelas-checkbox-label:has(input:checked) {
    background: var(--clr-primary-light);
    border-color: var(--clr-primary) !important;
}
.kelas-checkbox-label:has(input:checked) span {
    color: var(--clr-primary);
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.guru-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
        const panel = document.getElementById(btn.dataset.target);
        const icon  = btn.querySelector('.toggle-icon');
        const open  = panel.style.display === 'none';
        panel.style.display = open ? 'block' : 'none';
        icon.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
    });
});

document.querySelectorAll('.kelas-cb').forEach(cb => {
    cb.addEventListener('change', () => {
        const guruId  = cb.dataset.guru;
        const checked = document.querySelectorAll(`.kelas-cb[data-guru="${guruId}"]:checked`).length;
        const counter = document.getElementById(`counter-${guruId}`);
        counter.textContent = checked + ' kelas';
        counter.style.background = checked > 0 ? 'var(--clr-primary)' : 'var(--clr-primary-light)';
        counter.style.color      = checked > 0 ? '#fff'               : 'var(--clr-primary)';
    });
});
</script>
@endpush