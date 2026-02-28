<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    public function index($tantanganId)
    {
        $tantangan = Tantangan::with(['mapel', 'kelas', 'soal'])->findOrFail($tantanganId);
        if ($tantangan->guru_id !== Auth::id()) abort(403);
        
        return view('guru.tantangan.show', compact('tantangan'));
    }

    public function create($tantanganId)
    {
        $tantangan = Tantangan::with('mapel', 'kelas')->findOrFail($tantanganId);
        if ($tantangan->guru_id !== Auth::id()) abort(403);

        return view('guru.soal.create', compact('tantangan'));
    }

public function store(Request $request, $tantanganId)
{
    $tantangan = Tantangan::findOrFail($tantanganId);
    if ($tantangan->guru_id !== Auth::id()) abort(403);

    $request->validate([
        'pertanyaan' => 'required|string|max:500',
        'tipe' => 'required|in:pg,essay,matching',
    ]);

    $soalData = [
        'tantangan_id' => $tantanganId,
        'pertanyaan' => $request->pertanyaan,
        'tipe' => $request->tipe,
    ];

    // ================= PG =================
    if ($request->tipe === 'pg') {

        $request->validate([
            'opsi_a' => 'required|string|max:255',
            'opsi_b' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        $soalData += [
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'jawaban_benar' => $request->jawaban_benar,
        ];
    }

    // ================= ESSAY =================
    elseif ($request->tipe === 'essay') {

        $request->validate([
            'jawaban_benar' => 'required|string|max:500'
        ]);

        $soalData['jawaban_benar'] = $request->jawaban_benar;
    }

    // ================= MATCHING =================
    elseif ($request->tipe === 'matching') {

        $kiri = [];
        $kanan = [];
        $pairs = [];

        for ($i = 1; $i <= 6; $i++) {
            if ($request->filled("kiri_$i")) {

                $kiri[] = $request->input("kiri_$i");
                $kanan[] = $request->input("kanan_$i");
                $pairs[] = [count($kiri)-1, count($kiri)-1];
            }
        }

        if (count($kiri) < 2) {
            return back()->withErrors([
                'kiri_1' => 'Minimal 2 pasangan!'
            ])->withInput();
        }

        $soalData += [
            'kiri_items' => json_encode($kiri),
            'kanan_items' => json_encode($kanan),
            'matching_pairs' => json_encode($pairs),
            'matching_count' => count($kiri),
            'jawaban_benar' => json_encode($pairs),
        ];
    }

    Soal::create($soalData);

    return $request->filled('tambah_lagi')
        ? redirect()->route('guru.soal.create', $tantangan)
        : redirect()->route('guru.tantangan.show', $tantangan)
            ->with('success', 'Soal berhasil ditambahkan!');
}
    public function destroy($tantanganId, Soal $soal)
    {
        $tantangan = Tantangan::findOrFail($tantanganId);
        if ($tantangan->guru_id !== Auth::id()) abort(403);

        $soal->delete();

        return back()->with('success', '✅ Soal dihapus!');
    }
}
