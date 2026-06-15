<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\TantanganKelas;
use App\Models\GuruMapelKelas;
use Illuminate\Http\Request;
use App\Jobs\KirimNotifikasiTantangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TantanganController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();

        $mapelIds = GuruMapelKelas::where('guru_id', $guruId)->pluck('mapel_id');

        $tantangan = Tantangan::with(['mapel', 'guru', 'publishKelas.kelas'])
            ->whereIn('mapel_id', $mapelIds)
            ->orderBy('bab')
            ->orderBy('urutan')
            ->paginate(10);

        return view('guru.tantangan.index', compact('tantangan'));
    }

    public function create()
    {
        $relasi = GuruMapelKelas::with(['mapel', 'kelas'])
            ->where('guru_id', auth()->id())
            ->whereNotNull('kelas_id')
            ->get();

        return view('guru.tantangan.create', compact('relasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'               => 'required|max:255',
            'deskripsi'           => 'required',
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
            'batas_waktu'         => 'required|date|after:now',
            'poin'                => 'required|integer|min:1|max:1000',
            'difficulty'          => 'required|in:chapter_1,chapter_2,chapter_3,chapter_4,chapter_5,chapter_6,chapter_7,chapter_8,easy,medium,hard,expert',
            'bab'                 => 'required|string|max:100',
        ], [
            'judul.required'               => 'Nama tantangan wajib diisi.',
            'judul.max'                    => 'Nama tantangan maksimal 255 karakter.',
            'deskripsi.required'           => 'Instruksi misi wajib diisi.',
            'guru_mapel_kelas_id.required' => 'Target kelas & mapel wajib dipilih.',
            'guru_mapel_kelas_id.exists'   => 'Kelas & mapel yang dipilih tidak valid.',
            'batas_waktu.required'         => 'Batas waktu misi wajib diisi.',
            'batas_waktu.date'             => 'Format batas waktu tidak valid.',
            'batas_waktu.after'            => 'Batas waktu harus lebih dari waktu sekarang.',
            'poin.required'                => 'Reward poin wajib diisi.',
            'poin.integer'                 => 'Poin harus berupa angka bulat.',
            'poin.min'                     => 'Poin minimal adalah 1.',
            'poin.max'                     => 'Poin maksimal adalah 1.000.',
            'difficulty.required'          => 'Tingkat kesulitan wajib dipilih.',
            'difficulty.in'                => 'Tingkat kesulitan tidak valid.',
            'bab.required'                 => 'BAB wajib dipilih.',
        ]);

        $relasi = GuruMapelKelas::where('id', $request->guru_mapel_kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        // Extract nomor BAB dari string "BAB X" (e.g., "BAB 1" -> 1)
        $babString = $request->bab;
        preg_match('/\d+/', $babString, $matches);
        $babInt = (int) ($matches[0] ?? 1);

        // ✅ FIX BUG #1: Urutan dihitung PER-BAB, bukan global seluruh mapel+kelas.
        // Sebelumnya: max(urutan) dari semua bab → bab baru mulai dari urutan 4, bukan 1.
        // Sekarang: max(urutan) hanya dalam bab yang sama → tiap bab mulai dari urutan 1.
        $urutanBerikutnya = Tantangan::where('mapel_id', $relasi->mapel_id)
            ->where('kelas_id', $relasi->kelas_id)
            ->where('bab', $babInt)
            ->max('urutan') + 1;

        $tantangan = Tantangan::create([
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'mapel_id'    => $relasi->mapel_id,
            'kelas_id'    => $relasi->kelas_id,
            'guru_id'     => auth()->id(),
            'batas_waktu' => $request->batas_waktu,
            'poin'        => $request->poin,
            'status'      => 'draft',
            'urutan'      => $urutanBerikutnya,
            'difficulty'  => $request->difficulty,
            'bab'         => $babInt,
        ]);

        return redirect()->route('guru.soal.create', $tantangan)
            ->with('success', 'Tantangan berhasil dibuat! Sekarang tambahkan soal.');
    }

    public function show(Tantangan $tantangan)
    {
        $guruId   = auth()->id();
        $mapelIds = GuruMapelKelas::where('guru_id', $guruId)->pluck('mapel_id');

        if (!in_array($tantangan->mapel_id, $mapelIds->toArray())) abort(403);

        $tantangan->load(['soal', 'mapel', 'guru', 'publishKelas.kelas', 'publishKelas.guru']);

        return view('guru.tantangan.show', compact('tantangan'));
    }

    public function publish(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ], [
            'kelas_id.required' => 'Kelas tujuan publish wajib dipilih.',
            'kelas_id.exists'   => 'Kelas yang dipilih tidak ditemukan.',
        ]);

        $tantangan = Tantangan::with('soal')->findOrFail($id);

        $boleh = GuruMapelKelas::where('guru_id', auth()->id())
            ->where('kelas_id', $request->kelas_id)
            ->where('mapel_id', $tantangan->mapel_id)
            ->exists();

        if (!$boleh) {
            return back()->with('error', 'Anda tidak mengampu kelas ini.');
        }

        if ($tantangan->soal->count() == 0) {
            return back()->with('error', 'Tambahkan minimal 1 soal sebelum publish.');
        }

        TantanganKelas::updateOrCreate(
            ['tantangan_id' => $tantangan->id, 'kelas_id' => $request->kelas_id],
            ['guru_id' => auth()->id(), 'batas_waktu' => $tantangan->batas_waktu, 'status' => 'published']
        );

        $tantangan->update(['status' => 'published']);

        KirimNotifikasiTantangan::dispatch($tantangan, $request->kelas_id);

        return back()->with('success', 'Tantangan berhasil dipublish & notifikasi sedang dikirim!');
    }

    public function unpublish($id, $kelasId)
    {
        $publish   = TantanganKelas::where('tantangan_id', $id)->where('kelas_id', $kelasId)->firstOrFail();
        $tantangan = Tantangan::findOrFail($id);

        $boleh = GuruMapelKelas::where('guru_id', auth()->id())
            ->where('kelas_id', $kelasId)
            ->where('mapel_id', $tantangan->mapel_id)
            ->exists();

        if (!$boleh) abort(403, 'Anda tidak mengampu kelas ini untuk mata pelajaran ini.');

        $publish->delete();

        $masihPublish = TantanganKelas::where('tantangan_id', $id)->where('status', 'published')->exists();
        $tantangan->update(['status' => $masihPublish ? 'published' : 'draft']);

        return back()->with('success', 'Publish tantangan untuk kelas berhasil dibatalkan.');
    }

    public function edit(Tantangan $tantangan)
    {
        if ($tantangan->guru_id != auth()->id()) abort(403);

        if ($tantangan->status === 'published') {
            return redirect()->route('guru.tantangan.index')
                ->with('error', 'Tantangan yang sudah dipublish tidak dapat diedit.');
        }

        $relasi = GuruMapelKelas::with(['mapel', 'kelas'])
            ->where('guru_id', auth()->id())
            ->get();

        return view('guru.tantangan.edit', compact('tantangan', 'relasi'));
    }

    public function update(Request $request, Tantangan $tantangan)
    {
        if ($tantangan->guru_id != auth()->id()) abort(403);

        if ($tantangan->status === 'published') {
            return redirect()->route('guru.tantangan.index')
                ->with('error', 'Tantangan yang sudah dipublish tidak dapat diubah.');
        }

        $request->validate([
            'judul'               => 'required|max:255',
            'deskripsi'           => 'required',
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
            'batas_waktu'         => 'required|date',
            'poin'                => 'required|integer|min:1|max:1000',
            'bab'                 => 'nullable|string|max:100',
        ], [
            'judul.required'               => 'Nama tantangan wajib diisi.',
            'judul.max'                    => 'Nama tantangan maksimal 255 karakter.',
            'deskripsi.required'           => 'Instruksi misi wajib diisi.',
            'guru_mapel_kelas_id.required' => 'Target kelas & mapel wajib dipilih.',
            'batas_waktu.required'         => 'Batas waktu misi wajib diisi.',
            'poin.required'                => 'Reward poin wajib diisi.',
        ]);

        $relasi = GuruMapelKelas::findOrFail($request->guru_mapel_kelas_id);

        // Normalize bab input jika ada (ambil angka saja)
        $babValue = $tantangan->bab;
        if ($request->filled('bab')) {
            preg_match('/\d+/', $request->bab, $matches);
            $babValue = (int) ($matches[0] ?? $tantangan->bab);
        }

        $tantangan->update([
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'mapel_id'    => $relasi->mapel_id,
            'kelas_id'    => $relasi->kelas_id,
            'batas_waktu' => $request->batas_waktu,
            'poin'        => $request->poin,
            'bab'         => $babValue,
        ]);

        return redirect()->route('guru.tantangan.index')
            ->with('success', 'Tantangan berhasil diupdate.');
    }

    public function destroy(Tantangan $tantangan)
    {
        if ($tantangan->guru_id != auth()->id()) abort(403);

        $tantangan->delete();

        return back()->with('success', 'Tantangan berhasil dihapus.');
    }

    /**
     * Form buat pengayaan untuk tantangan yang sudah published.
     * Pengayaan adalah tantangan remedial yang bisa dikerjakan siswa
     * jika tantangan aslinya sudah expired tanpa dikerjakan.
     */
    public function createPengayaan(Tantangan $tantangan)
    {
        $guruId   = auth()->id();
        $mapelIds = GuruMapelKelas::where('guru_id', $guruId)->pluck('mapel_id');

        if (!in_array($tantangan->mapel_id, $mapelIds->toArray())) abort(403);

        // Cegah buat pengayaan untuk pengayaan
        if ($tantangan->is_pengayaan) {
            return redirect()->route('guru.tantangan.show', $tantangan)
                ->with('error', 'Pengayaan tidak bisa dibuat dari tantangan pengayaan.');
        }

        // Cegah duplikat — satu tantangan hanya boleh punya satu pengayaan
        if ($tantangan->pengayaan) {
            return redirect()->route('guru.tantangan.show', $tantangan)
                ->with('error', 'Tantangan ini sudah memiliki pengayaan.');
        }

        return view('guru.tantangan.create-pengayaan', compact('tantangan'));
    }

    /**
     * Simpan pengayaan baru dan langsung arahkan ke halaman tambah soal.
     */
    public function storePengayaan(Request $request, Tantangan $tantangan)
    {
        $guruId   = auth()->id();
        $mapelIds = GuruMapelKelas::where('guru_id', $guruId)->pluck('mapel_id');

        if (!in_array($tantangan->mapel_id, $mapelIds->toArray())) abort(403);

        if ($tantangan->is_pengayaan) abort(422, 'Pengayaan tidak bisa dibuat dari tantangan pengayaan.');
        if ($tantangan->pengayaan)    abort(422, 'Tantangan ini sudah memiliki pengayaan.');

        $request->validate([
            'judul'       => 'required|max:255',
            'deskripsi'   => 'required',
            'batas_waktu' => 'required|date|after:now',
            'poin'        => 'required|integer|min:1|max:1000',
        ], [
            'judul.required'       => 'Judul pengayaan wajib diisi.',
            'deskripsi.required'   => 'Instruksi pengayaan wajib diisi.',
            'batas_waktu.required' => 'Batas waktu pengayaan wajib diisi.',
            'batas_waktu.after'    => 'Batas waktu harus lebih dari sekarang.',
            'poin.required'        => 'Poin pengayaan wajib diisi.',
            'poin.min'             => 'Poin minimal 1.',
            'poin.max'             => 'Poin maksimal 1.000.',
        ]);

        $pengayaan = Tantangan::create([
            'judul'               => $request->judul,
            'deskripsi'           => $request->deskripsi,
            'mapel_id'            => $tantangan->mapel_id,
            'kelas_id'            => $tantangan->kelas_id,
            'guru_id'             => $guruId,
            'batas_waktu'         => $request->batas_waktu,
            'poin'                => $request->poin,
            'status'              => 'draft',
            'difficulty'          => $tantangan->difficulty,
            'bab'                 => $tantangan->bab,
            'is_pengayaan'        => 1,
            'parent_tantangan_id' => $tantangan->id,
            // Pengayaan tidak masuk urutan normal — null agar tidak mengganggu isLockedFor()
            'urutan'              => null,
        ]);

        return redirect()->route('guru.soal.create', $pengayaan)
            ->with('success', 'Pengayaan berhasil dibuat! Sekarang tambahkan soal untuk pengayaan ini.');
    }

        public function createRemedial(Tantangan $tantangan)
    {
        $guruId   = auth()->id();
        $mapelIds = GuruMapelKelas::where('guru_id', $guruId)->pluck('mapel_id');
 
        if (!in_array($tantangan->mapel_id, $mapelIds->toArray())) abort(403);
 
        // Cegah buat remedial untuk remedial atau pengayaan
        if ($tantangan->is_remedial || $tantangan->is_pengayaan) {
            return redirect()->route('guru.tantangan.show', $tantangan)
                ->with('error', 'Remedial tidak bisa dibuat dari tantangan remedial/pengayaan.');
        }
 
        // Cegah duplikat
        if ($tantangan->remedial) {
            return redirect()->route('guru.tantangan.show', $tantangan)
                ->with('error', 'Tantangan ini sudah memiliki remedial.');
        }
 
        return view('guru.tantangan.create-remedial', compact('tantangan'));
    }
 
    /**
     * Simpan REMEDIAL — langsung arahkan ke tambah soal.
     */
    public function storeRemedial(Request $request, Tantangan $tantangan)
    {
        $guruId   = auth()->id();
        $mapelIds = GuruMapelKelas::where('guru_id', $guruId)->pluck('mapel_id');
 
        if (!in_array($tantangan->mapel_id, $mapelIds->toArray())) abort(403);
 
        if ($tantangan->is_remedial || $tantangan->is_pengayaan)
            abort(422, 'Remedial tidak bisa dibuat dari remedial/pengayaan.');
 
        if ($tantangan->remedial)
            abort(422, 'Tantangan ini sudah memiliki remedial.');
 
        $request->validate([
            'judul'       => 'required|max:255',
            'deskripsi'   => 'required',
            'batas_waktu' => 'required|date|after:now',
            'poin'        => 'required|integer|min:1|max:1000',
        ], [
            'judul.required'       => 'Judul remedial wajib diisi.',
            'deskripsi.required'   => 'Instruksi remedial wajib diisi.',
            'batas_waktu.required' => 'Batas waktu remedial wajib diisi.',
            'batas_waktu.after'    => 'Batas waktu harus lebih dari sekarang.',
            'poin.required'        => 'Poin remedial wajib diisi.',
            'poin.min'             => 'Poin minimal 1.',
            'poin.max'             => 'Poin maksimal 1.000.',
        ]);
 
        $remedial = Tantangan::create([
            'judul'               => $request->judul,
            'deskripsi'           => $request->deskripsi,
            'mapel_id'            => $tantangan->mapel_id,
            'kelas_id'            => $tantangan->kelas_id,
            'guru_id'             => $guruId,
            'batas_waktu'         => $request->batas_waktu,
            'poin'                => $request->poin,
            'status'              => 'published', // langsung published agar siswa bisa akses
            'difficulty'          => $tantangan->difficulty,
            'bab'                 => $tantangan->bab,
            'is_remedial'         => 1,
            'is_pengayaan'        => 0,
            'parent_tantangan_id' => $tantangan->id,
            'urutan'              => null, // tidak masuk urutan normal
        ]);
 
        return redirect()->route('guru.soal.create', $remedial)
            ->with('success', 'Remedial berhasil dibuat! Sekarang tambahkan soal.');
    }
}