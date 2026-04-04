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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\BadgeService;
use App\Models\SiswaBadge;
use App\Models\Badge;
use App\Models\MateriSelesai;

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

$totalPoin = DB::table('nilai_tantangan')
    ->where('siswa_id', auth()->id())
    ->sum('poin_didapat');

$leaderboardData = DB::table('nilai_tantangan')
    ->join('siswa_kelas', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
    ->where('siswa_kelas.kelas_id', $kelasId)
    ->select(
        'nilai_tantangan.siswa_id',
        DB::raw('SUM(nilai_tantangan.poin_didapat) as total_poin'),
        DB::raw('SUM(TIMESTAMPDIFF(SECOND, nilai_tantangan.created_at, nilai_tantangan.waktu_submit)) as total_waktu')
    )
    ->groupBy('nilai_tantangan.siswa_id')
    ->orderByDesc('total_poin')
    ->orderBy('total_waktu')
    ->get();

$rankIndex = $leaderboardData->search(fn($item) => $item->siswa_id == auth()->id());
$rankKelas = $rankIndex !== false ? $rankIndex + 1 : '-';

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
        return redirect()->route('siswa.tantangan')
            ->with('error','Akses ditolak');
    }

    $jawabans = $request->input('jawaban', []);

    $totalNilai = 0;
    $soalDinilai = 0;

    foreach ($tantangan->soal as $soal) {

        // pastikan tidak null
        $jawabanSiswa = $jawabans[$soal->id] ?? '-';

        $nilai = 0;
        $manual = 0;

        // =====================
        // PILIHAN GANDA
        // =====================
        if ($soal->tipe == 'pg') {

            $nilai = strtoupper($jawabanSiswa) == strtoupper($soal->jawaban_benar)
                ? 100 : 0;

            $totalNilai += $nilai;
            $soalDinilai++;
        }

        // =====================
        // MATCHING
        // =====================
        elseif ($soal->tipe == 'matching') {

            $pairsBenar = json_decode($soal->matching_pairs, true) ?? [];

            $pairsJawaban = is_array($jawabanSiswa)
                ? $jawabanSiswa
                : json_decode($jawabanSiswa, true) ?? [];

            $benar = 0;

            foreach ($pairsBenar as $key => $val) {
                if(isset($pairsJawaban[$key]) && $pairsJawaban[$key] == $val){
                    $benar++;
                }
            }

            $jumlahPair = count($pairsBenar);

            $nilai = $jumlahPair > 0
                ? ($benar / $jumlahPair) * 100
                : 0;

            $totalNilai += $nilai;
            $soalDinilai++;
        }

        // =====================
        // ESSAY
        // =====================
        else {

            $nilai = null;
            $manual = 1;
        }

        // simpan jawaban
        JawabanSiswa::create([
            'siswa_id' => auth()->id(),
            'tantangan_id' => $tantangan->id,
            'soal_id' => $soal->id,
            'jawaban' => is_array($jawabanSiswa)
                ? json_encode($jawabanSiswa)
                : $jawabanSiswa,
            'nilai' => $nilai,
            'dinilai_manual' => $manual
        ]);
    }

    // =====================
    // HITUNG NILAI AKHIR
    // =====================

    $rataNilai = $soalDinilai > 0
        ? $totalNilai / $soalDinilai
        : 0;

    $poinDidapat = round(($rataNilai / 100) * $tantangan->poin);

    NilaiTantangan::create([
        'siswa_id' => auth()->id(),
        'tantangan_id' => $tantangan->id,
        'total_nilai' => $rataNilai,
        'poin_didapat' => $poinDidapat,
        'waktu_submit' => now()
    ]);

// 2. TRIGGER CEK BADGE DI SINI
    // Kita panggil fungsi static dari BadgeController
    \App\Http\Controllers\BadgeController::checkAndGiveBadge(auth()->id(), $tantangan->id);

    return redirect()->route('siswa.tantangan')
        ->with('success', "🎉 Tantangan selesai! Kamu dapat $poinDidapat poin.");
}

public function profil()
{
    $user = auth()->user();

    // Ambil kelas
    $kelas = DB::table('siswa_kelas')
        ->join('kelas', 'kelas.id', '=', 'siswa_kelas.kelas_id')
        ->where('siswa_kelas.siswa_id', $user->id)
        ->select('kelas.*')
        ->first();

    // Total tantangan selesai
    $tantanganSelesai = DB::table('nilai_tantangan')
        ->where('siswa_id', $user->id)
        ->count();

    // Total poin
    $totalPoin = DB::table('nilai_tantangan')
        ->where('siswa_id', $user->id)
        ->sum('poin_didapat');

    // Hitung leaderboard kelas
    $leaderboard = DB::table('nilai_tantangan')
    ->join('siswa_kelas', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
    ->where('siswa_kelas.kelas_id', $kelas->id)
    ->select(
        'nilai_tantangan.siswa_id',
        DB::raw('SUM(nilai_tantangan.poin_didapat) as total_poin'),
        DB::raw('SUM(
            TIMESTAMPDIFF(
                SECOND, 
                nilai_tantangan.created_at, 
                nilai_tantangan.waktu_submit
            )
        ) as total_waktu')
    )
    ->groupBy('nilai_tantangan.siswa_id')
    ->orderByDesc('total_poin')
    ->orderBy('total_waktu')
    ->get();
    // Tentukan rank user
    $rank = null;
    foreach ($leaderboard as $index => $item) {
        if ($item->siswa_id == $user->id) {
            $rank = $index + 1;
        }
    }

    // ======================
    // LEVEL SYSTEM
    // ======================

    $materiSelesai = DB::table('materi_selesai')
        ->where('siswa_id', $user->id)
        ->count();

    $level = 1;

    if ($materiSelesai >= 12 && $tantanganSelesai >= 12) {
        $level = 5;
    } elseif ($materiSelesai >= 9 && $tantanganSelesai >= 9) {
        $level = 4;
    } elseif ($materiSelesai >= 6 && $tantanganSelesai >= 6) {
        $level = 3;
    } elseif ($materiSelesai >= 3 && $tantanganSelesai >= 3) {
        $level = 2;
    }

    return view('siswa.profil', compact(
        'user',
        'kelas',
        'tantanganSelesai',
        'totalPoin',
        'rank',
        'level'
    ));
}

public function materiShow(Materi $materi)
{
    $materi->load('mapel', 'guru', 'kelas');

    $sudahSelesai = MateriSelesai::where('siswa_id', Auth::id())
        ->where('materi_id', $materi->id)
        ->exists();

    return view('siswa.materi.show', compact('materi', 'sudahSelesai'));
}

public function selesai(Materi $materi)
{
    MateriSelesai::firstOrCreate([
        'siswa_id' => Auth::id(),
        'materi_id' => $materi->id
    ]);

    return back()->with('success', 'Materi berhasil ditandai selesai.');
}
}
