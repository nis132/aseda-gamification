@extends('layouts.app')
@section('title', 'Buat Soal - ' . $tantangan->judul)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Penyusunan Pertanyaan</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Tantangan: <strong style="color: var(--clr-primary);">{{ $tantangan->judul }}</strong>
        </p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="badge fw-normal"
              style="background: #fef3c7; color: var(--clr-warning); font-size: 0.78rem; padding: 0.45em 0.9em;">
            <i class="fas fa-star me-1"></i>Reward: {{ $tantangan->poin }} XP
        </span>
        <a href="{{ route('guru.tantangan.show', $tantangan) }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

{{-- TOMBOL DOWNLOAD TEMPLATE --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('guru.soal.template', [$tantangan, 'pg']) }}" class="btn btn-light btn-sm">
        <i class="fas fa-download me-1" style="color: var(--clr-primary);"></i>Template PG
    </a>
    <a href="{{ route('guru.soal.template', [$tantangan, 'essay']) }}" class="btn btn-light btn-sm">
        <i class="fas fa-download me-1" style="color: var(--clr-success);"></i>Template Essay
    </a>
    <a href="{{ route('guru.soal.template', [$tantangan, 'matching']) }}" class="btn btn-light btn-sm">
        <i class="fas fa-download me-1" style="color: var(--clr-info);"></i>Template Matching
    </a>
</div>

