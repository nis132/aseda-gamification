<?php
namespace App\Http\Controllers;

use App\Models\SiswaBadge;
use App\Models\Badge;
use Barryvdh\DomPDF\Facade\Pdf;

class BadgeController extends Controller
{
    public function index()
        {
            $siswa   = auth()->user();
            $kelasId = \App\Models\SiswaKelas::where('siswa_id', $siswa->id)->value('kelas_id');

            $mapelIds = \App\Models\Tantangan::where('kelas_id', $kelasId)
                ->distinct()
                ->pluck('mapel_id');

            foreach ($mapelIds as $mapelId) {
                \App\Services\BadgeService::checkAll($siswa->id, $mapelId);
            }

            $levelSiswa  = $siswa->hitungLevel($kelasId);
            $allBadges   = Badge::orderBy('level_required')->orderBy('id')->get();
            $ownedBadges = SiswaBadge::with('badge')
                ->where('siswa_id', $siswa->id)
                ->get()
                ->groupBy('badge_id');

            $badgeDiraih = $allBadges->filter(fn($b) => $ownedBadges->has($b->id));
            $badgeBelum  = $allBadges->filter(fn($b) => !$ownedBadges->has($b->id));

            return view('badge.index', compact('allBadges', 'ownedBadges', 'levelSiswa', 'badgeDiraih', 'badgeBelum'));
        }
    
 
    public function sertifikat(int $badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        abort_if(!$badge->ada_sertifikat, 403, 'Badge ini tidak memiliki sertifikat.');

        $siswaBadge = SiswaBadge::where('siswa_id', auth()->id())
            ->where('badge_id', $badgeId)
            ->firstOrFail();

        $siswa = auth()->user();

        return view('badge.sertifikat', compact('badge', 'siswaBadge', 'siswa'));
    }

    public function downloadSertifikat(int $badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        abort_if(!$badge->ada_sertifikat, 403, 'Badge ini tidak memiliki sertifikat.');

        $siswaBadge = SiswaBadge::where('siswa_id', auth()->id())
            ->where('badge_id', $badgeId)
            ->firstOrFail();

        $siswa = auth()->user();

        $pdf = Pdf::loadView('badge.sertifikat-pdf', compact('badge', 'siswaBadge', 'siswa'))
            ->setPaper('a4', 'landscape');

        $filename = 'Sertifikat_'
            . str_replace(' ', '_', $badge->nama_badge) . '_'
            . str_replace(' ', '_', $siswa->nama) . '.pdf';

        return $pdf->download($filename);
    }
}