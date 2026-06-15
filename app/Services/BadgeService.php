<?php
// app/Services/BadgeService.php
namespace App\Services;

use App\Models\SiswaBadge;
use App\Models\Badge;
use App\Models\User;
use App\Models\MateriSelesai;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    public static function checkAll(int $siswaId, int $mapelId): void
    {
        $siswa = User::find($siswaId);
        if (!$siswa) return;

        // Ambil kelasId agar hitungLevel akurat untuk satu kelas saja
        $kelasId = \App\Models\SiswaKelas::where('siswa_id', $siswaId)->value('kelas_id');
        $level   = $siswa->hitungLevel($kelasId);

        self::checkBadgeLevel($siswaId, $level);
        self::checkSemuaMapel($siswaId, $mapelId);
    }

    // ── Badge berbasis level ──────────────────────────────────────────
    private static function checkBadgeLevel(int $siswaId, int $level): void
    {
        $badgesYangBerhak = Badge::where('tipe_syarat', 'level')
            ->where('level_required', '<=', $level)
            ->get();
    
        foreach ($badgesYangBerhak as $badge) {
            $sudahPunya = SiswaBadge::where('siswa_id', $siswaId)
                ->where('badge_id', $badge->id)
                ->exists();
    
            if ($sudahPunya) continue;
    
            SiswaBadge::create([
                'siswa_id'      => $siswaId,
                'badge_id'      => $badge->id,
                'tantangan_id'  => null,
                'is_new'        => true,
                'diterima_pada' => now(),
            ]);
        }
    }

    // ── Badge Penguasa Mapel ──────────────────────────────────────────
    private static function checkSemuaMapel(int $siswaId, int $mapelId): void
    {
        $kelasId = DB::table('siswa_kelas')
            ->where('siswa_id', $siswaId)
            ->value('kelas_id');

        if (!$kelasId) return;

        $totalTantangan = DB::table('tantangan')
            ->where('mapel_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->count();

        if ($totalTantangan === 0) return;

        $selesai = DB::table('nilai_tantangan')
            ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
            ->where('nilai_tantangan.siswa_id', $siswaId)
            ->where('tantangan.mapel_id', $mapelId)
            ->where('tantangan.kelas_id', $kelasId)
            ->count();

        if ($selesai < $totalTantangan) return;

        $badge = Badge::where('tipe_syarat', 'semua_mapel')->first();
        if (!$badge) return;

        // Cukup cek per siswa + badge_id saja
        // tantangan_id di-set null karena FK ke tabel tantangan, bukan mapel
        $sudahPunya = SiswaBadge::where('siswa_id', $siswaId)
            ->where('badge_id', $badge->id)
            ->exists();

        if (!$sudahPunya) {
            SiswaBadge::create([
                'siswa_id'      => $siswaId,
                'badge_id'      => $badge->id,
                'tantangan_id'  => null, // ← null, bukan $mapelId
                'is_new'        => true,
                'diterima_pada' => now(),
            ]);
        }
    }
}