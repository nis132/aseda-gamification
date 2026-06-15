@extends('layouts.app')

@section('title', 'Koreksi Jawaban - ' . $siswa->nama)

@section('content')

{{-- TOP NAV BAR (navigasi antar siswa) --}}
<div class="card mb-4" style="background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%); border: none;">
    <div class="card-body py-3 d-flex justify-content-between align-items-center gap-3">

        <a href="{{ route('guru.nilai.index', $tantangan->id) }}"
           class="btn btn-light btn-action">
            <i class="fas fa-th-large me-2"></i>Semua Siswa
        </a>

        <div class="d-flex align-items-center gap-3">
            @if(isset($prevId))
                <a href="{{ route('guru.nilai.detail', [$tantangan->id, $prevId]) }}"
                   style="color: rgba(255,255,255,0.75); transition: color var(--transition);"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">
                    <i class="fas fa-chevron-left fa-lg"></i>
                </a>
            @endif

            <div class="text-center">
                <div class="fw-bold" style="color: #fff; font-size: 1rem;">{{ $siswa->nama }}</div>
                <small style="color: rgba(255,255,255,0.65);">
                    Siswa {{ $nomorUrut ?? '?' }} dari {{ $totalSiswa ?? '?' }}
                </small>
            </div>

            @if(isset($nextId))
                <a href="{{ route('guru.nilai.detail', [$tantangan->id, $nextId]) }}"
                   style="color: rgba(255,255,255,0.75); transition: color var(--transition);"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">
                    <i class="fas fa-chevron-right fa-lg"></i>
                </a>
            @endif
        </div>

        <div class="d-none d-md-block">
            <span class="badge"
                  style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.75rem; padding: 0.4em 0.9em;">
                {{ Str::limit($tantangan->judul, 24) }}
            </span>
        </div>

    </div>
</div>

@if($errors->any())
    <div class="card mb-4" style="border-left: 4px solid var(--clr-danger);">
        <div class="card-body py-3">
            <ul class="mb-0" style="font-size: 0.85rem; color: var(--clr-danger);">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- SECTION STATUS REVIEW --}}
@php
    $nilaiTantangan = \App\Models\NilaiTantangan::where('siswa_id', $siswa->id)
        ->where('tantangan_id', $tantangan->id)->first();
    $reviewDibuka = $nilaiTantangan && $nilaiTantangan->review_dibuka_pada;
@endphp

<div class="card mb-4" style="border-left: 4px solid {{ $reviewDibuka ? 'var(--clr-success)' : 'var(--clr-warning)' }}; background: {{ $reviewDibuka ? '#f0fdf4' : '#fefce8' }};">
    <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 class="mb-0 fw-bold" style="color: {{ $reviewDibuka ? 'var(--clr-success)' : 'var(--clr-warning)' }};">
                <i class="fas {{ $reviewDibuka ? 'fa-unlock-alt' : 'fa-lock' }} me-2"></i>
                Status Review: <span>{{ $reviewDibuka ? 'Dibuka' : 'Tertutup' }}</span>
            </h6>
            @if($reviewDibuka)
                <small style="color: var(--txt-secondary);">
                    Siswa dapat melihat review jawaban
                    @if($nilaiTantangan->review_dibuka_pada)
                        sejak {{ $nilaiTantangan->review_dibuka_pada->format('d M Y H:i') }}
                    @endif
                </small>
            @else
                <small style="color: var(--txt-secondary);">
                    Siswa belum dapat melihat review jawaban
                </small>
            @endif
        </div>
        <div class="d-flex gap-2">
            @if($reviewDibuka)
                <form method="POST" action="{{ route('guru.review.tutup', $tantangan->id) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-lock me-1"></i>Tutup Review
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('guru.review.buka', $tantangan->id) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-unlock-alt me-1"></i>Buka Review
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<form method="POST" action="{{ route('guru.nilai.simpan', [$tantangan->id, $siswa->id]) }}">
@csrf

