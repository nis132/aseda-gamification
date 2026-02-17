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

    // Simpan jawaban siswa
    foreach ($tantangan->soal as $soal) {
        $jawabanSiswa = $jawabans[$soal->id] ?? '';
        $benar = strtoupper($jawabanSiswa) == $soal->jawaban_benar ? 100 : 0;
        
        JawabanSiswa::create([
            'siswa_id' => auth()->id(),
            'tantangan_id' => $tantangan->id,
            'soal_id' => $soal->id,
            'jawaban' => $jawabanSiswa,
            'nilai' => $benar,
            'dinilai_manual' => 0
        ]);

        $totalNilai += $benar;
    }

    $rataNilai = $totalSoal > 0 ? $totalNilai / $totalSoal : 0;
    $poinDidapat = round(($rataNilai / 100) * $tantangan->poin);

    // Simpan nilai tantangan
    NilaiTantangan::create([
        'siswa_id' => auth()->id(),
        'tantangan_id' => $tantangan->id,
        'total_nilai' => $rataNilai,
        'poin_didapat' => $poinDidapat,
        'waktu_submit' => now()
    ]);

    $this->updateLeaderboard();

    // 🔥 FIX: FLASH MESSAGE bukan JSON!
    return redirect()->route('siswa.tantangan')
        ->with([
            'success' => true,
            'message' => "🎉 Tantangan selesai!",
            'nilai' => round($rataNilai, 1) . '%',
            'poin' => $poinDidapat,
            'total_soal' => $totalSoal
        ]);
}


    public function leaderboard()
    {
        $kelasId = auth()->user()->kelasIds()->first();
        $leaderboard = Leaderboard::where('kelas_id', $kelasId)
            ->with('siswa')
            ->orderBy('total_poin', 'desc')
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        $myRank = Leaderboard::where('kelas_id', $kelasId)
            ->where('siswa_id', auth()->id())
            ->first()?->rank ?? '-';

        return view('siswa.leaderboard', compact('leaderboard', 'myRank'));
    }

    public function profil()
    {
        $profil = auth()->user();
        $leaderboard = Leaderboard::where('siswa_id', $profil->id)->first();
        $tantanganSelesai = NilaiTantangan::where('siswa_id', $profil->id)->count();

        return view('siswa.profil', compact('profil', 'leaderboard', 'tantanganSelesai'));
    }

    private function updateLeaderboard()
    {
        $kelasId = auth()->user()->kelasIds()->first();
        $totalPoin = NilaiTantangan::where('siswa_id', auth()->id())->sum('poin_didapat');

        Leaderboard::updateOrCreate(
            ['siswa_id' => auth()->id(), 'kelas_id' => $kelasId],
            ['total_poin' => $totalPoin, 'updated_at' => now()]
        );

        // Update rank per kelas
        DB::table('leaderboard')
            ->where('kelas_id', $kelasId)
            ->orderBy('total_poin', 'desc')
            ->orderBy('updated_at', 'desc')
            ->chunk(100, function ($items) use ($kelasId) {
                foreach ($items as $index => $item) {
                    DB::table('leaderboard')
                        ->where('id', $item->id)
                        ->update(['rank' => $index + 1]);
                }
            });
    }

    public function materiShow(Materi $materi)
{
    $materi->load('mapel', 'guru');
    
    return view('siswa.materi.show', compact('materi'));
}

}
