@extends('layouts.app')

@section('title', 'Rekap Nilai Siswa')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-bar me-2" style="color: var(--clr-primary);"></i>
            Rekap Nilai Siswa
        </h1>
        <p style="color: var(--txt-secondary); font-size: 0.85rem; margin: 0;">
            Rekapitulasi nilai berdasarkan tantangan per mata pelajaran.
        </p>
    </div>
    <a href="{{ route('guru.leaderboard') }}" class="btn btn-warning rounded-pill px-4 fw-bold">
        <i class="fas fa-trophy me-2"></i>Leaderboard
    </a>
</div>

{{-- FILTER --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('guru.rekap.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-lg-5">
                    <label class="form-label small fw-bold mb-1">Mata Pelajaran &amp; Kelas</label>
                    <select name="mapel_id" id="pilihMapel" class="form-select">
                        <option value="">— Pilih Mapel &amp; Kelas —</option>
                        @foreach($mengajar as $m)
                            @if($m->kelas && $m->mapel)
                            <option value="{{ $m->mapel_id }}"
                                    data-kelas="{{ $m->kelas_id }}"
                                    {{ $selectedMapelId == $m->mapel_id && $selectedKelasId == $m->kelas_id ? 'selected' : '' }}>
                                {{ $m->mapel->nama_mapel }} — {{ $m->kelas->nama_kelas }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                    <input type="hidden" name="kelas_id" id="hiddenKelasId" value="{{ $selectedKelasId }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Tampilkan
                    </button>
                </div>
                @if($selectedMapelId && $selectedKelasId)
                <div class="col-auto">
                    <a href="{{ route('guru.rekap.export', ['mapel_id' => $selectedMapelId, 'kelas_id' => $selectedKelasId]) }}"
                       class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i>Export Excel
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

@if($selectedMapelId && $selectedKelasId)

    @if($tantanganList->isEmpty())
    <div class="card">
        <div class="card-body empty-state">
            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
            <h6>Belum Ada Tantangan Published</h6>
            <p>Buat dan publish tantangan terlebih dahulu.</p>
            <a href="{{ route('guru.tantangan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Tantangan
            </a>
        </div>
    </div>

    @elseif($siswaList->isEmpty())
    <div class="card">
        <div class="card-body empty-state">
            <div class="empty-state-icon"><i class="fas fa-users"></i></div>
            <h6>Belum Ada Siswa di Kelas Ini</h6>
            <p>Belum ada siswa yang terdaftar.</p>
        </div>
    </div>

    @else

    {{-- RINGKASAN CEPAT --}}
    @php
        $totalSiswa      = $siswaList->count();
        $totalTantangan  = $tantanganList->count();
        $totalMengerjakan = collect($statistik)->sum('mengumpulkan');
        $totalTuntas      = collect($statistik)->sum('tuntas');
        $rataKelas        = $totalMengerjakan > 0
            ? round(collect($statistik)->sum(fn($s) => $s['rata'] * $s['mengumpulkan']) / $totalMengerjakan, 1)
            : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon stat-icon-primary"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-number">{{ $totalSiswa }}</div>
                        <div class="text-label">Total Siswa</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon stat-icon-warning"><i class="fas fa-tasks"></i></div>
                    <div>
                        <div class="stat-number">{{ $totalTantangan }}</div>
                        <div class="text-label">Total Tantangan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon stat-icon-success"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-number">{{ $totalTuntas }}</div>
                        <div class="text-label">Total Tuntas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon stat-icon-info"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="stat-number">{{ $rataKelas }}</div>
                        <div class="text-label">Rata Kelas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STAT PER TANTANGAN — accordion per BAB --}}
    @php $tantanganPerBab = $tantanganList->groupBy(fn($t) => $t->bab ?? 'Umum'); @endphp

    <div class="card mb-4">
        <div class="card-header">
            <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
                <i class="fas fa-layer-group me-2" style="color:var(--clr-primary);"></i>
                Statistik Per Tantangan
            </h6>
        </div>
        <div class="card-body p-3">
            <div class="accordion" id="accordionBab">
                @foreach($tantanganPerBab as $bab => $tantangans)
                @php $babKey = 'bab-' . Str::slug($bab); @endphp
                <div class="accordion-item border-0 mb-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button rounded-2 fw-bold collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $babKey }}"
                                style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.875rem;">
                            <i class="fas fa-book-open me-2"></i>
                            BAB {{ $bab }}
                            <span class="badge ms-2"
                                  style="background:var(--clr-primary);color:#fff;font-size:0.65rem;">
                                {{ $tantangans->count() }} tantangan
                            </span>
                        </button>
                    </h2>
                    <div id="{{ $babKey }}" class="accordion-collapse collapse show">
                        <div class="accordion-body p-0 pt-2">
                            <div class="row g-2">
                                @foreach($tantangans as $t)
                                <div class="col-md-6 col-lg-4">
                                    <div class="p-3 rounded-3 h-100"
                                         style="background:var(--bg-muted);border:1px solid var(--border-color);">
                                        <div class="fw-semibold mb-2 text-truncate"
                                             style="font-size:0.82rem;color:var(--txt-primary);"
                                             title="{{ $t->judul }}">
                                            {{ Str::limit($t->judul, 35) }}
                                        </div>
                                        <div class="row g-1 mb-2">
                                            <div class="col-4 text-center">
                                                <div style="font-size:1rem;font-weight:700;
                                                    color:{{ ($statistik[$t->id]['rata'] ?? 0) >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)' }};">
                                                    {{ $statistik[$t->id]['rata'] ?? 0 }}
                                                </div>
                                                <div style="font-size:0.62rem;color:var(--txt-secondary);">Rata</div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <div style="font-size:1rem;font-weight:700;color:var(--clr-primary);">
                                                    {{ $statistik[$t->id]['mengumpulkan'] ?? 0 }}/{{ $totalSiswa }}
                                                </div>
                                                <div style="font-size:0.62rem;color:var(--txt-secondary);">Kumpul</div>
                                            </div>
                                            <div class="col-4 text-center">
                                                <div style="font-size:1rem;font-weight:700;color:var(--clr-success);">
                                                    {{ $statistik[$t->id]['tuntas'] ?? 0 }}
                                                </div>
                                                <div style="font-size:0.62rem;color:var(--txt-secondary);">Tuntas</div>
                                            </div>
                                        </div>
                                        @php
                                            $pct = $totalSiswa > 0
                                                ? (($statistik[$t->id]['mengumpulkan'] ?? 0) / $totalSiswa) * 100
                                                : 0;
                                        @endphp
                                        <div class="progress rounded-pill mb-2" style="height:4px;background:var(--border-color);">
                                            <div class="progress-bar rounded-pill"
                                                 style="width:{{ $pct }}%;background:var(--clr-primary);"></div>
                                        </div>
                                        <a href="{{ route('guru.nilai.index', $t->id) }}"
                                           class="btn btn-outline-primary btn-sm w-100 rounded-pill"
                                           style="font-size:0.72rem;">
                                            <i class="fas fa-user-check me-1"></i>Lihat Penilaian
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============================================================
         TABEL REKAP — 1 KARTU, TAB BAB, PAGINATION JS
    ============================================================ --}}

    {{-- Data siswa + nilai per bab — di-encode ke JSON untuk JS --}}
    @php
        // Hitung nilai_akhir_total (semua tantangan semua bab) per siswa
        $nilaiAkhirTotal = [];
        foreach ($siswaList as $siswa) {
            $jumlahSemua = 0;
            foreach ($tantanganList as $t) {
                $n = $nilaiMap[$siswa->id][$t->id] ?? null;
                $jumlahSemua += $n !== null ? (float)$n : 0;
            }
            $nilaiAkhirTotal[$siswa->id] = $tantanganList->count() > 0
                ? round($jumlahSemua / $tantanganList->count(), 1)
                : null;
        }
        // Rata kelas keseluruhan
        $totalNilaiSemua = array_sum(array_filter($nilaiAkhirTotal, fn($v) => $v !== null));
        $jumlahAdaNilai  = count(array_filter($nilaiAkhirTotal, fn($v) => $v !== null));
        $rataKelasTotal  = $jumlahAdaNilai > 0 ? round($totalNilaiSemua / $jumlahAdaNilai, 1) : 0;

        // Siapkan data semua bab untuk JS
        $rekapData = [];
        foreach ($tantanganPerBab as $bab => $tantangansBab) {
            $babTantanganIds = $tantangansBab->pluck('id');
            $totalMengerjakanBab = collect($statistik)->only($babTantanganIds->toArray())->sum('mengumpulkan');
            $rataKelasBab = $totalMengerjakanBab > 0
                ? round(collect($statistik)->only($babTantanganIds->toArray())->sum(fn($s) => $s['rata'] * $s['mengumpulkan']) / $totalMengerjakanBab, 1)
                : 0;

            $kolom = [];
            foreach ($tantangansBab as $t) {
                $kolom[] = [
                    'id'     => $t->id,
                    'judul'  => Str::limit($t->judul, 20),
                    'judul_full' => $t->judul,
                    'urutan' => $t->urutan ?? '-',
                    'rata'   => $statistik[$t->id]['rata'] ?? 0,
                ];
            }

            $siswaRows = [];
            foreach ($siswaList as $i => $siswa) {
                $jumlahNilaiBab = 0;
                $nilaiPerSoal = [];
                foreach ($tantangansBab as $t) {
                    $n = $nilaiMap[$siswa->id][$t->id] ?? null;
                    $nilaiPerSoal[$t->id] = $n;
                    $jumlahNilaiBab += $n !== null ? (float)$n : 0;
                }
                $nilaiAkhirBab = $tantangansBab->count() > 0
                    ? round($jumlahNilaiBab / $tantangansBab->count(), 1)
                    : null;

                $siswaRows[] = [
                    'no'                => $i + 1,
                    'nama'              => $siswa->nama,
                    'nis'               => $siswa->nis ?? '-',
                    'inisial'           => strtoupper(substr($siswa->nama, 0, 1)),
                    'nilai'             => $nilaiPerSoal,
                    'nilai_akhir'       => $nilaiAkhirBab,
                    'nilai_akhir_total' => $nilaiAkhirTotal[$siswa->id],
                ];
            }

            $rekapData[(string)$bab] = [
                'bab'              => $bab,
                'rata_kelas'       => $rataKelasBab,
                'rata_kelas_total' => $rataKelasTotal,
                'kolom'            => $kolom,
                'siswa'            => $siswaRows,
            ];
        }
        $babKeys = array_keys($rekapData);
    @endphp

    <div class="card mb-4" id="kartuRekap">
        {{-- Header + search --}}
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
                <i class="fas fa-table me-2" style="color:var(--clr-primary);"></i>
                Tabel Rekap Nilai
            </h6>
            <div class="input-group" style="width:210px;">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-search" style="font-size:0.75rem;color:var(--txt-tertiary);"></i>
                </span>
                <input type="text" id="cariSiswa" class="form-control border-start-0"
                       placeholder="Cari siswa..." style="font-size:0.82rem;">
            </div>
        </div>

        {{-- TAB BAB --}}
        <div class="px-3 pt-3 pb-0" style="border-bottom:1px solid var(--border-color);">
            <div class="d-flex flex-wrap gap-2" id="tabBab">
                @foreach($babKeys as $idx => $bab)
                <button class="btn btn-sm rounded-pill fw-semibold tab-bab-btn {{ $idx === 0 ? 'btn-primary' : 'btn-light' }}"
                        data-bab="{{ $bab }}" style="font-size:0.78rem;margin-bottom:8px;">
                    <i class="fas fa-book-open me-1"></i>BAB {{ $bab }}
                    <span class="badge ms-1 rounded-pill"
                          style="background:{{ $idx === 0 ? 'rgba(255,255,255,0.3)' : 'var(--clr-primary-light)' }};
                                 color:{{ $idx === 0 ? '#fff' : 'var(--clr-primary)' }};font-size:0.6rem;">
                        {{ count($rekapData[$bab]['kolom']) }}
                    </span>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Info BAB aktif --}}
        <div class="px-3 py-2 d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:var(--clr-primary-light);border-bottom:1px solid var(--border-color);">
            <small id="infoBab" style="color:var(--clr-primary);font-weight:600;"></small>
            <small id="infoPaginasi" style="color:var(--txt-secondary);"></small>
        </div>

        {{-- Tabel --}}
        <div class="table-responsive" id="wrapperTabel">
            <table class="table table-hover align-middle mb-0 rekap-tabel" id="rekapTabel">
                <thead id="theadRekap" style="background:var(--bg-muted);"></thead>
                <tbody id="tbodyRekap"></tbody>
                <tfoot id="tfootRekap" style="background:var(--bg-muted);border-top:2px solid var(--border-color);"></tfoot>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2 flex-wrap gap-2"
             style="border-top:1px solid var(--border-color);">
            <div class="d-flex align-items-center gap-2">
                <small style="color:var(--txt-secondary);">Baris per halaman:</small>
                <select id="perPageSelect" class="form-select form-select-sm" style="width:70px;font-size:0.8rem;">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginasiList"></ul>
            </nav>
        </div>

        {{-- Legenda --}}
        <div class="p-3" style="border-top:1px solid var(--border-color);">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span style="font-size:0.72rem;color:var(--txt-secondary);font-weight:600;">Keterangan:</span>
                <span class="badge" style="background:#d1fae5;color:#065f46;font-size:0.68rem;">85–100 Sangat Baik (A)</span>
                <span class="badge" style="background:#dbeafe;color:#1e40af;font-size:0.68rem;">75–84 Baik (B)</span>
                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:0.68rem;">60–74 Cukup (C)</span>
                <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:0.68rem;">&lt;60 Perlu Bimbingan (D)</span>
                <span style="font-size:0.68rem;color:var(--txt-tertiary);">— = belum mengerjakan (dihitung 0)</span>
            </div>
        </div>
    </div>

    {{-- JSON data untuk JS --}}
    <script>
    const REKAP_DATA = @json($rekapData);
    </script>

    @endif

