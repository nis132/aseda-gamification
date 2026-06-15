<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\Materi;
use App\Models\GuruMapelKelas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();

        $tantanganCount = Tantangan::where('guru_id', $guruId)->count();

        $materiCount = Materi::where('guru_id', $guruId)->count();

        $mapelCount = GuruMapelKelas::where('guru_id', $guruId)
            ->distinct('mapel_id')
            ->count('mapel_id');

        $recentTantangan = Tantangan::with(['mapel', 'kelas'])
            ->where('guru_id', $guruId)
            ->latest()
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact(
            'tantanganCount',
            'materiCount',
            'mapelCount',
            'recentTantangan'
        ));
    }
}