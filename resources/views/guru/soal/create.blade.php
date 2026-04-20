@extends('layouts.app')
@section('title', 'Buat Soal - ' . $tantangan->judul)

@section('content')
<div class="container-fluid py-3">

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-feather me-2"></i>Buat Soal - {{ $tantangan->judul }}
            </h5>
        </div>

        <form method="POST" action="{{ route('guru.soal.store', $tantangan) }}">
            @csrf

            <div class="card-body">

                {{-- ================= FORM INPUT SOAL ================= --}}
                <div class="border rounded p-3 mb-4 bg-light">

                    <div class="mb-3">
                        <label class="fw-bold">Jenis Soal</label>
                        <select id="tipeSoal" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="pg">Pilihan Ganda</option>
                            <option value="essay">Essay</option>
                            <option value="matching">Menjodohkan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Pertanyaan</label>
                        <textarea id="pertanyaan" class="form-control"></textarea>
                    </div>

                    {{-- PG --}}
                    <div id="formPG" class="d-none mb-3">
                        <input class="form-control mb-2" id="a" placeholder="A">
                        <input class="form-control mb-2" id="b" placeholder="B">
                        <input class="form-control mb-2" id="c" placeholder="C">
                        <input class="form-control mb-2" id="d" placeholder="D">

                        <select id="jawaban" class="form-select">
                            <option value="">Jawaban Benar</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    {{-- Essay --}}
                    <div id="formEssay" class="d-none mb-3">
                        <textarea id="jawabanEssay" class="form-control" placeholder="Jawaban / kunci"></textarea>
                    </div>

                    {{-- Matching --}}
                    <div id="formMatching" class="d-none mb-3">
                        <input id="kiri" class="form-control mb-2" placeholder="Kiri (pisahkan koma)">
                        <input id="kanan" class="form-control" placeholder="Kanan (pisahkan koma)">
                    </div>

                    <button type="button" id="addSoal" class="btn btn-primary mt-3">
                        + Tambah Soal
                    </button>

                </div>

                {{-- ================= LIST SOAL ================= --}}
                <h5 class="fw-bold mb-3">Daftar Soal</h5>
                <div id="listSoal" class="row g-3"></div>

                {{-- hidden input final --}}
                <input type="hidden" name="soal_data" id="soalData">

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-lg">
                    Simpan Semua Soal
                </button>
            </div>

        </form>
    </div>
</div>

<script>
let soalList = [];

const tipe = document.getElementById('tipeSoal');
const formPG = document.getElementById('formPG');
const formEssay = document.getElementById('formEssay');
const formMatching = document.getElementById('formMatching');

tipe.addEventListener('change', function () {

    formPG.classList.add('d-none');
    formEssay.classList.add('d-none');
    formMatching.classList.add('d-none');

    if (this.value === 'pg') formPG.classList.remove('d-none');
    if (this.value === 'essay') formEssay.classList.remove('d-none');
    if (this.value === 'matching') formMatching.classList.remove('d-none');

});

document.getElementById('addSoal').addEventListener('click', function () {

    let tipeVal = tipe.value;
    let pertanyaan = document.getElementById('pertanyaan').value;

    if (!tipeVal || !pertanyaan) return alert('Lengkapi soal');

    let data = {
        tipe: tipeVal,
        pertanyaan: pertanyaan
    };

    if (tipeVal === 'pg') {
        data.opsi_a = document.getElementById('a').value;
        data.opsi_b = document.getElementById('b').value;
        data.opsi_c = document.getElementById('c').value;
        data.opsi_d = document.getElementById('d').value;
        data.jawaban_benar = document.getElementById('jawaban').value;
    }

    if (tipeVal === 'essay') {
        data.jawaban_benar = document.getElementById('jawabanEssay').value;
    }

    if (tipeVal === 'matching') {
        data.kiri_items = document.getElementById('kiri').value.split(',');
        data.kanan_items = document.getElementById('kanan').value.split(',');
    }

    soalList.push(data);

    renderSoal();
    resetForm();

});

function renderSoal() {
    let html = '';

    soalList.forEach((s, i) => {
        html += `
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <span class="badge bg-primary mb-2">${s.tipe.toUpperCase()}</span>
                    <h6>${s.pertanyaan}</h6>
                </div>
            </div>
        </div>`;
    });

    document.getElementById('listSoal').innerHTML = html;
    document.getElementById('soalData').value = JSON.stringify(soalList);
}

function resetForm() {
    document.getElementById('pertanyaan').value = '';
    document.getElementById('a').value = '';
    document.getElementById('b').value = '';
    document.getElementById('c').value = '';
    document.getElementById('d').value = '';
    document.getElementById('jawaban').value = '';
    document.getElementById('jawabanEssay').value = '';
    document.getElementById('kiri').value = '';
    document.getElementById('kanan').value = '';
}
</script>

@endsection