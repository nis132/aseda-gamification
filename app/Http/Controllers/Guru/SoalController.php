<?php

namespace App\Http\Controllers\Guru;

use App\Exports\SoalTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\SoalImport;
use App\Models\Soal;
use App\Models\Tantangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SoalController extends Controller
{
    public function index($tantanganId)
    {
        $tantangan = Tantangan::with(['mapel', 'kelas', 'soal'])
            ->findOrFail($tantanganId);

        if ($tantangan->guru_id !== Auth::id()) abort(403);

        return view('guru.tantangan.show', compact('tantangan'));
    }

    public function downloadTemplate($tantangan, $tipe)
    {
        if (! in_array($tipe, ['pg', 'essay', 'matching'])) {
            abort(404);
        }

        return Excel::download(
            new SoalTemplateExport($tipe),
            'format_soal_'.$tipe.'.xlsx'
        );
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

    $soals = json_decode($request->soal_data, true) ?? [];

    if (!is_array($soals) || count($soals) === 0) {
        return back()->withErrors([
            'soal' => 'Belum ada soal yang ditambahkan. Tambahkan minimal 1 soal sebelum menyimpan.',
        ])->withInput();
    }

    $errors = [];

    foreach ($soals as $i => $s) {
        $no = $i + 1;

        if (empty($s['pertanyaan'])) {
            $errors[] = "Soal #$no: Pertanyaan tidak boleh kosong.";
        }

        if (empty($s['tipe'])) {
            $errors[] = "Soal #$no: Jenis soal tidak valid.";
            continue;
        }

        if ($s['tipe'] === 'pg') {
            foreach (['opsi_a', 'opsi_b', 'opsi_c', 'opsi_d'] as $opsi) {
                if (empty($s[$opsi])) {
                    $errors[] = "Soal #$no (PG): Semua opsi jawaban (A-D) wajib diisi.";
                    break;
                }
            }
            if (empty($s['jawaban_benar']) || !in_array($s['jawaban_benar'], ['A','B','C','D'])) {
                $errors[] = "Soal #$no (PG): Kunci jawaban wajib dipilih (A/B/C/D).";
            }
        }

        if ($s['tipe'] === 'essay') {
            if (empty($s['jawaban_benar'])) {
                $errors[] = "Soal #$no (Esai): Kunci jawaban wajib diisi.";
            }
        }

        if ($s['tipe'] === 'matching') {
            $kiri  = $s['kiri_items']  ?? [];
            $kanan = $s['kanan_items'] ?? [];

            if (!is_array($kiri) || !is_array($kanan)) {
                $errors[] = "Soal #$no (Menjodohkan): Format data tidak valid.";
                continue;
            }
            if (count($kiri) < 2 || count($kanan) < 2) {
                $errors[] = "Soal #$no (Menjodohkan): Minimal 2 pasangan item diperlukan.";
            }
            if (count($kiri) !== count($kanan)) {
                $errors[] = "Soal #$no (Menjodohkan): Jumlah item kiri dan kanan harus sama.";
            }
        }
    }

    if (!empty($errors)) {
        return back()->withErrors(['soal_detail' => $errors])->withInput();
    }

    // simpan semua soal
    foreach ($soals as $s) {
        if (!isset($s['pertanyaan']) || !isset($s['tipe'])) continue;

        $data = [
            'tantangan_id' => $tantanganId,
            'pertanyaan'   => $s['pertanyaan'],
            'tipe'         => $s['tipe'],
        ];

        if ($s['tipe'] === 'pg') {
            $data['opsi_a']        = $s['opsi_a'] ?? null;
            $data['opsi_b']        = $s['opsi_b'] ?? null;
            $data['opsi_c']        = $s['opsi_c'] ?? null;
            $data['opsi_d']        = $s['opsi_d'] ?? null;
            $data['jawaban_benar'] = $s['jawaban_benar'] ?? null;
        }

        if ($s['tipe'] === 'essay') {
            $data['jawaban_benar'] = $s['jawaban_benar'] ?? null;
        }

        if ($s['tipe'] === 'matching') {
            $kiri  = $s['kiri_items']  ?? [];
            $kanan = $s['kanan_items'] ?? [];
            $pairs = [];
            foreach ($kiri as $idx => $val) {
                $pairs[] = ['kiri' => $val, 'kanan' => $kanan[$idx] ?? null];
            }
            $data['kiri_items']     = json_encode($kiri);
            $data['kanan_items']    = json_encode($kanan);
            $data['matching_pairs'] = json_encode($pairs);
            $data['matching_count'] = count($kiri);
            $data['jawaban_benar']  = json_encode($pairs);
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
    $data['matching_count'] = 0;
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

$kiri = array_filter(explode(',', $request->kiri ?? ''));
$kanan = array_filter(explode(',', $request->kanan ?? ''));

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
        $data['matching_count'] = !empty($kiri) ? count($kiri) : 0;
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