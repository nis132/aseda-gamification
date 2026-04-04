<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil kelas siswa
        $kelas = DB::table('siswa_kelas')
            ->where('siswa_id', $user->id)
            ->first();

        if (!$kelas) {
            return back()->with('error', 'Kamu belum terdaftar di kelas.');
        }

$leaderboard = DB::table('siswa_kelas')
    ->join('users', 'users.id', '=', 'siswa_kelas.siswa_id')
    ->leftJoin('nilai_tantangan', function ($join) {
        $join->on('nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id');
    })
    ->where('siswa_kelas.kelas_id', $kelas->kelas_id)
    ->select(
        'users.id',
        'users.nama',
        DB::raw('COALESCE(SUM(nilai_tantangan.poin_didapat), 0) as total_poin'),
        DB::raw('COALESCE(SUM(
            TIMESTAMPDIFF(
                SECOND,
                nilai_tantangan.created_at,
                nilai_tantangan.waktu_submit
            )
        ), 0) as total_waktu')
    )
    ->groupBy('users.id', 'users.nama')
    ->orderByDesc('total_poin')
    ->orderBy('total_waktu')
    ->get();
    
        // Hitung ranking manual
        $rank = 1;
        foreach ($leaderboard as $item) {
            $item->rank = $rank++;
        }

        return view('leaderboard.index', compact('leaderboard'));
    }
}