<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\Materi;
use App\Models\NilaiTantangan;
use App\Models\Leaderboard;
use App\Models\SiswaKelas;
use App\Models\JawabanSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\BadgeService;
use App\Models\SiswaBadge;
use App\Models\Badge;


class SiswaController extends Controller
{
    public function dashboard()
    {
        $kelasId = auth()->user()->kelasIds()->first();
        $tantanganAktif = Tantangan::where('kelas_id', $kelasId)
            ->where('batas_waktu', '>', now())
            ->with('mapel', 'guru')
            ->latest()
            ->limit(5)
            ->get();

        $totalPoin = Leaderboard::where('siswa_id', auth()->id())->sum('total_poin');
        $leaderboardData = Leaderboard::where('kelas_id', $kelasId)
            ->orderBy('total_poin', 'desc')
            ->get();
        $rankKelas = $leaderboardData->search(fn($item) => $item->siswa_id == auth()->id()) + 1 ?? '-';

        return view('siswa.dashboard', compact('tantanganAktif', 'totalPoin', 'rankKelas'));
    }

public function materi(Request $request)
{
    $kelasId = auth()->user()->kelasIds()->first();
    
    $materis = Materi::with('mapel', 'guru', 'kelas')
        ->when($kelasId, function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId); // 🔥 SIMPLE & AKURAT!
        })
        ->latest()
        ->paginate(8);

    return view('siswa.materi.index', compact('materis', 'kelasId'));
}



    public function tantangan()
    {
        $kelasId = auth()->user()->kelasIds()->first();
        $tantangans = Tantangan::where('kelas_id', $kelasId)
            ->with(['mapel', 'guru', 'nilaiTantangan' => fn($q) => $q->where('siswa_id', auth()->id())])
            ->withCount('soal')
            ->latest()
            ->paginate(10);

        return view('siswa.tantangan.index', compact('tantangans'));
    }

    public function kerjakan(Tantangan $tantangan)
    {
        $kelasId = auth()->user()->kelasIds()->first();
        if ($tantangan->kelas_id != $kelasId || $tantangan->batas_waktu <= now()) {
            return redirect()->route('siswa.tantangan')->with('error', 'Tantangan tidak tersedia!');
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
    $kelasId = auth()->user()->kelasIds()->first();
    if ($tantangan->kelas_id != $kelasId) {
        return back()->with('error', 'Akses ditolak!');
    }

    $jawabans = $request->jawaban ?? [];
    $totalNilai = 0;
    $totalSoal = $tantangan->soal->count();
    $soalDinilai = 0; // hanya soal auto

    foreach ($tantangan->soal as $soal) {

        $jawabanSiswa = $jawabans[$soal->id] ?? null;
        $nilai = 0;
        $manual = 0;

        // ======================
        // PILIHAN GANDA
        // ======================
        if ($soal->tipe == 'pg') {

            $nilai = strtoupper($jawabanSiswa) == $soal->jawaban_benar ? 100 : 0;
            $totalNilai += $nilai;
            $soalDinilai++;
        }

        // ======================
        // MATCHING
        // ======================
        elseif ($soal->tipe == 'matching') {

            $pairsBenar = json_decode($soal->matching_pairs, true) ?? [];
            $pairsJawaban = json_decode($jawabanSiswa, true) ?? [];

            $benarCount = 0;

            foreach ($pairsBenar as $key => $value) {
                if (isset($pairsJawaban[$key]) && $pairsJawaban[$key] == $value) {
                    $benarCount++;
                }
            }

            $jumlahPair = count($pairsBenar);
            $nilai = $jumlahPair > 0 ? ($benarCount / $jumlahPair) * 100 : 0;

            $totalNilai += $nilai;
            $soalDinilai++;
        }

        // ======================
        // ESSAY
        // ======================
        else {

            $nilai = null;
            $manual = 1; // harus dinilai guru
        }

        JawabanSiswa::create([
            'siswa_id' => auth()->id(),
            'tantangan_id' => $tantangan->id,
            'soal_id' => $soal->id,
            'jawaban' => $jawabanSiswa,
            'nilai' => $nilai,
            'dinilai_manual' => $manual
        ]);
    }

    // ======================
    // HITUNG NILAI RATA AUTO
    // ======================

    $rataNilai = $soalDinilai > 0 ? $totalNilai / $soalDinilai : 0;
    $poinDidapat = round(($rataNilai / 100) * $tantangan->poin);

    NilaiTantangan::create([
        'siswa_id' => auth()->id(),
        'tantangan_id' => $tantangan->id,
        'total_nilai' => $rataNilai, // nanti akan diupdate setelah manual grading
        'poin_didapat' => $poinDidapat,
        'waktu_submit' => now()
    ]);

    $mapelId = $tantangan->mapel_id;
    $siswaId = auth()->id();

    BadgeService::checkKeaktifan($siswaId, $mapelId);
    BadgeService::checkPrestasi($siswaId);
    $this->updateLeaderboard();

    return redirect()->route('siswa.tantangan')
        ->with([
            'success' => true,
            'message' => "🎉 Tantangan selesai!",
            'nilai' => round($rataNilai, 1) . '% (sementara)',
            'poin' => $poinDidapat,
            'total_soal' => $totalSoal
        ]);
}
private function updateLeaderboard()
{
    $siswaId = auth()->id();
    $kelasId = auth()->user()->kelasIds()->first();

    $totalPoin = \App\Models\NilaiTantangan::where('siswa_id', $siswaId)
        ->sum('poin_didapat');

    \App\Models\Leaderboard::updateOrCreate(
        [
            'siswa_id' => $siswaId,
            'kelas_id' => $kelasId
        ],
        [
            'total_poin' => $totalPoin
        ]
    );
}


public function profil()
{
    $profil = auth()->user();
    $leaderboard = Leaderboard::where('siswa_id', $profil->id)->first();
    $tantanganSelesai = NilaiTantangan::where('siswa_id', $profil->id)->count();

    // 🔥 Ambil badge yang dimiliki siswa
    $badges = SiswaBadge::with('badge')
        ->where('siswa_id', $profil->id)
        ->get()
        ->groupBy('badge_id');

    // Ambil semua master badge
    $masterBadges = Badge::all();

    return view('siswa.profil', compact(
        'profil',
        'leaderboard',
        'tantanganSelesai',
        'badges',
        'masterBadges'
    ));
}
    public function materiShow(Materi $materi)
{
    $materi->load('mapel', 'guru');
    
    return view('siswa.materi.show', compact('materi'));
}

}