<div class="row g-4">

    {{-- KONTEN SOAL --}}
    <div class="col-lg-8">

        @foreach($jawaban as $j)
        @php
            $isEssay    = $j->soal->tipe == 'essay';
            $isPg       = $j->soal->tipe == 'pg';
            $isMatching = $j->soal->tipe == 'matching';
        @endphp

        <div class="card mb-4">

            {{-- HEADER SOAL --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-label">Soal #{{ $loop->iteration }}</span>
                @if($isPg)
                    <span class="badge" style="background: #d1fae5; color: var(--clr-success); padding: 0.35em 0.75em;">
                        <i class="fas fa-check-circle me-1"></i>Pilihan Ganda
                    </span>
                @elseif($isMatching)
                    <span class="badge" style="background: #dbeafe; color: var(--clr-info); padding: 0.35em 0.75em;">
                        <i class="fas fa-random me-1"></i>Matching
                    </span>
                @else
                    <span class="badge" style="background: #fef3c7; color: #92400e; padding: 0.35em 0.75em;">
                        <i class="fas fa-pen me-1"></i>Essay
                    </span>
                @endif
            </div>

            <div class="card-body p-4">

                {{-- PERTANYAAN --}}
                <div class="mb-4">
                    <div class="text-label mb-2">Soal</div>
                    <div class="p-3 rounded-3" style="background: var(--bg-muted); border: 1px solid var(--border-color); font-size: 0.875rem;">
                        {!! $j->soal->pertanyaan !!}
                    </div>
                </div>

                {{-- JAWABAN SISWA --}}
                <div class="mb-4">
                    <div class="text-label mb-2">Jawaban Siswa</div>
                    <div class="p-3 rounded-3" style="border: 1px solid var(--border-color); font-size: 0.875rem;">
                        @if($isMatching)
                            @php $jawabanSiswa = json_decode($j->jawaban, true); @endphp
                            @if(is_array($jawabanSiswa))
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-0" style="font-size: 0.82rem;">
                                        <thead>
                                            <tr><th>Kiri</th><th>Jawaban Siswa</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jawabanSiswa as $kiri => $kanan)
                                            <tr><td>{{ $kiri }}</td><td>{{ $kanan }}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                {{ $j->jawaban }}
                            @endif
                        @else
                            {{ $j->jawaban ?? '-' }}
                        @endif
                    </div>
                </div>

                {{-- KUNCI JAWABAN --}}
                <div class="mb-4">
                    <div class="text-label mb-2">Kunci Jawaban</div>
                    <div class="p-3 rounded-3"
                         style="background: #d1fae5; border: 1px solid #6ee7b7; font-size: 0.875rem;">
                        @if($isMatching)
                            @php $kunci = json_decode($j->soal->jawaban_benar, true); @endphp
                            @if(is_array($kunci))
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-0" style="font-size: 0.82rem;">
                                        <thead>
                                            <tr><th>Kiri</th><th>Kunci (Kanan)</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kunci as $pair)
                                            <tr>
                                                <td>{{ $pair['kiri'] ?? '-' }}</td>
                                                <td>{{ $pair['kanan'] ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                {{ $j->soal->jawaban_benar }}
                            @endif
                        @else
                            {{ $j->soal->jawaban_benar ?? '-' }}
                        @endif
                    </div>
                </div>

                {{-- NILAI --}}
                <div>
                    <div class="text-label mb-2">Nilai</div>

                    @if($isEssay)
                        <div class="input-group" style="max-width: 220px;">
                            <input type="number"
                                   name="nilai_{{ $j->id }}"
                                   value="{{ old('nilai_'.$j->id, $j->nilai) }}"
                                   class="form-control nilai-input @error('nilai_'.$j->id) is-invalid @enderror"
                                   min="0" max="100" required
                                   style="font-size: 1rem; font-weight: 600;">
                            <span class="input-group-text fw-bold"
                                  style="background: var(--clr-primary); color: #fff; border: none;">
                                Poin
                            </span>
                        </div>
                        @error('nilai_'.$j->id)
                            <div style="font-size: 0.8rem; color: var(--clr-danger); margin-top: 0.4rem;">{{ $message }}</div>
                        @enderror
                    @else
                        <div class="d-flex align-items-center gap-3">
                            <span class="stat-number"
                                  style="font-size: 2rem; color: {{ $j->nilai >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)' }};">
                                {{ $j->nilai }}
                            </span>
                            <span class="badge px-3 py-2" style="background: #d1fae5; color: var(--clr-success);">
                                <i class="fas fa-robot me-1"></i>Dinilai Otomatis
                            </span>
                        </div>
                        <input type="hidden" class="nilai-input" value="{{ $j->nilai }}">
                    @endif
                </div>

            </div>
        </div>
        @endforeach

    </div>

    {{-- SIDEBAR RINGKASAN --}}
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-body p-4 text-center">

                <div class="text-label mb-3">Ringkasan Nilai</div>

                <div class="stat-number mb-0" style="font-size: 2.5rem; color: var(--clr-primary);">
                    <span id="totalNilai">0</span>
                    <small style="font-size: 1rem; font-weight: 400; color: var(--txt-secondary);">/ 100</small>
                </div>
                <p style="font-size: 0.8rem; color: var(--txt-secondary); margin-top: 0.25rem; margin-bottom: 0;">
                    Rata-rata seluruh soal
                </p>

                @php
                    $essayCount = $jawaban->filter(fn($j) => $j->soal->tipe == 'essay')->count();
                @endphp

                @if($essayCount > 0)
                <hr style="border-color: var(--border-color);">

                {{-- BULK NILAI --}}
                <div class="mb-3">
                    <label class="form-label text-start d-block">Isi semua essay sekaligus</label>
                    <input type="number" id="bulkNilai" class="form-control mb-2"
                           placeholder="Masukkan nilai..." min="0" max="100">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary w-100" onclick="applyBulk()">Apply</button>
                        <button type="button" class="btn btn-light w-100" onclick="isiSemua(100)">100</button>
                        <button type="button" class="btn btn-light w-100" onclick="isiSemua(0)">0</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold" style="padding: 0.65rem;">
                    <i class="fas fa-save me-2"></i>Simpan Nilai Essay
                </button>

                @else

                <hr style="border-color: var(--border-color);">
                <div class="d-flex align-items-center justify-content-center gap-2 py-2"
                     style="background: #d1fae5; border-radius: var(--border-radius-sm); padding: 0.75rem !important;">
                    <i class="fas fa-check-circle" style="color: var(--clr-success);"></i>
                    <span style="font-size: 0.85rem; color: var(--clr-success); font-weight: 600;">
                        Semua soal dinilai otomatis
                    </span>
                </div>

                @endif

            </div>
        </div>
    </div>

</div>

</form>

@push('scripts')
<script>
document.querySelectorAll('.nilai-input').forEach(input => {
    input.addEventListener('input', hitung);
});

function hitung() {
    let total = 0, count = 0;
    document.querySelectorAll('.nilai-input').forEach(el => {
        let val = parseFloat(el.value);
        if (!isNaN(val)) { total += val; count++; }
    });
    document.getElementById('totalNilai').innerText =
        count > 0 ? (total / count).toFixed(1) : 0;
}

function applyBulk() {
    const val = document.getElementById('bulkNilai').value;
    if (val === '') return;
    document.querySelectorAll('input[type="number"].nilai-input').forEach(i => i.value = val);
    hitung();
}

function isiSemua(nilai) {
    document.querySelectorAll('input[type="number"].nilai-input').forEach(i => i.value = nilai);
    hitung();
}

window.addEventListener('load', hitung);
</script>
@endpush

@endsection