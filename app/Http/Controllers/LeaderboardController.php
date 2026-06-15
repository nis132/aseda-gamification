<?php
// app/Http/Controllers/LeaderboardController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\LeaderboardFinal;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaderboardController extends Controller
{
    // ── SISWA: leaderboard kelas sendiri ────────────────────────────
    public function index()
    {
        $user  = Auth::user();
        $kelas = DB::table('siswa_kelas')
            ->join('kelas', 'kelas.id', '=', 'siswa_kelas.kelas_id')
            ->where('siswa_kelas.siswa_id', $user->id)
            ->select('kelas.id as kelas_id', 'kelas.nama_kelas')
            ->first();

        if (!$kelas) {
            return back()->with('error', 'Kamu belum terdaftar di kelas.');
        }

        $mapels = DB::table('tantangan')
            ->join('mapel', 'mapel.id', '=', 'tantangan.mapel_id')
            ->where('tantangan.kelas_id', $kelas->kelas_id)
            ->where('tantangan.status', 'published')
            ->select('mapel.id', 'mapel.nama_mapel')
            ->distinct()
            ->get();

        $mapelId     = request('mapel');
        $leaderboard = $this->buildLeaderboard($kelas->kelas_id, $mapelId);

        // Apakah sudah ada snapshot final untuk kelas ini?
        $finalTerkunci = DB::table('leaderboard_final')
            ->where('kelas_id', $kelas->kelas_id)
            ->orderByDesc('dikunci_pada')
            ->value('periode');

        // Cek apakah siswa ini masuk top 3 di snapshot final
        $sertifikatJuara = null;
        if ($finalTerkunci) {
            $sertifikatJuara = DB::table('leaderboard_final')
                ->where('kelas_id', $kelas->kelas_id)
                ->where('siswa_id', $user->id)
                ->where('periode', $finalTerkunci)
                ->where('rank', '<=', 3)
                ->first();
        }

        return view('leaderboard.index', compact(
            'leaderboard', 'kelas', 'mapels', 'mapelId',
            'finalTerkunci', 'sertifikatJuara'
        ));
    }

    // ── GURU: lihat & kelola leaderboard semua kelas yang diajar ────
    public function guru(Request $request)
    {
        $guruId = Auth::id();

        $relasiList = DB::table('guru_mapel_kelas')
            ->join('kelas', 'kelas.id', '=', 'guru_mapel_kelas.kelas_id')
            ->join('mapel', 'mapel.id', '=', 'guru_mapel_kelas.mapel_id')
            ->where('guru_mapel_kelas.guru_id', $guruId)
            ->whereNotNull('guru_mapel_kelas.kelas_id')
            ->select(
                'guru_mapel_kelas.kelas_id',
                'kelas.nama_kelas',
                'guru_mapel_kelas.mapel_id',
                'mapel.nama_mapel'
            )
            ->get();

        $kelasList = $relasiList->unique('kelas_id')->values();
        $mapelList = $relasiList->unique('mapel_id')->values();

        $kelasId   = $request->kelas ?? optional($kelasList->first())->kelas_id;
        $mapelId   = $request->mapel;
        $namaKelas = '-';

        $leaderboard       = collect();
        $finalTerkunci     = null;
        $finalLeaderboard  = collect();

        if ($kelasId) {
            $namaKelas    = optional($kelasList->firstWhere('kelas_id', $kelasId))->nama_kelas ?? '-';
            $leaderboard  = $this->buildLeaderboard($kelasId, $mapelId, $guruId);

            // Ambil snapshot final jika sudah pernah dikunci
            $finalTerkunci = DB::table('leaderboard_final')
                ->where('kelas_id', $kelasId)
                ->orderByDesc('dikunci_pada')
                ->value('periode');

            if ($finalTerkunci) {
                $finalLeaderboard = DB::table('leaderboard_final')
                    ->join('users', 'users.id', '=', 'leaderboard_final.siswa_id')
                    ->where('leaderboard_final.kelas_id', $kelasId)
                    ->where('leaderboard_final.periode', $finalTerkunci)
                    ->select(
                        'users.nama',
                        'users.nis',
                        'leaderboard_final.rank',
                        'leaderboard_final.total_poin',
                        'leaderboard_final.jumlah_selesai',
                        'leaderboard_final.dikunci_pada',
                    )
                    ->orderBy('leaderboard_final.rank')
                    ->get();
            }
        }

        return view('guru.leaderboard', compact(
            'leaderboard', 'kelasList', 'mapelList',
            'kelasId', 'mapelId', 'namaKelas',
            'finalTerkunci', 'finalLeaderboard'
        ));
    }

    // ── GURU: kunci leaderboard sebagai snapshot final ───────────────
    public function kunci(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'periode'  => 'required|string|max:30', // "2025/2026 Genap"
        ]);

        $guruId   = Auth::id();
        $kelasId  = $request->kelas_id;
        $periode  = $request->periode;

        // Pastikan guru punya akses ke kelas ini
        $boleh = DB::table('guru_mapel_kelas')
            ->where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->exists();

        abort_if(!$boleh, 403, 'Anda tidak mengajar kelas ini.');

        // Cek belum pernah dikunci untuk periode yang sama
        $sudahAda = DB::table('leaderboard_final')
            ->where('kelas_id', $kelasId)
            ->where('periode', $periode)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', "Leaderboard untuk periode \"{$periode}\" sudah pernah dikunci.");
        }

        // Ambil data leaderboard saat ini (tanpa filter mapel/guru agar total semua mapel)
        $snapshot = $this->buildLeaderboard($kelasId);

        $now = now();
        $rows = [];
        foreach ($snapshot as $item) {
            $rows[] = [
                'kelas_id'      => $kelasId,
                'siswa_id'      => $item->id,
                'total_poin'    => $item->total_poin,
                'jumlah_selesai'=> $item->jumlah_selesai,
                'rank'          => $item->rank,
                'periode'       => $periode,
                'di_kunci_oleh' => $guruId,
                'dikunci_pada'  => $now,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        DB::table('leaderboard_final')->insert($rows);

        return back()->with('success',
            "Leaderboard \"{$periode}\" berhasil dikunci. Siswa peringkat 1–3 kini bisa download sertifikat juara kelas."
        );
    }

    // ── SISWA: download sertifikat juara kelas ───────────────────────
    public function sertifikatJuara(Request $request)
    {
        $siswa   = Auth::user();
        $kelasId = DB::table('siswa_kelas')
            ->where('siswa_id', $siswa->id)
            ->value('kelas_id');

        abort_if(!$kelasId, 403, 'Kamu belum terdaftar di kelas.');

        $periode = $request->query('periode');
        abort_if(!$periode, 400, 'Parameter periode diperlukan.');

        $data = DB::table('leaderboard_final')
            ->join('kelas', 'kelas.id', '=', 'leaderboard_final.kelas_id')
            ->where('leaderboard_final.kelas_id', $kelasId)
            ->where('leaderboard_final.siswa_id', $siswa->id)
            ->where('leaderboard_final.periode', $periode)
            ->where('leaderboard_final.rank', '<=', 3)
            ->select(
                'leaderboard_final.*',
                'kelas.nama_kelas',
            )
            ->first();

        abort_if(!$data, 403, 'Kamu tidak berhak mendapatkan sertifikat ini.');

        $namaJuara = match($data->rank) {
            1 => 'Juara 1',
            2 => 'Juara 2',
            3 => 'Juara 3',
            default => 'Juara',
        };

        $pdf = Pdf::loadView('leaderboard.sertifikat-juara', [
            'siswa'      => $siswa,
            'data'       => $data,
            'namaJuara'  => $namaJuara,
        ])->setPaper('a4', 'landscape');

        $filename = "Sertifikat_{$namaJuara}_{$data->nama_kelas}_"
            . str_replace([' ', '/'], '_', $periode) . '_'
            . str_replace(' ', '_', $siswa->nama) . '.pdf';

        return $pdf->download($filename);
    }

private function buildLeaderboard(int $kelasId, ?int $mapelId = null, ?int $guruId = null): \Illuminate\Support\Collection
{
    $query = DB::table('siswa_kelas')
        ->join('users', 'users.id', '=', 'siswa_kelas.siswa_id')
 
        // Poin dari tantangan
        ->leftJoin('nilai_tantangan', 'nilai_tantangan.siswa_id', '=', 'siswa_kelas.siswa_id')
        ->leftJoin('tantangan', function ($join) use ($kelasId, $mapelId, $guruId) {
            $join->on('tantangan.id', '=', 'nilai_tantangan.tantangan_id')
                 ->where('tantangan.kelas_id', $kelasId)
                 ->where('tantangan.status', 'published');
            if ($mapelId) $join->where('tantangan.mapel_id', $mapelId);
            if ($guruId)  $join->where('tantangan.guru_id', $guruId);
        })
 
        // Nilai tambah manual dari guru
        ->leftJoin('leaderboard_nilai_tambah', function ($join) use ($kelasId) {
            $join->on('leaderboard_nilai_tambah.siswa_id', '=', 'siswa_kelas.siswa_id')
                 ->where('leaderboard_nilai_tambah.kelas_id', $kelasId);
        })
 
        // Poin dari materi yang diselesaikan (filter kelas supaya tidak lintas kelas)
        ->leftJoin('materi_selesai', 'materi_selesai.siswa_id', '=', 'siswa_kelas.siswa_id')
        ->leftJoin('materi as m_poin', function ($join) use ($kelasId, $mapelId) {
            $join->on('m_poin.id', '=', 'materi_selesai.materi_id')
                 ->where('m_poin.kelas_id', $kelasId);
            if ($mapelId) $join->where('m_poin.mapel_id', $mapelId);
        })
 
        ->where('siswa_kelas.kelas_id', $kelasId)
        ->select(
            'users.id',
            'users.nama',
            'users.nis',
            DB::raw('
                COALESCE(SUM(nilai_tantangan.poin_didapat), 0)
                + COALESCE(MAX(leaderboard_nilai_tambah.nilai_tambah), 0)
                + COALESCE(SUM(materi_selesai.poin), 0)
                as total_poin
            '),
            DB::raw('COUNT(DISTINCT nilai_tantangan.id) as jumlah_selesai'),
            DB::raw('COALESCE(AVG(TIMESTAMPDIFF(SECOND, nilai_tantangan.created_at, nilai_tantangan.waktu_submit)), 0) as rata_waktu')
        )
        ->groupBy('users.id', 'users.nama', 'users.nis')
        ->orderByDesc('total_poin')
        ->orderByDesc('jumlah_selesai')
        ->orderBy('rata_waktu')
        ->get();
 
    // Dense rank: poin + jumlah tantangan identik → rank sama
    $rank = 1;
    $prev = null;
    foreach ($query as $i => $item) {
        $key = $item->total_poin . '|' . $item->jumlah_selesai;
        if ($prev === null || $key !== $prev) {
            $rank = $i + 1;
        }
        $item->rank = $rank;
        $prev = $key;
    }
 
    return $query;
}
}