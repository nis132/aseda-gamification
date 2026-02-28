<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tantangan;
use App\Models\JawabanSiswa;
use App\Models\NilaiTantangan;
use App\Models\User;

class PenilaianController extends Controller
{
    public function index($id)
    {
        $tantangan = Tantangan::with('soal')->findOrFail($id);

        $jawaban = JawabanSiswa::with('soal','siswa')
            ->where('tantangan_id', $id)
            ->get()
            ->groupBy('siswa_id');

        return view('guru.nilai.index', compact('tantangan','jawaban'));
    }

    public function simpanNilai(Request $request, $id, $siswaId)
    {
        $jawaban = JawabanSiswa::where('tantangan_id',$id)
                    ->where('siswa_id',$siswaId)
                    ->get();

        $totalNilai = 0;

        foreach ($jawaban as $j) {

            if ($j->dinilai_manual) {
                $nilaiManual = $request->input('nilai_'.$j->id);
                $j->update([
                    'nilai' => $nilaiManual
                ]);
                $totalNilai += $nilaiManual;
            } else {
                $totalNilai += $j->nilai;
            }
        }

        NilaiTantangan::updateOrCreate(
            [
                'siswa_id' => $siswaId,
                'tantangan_id' => $id
            ],
            [
                'total_nilai' => $totalNilai
            ]
        );

        return back()->with('success','Nilai berhasil diperbarui');
    }

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
}