<div class="row g-4">

    {{-- PANEL KIRI: FORM INPUT --}}
    <div class="col-lg-5">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header card-header-gradient">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span class="fw-bold" style="font-size: 0.9rem;">Tambah Soal Baru</span>
                </div>
            </div>
            <div class="card-body p-4">

                {{-- IMPORT EXCEL --}}
                <button type="button" class="btn btn-light w-100 mb-3"
                        data-bs-toggle="modal" data-bs-target="#importExcelModal">
                    <i class="fas fa-file-excel me-2" style="color: var(--clr-success);"></i>Import dari Excel
                </button>

                <div style="border-top: 1px solid var(--border-color); margin: 1rem 0;"></div>

                {{-- TIPE SOAL --}}
                <div class="mb-3">
                    <label class="form-label">Jenis Pertanyaan</label>
                    <select id="tipeSoal" class="form-select">
                        <option value="" disabled selected>-- Pilih Jenis --</option>
                        <option value="pg">Pilihan Ganda</option>
                        <option value="essay">Esai (Uraian)</option>
                        <option value="matching">Menjodohkan</option>
                    </select>
                    <div class="invalid-feedback d-block" id="error-tipeSoal" style="font-size: 0.76rem;"></div>
                </div>

                {{-- PERTANYAAN --}}
                <div class="mb-3">
                    <label class="form-label">Pertanyaan</label>
                    <textarea id="pertanyaan" class="form-control" rows="3"
                              placeholder="Tulis instruksi atau pertanyaan di sini..."></textarea>
                    <div class="invalid-feedback d-block" id="error-pertanyaan" style="font-size: 0.76rem;"></div>
                </div>

                <div style="border-top: 1px solid var(--border-color); margin: 1rem 0;"></div>

                {{-- FORM DINAMIS --}}
                <div id="dynamicFormContainer">

                    {{-- PG --}}
                    <div id="formPG" class="d-none">
                        <label class="form-label">Opsi Jawaban</label>
                        @foreach(['A','B','C','D'] as $opt)
                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text fw-bold"
                                      style="background: var(--bg-muted); border-color: var(--border-color);
                                             color: var(--txt-secondary); min-width: 40px; justify-content: center;">
                                    {{ $opt }}
                                </span>
                                <input type="text" id="{{ strtolower($opt) }}"
                                       class="form-control"
                                       placeholder="Jawaban {{ $opt }}">
                            </div>
                            <div class="invalid-feedback d-block" id="error-{{ strtolower($opt) }}"
                                 style="font-size: 0.76rem;"></div>
                        </div>
                        @endforeach
                        <div class="mt-3">
                            <label class="form-label">Kunci Jawaban</label>
                            <select id="jawaban" class="form-select"
                                    style="border-color: var(--clr-success); color: var(--clr-success); font-weight: 600;">
                                <option value="">Pilih Huruf Benar</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                            <div class="invalid-feedback d-block" id="error-jawaban" style="font-size: 0.76rem;"></div>
                        </div>
                    </div>

                    {{-- ESSAY --}}
                    <div id="formEssay" class="d-none">
                        <label class="form-label">Kunci Jawaban Esai</label>
                        <textarea id="jawabanEssay" class="form-control" rows="3"
                                  placeholder="Masukkan kata kunci atau jawaban yang benar..."></textarea>
                        <div class="invalid-feedback d-block" id="error-jawabanEssay" style="font-size: 0.76rem;"></div>
                    </div>

                    {{-- MATCHING --}}
                    <div id="formMatching" class="d-none">
                        <div class="px-3 py-2 rounded-2 mb-3"
                             style="background: #dbeafe; color: var(--clr-info); font-size: 0.78rem;">
                            <i class="fas fa-info-circle me-1"></i>Pisahkan item dengan tanda koma ( , )
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grup Kiri</label>
                            <input id="kiri" class="form-control" placeholder="Contoh: Apel, Jeruk, Pisang">
                            <div class="invalid-feedback d-block" id="error-kiri" style="font-size: 0.76rem;"></div>
                        </div>
                        <div>
                            <label class="form-label">Grup Kanan (Jawaban)</label>
                            <input id="kanan" class="form-control" placeholder="Contoh: Merah, Oranye, Kuning">
                            <div class="invalid-feedback d-block" id="error-kanan" style="font-size: 0.76rem;"></div>
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="button" id="addSoal" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Tambahkan ke Daftar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL KANAN: DAFTAR SOAL --}}
    <div class="col-lg-7">

        {{-- ERROR VALIDASI DARI SERVER --}}
        @if($errors->has('soal') || $errors->has('soal_detail'))
        <div class="alert d-flex gap-3 align-items-start mb-3 rounded-3"
             style="background: #fee2e2; border: 1px solid #fca5a5;" role="alert">
            <i class="fas fa-exclamation-circle mt-1"
               style="color: var(--clr-danger); flex-shrink:0; font-size:1.1rem;"></i>
            <div>
                <div class="fw-bold mb-1" style="color: #991b1b; font-size: 0.875rem;">
                    Soal gagal disimpan:
                </div>
                @if($errors->has('soal'))
                    <p class="mb-0" style="font-size: 0.82rem; color: #7f1d1d;">
                        {{ $errors->first('soal') }}
                    </p>
                @endif
                @if($errors->has('soal_detail'))
                    <ul class="mb-0 ps-3" style="font-size: 0.82rem; color: #7f1d1d;">
                        @foreach($errors->get('soal_detail') as $detailGroup)
                            @if(is_array($detailGroup))
                                @foreach($detailGroup as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            @else
                                <li>{{ $detailGroup }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('guru.soal.store', $tantangan) }}">
            @csrf

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0" style="color: var(--txt-primary);">
                    Daftar Soal
                    <span class="badge ms-1"
                          style="background: var(--clr-primary-light); color: var(--clr-primary); font-size: 0.78rem;"
                          id="countSoal">0</span>
                </h5>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cloud-upload-alt me-2"></i>Simpan Semua
                </button>
            </div>

            <div id="listSoal">
                <div id="emptyState" class="card text-center py-5"
                     style="border: 2px dashed var(--border-color); background: var(--bg-muted);">
                    <div class="empty-state-icon mx-auto">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <p style="color: var(--txt-tertiary); font-size: 0.85rem; margin: 0;">
                        Belum ada soal ditambahkan.
                    </p>
                </div>
            </div>

            <input type="hidden" name="soal_data" id="soalData">
        </form>
    </div>
</div>

{{-- MODAL IMPORT EXCEL --}}
<x-modal id="importExcelModal" title="Import Soal dari Excel" type="success" icon="fa-file-excel">
    <div class="mb-3">
        <label class="form-label">Pilih File Excel</label>
        <input type="file" id="excelFile" class="form-control" accept=".xlsx,.xls,.csv">
    </div>
    <div class="px-3 py-2 rounded-2"
         style="background: #dbeafe; color: var(--clr-info); font-size: 0.8rem;">
        <i class="fas fa-info-circle me-1"></i>
        File akan masuk ke preview Daftar Soal terlebih dahulu, belum langsung disimpan.
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-center gap-2 w-100">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <button type="button" id="previewImportExcel" class="btn btn-primary px-4">
                <i class="fas fa-upload me-2"></i>Import ke Preview
            </button>
        </div>
    </x-slot:footer>
</x-modal>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
let soalList = [];

const tipe  = document.getElementById('tipeSoal');
const forms = {
    pg:       document.getElementById('formPG'),
    essay:    document.getElementById('formEssay'),
    matching: document.getElementById('formMatching'),
};