@else
<div class="card">
    <div class="card-body empty-state">
        <div class="empty-state-icon"><i class="fas fa-filter"></i></div>
        <h6>Pilih Mata Pelajaran dan Kelas</h6>
        <p>Gunakan filter di atas untuk menampilkan rekap nilai siswa.</p>
    </div>
</div>
@endif

@push('styles')
<style>
.accordion-button:not(.collapsed) { box-shadow: none; }
.accordion-button:focus { box-shadow: none; }

.rekap-tabel thead th {
    border-bottom: 2px solid var(--border-color);
    padding: 0.65rem 0.75rem;
    white-space: nowrap;
}
.rekap-tabel tbody td {
    padding: 0.6rem 0.75rem;
    vertical-align: middle;
}
.rekap-tabel tbody tr:hover {
    background: var(--clr-primary-light);
}

/* Sticky kolom nama — tiap tabel sendiri */
.rekap-tabel thead th:nth-child(2),
.rekap-tabel tbody td:nth-child(2),
.rekap-tabel tfoot td:nth-child(1) {
    position: sticky;
    left: 0;
    background: #fff;
    z-index: 1;
    box-shadow: 2px 0 4px rgba(0,0,0,0.06);
}
.rekap-tabel thead th:nth-child(2) { background: var(--bg-muted); }
.rekap-tabel tbody tr:hover td:nth-child(2) { background: var(--clr-primary-light); }
</style>
@endpush

