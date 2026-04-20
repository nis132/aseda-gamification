<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\TantanganBaruNotification;
// use Illuminate\Support\Facades\Notification; // ❌ DIHAPUS
// use App\Notifications\TantanganBaruNotification; // ❌ DIHAPUS

class TantanganController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();
        $tantangan = Tantangan::with(['mapel', 'kelas', 'guru'])
            ->where('guru_id', $guruId)
            ->latest()
            ->paginate(10);

        return view('guru.tantangan.index', compact('tantangan'));
    }

    public function create()
    {
        $kelas = Kelas::select('id', 'nama_kelas')->get();
        return view('guru.tantangan.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'batas_waktu' => 'required|date|after:now',
            'poin' => 'required|integer|min:1|max:1000',
        ]);

        $guru = Auth::user();

        // ambil mapel pertama guru
        $mapel = $guru->mapel()->first();

        if (!$mapel) {
            return back()->withErrors(['mapel' => 'Guru belum memiliki mapel!']);
        }

        $tantangan = Tantangan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'batas_waktu' => $request->batas_waktu,
            'poin' => $request->poin,
            'status' => 'draft',
        ]);

        return redirect()->route('guru.soal.create', $tantangan)
            ->with('success', 'Tantangan dibuat! Sekarang tambahkan soal.');
    }

    public function show(Tantangan $tantangan)
    {
        if ($tantangan->guru_id != Auth::id()) {
            abort(403);
        }

        $tantangan->load(['soal', 'mapel', 'kelas', 'jawabanSiswa.siswa', 'nilaiTantangan.siswa']);
        $siswaCount = DB::table('siswa_kelas')
            ->where('kelas_id', $tantangan->kelas_id)
            ->count();

        return view('guru.tantangan.show', compact('tantangan', 'siswaCount'));
    }

public function publish(Request $request, $id)
{
    $tantangan = Tantangan::findOrFail($id);

    $tantangan->update([
        'status' => 'published',
        'kelas_id' => $request->kelas_id
    ]);

    $siswas = User::where('role', 'siswa')->get();

    foreach ($siswas as $siswa) {
        $siswa->notify(new TantanganBaruNotification($tantangan));
    }

    return back()->with('success', 'Tantangan berhasil dipublish & notifikasi terkirim!');
}

    public function unpublish(Tantangan $tantangan)
    {
        if ($tantangan->guru_id !== Auth::id()) { 
            abort(403); 
        }

        $tantangan->update([
            'status' => 'draft'
        ]);

        return back()->with('success', 'Tantangan ditarik kembali (menjadi draft).');
    }

    public function edit(Tantangan $tantangan)
    {
        if ($tantangan->guru_id != Auth::id()) {
            abort(403);
        }

        $kelas = Kelas::select('id', 'nama_kelas')->get();
        $mapelGuru = Mapel::whereHas('guruMapel', function($q) {
            $q->where('guru_id', Auth::id());
        })->select('id', 'nama_mapel')->get();

        return view('guru.tantangan.edit', compact('tantangan', 'kelas', 'mapelGuru'));
    }

    public function update(Request $request, Tantangan $tantangan)
    {
        if ($tantangan->guru_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'batas_waktu' => 'required|date|after:now',
            'poin' => 'required|integer|min:1|max:1000',
        ]);

        $tantangan->update($request->all());

        return redirect()->route('guru.tantangan.index')
            ->with('success', 'Tantangan berhasil diupdate!');
    }

    public function destroy(Tantangan $tantangan)
    {
        if ($tantangan->guru_id != Auth::id()) {
            abort(403);
        }

        $tantangan->delete();

        return back()->with('success', 'Tantangan dihapus!');
    }
}