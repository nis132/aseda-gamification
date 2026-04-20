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
        $tantangan = Tantangan::with(['mapel', 'kelas', 'soal'])
            ->findOrFail($tantanganId);

        if ($tantangan->guru_id !== Auth::id()) abort(403);

        return view('guru.tantangan.show', compact('tantangan'));
    }

    public function create($tantanganId)
    {
        $tantangan = Tantangan::with(['mapel', 'kelas'])
            ->findOrFail($tantanganId);

        if ($tantangan->guru_id !== Auth::id()) abort(403);

        return view('guru.soal.create', compact('tantangan'));
    }

    public function store(Request $request, $tantanganId)
    {
        $tantangan = Tantangan::findOrFail($tantanganId);

        if ($tantangan->guru_id !== Auth::id()) abort(403);

        // ===============================
        // 🔥 AMBIL DATA JSON DENGAN AMAN
        // ===============================
        $soals = json_decode($request->soal_data, true) ?? [];

        // ===============================
        // VALIDASI: HARUS ADA SOAL
        // ===============================
        if (!is_array($soals) || count($soals) === 0) {
            return back()->withErrors([
                'soal' => 'Belum ada soal yang ditambahkan'
            ]);
        }

        foreach ($soals as $s) {

            // ===============================
            // BASIC DATA
            // ===============================
            if (!isset($s['pertanyaan']) || !isset($s['tipe'])) {
                continue; // skip data rusak
            }

            $data = [
                'tantangan_id' => $tantanganId,
                'pertanyaan' => $s['pertanyaan'],
                'tipe' => $s['tipe'],
            ];

            // ===============================
            // PG (Pilihan Ganda)
            // ===============================
            if ($s['tipe'] === 'pg') {

                $data['opsi_a'] = $s['opsi_a'] ?? null;
                $data['opsi_b'] = $s['opsi_b'] ?? null;
                $data['opsi_c'] = $s['opsi_c'] ?? null;
                $data['opsi_d'] = $s['opsi_d'] ?? null;
                $data['jawaban_benar'] = $s['jawaban_benar'] ?? null;
            }

            // ===============================
            // ESSAY
            // ===============================
            if ($s['tipe'] === 'essay') {
                $data['jawaban_benar'] = $s['jawaban_benar'] ?? null;
            }

            // ===============================
            // MATCHING
            // ===============================
            if ($s['tipe'] === 'matching') {

                $kiri = $s['kiri_items'] ?? [];
                $kanan = $s['kanan_items'] ?? [];

                if (!is_array($kiri) || !is_array($kanan)) {
                    continue;
                }

                if (count($kiri) < 2 || count($kanan) < 2) {
                    continue;
                }

                $pairs = [];

                foreach ($kiri as $i => $val) {
                    $pairs[] = [
                        'kiri' => $val,
                        'kanan' => $kanan[$i] ?? null
                    ];
                }

                $data['kiri_items'] = json_encode($kiri);
                $data['kanan_items'] = json_encode($kanan);
                $data['matching_pairs'] = json_encode($pairs);
                $data['matching_count'] = count($kiri);
                $data['jawaban_benar'] = json_encode($pairs);
            }

            Soal::create($data);
        }

        return redirect()
            ->route('guru.tantangan.show', $tantanganId)
            ->with('success', 'Soal berhasil disimpan!');
    }
public function edit(Tantangan $tantangan, Soal $soal)
{
    if ($tantangan->guru_id !== Auth::id()) abort(403);

    return view('guru.soal.edit', compact('tantangan', 'soal'));
}

public function update(Request $request, Tantangan $tantangan, Soal $soal)
{
    if ($tantangan->guru_id !== Auth::id()) abort(403);

    $data = [
        'pertanyaan' => $request->pertanyaan,
        'tipe' => $request->tipe,
    ];

    // reset semua dulu
    $data['opsi_a'] = null;
    $data['opsi_b'] = null;
    $data['opsi_c'] = null;
    $data['opsi_d'] = null;
    $data['kiri_items'] = null;
    $data['kanan_items'] = null;
    $data['matching_pairs'] = null;
    $data['matching_count'] = null;
    $data['jawaban_benar'] = null;

    // ================= PG =================
    if ($request->tipe === 'pg') {
        $data['opsi_a'] = $request->opsi_a;
        $data['opsi_b'] = $request->opsi_b;
        $data['opsi_c'] = $request->opsi_c;
        $data['opsi_d'] = $request->opsi_d;
        $data['jawaban_benar'] = $request->jawaban_pg;
    }

    // ================= ESSAY =================
    if ($request->tipe === 'essay') {
        $data['jawaban_benar'] = $request->jawaban_essay;
    }

    // ================= MATCHING =================
    if ($request->tipe === 'matching') {

        $kiri = explode(',', $request->kiri);
        $kanan = explode(',', $request->kanan);

        $pairs = [];

        foreach ($kiri as $i => $val) {
            $pairs[] = [
                'kiri' => trim($val),
                'kanan' => trim($kanan[$i] ?? '')
            ];
        }

        $data['kiri_items'] = json_encode($kiri);
        $data['kanan_items'] = json_encode($kanan);
        $data['matching_pairs'] = json_encode($pairs);
        $data['matching_count'] = count($kiri);
        $data['jawaban_benar'] = json_encode($pairs);
    }

    // 🔥 INI YANG SERING KELEWAT
    $soal->update($data);

    return redirect()
        ->route('guru.tantangan.show', $tantangan)
        ->with('success', 'Soal berhasil diupdate!');
}

    public function destroy(Tantangan $tantangan, Soal $soal)
    {
        if ($tantangan->guru_id !== Auth::id()) abort(403);

        $soal->delete();

        return back()->with('success', 'Soal berhasil dihapus!');
    }
}