tipe.addEventListener('change', function () {
    Object.values(forms).forEach(f => f.classList.add('d-none'));
    if (this.value) forms[this.value].classList.remove('d-none');
    clearError('tipeSoal');
});

document.querySelectorAll('input, textarea, select').forEach(el => {
    el.addEventListener('input', () => clearError(el.id));
});

function setError(id, msg) {
    const el  = document.getElementById(id);
    const err = document.getElementById('error-' + id);
    if (el)  el.classList.add('is-invalid');
    if (err) err.innerText = msg;
}

function clearError(id) {
    const el  = document.getElementById(id);
    const err = document.getElementById('error-' + id);
    if (el)  el.classList.remove('is-invalid');
    if (err) err.innerText = '';
}

function clearAllErrors() {
    ['tipeSoal','pertanyaan','a','b','c','d','jawaban','jawabanEssay','kiri','kanan']
        .forEach(id => clearError(id));
}

document.getElementById('addSoal').addEventListener('click', function () {
    clearAllErrors();
    let hasError = false;

    const tipeVal    = tipe.value;
    const pertanyaan = document.getElementById('pertanyaan').value.trim();

    if (!tipeVal)    { setError('tipeSoal',   'Jenis soal wajib dipilih.');       hasError = true; }
    if (!pertanyaan) { setError('pertanyaan', 'Pertanyaan tidak boleh kosong.');  hasError = true; }
    if (hasError) return;

    let data = { tipe: tipeVal, pertanyaan };

    if (tipeVal === 'pg') {
        ['a','b','c','d'].forEach(o => {
            const val = document.getElementById(o).value.trim();
            if (!val) { setError(o, `Opsi ${o.toUpperCase()} harus diisi.`); hasError = true; }
            else data['opsi_' + o] = val;
        });
        const jwb = document.getElementById('jawaban').value;
        if (!jwb) { setError('jawaban', 'Pilih kunci jawaban yang benar.'); hasError = true; }
        else data.jawaban_benar = jwb;

    } else if (tipeVal === 'essay') {
        const jwb = document.getElementById('jawabanEssay').value.trim();
        if (!jwb) { setError('jawabanEssay', 'Kunci jawaban esai wajib diisi.'); hasError = true; }
        else data.jawaban_benar = jwb;

    } else if (tipeVal === 'matching') {
        const kiri  = document.getElementById('kiri').value.trim();
        const kanan = document.getElementById('kanan').value.trim();

        if (!kiri)  { setError('kiri',  'Grup kiri wajib diisi.');  hasError = true; }
        if (!kanan) { setError('kanan', 'Grup kanan wajib diisi.'); hasError = true; }

        if (!hasError) {
            const kiriArr  = kiri.split(',').map(i => i.trim()).filter(Boolean);
            const kananArr = kanan.split(',').map(i => i.trim()).filter(Boolean);

            if (kiriArr.length < 2) {
                setError('kiri', 'Minimal 2 item di grup kiri.');
                hasError = true;
            }
            if (kananArr.length < 2) {
                setError('kanan', 'Minimal 2 item di grup kanan.');
                hasError = true;
            }
            if (!hasError && kiriArr.length !== kananArr.length) {
                setError('kanan', 'Jumlah item kiri dan kanan harus sama.');
                hasError = true;
            }
            if (!hasError) {
                data.kiri_items  = kiriArr;
                data.kanan_items = kananArr;
            }
        }
    }

    if (hasError) return;
    soalList.push(data);
    renderSoal();
    resetForm();
});

function badgeStyle(tipe) {
    const map = {
        pg:       'background: var(--clr-primary-light); color: var(--clr-primary);',
        essay:    'background: #d1fae5; color: var(--clr-success);',
        matching: 'background: #dbeafe; color: var(--clr-info);',
    };
    return map[tipe] || '';
}

function labelTipe(tipe) {
    return { pg: 'Pilihan Ganda', essay: 'Esai', matching: 'Menjodohkan' }[tipe] || tipe.toUpperCase();
}

