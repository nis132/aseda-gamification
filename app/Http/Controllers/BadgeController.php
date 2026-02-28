<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SiswaBadge;

class BadgeController extends Controller
{
    public function index()
    {
        $siswaId = Auth::id();

        $badges = SiswaBadge::with('badge')
            ->where('siswa_id', $siswaId)
            ->get()
            ->groupBy('badge_id');

        return view('badge.index', compact('badges'));
    }
}
