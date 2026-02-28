<?php

namespace App\Services;

use App\Models\SiswaBadge;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    // ===============================
    // BADGE KEAKTIFAN (3 berturut per mapel)
    // ===============================
    public static function checkKeaktifan($siswaId, $mapelId)
    {
        $tasks = DB::table('nilai_tantangan')
            ->join('tantangan', 'nilai_tantangan.tantangan_id', '=', 'tantangan.id')
            ->where('nilai_tantangan.siswa_id', $siswaId)
            ->where('tantangan.mapel_id', $mapelId)
            ->orderBy('nilai_tantangan.waktu_submit')
            ->select(
                'nilai_tantangan.waktu_submit',
                'tantangan.batas_waktu'
            )
            ->get();

        $streak = 0;
        $totalBadge = 0;

        foreach ($tasks as $task) {
            if ($task->waktu_submit <= $task->batas_waktu) {
                $streak++;

                if ($streak == 3) {
                    $totalBadge++;
                    $streak = 0;
                }
            } else {
                $streak = 0;
            }
        }

        $sudahPunya = SiswaBadge::where('siswa_id', $siswaId)
            ->where('badge_id', 1) // ID badge aktif
            ->count();

        $harusInsert = $totalBadge - $sudahPunya;

        for ($i = 0; $i < $harusInsert; $i++) {
            SiswaBadge::create([
                'siswa_id' => $siswaId,
                'badge_id' => 1,
                'diterima_pada' => now()
            ]);
        }
    }

    // ===============================
    // BADGE NILAI >= 80
    // ===============================
    public static function checkPrestasi($siswaId)
    {
        $totalLulus = DB::table('nilai_tantangan')
            ->where('siswa_id', $siswaId)
            ->where('total_nilai', '>=', 80)
            ->count();

        $sudahPunya = SiswaBadge::where('siswa_id', $siswaId)
            ->where('badge_id', 2) // ID badge prestasi
            ->count();

        $harusInsert = $totalLulus - $sudahPunya;

        for ($i = 0; $i < $harusInsert; $i++) {
            SiswaBadge::create([
                'siswa_id' => $siswaId,
                'badge_id' => 2,
                'diterima_pada' => now()
            ]);
        }
    }
}
