<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tantangan;
use App\Models\Materi;
use App\Models\NilaiTantangan;
use App\Models\Leaderboard;
use App\Models\SiswaKelas;
use App\Models\JawabanSiswa;
use Parsedown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\BadgeService;
use App\Services\PointCalculationService;
use App\Models\SiswaBadge;
use App\Models\Badge;
use App\Models\MateriSelesai;

class SiswaController extends Controller
{
    public function dashboard(Request $request)
    {
        $siswaId = auth()->id();
        $kelasId = auth()->user()->kelasIds()->first();

        $selectedMapel = $request->mapel;

        $tantanganAktif = Tantangan::where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('mapel_id', $selectedMapel);
            })
            ->with([
                'mapel',
                'guru',
                'nilaiTantangan' => fn($q) => $q->where('siswa_id', $siswaId)
            ])
            ->withCount('soal')
            ->latest()
            ->limit(5)
            ->get();

        $totalPoin = DB::table('nilai_tantangan')
            ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
            ->where('nilai_tantangan.siswa_id', $siswaId)
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('tantangan.mapel_id', $selectedMapel);
            })
            ->sum('nilai_tantangan.poin_didapat');

        $leaderboardData = DB::table('nilai_tantangan')
            ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
            ->join('siswa_kelas', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
            ->where('siswa_kelas.kelas_id', $kelasId)
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('tantangan.mapel_id', $selectedMapel);
            })
            ->select(
                'nilai_tantangan.siswa_id',
                DB::raw('SUM(nilai_tantangan.poin_didapat) as total_poin'),
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, nilai_tantangan.created_at, nilai_tantangan.waktu_submit)) as total_waktu')
            )
            ->groupBy('nilai_tantangan.siswa_id')
            ->orderByDesc('total_poin')
            ->orderBy('total_waktu')
            ->get();

        $rankIndex = $leaderboardData->search(fn($item) => $item->siswa_id == $siswaId);
        $rankKelas = $rankIndex !== false ? $rankIndex + 1 : '-';

        $mapelIds = DB::table('tantangan')
            ->where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->pluck('mapel_id')
            ->unique();

        $nilaiAkhirGlobal = DB::table('nilai_tantangan')
            ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
            ->where('nilai_tantangan.siswa_id', $siswaId)
            ->where('tantangan.kelas_id', $kelasId)
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('tantangan.mapel_id', $selectedMapel);
            })
            ->avg('nilai_tantangan.total_nilai');

        $nilaiAkhirGlobal = round($nilaiAkhirGlobal ?? 0);

        $statsPerMapel = \App\Models\Mapel::whereIn('id', $mapelIds)
            ->get()
            ->map(function ($mapel) use ($kelasId, $siswaId) {
                $totalTantangan = Tantangan::where('kelas_id', $kelasId)
                    ->where('mapel_id', $mapel->id)
                    ->where('status', 'published')
                    ->count();

                $selesai = DB::table('nilai_tantangan')
                    ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
                    ->where('nilai_tantangan.siswa_id', $siswaId)
                    ->where('tantangan.mapel_id', $mapel->id)
                    ->where('tantangan.kelas_id', $kelasId)
                    ->count();

                $poinMapel = DB::table('nilai_tantangan')
                    ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
                    ->where('nilai_tantangan.siswa_id', $siswaId)
                    ->where('tantangan.mapel_id', $mapel->id)
                    ->where('tantangan.kelas_id', $kelasId)
                    ->sum('nilai_tantangan.poin_didapat');

                $rataMapel = DB::table('nilai_tantangan')
                    ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
                    ->where('nilai_tantangan.siswa_id', $siswaId)
                    ->where('tantangan.mapel_id', $mapel->id)
                    ->where('tantangan.kelas_id', $kelasId)
                    ->avg('nilai_tantangan.total_nilai');
                $rataMapel = round($rataMapel ?? 0);

                $belumAktif = Tantangan::where('kelas_id', $kelasId)
                    ->where('mapel_id', $mapel->id)
                    ->where('status', 'published')
                    ->where('batas_waktu', '>', now())
                    ->whereDoesntHave('nilaiTantangan', fn($q) => $q->where('siswa_id', $siswaId))
                    ->count();

                $nilaiAkhir = $rataMapel;

                if ($nilaiAkhir >= 85)      $labelNilai = 'A';
                elseif ($nilaiAkhir >= 75)  $labelNilai = 'B';
                elseif ($nilaiAkhir >= 60)  $labelNilai = 'C';
                else                         $labelNilai = 'D';

                $babProgress = Tantangan::where('kelas_id', $kelasId)
                    ->where('mapel_id', $mapel->id)
                    ->where('status', 'published')
                    ->whereNotNull('bab')
                    ->get()
                    ->groupBy('bab')
                    ->map(function ($items) use ($siswaId) {
                        $total   = $items->count();
                        $selesai = $items->filter(fn($t) =>
                            \App\Models\NilaiTantangan::where('siswa_id', $siswaId)
                                ->where('tantangan_id', $t->id)->exists()
                        )->count();
                        return ['total' => $total, 'selesai' => $selesai,
                                'persen' => $total > 0 ? round(($selesai/$total)*100) : 0];
                    });

                $babMateriProgress = \App\Models\Materi::where('kelas_id', $kelasId)
                    ->where('mapel_id', $mapel->id)
                    ->get()
                    ->groupBy(fn($m) => $m->bab ?? $m->level_required ?? 1)
                    ->map(function ($items) use ($siswaId) {
                        $total   = $items->count();
                        $selesai = $items->filter(fn($m) =>
                            \App\Models\MateriSelesai::where('siswa_id', $siswaId)
                                ->where('materi_id', $m->id)->exists()
                        )->count();
                        return ['total' => $total, 'selesai' => $selesai,
                                'persen' => $total > 0 ? round(($selesai/$total)*100) : 0];
                    });

                return [
                    'nama_mapel'          => $mapel->nama_mapel,
                    'mapel_id'            => $mapel->id,
                    'total_tantangan'     => $totalTantangan,
                    'selesai'             => $selesai,
                    'belum_aktif'         => $belumAktif,
                    'poin'                => $poinMapel,
                    'rata_nilai'          => $rataMapel,
                    'nilai_akhir'         => $nilaiAkhir,
                    'label_nilai'         => $labelNilai,
                    'persen'              => $totalTantangan > 0 ? round(($selesai / $totalTantangan) * 100) : 0,
                    'bab_progress'        => $babProgress,
                    'bab_materi_progress' => $babMateriProgress,
                ];
            });

        if ($nilaiAkhirGlobal >= 85)     $labelNilaiGlobal = 'A';
        elseif ($nilaiAkhirGlobal >= 75) $labelNilaiGlobal = 'B';
        elseif ($nilaiAkhirGlobal >= 60) $labelNilaiGlobal = 'C';
        else                              $labelNilaiGlobal = 'D';

        $totalTantanganSelesai = DB::table('nilai_tantangan')
            ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
            ->where('nilai_tantangan.siswa_id', $siswaId)
            ->where('tantangan.kelas_id', $kelasId)
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('tantangan.mapel_id', $selectedMapel);
            })
            ->count();

        $totalMateriSelesai = DB::table('materi_selesai')
            ->join('materi', 'materi_selesai.materi_id', '=', 'materi.id')
            ->where('materi_selesai.siswa_id', $siswaId)
            ->where('materi.kelas_id', $kelasId)
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('materi.mapel_id', $selectedMapel);
            })
            ->count();

        $totalBelumSelesai = Tantangan::where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('mapel_id', $selectedMapel);
            })
            ->whereDoesntHave('nilaiTantangan', function ($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            })
            ->count();

        $totalTantanganTersedia = Tantangan::where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('mapel_id', $selectedMapel);
            })
            ->count();

        $totalMateriTersedia = \App\Models\Materi::where('kelas_id', $kelasId)
            ->when($selectedMapel, function ($q) use ($selectedMapel) {
                $q->where('mapel_id', $selectedMapel);
            })
            ->count();

        $persenTantangan = $totalTantanganTersedia > 0 ? round(($totalTantanganSelesai / $totalTantanganTersedia) * 100) : 0;
        $persenMateri    = $totalMateriTersedia > 0 ? round(($totalMateriSelesai / $totalMateriTersedia) * 100) : 0;

        return view('siswa.dashboard', compact(
            'tantanganAktif',
            'totalPoin',
            'rankKelas',
            'statsPerMapel',
            'nilaiAkhirGlobal',
            'labelNilaiGlobal',
            'totalTantanganSelesai',
            'totalMateriSelesai',
            'totalBelumSelesai',
            'totalTantanganTersedia',
            'totalMateriTersedia',
            'persenTantangan',
            'persenMateri',
            'selectedMapel'
        ));
    }

    public function materi(Request $request)
    {
        $kelasId    = auth()->user()->kelasIds()->first();
        // ✅ FIX BUG #3 & #4: Kirim $kelasId ke hitungLevel() agar tidak lintas mapel/kelas
        $levelSiswa = auth()->user()->hitungLevel($kelasId);
        $siswaId    = auth()->id();
        $mapels     = \App\Models\Mapel::all();

        $materis = Materi::with('mapel', 'guru', 'kelas')
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($request->mapel, fn($q) => $q->where('mapel_id', $request->mapel))
            ->orderBy('bab')
            ->orderBy('id')
            ->paginate(8)
            ->withQueryString();

        $materis->getCollection()->transform(function ($m) use ($levelSiswa, $siswaId) {
            $bab = $m->bab ?? $m->level_required ?? 1;
            $m->bab_display = $bab;
            // Materi tidak dikunci — siswa bebas baca materi kapanpun
            $m->is_locked = false;
            return $m;
        });

        $selesaiIds = \App\Models\MateriSelesai::where('siswa_id', $siswaId)
            ->pluck('materi_id')->toArray();

        $totalMateri  = Materi::where('kelas_id', $kelasId)->count();
        $totalSelesai = \App\Models\MateriSelesai::where('siswa_id', $siswaId)
            ->whereHas('materi', fn($q) => $q->where('kelas_id', $kelasId))
            ->count();

        $materiPerBab = Materi::where('kelas_id', $kelasId)
            ->when($request->mapel, fn($q) => $q->where('mapel_id', $request->mapel))
            ->orderBy('bab')->orderBy('id')
            ->get()
            ->groupBy(fn($m) => $m->bab ?? $m->level_required ?? 1);

        $babMateriProgress = $materiPerBab->map(function ($items) use ($selesaiIds) {
            $total   = $items->count();
            $selesai = $items->filter(fn($m) => in_array($m->id, $selesaiIds))->count();
            return [
                'total'         => $total,
                'selesai'       => $selesai,
                'persen'        => $total > 0 ? round(($selesai / $total) * 100) : 0,
                'selesai_semua' => $selesai >= $total,
            ];
        });

        return view('siswa.materi.index', compact(
            'materis', 'kelasId', 'mapels', 'selesaiIds',
            'totalMateri', 'totalSelesai', 'levelSiswa', 'babMateriProgress'
        ));
    }

    public function tantangan(Request $request)
    {
        $siswaId = auth()->id();
        $siswa   = auth()->user();
        $kelasId = $siswa->kelasIds()->first();
        $mapelId = $request->mapel;
 
        $levelSiswa = $siswa->hitungLevel($kelasId);
 
        $query = Tantangan::where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->with([
                'mapel',
                'guru',
                'nilaiTantangan' => fn($q) => $q->where('siswa_id', $siswaId),
                'remedial',    // ← relasi baru
                'pengayaan',   // ← tetap ada
            ])
            ->withCount('soal')
            ->orderBy('bab')
            ->orderBy('urutan');
 
        if ($mapelId) $query->where('mapel_id', $mapelId);
 
        $tantangans = $query->get();
 
        $diffConfig = Tantangan::difficultyConfig();
        $levelReq   = Tantangan::levelRequired();
 
        $tantangans->transform(function ($t) use ($siswaId, $levelSiswa, $diffConfig, $levelReq) {
            $t->is_locked      = $t->isLockedFor($siswaId);
            $t->is_bab_locked  = $t->isBabLockedFor($levelSiswa);
            $t->diff_config    = $diffConfig[$t->difficulty] ?? $diffConfig['easy'];
            $t->level_required = $levelReq[$t->difficulty] ?? 1;
            $nilai             = $t->nilaiTantangan->first();
            $t->poin_didapat_siswa = $nilai ? (int) $nilai->poin_didapat : 0;
            $t->nilai_didapat      = $nilai ? round($nilai->total_nilai) : null;
 
            // Flag: apakah siswa butuh remedial untuk task ini?
            $t->butuh_remedial = (!$t->is_remedial && !$t->is_pengayaan)
                ? $t->butuhRemedialFor($siswaId)
                : false;
 
            return $t;
        });
 
        // ── PISAHKAN: remedial | pengayaan | reguler ──────────────────
        $remedialAll  = $tantangans->where('is_remedial', 1);
        $pengayaanAll = $tantangans->where('is_pengayaan', 1);
        $regulerAll   = $tantangans->where('is_remedial', 0)->where('is_pengayaan', 0);
 
        // Remedial per bab (key = bab integer)
        $remedialPerBab  = $remedialAll->groupBy(fn($t) => (int)($t->bab ?? 0));
 
        // Pengayaan per bab — hanya muncul jika SEMUA task bab selesai
        // (filter dilakukan saat build $babProgress di bawah)
        $pengayaanPerBabRaw = $pengayaanAll->groupBy(fn($t) => (int)($t->bab ?? 0));
 
        // Grouping hanya dari tantangan reguler
        $groupedByBab = $regulerAll->groupBy(function ($t) {
            $tipe   = $t->tipe ?? 'reguler';
            $babVal = (string) ($t->bab ?? '');
            if ($tipe === 'uts' || ($babVal === '' && stripos($t->judul, 'UTS') !== false)) return '__uts__';
            if ($tipe === 'uas' || ($babVal === '' && stripos($t->judul, 'UAS') !== false)) return '__uas__';
            return is_numeric($babVal) ? (int) $babVal : 0;
        });
 
        $groupedByBab = $groupedByBab->sortBy(function ($items, $key) {
            if ($key === '__uts__') return 3.5; // setelah BAB 3, sebelum BAB 4
            if ($key === '__uas__') return 7.5; // setelah BAB 7, paling akhir
            return (int) $key;
        });
 
        // ── BOBOT NILAI AKHIR ───────────────────────────────────────────
        // UTS 30% | UAS 40% | Tugas Harian 30%
        $bobotConfig = [
            'harian' => 30,
            'uts'    => 30,
            'uas'    => 40,
        ];
 
        // Hitung nilai per komponen untuk siswa ini
        $nilaiHarian = \App\Models\NilaiTantangan::whereHas('tantangan', function ($q) use ($kelasId, $mapelId) {
            $q->where('kelas_id', $kelasId)
              ->where('status', 'published')
              ->where(fn($qq) => $qq->whereNull('tipe')->orWhere('tipe', 'reguler'))
              ->whereNot('is_remedial', 1)
              ->whereNot('is_pengayaan', 1)
              ->when($mapelId, fn($qq) => $qq->where('mapel_id', $mapelId));
        })->where('siswa_id', $siswaId)->avg('total_nilai') ?? 0;
 
        $nilaiUTS = \App\Models\NilaiTantangan::whereHas('tantangan', function ($q) use ($kelasId, $mapelId) {
            $q->where('kelas_id', $kelasId)
              ->where('tipe', 'uts')
              ->when($mapelId, fn($qq) => $qq->where('mapel_id', $mapelId));
        })->where('siswa_id', $siswaId)->avg('total_nilai') ?? 0;
 
        $nilaiUAS = \App\Models\NilaiTantangan::whereHas('tantangan', function ($q) use ($kelasId, $mapelId) {
            $q->where('kelas_id', $kelasId)
              ->where('tipe', 'uas')
              ->when($mapelId, fn($qq) => $qq->where('mapel_id', $mapelId));
        })->where('siswa_id', $siswaId)->avg('total_nilai') ?? 0;
 
        $nilaiAkhir = round(
            ($nilaiHarian * $bobotConfig['harian'] / 100) +
            ($nilaiUTS    * $bobotConfig['uts']    / 100) +
            ($nilaiUAS    * $bobotConfig['uas']    / 100)
        );
 
        // ── PROGRESS PER BAB ────────────────────────────────────────────
        $babProgress = $groupedByBab->map(function ($items) use ($siswaId, $pengayaanPerBabRaw) {
            $total     = $items->count();
            $selesai   = $items->filter(fn($t) => $t->nilaiTantangan->isNotEmpty())->count();
 
            // Task yang butuh remedial (expired ATAU nilai < 60)
            $butuhRemedial = $items->filter(fn($t) => $t->butuh_remedial)->count();
 
            // Nilai rata-rata task yang sudah dikerjakan
            $nilaiRata = $items->filter(fn($t) => $t->nilai_didapat !== null)
                ->avg('nilai_didapat') ?? 0;
 
            $totalPoin = $items->sum('poin');
            $dapatPoin = $items->sum('poin_didapat_siswa');
 
            // Pengayaan hanya tampil jika SEMUA task selesai (tidak ada yang butuh remedial)
            $selesaiSemua  = $total > 0 && $selesai >= $total;
            $tampilPengayaan = $selesaiSemua && $butuhRemedial === 0;
 
            return [
                'total'            => $total,
                'selesai'          => $selesai,
                'butuh_remedial'   => $butuhRemedial,   // ← gantikan 'expired'
                'ada_remedial'     => $butuhRemedial > 0,
                'tampil_pengayaan' => $tampilPengayaan, // ← pengayaan hanya jika semua tuntas
                'nilai_rata'       => round($nilaiRata),
                'persen'           => $total > 0 ? round(($selesai / $total) * 100) : 0,
                'selesai_semua'    => $selesaiSemua,
                'total_poin'       => $totalPoin,
                'didapat_poin'     => $dapatPoin,
            ];
        });
 
        // Filter pengayaan per bab: hanya tampil jika babProgress['tampil_pengayaan'] = true
        $pengayaanPerBab = collect();
        foreach ($pengayaanPerBabRaw as $babInt => $items) {
            $progress = $babProgress->get($babInt);
            if ($progress && $progress['tampil_pengayaan']) {
                $pengayaanPerBab[$babInt] = $items;
            }
        }
 
        // Filter remedial per bab: hanya tampil jika ada task butuh remedial
        $remedialPerBabFinal = collect();
        foreach ($remedialPerBab as $babInt => $items) {
            $progress = $babProgress->get($babInt);
            if ($progress && $progress['ada_remedial']) {
                $remedialPerBabFinal[$babInt] = $items;
            }
        }
 
        $mapels = DB::table('mapel')->get();
 
        return view('siswa.tantangan.index', compact(
            'groupedByBab',
            'babProgress',
            'pengayaanPerBab',     // pengayaan (semua tuntas + nilai >= 60)
            'remedialPerBabFinal', // remedial (expired ATAU nilai < 60)
            'mapels',
            'mapelId',
            'levelSiswa',
            'nilaiAkhir',          // nilai akhir berbobot
            'bobotConfig',         // untuk ditampilkan di view
            'nilaiHarian',
            'nilaiUTS',
            'nilaiUAS',
        ));
    }
 

    public function kerjakan(Tantangan $tantangan)
    {
        $siswa      = auth()->user();
        $kelasId    = $siswa->kelasIds()->first();

        // ✅ FIX BUG #3 & #4: Kirim $kelasId agar level tidak inflate lintas mapel
        $levelSiswa = $siswa->hitungLevel($kelasId);

        if ($tantangan->status !== 'published' || $tantangan->kelas_id != $kelasId) {
            return redirect()->route('siswa.tantangan')->with('error', 'Tantangan tidak tersedia.');
        }

        // Pengayaan: lewati cek deadline, level, bab
        if (!$tantangan->is_pengayaan) {

            if ($tantangan->batas_waktu && $tantangan->batas_waktu <= now()) {
                // Tantangan expired — cek apakah ada pengayaan sebagai jalan remedial
                $pengayaan = $tantangan->pengayaan;
                if ($pengayaan) {
                    $sudahKerjakanPengayaan = NilaiTantangan::where('siswa_id', auth()->id())
                        ->where('tantangan_id', $pengayaan->id)
                        ->exists();

                    if (!$sudahKerjakanPengayaan) {
                        return redirect()->route('siswa.tantangan.kerjakan', $pengayaan->id)
                            ->with('info', 'Tantangan sudah berakhir. Kerjakan pengayaan berikut untuk mengejar ketertinggalan!');
                    }
                }

                return redirect()->route('siswa.tantangan')
                    ->with('error', 'Tantangan sudah berakhir dan tidak ada pengayaan tersedia.');
            }

            if ($tantangan->isLockedFor(auth()->id())) {
                return redirect()->route('siswa.tantangan')
                    ->with('error', 'Selesaikan tantangan sebelumnya terlebih dahulu!');
            }

            if ($tantangan->isBabLockedFor($levelSiswa)) {
                return redirect()->route('siswa.tantangan')
                    ->with('error', 'Selesaikan minimal 3 tantangan di BAB sebelumnya untuk membuka BAB ini!');
            }
        }

        $sudahKerjakan = NilaiTantangan::where('siswa_id', auth()->id())
            ->where('tantangan_id', $tantangan->id)
            ->exists();

        if ($sudahKerjakan) {
            return redirect()->route('siswa.tantangan')->with('info', 'Tantangan sudah dikerjakan!');
        }

        $soals = $tantangan->soal()->get();

        return view('siswa.tantangan.kerjakan', compact('tantangan', 'soals'));
    }

    public function submit(Request $request, Tantangan $tantangan)
    {
        $siswa   = auth()->user();
        $kelasId = $siswa->kelasIds()->first();
        $mapelId = $tantangan->mapel_id;

        if (
            $tantangan->status !== 'published' ||
            $tantangan->kelas_id != $kelasId
        ) {
            return redirect()->route('siswa.tantangan')->with('error', 'Akses ditolak.');
        }

        $jawabans      = $request->input('jawaban', []);
        $nilaiOtomatis = 0;
        $soalOtomatis  = 0;
        $adaSoalManual = false;

        foreach ($tantangan->soal as $soal) {
            $jawabanSiswa = $jawabans[$soal->id] ?? '-';
            $nilai  = 0;
            $manual = 0;

            if ($soal->tipe == 'pg') {
                $nilai = strtoupper($jawabanSiswa) == strtoupper($soal->jawaban_benar) ? 100 : 0;
                $nilaiOtomatis += $nilai;
                $soalOtomatis++;

            } elseif ($soal->tipe == 'matching') {
                $pairsBenar   = json_decode($soal->matching_pairs, true) ?? [];
                $pairsJawaban = [];

                if (!empty($jawabanSiswa)) {
                    foreach (explode(',', $jawabanSiswa) as $item) {
                        $item = trim($item);
                        if (strpos($item, '-') !== false) {
                            [$kiri, $kanan] = explode('-', $item, 2);
                            $pairsJawaban[intval($kiri) - 1] = intval($kanan);
                        }
                    }
                }

                $benar = 0;
                foreach ($pairsBenar as $pair) {
                    $kiriKey    = isset($pair['kiri'])  ? intval($pair['kiri'])  : null;
                    $kananBenar = isset($pair['kanan']) ? intval($pair['kanan']) : null;
                    if ($kiriKey !== null && isset($pairsJawaban[$kiriKey]) && $pairsJawaban[$kiriKey] == $kananBenar) {
                        $benar++;
                    }
                }

                $jumlahPair     = count($pairsBenar);
                $nilai          = $jumlahPair > 0 ? ($benar / $jumlahPair) * 100 : 0;
                $nilaiOtomatis += $nilai;
                $soalOtomatis++;

            } else {
                $nilai         = null;
                $manual        = 1;
                $adaSoalManual = true;
            }

            JawabanSiswa::create([
                'siswa_id'       => auth()->id(),
                'tantangan_id'   => $tantangan->id,
                'soal_id'        => $soal->id,
                'jawaban'        => is_array($jawabanSiswa) ? json_encode($jawabanSiswa) : $jawabanSiswa,
                'nilai'          => $nilai,
                'dinilai_manual' => $manual,
            ]);
        }

        $isPending = $adaSoalManual;
        $totalSoal = $tantangan->soal->count();

        if ($isPending) {
            $rataNilai   = $totalSoal > 0 ? $nilaiOtomatis / $totalSoal : 0;
            $poinDidapat = 0;
        } else {
            $rataNilai   = $soalOtomatis > 0 ? $nilaiOtomatis / $soalOtomatis : 0;
            // ✅ FIX BUG #5: PointCalculationService sekarang mendukung chapter_* (lihat service).
            // Sebelumnya: chapter_* jatuh ke default multiplier 1.0 → poin sama semua bab.
            // Sekarang: chapter_1=1.0 s/d chapter_8=2.5 → bab lebih tinggi = poin lebih besar.
            $poinDidapat = PointCalculationService::calculatePoints(
                $tantangan->poin,
                (int) round($rataNilai),
                $tantangan->difficulty
            );
        }

        NilaiTantangan::create([
            'siswa_id'     => auth()->id(),
            'tantangan_id' => $tantangan->id,
            'total_nilai'  => $rataNilai,
            'poin_didapat' => $poinDidapat,
            'waktu_submit' => now(),
            'is_pending'   => $isPending,
        ]);

        if (!$isPending) {
            BadgeService::checkAll(auth()->id(), $tantangan->mapel_id);

            // ✅ FIX BUG #4: Simpan level ke DB setelah submit agar tidak dihitung ulang
            // di setiap request (menghilangkan N+1 query di halaman yang menampilkan banyak siswa).
            $newLevel = $siswa->hitungLevel($kelasId);
            if ($newLevel !== (int) $siswa->level) {
                $siswa->update(['level' => $newLevel]);
            }
        }

        $newBadges = SiswaBadge::with('badge')
            ->where('siswa_id', auth()->id())
            ->where('is_new', 1)
            ->latest()
            ->take(1)
            ->get();

        return redirect()->route('siswa.tantangan', ['mapel' => $mapelId])
            ->with('hasil', [
                'poin'       => $poinDidapat,
                'nilai'      => $rataNilai,
                'badges'     => $newBadges,
                'is_pending' => $isPending,
                'is_essay'   => ($totalSoal > 0 && $soalOtomatis == 0),
            ]);
    }

    public function profil()
    {
        $user = auth()->user();

        $kelas = DB::table('siswa_kelas')
            ->join('kelas', 'kelas.id', '=', 'siswa_kelas.kelas_id')
            ->where('siswa_kelas.siswa_id', $user->id)
            ->select('kelas.id', 'kelas.nama_kelas')
            ->first();

        $tantanganSelesai = DB::table('nilai_tantangan')
            ->where('siswa_id', $user->id)
            ->count();

        $totalPoin = DB::table('nilai_tantangan')
            ->where('siswa_id', $user->id)
            ->sum('poin_didapat');

        $rank = null;

        if ($kelas) {
            $leaderboard = DB::table('nilai_tantangan')
                ->join('siswa_kelas', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
                ->where('siswa_kelas.kelas_id', $kelas->id)
                ->select(
                    'nilai_tantangan.siswa_id',
                    DB::raw('SUM(nilai_tantangan.poin_didapat) as total_poin'),
                    DB::raw('SUM(TIMESTAMPDIFF(SECOND, nilai_tantangan.created_at, nilai_tantangan.waktu_submit)) as total_waktu')
                )
                ->groupBy('nilai_tantangan.siswa_id')
                ->orderByDesc('total_poin')
                ->orderBy('total_waktu')
                ->get();

            foreach ($leaderboard as $index => $item) {
                if ($item->siswa_id == $user->id) {
                    $rank = $index + 1;
                    break;
                }
            }
        }

        $materiSelesai = DB::table('materi_selesai')
            ->where('siswa_id', $user->id)
            ->count();

        // ✅ FIX BUG #3 & #4: Kirim $kelasId agar level tidak inflate lintas kelas
        $level = $user->hitungLevel($kelas->id ?? null);

        $badges = \App\Models\SiswaBadge::with('badge')
            ->where('siswa_id', $user->id)
            ->get()
            ->groupBy('badge_id');

        return view('siswa.profil', compact(
            'user', 'kelas', 'tantanganSelesai', 'materiSelesai',
            'totalPoin', 'rank', 'level', 'badges'
        ));
    }

    public function hasil($id)
    {
        $tantangan = Tantangan::with(['soal'])->findOrFail($id);
        $nilai = $tantangan->nilaiTantangan()->where('siswa_id', auth()->id())->firstOrFail();
        return view('siswa.tantangan.hasil', compact('tantangan', 'nilai'));
    }

    public function review($id)
    {
        $tantangan = Tantangan::with(['soal'])->findOrFail($id);
        $jawaban   = JawabanSiswa::where('siswa_id', auth()->id())
            ->where('tantangan_id', $id)->get();

        $nilai = NilaiTantangan::where('siswa_id', auth()->id())
            ->where('tantangan_id', $id)
            ->first();

        if (!$nilai) {
            return redirect()->route('siswa.tantangan')
                ->with('error', 'Data nilai tidak ditemukan.');
        }

        if ($nilai->review_dibuka_pada === null) {
            return view('siswa.tantangan.review-locked', compact('tantangan', 'nilai'));
        }

        return view('siswa.tantangan.review', compact('tantangan', 'jawaban'));
    }

    public function materiShow(Materi $materi)
    {
        $siswaId    = auth()->id();
        $kelasId    = auth()->user()->kelasIds()->first();
        // ✅ FIX BUG #3 & #4: Kirim $kelasId
        $levelSiswa = auth()->user()->hitungLevel($kelasId);

        $materi->load('mapel', 'guru', 'kelas');

        $sudahSelesai = MateriSelesai::where('siswa_id', Auth::id())
            ->where('materi_id', $materi->id)
            ->exists();

        return view('siswa.materi.show', compact('materi', 'sudahSelesai'));
    }

public function selesai(Materi $materi)
{
    $POIN_PER_MATERI = 10;
 
    // firstOrCreate: kalau sudah pernah ditandai, tidak diberi poin lagi
    $sudahAda = MateriSelesai::where('siswa_id', Auth::id())
        ->where('materi_id', $materi->id)
        ->exists();
 
    if ($sudahAda) {
        return back()->with('info', 'Materi sudah pernah ditandai selesai.');
    }
 
    MateriSelesai::create([
        'siswa_id'  => Auth::id(),
        'materi_id' => $materi->id,
        'poin'      => $POIN_PER_MATERI,
    ]);
 
    return back()->with('success', "Materi selesai! Kamu mendapat +{$POIN_PER_MATERI} poin.");
}

    public function badgeValidasi()
    {
        $siswaId  = auth()->id();
        $siswa    = auth()->user();
        $kelasId  = $siswa->kelasIds()->first();

        // ✅ FIX BUG #3 & #4: Kirim $kelasId
        $levelSiswa = $siswa->hitungLevel($kelasId);

        $badgeDimiliki = SiswaBadge::with('badge')
            ->where('siswa_id', $siswaId)
            ->latest()
            ->get();

        $mapelIds = DB::table('tantangan')
            ->where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->pluck('mapel_id')
            ->unique();

        $progressMapel = \App\Models\Mapel::whereIn('id', $mapelIds)
            ->get()
            ->map(function ($mapel) use ($kelasId, $siswaId) {
                $total = DB::table('tantangan')
                    ->where('mapel_id', $mapel->id)
                    ->where('kelas_id', $kelasId)
                    ->where('status', 'published')
                    ->count();

                $selesai = DB::table('nilai_tantangan')
                    ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
                    ->where('nilai_tantangan.siswa_id', $siswaId)
                    ->where('tantangan.mapel_id', $mapel->id)
                    ->where('tantangan.kelas_id', $kelasId)
                    ->count();

                return [
                    'nama_mapel' => $mapel->nama_mapel,
                    'total'      => $total,
                    'selesai'    => $selesai,
                ];
            });

        return view('siswa.badge-validasi', compact(
            'badgeDimiliki', 'levelSiswa', 'progressMapel'
        ));
    }
}