@push('scripts')
<script>
// ── Filter mapel auto-submit ──────────────────────────────────────
document.getElementById('pilihMapel')?.addEventListener('change', function () {
    const sel = this.options[this.selectedIndex];
    document.getElementById('hiddenKelasId').value = sel.dataset.kelas || '';
    this.form.submit();
});

// ── Rekap tabel: tab BAB + pagination ────────────────────────────
if (typeof REKAP_DATA !== 'undefined') {

    const LABEL_COLOR = {
        A: { bg: '#d1fae5', text: '#065f46' },
        B: { bg: '#dbeafe', text: '#1e40af' },
        C: { bg: '#fef3c7', text: '#92400e' },
        D: { bg: '#fee2e2', text: '#991b1b' },
    };

    function getLabel(val) {
        if (val === null) return null;
        if (val >= 85) return 'A';
        if (val >= 75) return 'B';
        if (val >= 60) return 'C';
        return 'D';
    }

    function getKategoriHtml(val) {
        if (val === null) return `<span class="badge rounded-pill" style="background:var(--bg-muted);color:var(--txt-secondary);border:1px solid var(--border-color);font-size:0.72rem;">Belum Ada Nilai</span>`;
        if (val >= 85)   return `<span class="badge rounded-pill" style="background:#d1fae5;color:#065f46;font-size:0.72rem;padding:0.4em 0.8em;"><i class="fas fa-star me-1"></i>Sangat Baik</span>`;
        if (val >= 75)   return `<span class="badge rounded-pill" style="background:#dbeafe;color:#1e40af;font-size:0.72rem;padding:0.4em 0.8em;"><i class="fas fa-thumbs-up me-1"></i>Baik</span>`;
        if (val >= 60)   return `<span class="badge rounded-pill" style="background:#fef3c7;color:#92400e;font-size:0.72rem;padding:0.4em 0.8em;"><i class="fas fa-minus-circle me-1"></i>Cukup</span>`;
        return `<span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:0.72rem;padding:0.4em 0.8em;"><i class="fas fa-book-reader me-1"></i>Perlu Bimbingan</span>`;
    }

    let activeBab   = Object.keys(REKAP_DATA)[0];
    let currentPage = 1;
    let perPage     = 10;
    let searchQuery = '';

    function filteredSiswa() {
        const rows = REKAP_DATA[activeBab].siswa;
        if (!searchQuery) return rows;
        return rows.filter(r => r.nama.toLowerCase().includes(searchQuery));
    }

    function renderThead() {
        const d = REKAP_DATA[activeBab];
        let th = `<tr style="background:var(--bg-muted);">
            <th class="ps-3" style="width:40px;font-size:0.78rem;">No</th>
            <th style="min-width:130px;font-size:0.78rem;">Siswa</th>`;
        d.kolom.forEach(k => {
            th += `<th class="text-center" style="min-width:90px;">
                <div class="text-truncate fw-semibold" style="max-width:85px;font-size:0.72rem;color:var(--txt-primary);" title="${k.judul_full}">${k.judul}</div>
                <span class="badge mt-1" style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.58rem;">#${k.urutan}</span>
            </th>`;
        });
        th += `<th class="text-center" style="min-width:90px;font-size:0.78rem;">Nilai BAB</th>
               <th class="text-center" style="min-width:100px;font-size:0.78rem;background:#fef3c7;border-left:2px solid #f59e0b;">
                   <div style="color:#92400e;font-weight:700;">Nilai Akhir</div>
                   <div style="font-size:0.6rem;color:#92400e;font-weight:500;">Semua BAB</div>
               </th>
               <th class="text-center pe-3" style="min-width:110px;font-size:0.78rem;">Kategori</th></tr>`;
        document.getElementById('theadRekap').innerHTML = th;
    }

    function renderTbody() {
        const d    = REKAP_DATA[activeBab];
        const rows = filteredSiswa();
        const total = rows.length;
        const totalPage = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > totalPage) currentPage = totalPage;

        const slice = rows.slice((currentPage - 1) * perPage, currentPage * perPage);

        let html = '';
        slice.forEach((s, idx) => {
            const globalNo = (currentPage - 1) * perPage + idx + 1;

            // nilai per kolom
            let tdNilai = '';
            d.kolom.forEach(k => {
                const n = s.nilai[k.id] ?? null;
                if (n !== null) {
                    const bg = n >= 75 ? '#d1fae5' : (n >= 60 ? '#fef3c7' : '#fee2e2');
                    const tx = n >= 75 ? '#065f46' : (n >= 60 ? '#92400e' : '#991b1b');
                    tdNilai += `<td class="text-center"><span class="badge rounded-pill fw-bold" style="background:${bg};color:${tx};font-size:0.78rem;padding:0.35em 0.7em;">${Math.round(n)}</span></td>`;
                } else {
                    tdNilai += `<td class="text-center"><span style="color:var(--txt-tertiary);font-size:0.85rem;">—</span></td>`;
                }
            });

            // nilai akhir BAB ini
            let tdAkhirBab = '';
            if (s.nilai_akhir !== null) {
                const labelB = getLabel(s.nilai_akhir);
                const lcB    = LABEL_COLOR[labelB];
                const warnaB = s.nilai_akhir >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)';
                tdAkhirBab = `<td class="text-center"><div class="d-flex align-items-center justify-content-center gap-1">
                    <span class="fw-bold" style="font-size:0.95rem;color:${warnaB};">${s.nilai_akhir}</span>
                    <span class="badge fw-bold" style="background:${lcB.bg};color:${lcB.text};font-size:0.72rem;">${labelB}</span>
                </div></td>`;
            } else {
                tdAkhirBab = `<td class="text-center"><span style="color:var(--txt-tertiary);">—</span></td>`;
            }

            // nilai akhir total (semua bab) — kolom tetap
            const vt     = s.nilai_akhir_total;
            const labelT = getLabel(vt);
            const lcT    = labelT ? LABEL_COLOR[labelT] : null;
            let tdAkhirTotal = '';
            if (vt !== null && lcT) {
                const warnaT = vt >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)';
                tdAkhirTotal = `<td class="text-center" style="background:#fffbeb;border-left:2px solid #f59e0b;">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <span class="fw-bold" style="font-size:0.95rem;color:${warnaT};">${vt}</span>
                        <span class="badge fw-bold" style="background:${lcT.bg};color:${lcT.text};font-size:0.72rem;">${labelT}</span>
                    </div>
                </td>`;
            } else {
                tdAkhirTotal = `<td class="text-center" style="background:#fffbeb;border-left:2px solid #f59e0b;"><span style="color:var(--txt-tertiary);">—</span></td>`;
            }

            html += `<tr>
                <td class="ps-3" style="color:var(--txt-secondary);font-size:0.78rem;">${globalNo}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                             style="width:32px;height:32px;min-width:32px;background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.8rem;">
                            ${s.inisial}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.82rem;color:var(--txt-primary);line-height:1.3;">${s.nama}</div>
                            <div style="font-size:0.68rem;color:var(--txt-secondary);">${s.nis}</div>
                        </div>
                    </div>
                </td>
                ${tdNilai}
                ${tdAkhirBab}
                ${tdAkhirTotal}
                <td class="text-center pe-3">${getKategoriHtml(vt)}</td>
            </tr>`;
        });

        document.getElementById('tbodyRekap').innerHTML = html || `<tr><td colspan="99" class="text-center py-4" style="color:var(--txt-tertiary);">Tidak ada siswa ditemukan.</td></tr>`;

        // update info
        document.getElementById('infoPaginasi').textContent =
            `Menampilkan ${slice.length} dari ${total} siswa`;

        renderTfoot();
        renderPaginasi(totalPage);
    }

    function renderTfoot() {
        const d = REKAP_DATA[activeBab];
        let td = `<tr><td class="ps-3" colspan="2" style="font-size:0.78rem;font-weight:700;color:var(--txt-secondary);">Rata Kelas</td>`;
        d.kolom.forEach(k => {
            const warna = k.rata >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)';
            td += `<td class="text-center"><span class="fw-bold" style="font-size:0.82rem;color:${warna};">${k.rata}</span></td>`;
        });
        const warnaB = d.rata_kelas >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)';
        const warnaT = d.rata_kelas_total >= 75 ? 'var(--clr-success)' : 'var(--clr-danger)';
        td += `<td class="text-center"><span class="fw-bold" style="font-size:0.82rem;color:${warnaB};">${d.rata_kelas}</span></td>
               <td class="text-center" style="background:#fffbeb;border-left:2px solid #f59e0b;">
                   <span class="fw-bold" style="font-size:0.82rem;color:${warnaT};">${d.rata_kelas_total}</span>
               </td>
               <td></td></tr>`;
        document.getElementById('tfootRekap').innerHTML = td;
    }

    function renderPaginasi(totalPage) {
        const ul = document.getElementById('paginasiList');
        let html = '';

        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">
                <i class="fas fa-chevron-left" style="font-size:0.7rem;"></i>
            </a></li>`;

        for (let p = 1; p <= totalPage; p++) {
            if (totalPage > 7 && p > 2 && p < totalPage - 1 && Math.abs(p - currentPage) > 1) {
                if (p === 3 || p === totalPage - 2) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                continue;
            }
            html += `<li class="page-item ${p === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${p}" style="${p === currentPage ? 'background:var(--clr-primary);border-color:var(--clr-primary);' : ''}">${p}</a>
            </li>`;
        }

        html += `<li class="page-item ${currentPage === totalPage ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
            </a></li>`;

        ul.innerHTML = html;

        ul.querySelectorAll('a[data-page]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const p = parseInt(a.dataset.page);
                const total = Math.ceil(filteredSiswa().length / perPage);
                if (p >= 1 && p <= total) { currentPage = p; renderTbody(); }
            });
        });
    }

    function updateInfoBab() {
        const d = REKAP_DATA[activeBab];
        document.getElementById('infoBab').innerHTML =
            `<i class="fas fa-book-open me-1"></i>BAB ${activeBab} &nbsp;·&nbsp; ${d.kolom.length} tantangan &nbsp;·&nbsp; Rata Kelas: <strong>${d.rata_kelas}</strong>`;
    }

    function renderAll() {
        renderThead();
        renderTbody();
        updateInfoBab();
        // set min-width tabel sesuai jumlah kolom
        const kolom = REKAP_DATA[activeBab].kolom.length;
        document.getElementById('rekapTabel').style.minWidth = (320 + kolom * 100) + 'px';
    }

    // ── Tab klik ──────────────────────────────────────────────────
    document.querySelectorAll('.tab-bab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            activeBab   = btn.dataset.bab;
            currentPage = 1;
            searchQuery = document.getElementById('cariSiswa').value.toLowerCase();

            document.querySelectorAll('.tab-bab-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-light');
                b.querySelector('.badge').style.background = 'var(--clr-primary-light)';
                b.querySelector('.badge').style.color = 'var(--clr-primary)';
            });
            btn.classList.add('btn-primary');
            btn.classList.remove('btn-light');
            btn.querySelector('.badge').style.background = 'rgba(255,255,255,0.3)';
            btn.querySelector('.badge').style.color = '#fff';

            renderAll();
        });
    });

    // ── Search ────────────────────────────────────────────────────
    document.getElementById('cariSiswa')?.addEventListener('input', function () {
        searchQuery = this.value.toLowerCase();
        currentPage = 1;
        renderTbody();
    });

    // ── Per page ──────────────────────────────────────────────────
    document.getElementById('perPageSelect')?.addEventListener('change', function () {
        perPage     = parseInt(this.value);
        currentPage = 1;
        renderTbody();
    });

    // ── Init ──────────────────────────────────────────────────────
    renderAll();
}
</script>
@endpush

@endsection