function renderSoal() {
    const container = document.getElementById('listSoal');

    if (soalList.length === 0) {
        container.innerHTML = `
            <div class="card text-center py-5"
                 style="border: 2px dashed var(--border-color); background: var(--bg-muted);">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--bg-muted);
                            display:flex;align-items:center;justify-content:center;
                            margin:0 auto 1rem;font-size:1.5rem;color:var(--txt-tertiary);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <p style="color:var(--txt-tertiary);font-size:0.85rem;margin:0;">Belum ada soal ditambahkan.</p>
            </div>`;
    } else {
        container.innerHTML = soalList.map((s, i) => {

            let detail = '';

            if (s.tipe === 'pg') {
                detail = `
                    <div class="mt-2" style="font-size:0.8rem;color:var(--txt-secondary);">
                        <div>A. ${s.opsi_a || '-'}</div>
                        <div>B. ${s.opsi_b || '-'}</div>
                        <div>C. ${s.opsi_c || '-'}</div>
                        <div>D. ${s.opsi_d || '-'}</div>
                        <div class="mt-1 fw-bold" style="color:var(--clr-success);">
                            <i class="fas fa-check-circle me-1"></i>Jawaban: ${s.jawaban_benar}
                        </div>
                    </div>`;
            } else if (s.tipe === 'essay') {
                detail = `
                    <div class="mt-2" style="font-size:0.8rem;color:var(--clr-success);">
                        <i class="fas fa-key me-1"></i>Kunci: ${s.jawaban_benar}
                    </div>`;
            } else if (s.tipe === 'matching') {
                const pairs = (s.kiri_items || []).map((k, idx) =>
                    `<div>${k} → ${(s.kanan_items || [])[idx] || '-'}</div>`
                ).join('');
                detail = `<div class="mt-2" style="font-size:0.8rem;color:var(--txt-secondary);">${pairs}</div>`;
            }

            return `
                <div class="card mb-2">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge fw-bold" style="${badgeStyle(s.tipe)}">${labelTipe(s.tipe)}</span>
                            <span style="font-size:0.72rem;font-weight:700;color:var(--txt-tertiary);
                                         text-transform:uppercase;letter-spacing:0.05em;">Soal #${i+1}</span>
                            <button type="button" class="btn btn-action btn-light ms-auto"
                                    onclick="removeSoal(${i})" title="Hapus soal ini">
                                <i class="fas fa-trash-alt" style="color:var(--clr-danger);"></i>
                            </button>
                        </div>
                        <p class="fw-bold mb-0" style="font-size:0.88rem;color:var(--txt-primary);">${s.pertanyaan}</p>
                        ${detail}
                    </div>
                </div>`;
        }).join('');
    }

    document.getElementById('soalData').value     = JSON.stringify(soalList);
    document.getElementById('countSoal').innerText = soalList.length;
}

function removeSoal(index) {
    soalList.splice(index, 1);
    renderSoal();
}

function resetForm() {
    document.getElementById('pertanyaan').value = '';
    ['a','b','c','d','jawaban','jawabanEssay','kiri','kanan'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    clearAllErrors();
}

/* ---- Import Excel ---- */
document.getElementById('previewImportExcel').addEventListener('click', function () {
    const fileInput = document.getElementById('excelFile');
    const file = fileInput.files[0];
    if (!file) {
        alert('Pilih file Excel terlebih dahulu.');
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const wb   = XLSX.read(data, { type: 'array' });
        const ws   = wb.Sheets[wb.SheetNames[0]];
        const rows = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
        rows.shift(); // hapus header

        let imported = 0;

        rows.forEach(row => {
            if (!row[0]) return;
            const filled = row.filter(v => v !== null && v !== '').length;
            let soal = null;

            if (filled >= 6) {
                soal = {
                    tipe: 'pg',
                    pertanyaan: row[0],
                    opsi_a: row[1],
                    opsi_b: row[2],
                    opsi_c: row[3],
                    opsi_d: row[4],
                    jawaban_benar: String(row[5]).toUpperCase()
                };
            } else if (filled === 2) {
                soal = { tipe: 'essay', pertanyaan: row[0], jawaban_benar: row[1] };
            } else if (filled === 3) {
                const kiriItems  = String(row[1]).split(',').map(i => i.trim()).filter(Boolean);
                const kananItems = String(row[2]).split(',').map(i => i.trim()).filter(Boolean);
                if (kiriItems.length === kananItems.length && kiriItems.length >= 2) {
                    soal = { tipe: 'matching', pertanyaan: row[0], kiri_items: kiriItems, kanan_items: kananItems };
                }
            }

            if (soal) { soalList.push(soal); imported++; }
        });

        renderSoal();
        fileInput.value = '';
        bootstrap.Modal.getInstance(document.getElementById('importExcelModal')).hide();

        if (imported === 0) {
            alert('Tidak ada soal yang berhasil diimpor. Periksa format file Excel.');
        }
    };
    reader.readAsArrayBuffer(file);
});
</script>
@endpush