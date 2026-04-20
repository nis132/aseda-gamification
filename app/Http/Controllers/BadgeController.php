<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiswaBadge;
use App\Models\Badge;
use App\Models\NilaiTantangan;
use App\Models\Tantangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BadgeController extends Controller
{
    /**
     * Menampilkan koleksi badge milik siswa
     */
    public function index()
    {
        $siswaId = Auth::id();

        // Ambil semua badge yang dimiliki, grupkan berdasarkan badge_id
        $ownedBadges = SiswaBadge::with('badge')
            ->where('siswa_id', $siswaId)
            ->get()
            ->groupBy('badge_id');

        return view('badge.index', compact('ownedBadges'));
    }

    /**
     * Logika Otomatis: Cek kriteria badge setelah submit tugas
     * Panggil ini di SiswaController@submit
     */
    public static function checkAndGiveBadge($siswaId, $tantanganId)
    {
        $tantangan = Tantangan::find($tantanganId);
        if (!$tantangan) return;

        $mapelId = $tantangan->mapel_id;

        // =========================
        // 1. BADGE CAPAIAN (>=80)
        // =========================
        $nilai = NilaiTantangan::where('siswa_id', $siswaId)
            ->where('tantangan_id', $tantanganId)
            ->first();

        if ($nilai && $nilai->total_nilai >= 80) {
            self::assignBadge($siswaId, 'capaian', $tantanganId);
        }

        // =========================
        // 2. BADGE KEAKTIFAN (3x berturut-turut tepat waktu)
        // =========================
        $riwayat = NilaiTantangan::where('siswa_id', $siswaId)
            ->whereHas('tantangan', function ($q) use ($mapelId) {
                $q->where('mapel_id', $mapelId);
            })
            ->with('tantangan')
            ->orderBy('waktu_submit', 'desc')
            ->get();

        $count = 0;

        foreach ($riwayat as $item) {

            if (
                $item->waktu_submit &&
                $item->tantangan->batas_waktu &&
                Carbon::parse($item->waktu_submit)
                    ->lte(Carbon::parse($item->tantangan->batas_waktu))
            ) {
                $count++;
            } else {
                break;
            }

            if ($count >= 3) {
                self::assignBadge($siswaId, 'keaktifan', $tantanganId);
                break;
            }
        }

        // =========================
        // 3. LEVEL SYSTEM
        // =========================
        $totalMateri = DB::table('materi_selesai')
            ->join('materi', 'materi_selesai.materi_id', '=', 'materi.id')
            ->where('materi_selesai.siswa_id', $siswaId)
            ->where('materi.mapel_id', $mapelId)
            ->count();

        $totalTantangan = NilaiTantangan::where('siswa_id', $siswaId)
            ->whereHas('tantangan', function ($q) use ($mapelId) {
                $q->where('mapel_id', $mapelId);
            })
            ->count();

        $level = 1;

        if ($totalMateri >= 12 && $totalTantangan >= 12) {
            $level = 5;
        } elseif ($totalMateri >= 9 && $totalTantangan >= 9) {
            $level = 4;
        } elseif ($totalMateri >= 6 && $totalTantangan >= 6) {
            $level = 3;
        } elseif ($totalMateri >= 3 && $totalTantangan >= 3) {
            $level = 2;
        }

        DB::table('users')
            ->where('id', $siswaId)
            ->update(['level' => $level]);
    }

    /**
     * Simpan badge ke user jika belum punya untuk tantangan tersebut
     */
private static function assignBadge($siswaId, $namaBadge, $tantanganId)
{
    $badge = Badge::where('nama_badge', $namaBadge)->first();

    if (!$badge) return;

    $exists = SiswaBadge::where('siswa_id', $siswaId)
        ->where('badge_id', $badge->id)
        ->where('tantangan_id', $tantanganId)
        ->exists();

    if (!$exists) {
        SiswaBadge::create([
            'siswa_id' => $siswaId,
            'badge_id' => $badge->id,
            'tantangan_id' => $tantanganId,
            'is_new' => true
        ]);
    }
}
}