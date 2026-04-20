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
            ->join('kelas', 'kelas.id', '=', 'siswa_kelas.kelas_id')
            ->where('siswa_kelas.siswa_id', $user->id)
            ->select(
                'kelas.id as kelas_id',
                'kelas.nama_kelas'
            )
            ->first();

        if (!$kelas) {
            return back()->with('error', 'Kamu belum terdaftar di kelas.');
        }

        // LEADERBOARD
        $leaderboard = DB::table('siswa_kelas')
            ->join('users', 'users.id', '=', 'siswa_kelas.siswa_id')
            ->leftJoin('nilai_tantangan', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
            ->where('siswa_kelas.kelas_id', $kelas->kelas_id)
            ->select(
                'users.id',
                'users.nama',

                // TOTAL POIN
                DB::raw('COALESCE(SUM(nilai_tantangan.poin_didapat), 0) as total_poin'),

                // TOTAL WAKTU (SEMUA DURASI)
                DB::raw('COALESCE(SUM(
                    TIMESTAMPDIFF(
                        SECOND,
                        nilai_tantangan.created_at,
                        nilai_tantangan.waktu_submit
                    )
                ), 0) as total_waktu')
            )
            ->groupBy('users.id', 'users.nama')
            ->orderByDesc('total_poin')   // poin terbesar dulu
            ->orderBy('total_waktu')      // waktu terkecil (lebih cepat = menang)
            ->get();

        // ranking manual
        $rank = 1;
        foreach ($leaderboard as $item) {
            $item->rank = $rank++;
        }

        return view('leaderboard.index', compact('leaderboard'));
    }
}