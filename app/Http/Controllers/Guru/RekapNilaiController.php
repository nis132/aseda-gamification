<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\NilaiTantangan;
use App\Models\User;
use App\Models\GuruMapelKelas;
use App\Exports\RekapNilaiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $guruId = Auth::id();

        $mengajar = GuruMapelKelas::with(['mapel', 'kelas'])
            ->where('guru_id', $guruId)
            ->whereNotNull('kelas_id')
            ->get();

        $selectedMapelId = $request->input('mapel_id');
        $selectedKelasId = $request->input('kelas_id');

        $tantanganList = collect();
        $siswaList     = collect();
        $nilaiMap      = [];
        $statistik     = [];

        if ($selectedMapelId && $selectedKelasId) {

            // ── Ambil tantangan: gabungkan dari tantangan.status='published'
            // DAN dari tantangan_kelas.status='published'
            // supaya tidak ada yang terlewat
            $tantanganDariTabel = Tantangan::where('mapel_id', $selectedMapelId)
                ->where('kelas_id', $selectedKelasId)
                ->where('status', 'published')
                ->orderBy('bab')
                ->orderBy('urutan')
                ->get();

            $tantanganDariKelas = Tantangan::whereHas('publishKelas', function ($q) use ($selectedKelasId) {
                    $q->where('kelas_id', $selectedKelasId)
                      ->where('status', 'published');
                })
                ->where('mapel_id', $selectedMapelId)
                ->orderBy('bab')
                ->orderBy('urutan')
                ->get();

            // Gabung dan deduplikasi by id
            $tantanganList = $tantanganDariTabel
                ->merge($tantanganDariKelas)
                ->unique('id')
                ->sortBy(['bab', 'urutan'])
                ->values();

            // ── Ambil siswa di kelas ini ──────────────────────────────
            $siswaIds = DB::table('siswa_kelas')
                ->where('kelas_id', $selectedKelasId)
                ->pluck('siswa_id');

            $siswaList = User::whereIn('id', $siswaIds)
                ->orderBy('nama')
                ->get();

            // ── Build nilaiMap[siswa_id][tantangan_id] ────────────────
            if ($tantanganList->isNotEmpty() && $siswaList->isNotEmpty()) {
                $tantanganIds = $tantanganList->pluck('id');

                $nilaiRaw = NilaiTantangan::whereIn('siswa_id', $siswaIds)
                    ->whereIn('tantangan_id', $tantanganIds)
                    ->get();

                foreach ($nilaiRaw as $n) {
                    $nilaiMap[$n->siswa_id][$n->tantangan_id] = $n->total_nilai;
                }

                // ── Statistik per tantangan ───────────────────────────
                foreach ($tantanganList as $t) {
                    $nilaiTantangan     = $nilaiRaw->where('tantangan_id', $t->id);
                    $jumlahMengumpulkan = $nilaiTantangan->count();
                    $rata               = $jumlahMengumpulkan > 0
                        ? round($nilaiTantangan->avg('total_nilai'), 1)
                        : 0;
                    $tuntas = $nilaiTantangan->where('total_nilai', '>=', 75)->count();

                    $statistik[$t->id] = [
                        'rata'               => $rata,
                        'mengumpulkan'       => $jumlahMengumpulkan,
                        'tuntas'             => $tuntas,
                        'belum_tuntas'       => $jumlahMengumpulkan - $tuntas,
                        'belum_mengumpulkan' => $siswaList->count() - $jumlahMengumpulkan,
                    ];
                }
            }
        }

        return view('guru.rekap.index', compact(
            'mengajar',
            'selectedMapelId',
            'selectedKelasId',
            'tantanganList',
            'siswaList',
            'nilaiMap',
            'statistik'
        ));
    }

    public function export(Request $request)
    {
        $guruId  = Auth::id();
        $mapelId = $request->input('mapel_id');
        $kelasId = $request->input('kelas_id');

        $gmk = GuruMapelKelas::with(['mapel', 'kelas'])
            ->where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->first();

        $namaMapel = $gmk?->mapel?->nama_mapel ?? 'Mapel';
        $namaKelas = $gmk?->kelas?->nama_kelas ?? 'Kelas';

        // Sama dengan index — gabung dua sumber
        $tantanganDariTabel = Tantangan::where('mapel_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->orderBy('bab')->orderBy('urutan')
            ->get();

        $tantanganDariKelas = Tantangan::whereHas('publishKelas', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId)->where('status', 'published');
            })
            ->where('mapel_id', $mapelId)
            ->orderBy('bab')->orderBy('urutan')
            ->get();

        $tantanganList = $tantanganDariTabel
            ->merge($tantanganDariKelas)
            ->unique('id')
            ->sortBy(['bab', 'urutan'])
            ->values();

        $siswaIds  = DB::table('siswa_kelas')->where('kelas_id', $kelasId)->pluck('siswa_id');
        $siswaList = User::whereIn('id', $siswaIds)->orderBy('nama')->get();

        $nilaiMap = [];
        if ($tantanganList->isNotEmpty() && $siswaList->isNotEmpty()) {
            $nilaiRaw = NilaiTantangan::whereIn('siswa_id', $siswaIds)
                ->whereIn('tantangan_id', $tantanganList->pluck('id'))
                ->get();
            foreach ($nilaiRaw as $n) {
                $nilaiMap[$n->siswa_id][$n->tantangan_id] = $n->total_nilai;
            }
        }

        $filename = "Rekap_Nilai_{$namaMapel}_{$namaKelas}_" . now()->format('Ymd') . ".xlsx";

        return Excel::download(
            new RekapNilaiExport($tantanganList, $siswaList, $nilaiMap, $namaKelas, $namaMapel),
            $filename
        );
    }
}