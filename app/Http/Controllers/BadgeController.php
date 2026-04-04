<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiswaBadge;
use App\Models\Badge;
use App\Models\NilaiTantangan;
use App\Models\Tantangan;
use Carbon\Carbon;

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

        // --- 1. LOGIKA: BADGE CAPAIAN TANTANGAN (Nilai >= 80) ---
        $nilaiRecord = NilaiTantangan::where('siswa_id', $siswaId)
            ->where('tantangan_id', $tantanganId)
            ->first();

        if ($nilaiRecord && $nilaiRecord->total_nilai >= 80) {
            self::assignBadge($siswaId, 'Badge Capaian Tantangan', $tantanganId);
        }

        // --- 2. LOGIKA: BADGE KEAKTIFAN (3x Berturut-turut Tepat Waktu) ---
        $mapelId = $tantangan->mapel_id;
        
        // Ambil 3 pengerjaan terakhir pada mapel yang sama
        $riwayat = NilaiTantangan::where('siswa_id', $siswaId)
            ->whereHas('tantangan', function($q) use ($mapelId) {
                $q->where('mapel_id', $mapelId);
            })
            ->with('tantangan')
            ->latest('waktu_submit')
            ->take(3)
            ->get();

        if ($riwayat->count() == 3) {
            $isTepatWaktu = true;
            foreach ($riwayat as $item) {
                // Jika waktu submit lebih besar dari batas waktu, maka telat
                if (Carbon::parse($item->waktu_submit)->gt(Carbon::parse($item->tantangan->batas_waktu))) {
                    $isTepatWaktu = false;
                    break;
                }
            }

            if ($isTepatWaktu) {
                self::assignBadge($siswaId, 'Badge Keaktifan', $tantanganId);
            }
        }
    }

    /**
     * Simpan badge ke user jika belum punya untuk tantangan tersebut
     */
    private static function assignBadge($siswaId, $namaBadge, $tantanganId)
    {
        $badge = Badge::where('nama_badge', $namaBadge)->first();
        
        if ($badge) {
            // Cek agar tidak duplikat untuk tantangan yang sama
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
}