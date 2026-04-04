@extends('layouts.app')
@section('title','Kerjakan Tantangan - ' . $tantangan->judul)

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    body {
        background-color: #f3f4f6;
    }

    .challenge-card {
        border-radius: 24px;
        overflow: hidden;
        border: none;
    }

    .challenge-header {
        background: var(--primary-gradient);
        padding: 3rem 2rem;
    }

    .question-item {
        background: var(--glass-bg);
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .question-item:hover {
        border-color: #a5b4fc;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    /* Radio Button Customization */
    .option-label {
        display: block;
        padding: 1rem 1.25rem;
        border: 2px solid #f3f4f6;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .option-label:hover {
        background-color: #f9fafb;
        border-color: #e5e7eb;
    }

    input[type="radio"]:checked + .option-text {
        color: #4f46e5;
        font-weight: 600;
    }

    input[type="radio"]:checked ~ .option-label, 
    .option-label:has(input[type="radio"]:checked) {
        border-color: #6366f1;
        background-color: #eef2ff;
    }

    /* Matching Interaction */
    .matching-left-item {
        cursor: grab;
        transition: transform 0.2s;
        border: 2px solid transparent;
    }

    .matching-left-item:active { cursor: grabbing; }
    
    .matching-right-item {
        transition: all 0.3s ease;
        border: 2px dashed #d1d5db;
    }

    .matching-right-item.drag-over {
        border-color: #6366f1;
        background-color: #eef2ff;
        transform: scale(1.02);
    }

    .matching-right-item.connected {
        border-style: solid;
        border-color: #10b981;
        background-color: #ecfdf5;
        color: #065f46;
    }

    .progress {
        background-color: #e5e7eb;
        border-radius: 100px;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(90deg, #6366f1, #10b981);
    }

    /* Custom Scrollbar for Textarea */
    textarea.form-control {
        border-radius: 12px;
        border: 2px solid #f3f4f6;
        padding: 1rem;
    }
    
    textarea.form-control:focus {
        border-color: #6366f1;
        box-shadow: none;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card challenge-card shadow-xl">
                <!-- Header Section -->
                <div class="challenge-header text-white text-center">
                    <p class="text-uppercase tracking-widest small fw-bold opacity-75 mb-2">Tantangan Belajar</p>
                    <h1 class="display-5 fw-bold mb-4">{{ $tantangan->judul }}</h1>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <span class="badge rounded-pill bg-white text-primary px-3 py-2">
                            <i class="fas fa-book-open me-2"></i>{{ $tantangan->mapel->nama_mapel }}
                        </span>
                        <span class="badge rounded-pill bg-white text-dark px-3 py-2 opacity-90">
                            <i class="fas fa-graduation-cap me-2"></i>{{ $tantangan->kelas->nama_kelas }}
                        </span>
                        <span class="badge rounded-pill bg-indigo-200 text-indigo-800 px-3 py-2" style="background: rgba(255,255,255,0.2)">
                            <i class="fas fa-list-ol me-2"></i>{{ $soals->count() }} Soal
                        </span>
                    </div>
                </div>

                <form method="POST" id="formTantangan" action="{{ route('siswa.tantangan.submit', $tantangan) }}">
                    @csrf
                    <div class="card-body p-4 p-md-5">
                        @foreach($soals as $index => $soal)
                            <div class="question-item mb-5 p-4 p-md-5">
                                <div class="d-flex align-items-center mb-4">
                                    <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h4 class="fw-bold mb-0 text-dark">{{ $soal->pertanyaan }}</h4>
                                </div>

                                {{-- ============================= --}}
                                {{-- PILIHAN GANDA --}}
                                {{-- ============================= --}}
                                @if($soal->tipe === 'pg')
                                    <div class="options-container mt-4">
                                        @foreach(['a','b','c','d'] as $opsi)
                                            @php $field = "opsi_$opsi"; @endphp
                                            @if($soal->$field)
                                                <label class="option-label">
                                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ strtoupper($opsi) }}" class="d-none">
                                                    <span class="option-text">
                                                        <span class="fw-bold me-2">{{ strtoupper($opsi) }}.</span> 
                                                        {{ $soal->$field }}
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>

                                {{-- ============================= --}}
                                {{-- ESSAY --}}
                                {{-- ============================= --}}
                                @elseif($soal->tipe === 'essay')
                                    <div class="mt-3">
                                        <textarea name="jawaban[{{ $soal->id }}]" rows="4" class="form-control" placeholder="Ketik jawaban lengkap Anda di sini..."></textarea>
                                    </div>

                                {{-- ============================= --}}
                                {{-- MATCHING --}}
                                {{-- ============================= --}}
                                @elseif($soal->tipe === 'matching')
                                    @php
                                        $kiri = json_decode($soal->kiri_items ?? '[]', true);
                                        $kanan = json_decode($soal->kanan_items ?? '[]', true);
                                        $shuffled = collect($kanan)->shuffle()->values();
                                    @endphp

                                    <div class="row g-4 mt-2">
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-4">
                                                <h6 class="fw-bold text-primary mb-3 text-center">Item Kiri (Tarik Ini)</h6>
                                                @foreach($kiri as $i => $item)
                                                    <div class="matching-left-item p-3 mb-2 bg-white shadow-sm border rounded-3 d-flex align-items-center"
                                                        draggable="true" data-left="{{ $i }}" data-soal="{{ $soal->id }}">
                                                        <i class="fas fa-grip-vertical me-3 text-muted"></i>
                                                        {{ $item }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-4">
                                                <h6 class="fw-bold text-success mb-3 text-center">Target Kanan (Lepas Di Sini)</h6>
                                                @foreach($shuffled as $i => $item)
                                                    <div class="matching-right-item p-3 mb-2 shadow-sm rounded-3 bg-white text-center"
                                                        data-right="{{ array_search($item, $kanan) }}" data-soal="{{ $soal->id }}">
                                                        {{ $item }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="small fw-bold text-muted">Progres Menghubungkan</span>
                                            <span class="small fw-bold text-primary" id="status-{{ $soal->id }}">0/{{ count($kiri) }} Pasangan</span>
                                        </div>
                                        <div class="progress" style="height:10px;">
                                            <div class="progress-bar" id="progress-{{ $soal->id }}" style="width:0%"></div>
                                        </div>
                                    </div>

                                    <div id="result-{{ $soal->id }}"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-light border-0 p-5 text-center">
                        <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow" 
                            data-bs-toggle="modal" data-bs-target="#modalSubmit">
                            Selesaikan & Submit <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SUBMIT --}}
<div class="modal fade" id="modalSubmit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-5 text-center">
                <div class="text-warning mb-4">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h3 class="fw-bold mb-3">Siap Mengirim?</h3>
                <p class="text-muted">Pastikan semua jawaban telah diperiksa kembali sebelum dikirim ke pengajar.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Nanti Dulu</button>
                    <button class="btn btn-success rounded-pill px-4 fw-bold" id="btnSubmitFinal">Ya, Kirim Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let dragLeft = null;
    let dragSoal = null;

    // Matching Interaction
    document.querySelectorAll('.matching-left-item').forEach(left => {
        left.addEventListener('dragstart', function(e) {
            dragLeft = this.dataset.left;
            dragSoal = this.dataset.soal;
            this.style.opacity = '0.5';
        });
        
        left.addEventListener('dragend', function() {
            this.style.opacity = '1';
        });
    });

    document.querySelectorAll('.matching-right-item').forEach(right => {
        right.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        right.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        right.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            const rightIndex = this.dataset.right;
            const soalId = dragSoal;
            const container = document.getElementById('result-' + soalId);

            let input = document.querySelector(`input[name="jawaban[${soalId}][${dragLeft}]"]`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = `jawaban[${soalId}][${dragLeft}]`;
                container.appendChild(input);
            }
            input.value = rightIndex;

            // Update Progress & UI
            const progressBar = document.getElementById('progress-' + soalId);
            const status = document.getElementById('status-' + soalId);
            const total = document.querySelectorAll('.matching-left-item[data-soal="' + soalId + '"]').length;
            const connected = container.querySelectorAll('input').length;
            const percent = (connected / total) * 100;

            progressBar.style.width = percent + '%';
            status.textContent = connected + '/' + total + ' Pasangan';

            this.classList.add('connected');
            this.innerHTML = `<i class="fas fa-check-circle me-2"></i> Terhubung`;
        });
    });

    // Final Submit Button
    document.getElementById('btnSubmitFinal').addEventListener('click', function() {
        const form = document.getElementById('formTantangan');
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sedang Mengirim...';
        this.disabled = true;
        form.submit();
    });
});
</script>
@endsection