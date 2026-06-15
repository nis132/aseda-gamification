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
    <div class="d-none d-md-block">
        <span class="badge"
              style="background: var(--clr-primary-light); color: var(--clr-primary);
                     font-size: 0.78rem; padding: 0.45em 0.9em; border-radius: var(--border-radius-sm);">
            Total Soal: {{ $tantangan->soal->count() }}
        </span>
    </div>
</div>

{{-- LOOP SOAL --}}
@foreach($tantangan->soal as $i => $soal)
    @php
        $jawab       = $jawaban->firstWhere('soal_id', $soal->id);
        $isCorrect   = false;
        if ($soal->tipe === 'pg') {
            $isCorrect = ($jawab->jawaban ?? null) === $soal->jawaban_benar;
        }
    @endphp

    <div class="card mb-4 overflow-hidden">

        {{-- Status strip --}}
        @if($soal->tipe === 'pg')
            <div class="status-bar {{ $isCorrect ? 'bg-success' : 'bg-danger' }}"></div>
        @else
            <div class="status-bar" style="background: var(--clr-info);"></div>
        @endif

        <div class="card-body p-4">

            {{-- META --}}
            <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                <span class="fw-bold" style="color: var(--clr-primary);">Pertanyaan {{ $i + 1 }}</span>
                @if($soal->tipe === 'pg')
                    @if($isCorrect)
                        <span class="small fw-bold text-success">
                            <i class="fas fa-check-circle me-1"></i>Benar
                        </span>
                    @else
                        <span class="small fw-bold text-danger">
                            <i class="fas fa-times-circle me-1"></i>Salah
                        </span>
                    @endif
                @else
                    <span class="small fw-bold" style="color: var(--clr-info);">
                        <i class="fas fa-pen-fancy me-1"></i>Perlu Penilaian Guru
                    </span>
                @endif
            </div>

            {{-- TEKS PERTANYAAN --}}
            <h6 class="lh-base mb-4" style="color: var(--txt-primary); font-weight: 600;">
                {{ $soal->pertanyaan }}
            </h6>

            {{-- ===== PILIHAN GANDA ===== --}}
            @if($soal->tipe === 'pg')
                <div class="row g-2">
                    @foreach(['A','B','C','D'] as $opsi)
                        @php
                            $field        = 'opsi_' . strtolower($opsi);
                            $isCorrectKey = $soal->jawaban_benar === $opsi;
                            $isUserPick   = ($jawab->jawaban ?? null) === $opsi;

                            if ($isCorrectKey) {
                                $boxClass    = 'review-opt-correct';
                                $badgeStyle  = 'background: var(--clr-success); color: #fff;';
                            } elseif ($isUserPick) {
                                $boxClass    = 'review-opt-wrong';
                                $badgeStyle  = 'background: var(--clr-danger); color: #fff;';
                            } else {
                                $boxClass    = 'review-opt-neutral';
                                $badgeStyle  = 'background: var(--bg-muted); border: 1px solid var(--border-color); color: var(--txt-secondary);';
                            }
                        @endphp
                        <div class="col-12">
                            <div class="p-3 border rounded-3 d-flex align-items-center {{ $boxClass }}">
                                <div class="option-badge me-3" style="{{ $badgeStyle }}">{{ $opsi }}</div>
                                <div class="flex-grow-1" style="font-size: 0.875rem;">{{ $soal->$field }}</div>
                                @if($isCorrectKey)
                                    <i class="fas fa-check-circle ms-2 text-success"></i>
                                @elseif($isUserPick)
                                    <i class="fas fa-times-circle ms-2 text-danger"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            {{-- ===== ESSAY ===== --}}
            @elseif($soal->tipe === 'essay')
                <div class="rounded-3 p-4" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                    <div class="mb-3">
                        <label class="text-label mb-2 d-block">Jawaban Kamu</label>
                        <div class="p-3 rounded-3"
                             style="background: var(--bg-card); border: 1px solid var(--border-color);
                                    color: var(--txt-primary); font-size: 0.875rem;">
                            {{ $jawab->jawaban ?? 'Tidak menjawab' }}
                        </div>
                    </div>
                    <div class="p-3 rounded-3"
                         style="border-left: 4px solid var(--clr-success); background: var(--bg-card);">
                        <label class="text-label mb-2 d-block" style="color: var(--clr-success);">
                            Kunci Jawaban / Kata Kunci
                        </label>
                        <span style="color: var(--txt-primary); font-size: 0.875rem;">
                            {{ $soal->jawaban_benar }}
                        </span>
                    </div>
                </div>

            {{-- ===== MATCHING ===== --}}
            @elseif($soal->tipe === 'matching')
                <div class="rounded-3 p-4" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                    <div class="text-label mb-3">Kunci Pemasangan yang Benar</div>
                    @php $pairs = json_decode($soal->jawaban_benar, true); @endphp
                    <div class="row g-2">
                        @foreach($pairs as $pair)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3"
                                 style="background: var(--bg-card); border: 1px solid var(--border-color);">
                                <span class="badge me-2"
                                      style="background: var(--clr-primary-light); color: var(--clr-primary);">
                                    {{ $pair['kiri'] }}
                                </span>
                                <i class="fas fa-long-arrow-alt-right mx-2" style="color: var(--txt-tertiary);"></i>
                                <span class="fw-bold" style="color: var(--txt-primary); font-size: 0.875rem;">
                                    {{ $pair['kanan'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endforeach

{{-- ACTION BAWAH --}}
<div class="text-center mt-4 mb-2">
    <a href="{{ route('siswa.tantangan') }}" class="btn btn-primary px-5 rounded-pill fw-bold">
        <i class="fas fa-check me-2"></i>Selesai Review
    </a>
</div>

@endsection

@push('styles')
<style>
.status-bar {
    height: 4px;
    width: 100%;
}

/* Opsi review */
.review-opt-correct {
    background: #dcfce7;
    border-color: var(--clr-success) !important;
    color: #166534;
}

.review-opt-wrong {
    background: #fee2e2;
    border-color: var(--clr-danger) !important;
    color: #991b1b;
}

.review-opt-neutral {
    background: var(--bg-muted);
    border-color: var(--border-color) !important;
    color: var(--txt-secondary);
}

.option-badge {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius-sm);
    font-size: 0.82rem;
    font-weight: 700;
    flex-shrink: 0;
}
</style>
@endpush