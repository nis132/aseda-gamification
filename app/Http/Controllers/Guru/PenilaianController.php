<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tantangan;
use App\Models\JawabanSiswa;
use App\Models\NilaiTantangan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    // =========================
    // LIST SISWA YANG MENGERJAKAN
    // =========================
    public function index($id)
    {
        $tantangan = Tantangan::with('soal')->findOrFail($id);

        $jawaban = JawabanSiswa::with('soal','siswa')
            ->where('tantangan_id', $id)
            ->get()
            ->groupBy('siswa_id');

        return view('guru.nilai.index', compact('tantangan','jawaban'));
    }

    // =========================
    // DETAIL JAWABAN SISWA
    // =========================
    public function detail($id, $siswaId)
    {
        $tantangan = Tantangan::with('soal')->findOrFail($id);

        $jawaban = JawabanSiswa::with('soal','siswa')
            ->where('tantangan_id', $id)
            ->where('siswa_id', $siswaId)
            ->get();

        $siswa = User::findOrFail($siswaId);

        return view('guru.nilai.detail', compact('tantangan','jawaban','siswa'));
    }

    public function simpanNilai(Request $request, $id, $siswaId)
    {
        DB::beginTransaction();

        try {
            $jawaban = JawabanSiswa::where('tantangan_id', $id)
                ->where('siswa_id', $siswaId)
                ->get();

            $totalNilai = 0;
            $jumlahSoalDinilai = 0;

            foreach ($jawaban as $j) {

                if ($j->dinilai_manual) {

                    $field = 'nilai_' . $j->id;

                    if ($request->has($field)) {
                        $j->nilai = $request->$field;
                        $j->save();
                    }
                }

                if ($j->nilai !== null) {
                    $totalNilai += $j->nilai;
                    $jumlahSoalDinilai++;
                }
            }


            $rataNilai = $jumlahSoalDinilai > 0
                ? $totalNilai / $jumlahSoalDinilai
                : 0;

            $tantangan = Tantangan::findOrFail($id);

            $poinDidapat = round(($rataNilai / 100) * $tantangan->poin);

            NilaiTantangan::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tantangan_id' => $id
                ],
                [
                    'total_nilai' => $rataNilai,
                    'poin_didapat' => $poinDidapat
                ]
            );

            DB::commit();

            return redirect()
                ->route('guru.nilai.detail', [$id, $siswaId])
                ->with('success', 'Nilai berhasil disimpan & diperbarui!');

        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}