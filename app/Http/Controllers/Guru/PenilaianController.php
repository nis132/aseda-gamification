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
    $jawaban = JawabanSiswa::where('tantangan_id', $id)
        ->where('siswa_id', $siswaId)
        ->get();

    foreach ($jawaban as $j) {
        
        if ($j->dinilai_manual) {

            $field = 'nilai_' . $j->id;

            if ($request->has($field)) {
                $j->nilai = $request->$field;
                $j->save();
            }
        }
    }

    return redirect()
        ->route('guru.nilai.detail', [$id, $siswaId])
        ->with('success', 'Nilai berhasil disimpan!');
}
}