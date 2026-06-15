<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tantangan;
use App\Models\JawabanSiswa;
use App\Models\NilaiTantangan;
use App\Services\BadgeService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $siswa = User::findOrFail($siswaId);

        $jawaban = JawabanSiswa::with('soal')
            ->where('tantangan_id', $id)
            ->where('siswa_id', $siswaId)
            ->get();

        $semuaPengerja = JawabanSiswa::where('tantangan_id', $id)
            ->distinct()
            ->pluck('siswa_id')
            ->toArray();

        $currentIndex = array_search($siswaId, $semuaPengerja);

        $prevId = ($currentIndex > 0) ? $semuaPengerja[$currentIndex - 1] : null;
        $nextId = ($currentIndex < count($semuaPengerja) - 1) ? $semuaPengerja[$currentIndex + 1] : null;

        $totalSiswa = count($semuaPengerja);
        $nomorUrut = $currentIndex !== false ? $currentIndex + 1 : null;

        return view('guru.nilai.detail', compact(
            'tantangan', 
            'jawaban', 
            'siswa', 
            'prevId', 
            'nextId', 
            'totalSiswa', 
            'nomorUrut'
        ));
    }

    public function simpanNilai(Request $request, $id, $siswaId)
    {
        DB::beginTransaction();

        try {
            $jawaban = JawabanSiswa::where('tantangan_id', $id)
                ->where('siswa_id', $siswaId)
                ->get();

            // Validasi: semua soal manual di halaman ini wajib diisi
            $rules = [];
            foreach ($jawaban as $j) {
                if ($j->dinilai_manual) {
                    $rules['nilai_' . $j->id] = 'required|numeric|min:0|max:100';
                }
            }

            $request->validate($rules, [
                '*.required' => 'Semua nilai uraian harus diisi.',
                '*.numeric'  => 'Nilai harus berupa angka.',
                '*.min'      => 'Nilai minimal 0.',
                '*.max'      => 'Nilai maksimal 100.',
            ]);

            // Simpan nilai soal uraian
            foreach ($jawaban as $j) {
                if ($j->dinilai_manual) {
                    $field = 'nilai_' . $j->id;
                    if ($request->filled($field)) {
                        $j->nilai = $request->$field;
                        $j->save();
                    }
                }
            }

            $jawabanFresh  = JawabanSiswa::where('tantangan_id', $id)
                ->where('siswa_id', $siswaId)
                ->get();

            $totalNilai      = 0;
            $jumlahSoal      = 0;
            $masihAdaPending = false;

            foreach ($jawabanFresh as $j) {
                if (!is_null($j->nilai)) {
                    $totalNilai += $j->nilai;
                    $jumlahSoal++;
                } else {
                    $masihAdaPending = true; // masih ada uraian belum dinilai
                }
            }

            $rataNilai   = $jumlahSoal > 0 ? $totalNilai / $jumlahSoal : 0;
            $tantangan   = Tantangan::findOrFail($id);
            $poinDidapat = !$masihAdaPending
                ? round(($rataNilai / 100) * $tantangan->poin)
                : 0;

            NilaiTantangan::updateOrCreate(
                ['siswa_id' => $siswaId, 'tantangan_id' => $id],
                [
                    'total_nilai'  => $rataNilai,
                    'poin_didapat' => $poinDidapat,
                    'is_pending'   => $masihAdaPending,
                ]
            );

            // Badge dicek HANYA kalau semua soal sudah dinilai
            if (!$masihAdaPending) {
                $tantanganModel = Tantangan::find($id);
                if ($tantanganModel) {
                    \App\Services\BadgeService::checkAll($siswaId, $tantanganModel->mapel_id);
                }
            }

            DB::commit();

            $msg = $masihAdaPending
                ? 'Nilai tersimpan. Masih ada soal uraian lain yang belum dinilai.'
                : 'Nilai berhasil disimpan & diperbarui! Badge siswa sudah dicek.';

            return redirect()
                ->route('guru.nilai.detail', [$id, $siswaId])
                ->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Buka review untuk satu siswa atau semua siswa dalam satu tantangan
     */
    public function bukaReview(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'nullable|exists:users,id',
        ]);

        $tantangan = Tantangan::findOrFail($id);

        // Cek otorisasi: hanya guru pemilik tantangan
        if ($tantangan->guru_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak berhak membuka review tantangan ini.');
        }

        $siswaId = $request->siswa_id;

        if ($siswaId) {
            // Buka review untuk satu siswa
            $nilai = NilaiTantangan::where('siswa_id', $siswaId)
                ->where('tantangan_id', $id)
                ->firstOrFail();

            $nilai->update(['review_dibuka_pada' => now()]);

            return back()->with('success', 'Review berhasil dibuka untuk siswa ini!');
        } else {
            // Buka review untuk semua siswa dalam tantangan ini
            $updated = NilaiTantangan::where('tantangan_id', $id)
                ->whereNull('review_dibuka_pada')
                ->update(['review_dibuka_pada' => now()]);

            return back()->with('success', "Review berhasil dibuka untuk {$updated} siswa!");
        }
    }

    /**
     * Tutup review untuk satu siswa atau semua siswa
     */
    public function tutupReview(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'nullable|exists:users,id',
        ]);

        $tantangan = Tantangan::findOrFail($id);

        // Cek otorisasi
        if ($tantangan->guru_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak berhak menutup review tantangan ini.');
        }

        $siswaId = $request->siswa_id;

        if ($siswaId) {
            // Tutup review untuk satu siswa
            $nilai = NilaiTantangan::where('siswa_id', $siswaId)
                ->where('tantangan_id', $id)
                ->firstOrFail();

            $nilai->update(['review_dibuka_pada' => null]);

            return back()->with('success', 'Review berhasil ditutup untuk siswa ini!');
        } else {
            // Tutup review untuk semua siswa
            $updated = NilaiTantangan::where('tantangan_id', $id)
                ->whereNotNull('review_dibuka_pada')
                ->update(['review_dibuka_pada' => null]);

            return back()->with('success', "Review berhasil ditutup untuk {$updated} siswa!");
        }
    }
}