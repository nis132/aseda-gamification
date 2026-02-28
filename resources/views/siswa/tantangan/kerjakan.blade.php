@extends('layouts.app')
@section('title','Kerjakan Tantangan - {{ $tantangan->judul }}')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h2 class="mb-2"><i class="fas fa-tasks me-2"></i>{{ $tantangan->judul }}</h2>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark fs-6">{{ $tantangan->mapel->nama_mapel }}</span>
                        <span class="badge bg-secondary fs-6">{{ $tantangan->kelas->nama_kelas }}</span>
                        <span class="badge bg-info fs-6">{{ $soals->count() }} Soal</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('siswa.tantangan.submit', $tantangan) }}" id="tantanganForm">
                    @csrf
                    <div class="card-body p-5">
                        @foreach($soals as $index => $soal)
                        <div class="soal-item mb-5 p-4 border rounded-4 shadow-sm" data-soal-id="{{ $soal->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold mb-0">
                                    <span class="badge bg-primary fs-6 me-3">Soal {{ $index + 1 }}</span>
                                    {{ $soal->pertanyaan }}
                                </h5>
                                <span class="badge bg-info px-3 py-2 fs-6">{{ ucfirst($soal->tipe) }}</span>
                            </div>

                            {{-- PILIHAN GANDA --}}
                            @if($soal->tipe === 'pg')
                            <div class="row g-3">
                                @if($soal->opsi_a)
                                <div class="col-md-6">
                                    <label class="p-3 border rounded-3 d-block hover-effect">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="A" class="form-check-input me-3">
                                        <span class="fw-semibold fs-6">A. {{ $soal->opsi_a }}</span>
                                    </label>
                                </div>
                                @endif
                                @if($soal->opsi_b)
                                <div class="col-md-6">
                                    <label class="p-3 border rounded-3 d-block hover-effect">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="B" class="form-check-input me-3">
                                        <span class="fw-semibold fs-6">B. {{ $soal->opsi_b }}</span>
                                    </label>
                                </div>
                                @endif
                                @if($soal->opsi_c)
                                <div class="col-md-6">
                                    <label class="p-3 border rounded-3 d-block hover-effect">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="C" class="form-check-input me-3">
                                        <span class="fw-semibold fs-6">C. {{ $soal->opsi_c }}</span>
                                    </label>
                                </div>
                                @endif
                                @if($soal->opsi_d)
                                <div class="col-md-6">
                                    <label class="p-3 border rounded-3 d-block hover-effect">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="D" class="form-check-input me-3">
                                        <span class="fw-semibold fs-6">D. {{ $soal->opsi_d }}</span>
                                    </label>
                                </div>
                                @endif
                            </div>

                            {{-- ESSAY --}}
                            @elseif($soal->tipe === 'essay')
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fw-bold mb-3">Jawaban:</label>
                                    <textarea name="jawaban[{{ $soal->id }}]" rows="4" class="form-control" required 
                                              placeholder="Tulis jawaban Anda...">{{ old("jawaban.$soal->id") }}</textarea>
                                </div>
                            </div>

                            {{-- ✅ MATCHING - TARI GARIS + SHUFFLE KANAN --}}
                            @elseif($soal->tipe === 'matching')
                            <div class="matching-container p-4 bg-light rounded-3">
                                {{-- KIRI (FIXED ORDER) --}}
                                <div class="row mb-4">
                                    <div class="col-md-1 text-center fw-bold text-primary fs-6 mb-2">KIRI</div>
                                    <div class="col-md-5">
                                        <div class="kiri-items position-relative" id="kiri-{{ $soal->id }}">
                                            @foreach(json_decode($soal->kiri_items ?? '[]', true) as $index => $item)
                                            <div class="matching-left-item p-3 mb-2 bg-white border rounded-2 shadow-sm draggable-item cursor-pointer position-relative" 
                                                 data-left="{{ $index }}" data-soal="{{ $soal->id }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold text-primary me-3">{{ $index + 1 }}.</span>
                                                    <span>{{ $item }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="line-area position-relative" style="height: 300px; border-left: 2px dashed #ccc; border-right: 2px dashed #ccc; padding: 0 10px;">
                                            <div class="lines-container" id="lines-{{ $soal->id }}"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center fw-bold text-success fs-6 mb-2">KANAN</div>
                                    <div class="col-md-3">
                                        {{-- KANAN SHUFFLED --}}
                                        <div class="kanan-items position-relative" id="kanan-{{ $soal->id }}">
                                            @php
                                                $kananOriginal = json_decode($soal->kanan_items ?? '[]', true);
                                                $shuffledKanan = $kananOriginal ? collect($kananOriginal)->shuffle()->values()->all() : [];
                                            @endphp
                                            @foreach($shuffledKanan as $index => $item)
                                            <div class="matching-right-item p-3 mb-2 bg-white border rounded-2 shadow-sm dropzone-item cursor-pointer position-relative empty" 
                                                 data-right="{{ array_search($item, $kananOriginal) }}" data-soal="{{ $soal->id }}" data-pos="{{ $index }}">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-bold text-success me-3">{{ $index + 1 }}.</span>
                                                    <span>{{ $item }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- PROGRESS --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar" id="progress-{{ $soal->id }}" style="width: 0%"></div>
                                        </div>
                                        <small id="status-{{ $soal->id }}">Hubungkan {{ count($shuffledKanan) }} pasangan</small>
                                    </div>
                                </div>

                                {{-- HIDDEN INPUT --}}
                                <input type="hidden" name="jawaban[{{ $soal->id }}]" id="result-{{ $soal->id }}" value="">
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="card-footer py-4">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Submit Jawaban
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.hover-effect:hover { 
    background-color: #f8f9fa !important; 
    transform: translateY(-1px);
}
.draggable-item, .dropzone-item {
    transition: all 0.3s ease;
    user-select: none;
}
.draggable-item:hover, .dropzone-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.draggable-item.selected {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    color: white !important;
}
.line {
    position: absolute;
    height: 3px;
    background: #007bff;
    border-radius: 2px;
    z-index: 10;
    transition: all 0.5s ease;
}
.line.correct { background: #28a745; box-shadow: 0 0 10px rgba(40,167,69,0.5); }
.line.wrong { background: #dc3545; box-shadow: 0 0 10px rgba(220,53,69,0.5); }
.dropzone-item.connected { border-color: #007bff; }
.dropzone-item.correct { background: #d4edda !important; border-color: #28a745; }
.dropzone-item.wrong { background: #f8d7da !important; border-color: #dc3545; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let connections = {}; // {soalId: {leftIndex: rightOriginalIndex}}
    let selectedLeft = null;

    // Matching Logic - Klik kiri → Klik kanan → Garis
    document.querySelectorAll('.matching-container').forEach(container => {
        const soalId = container.closest('.soal-item').dataset.soalId;
        const leftContainer = document.getElementById(`kiri-${soalId}`);
        const rightContainer = document.getElementById(`kanan-${soalId}`);
        const linesContainer = document.getElementById(`lines-${soalId}`);
        const progressBar = document.getElementById(`progress-${soalId}`);
        const statusText = document.getElementById(`status-${soalId}`);
        const resultInput = document.getElementById(`result-${soalId}`);

        if (!connections[soalId]) connections[soalId] = {};

        // 1. Klik LEFT item → SELECT
        leftContainer.querySelectorAll('.matching-left-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const leftIndex = parseInt(this.dataset.left);
                
                // Reset previous selection
                document.querySelectorAll('.matching-left-item.selected').forEach(i => i.classList.remove('selected'));
                document.querySelectorAll('.line').forEach(line => line.remove());
                
                // Select this item
                this.classList.add('selected');
                selectedLeft = { element: this, index: leftIndex };
                statusText.textContent = `Pilih jawaban kanan untuk ${leftIndex + 1}...`;
            });
        });

        // 2. Klik RIGHT item → CONNECT
        rightContainer.querySelectorAll('.matching-right-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (selectedLeft) {
                    const rightOriginalIndex = parseInt(this.dataset.right);
                    
                    // Simpan koneksi
                    connections[soalId][selectedLeft.index] = rightOriginalIndex;
                    
                    // Buat garis
                    createLine(linesContainer, selectedLeft.element, this);
                    
                    // Mark connected
                    this.classList.add('connected');
                    this.classList.remove('empty');
                    
                    // Reset selection
                    selectedLeft.element.classList.remove('selected');
                    selectedLeft = null;
                    
                    // Update status
                    updateStatus(soalId);
                }
            });
        });

        function createLine(linesContainer, leftEl, rightEl) {
            const leftRect = leftEl.getBoundingClientRect();
            const rightRect = rightEl.getBoundingClientRect();
            const containerRect = linesContainer.getBoundingClientRect();
            
            const line = document.createElement('div');
            line.className = 'line';
            line.style.left = '0px';
            line.style.top = `${leftRect.top - containerRect.top + leftRect.height/2}px`;
            line.style.width = `${rightRect.left - containerRect.left}px`;
            line.style.height = '3px';
            linesContainer.appendChild(line);
        }

        function updateStatus(soalId) {
            const correctPairs = @json(json_decode($soal->matching_pairs ?? '[]'));
            let correct = 0;
            let total = correctPairs.length;

            // Cek jawaban
            Object.keys(connections[soalId]).forEach(leftIdx => {
                const rightIdx = connections[soalId][leftIdx];
                const correctPair = correctPairs.find(p => p[0] == leftIdx && p[1] == rightIdx);
                if (correctPair) {
                    correct++;
                    // Mark correct line & item
                    document.querySelectorAll(`#lines-${soalId} .line`)[leftIdx]?.classList.add('correct');
                    document.querySelectorAll(`#kanan-${soalId} .matching-right-item`)[rightIdx]?.classList.add('correct');
                } else {
                    document.querySelectorAll(`#lines-${soalId} .line`)[leftIdx]?.classList.add('wrong');
                    document.querySelectorAll(`#kanan-${soalId} .matching-right-item`)[rightIdx]?.classList.add('wrong');
                }
            });

            const percentage = (correct / total) * 100;
            progressBar.style.width = percentage + '%';
            statusText.textContent = `${correct}/${total} benar (${percentage.toFixed(0)}%)`;
            
            resultInput.value = JSON.stringify(connections[soalId]);
        }
    });
});
</script>
@endsection
