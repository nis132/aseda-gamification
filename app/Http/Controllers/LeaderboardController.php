<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil kelas siswa dari tabel siswa_kelas
        $kelas = DB::table('siswa_kelas')
            ->where('siswa_id', $user->id)
            ->first();

        if (!$kelas) {
            return back()->with('error', 'Kamu belum terdaftar di kelas.');
        }

        // Query leaderboard berdasarkan kelas user
        $leaderboard = DB::table('nilai_tantangan')
            ->join('siswa_kelas', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
            ->join('users', 'users.id', '=', 'nilai_tantangan.siswa_id')
            ->where('siswa_kelas.kelas_id', $kelas->kelas_id)
            ->select(
                'users.id',
                'users.nama',
                DB::raw('SUM(nilai_tantangan.poin_didapat) as total_poin'),
                DB::raw('SUM(
                    TIMESTAMPDIFF(SECOND, 
                    nilai_tantangan.created_at, 
                    nilai_tantangan.waktu_submit)
                ) as total_waktu')
            )
            ->groupBy('users.id', 'users.nama')
            ->orderByDesc('total_poin')
            ->orderBy('total_waktu')
            ->get();

        // Tambah ranking
        $rank = 1;
        foreach ($leaderboard as $item) {
            $item->rank = $rank++;
        }

        return view('leaderboard.index', compact('leaderboard'));
    }
}
