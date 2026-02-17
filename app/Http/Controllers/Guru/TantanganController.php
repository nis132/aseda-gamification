<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tantangan;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TantanganController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();
        
        $tantangan = Tantangan::with(['mapel', 'kelas'])
            ->where('guru_id', $guruId)
            ->latest()
            ->paginate(10);

        return view('guru.tantangan.index', compact('tantangan'));
    }

public function create()
{
    $kelas = Kelas::all(['id', 'nama_kelas']);
    $mapelGuru = Mapel::whereHas('guruMapel', function($q) {
        $q->where('guru_id', Auth::id());
    })->with('guruMapel')->get(['id', 'nama_mapel']);

    return view('guru.tantangan.create', compact('kelas', 'mapelGuru'));
}

public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|max:255',
        'deskripsi' => 'required',
        'mapel_id' => 'required|exists:mapel,id',
        'kelas_id' => 'required|exists:kelas,id',
        'tipe' => 'required|in:pg,essay,matching',
        'batas_waktu' => 'required|date|after:now',
        'poin' => 'required|integer|min:1|max:1000'
    ]);

    $tantangan = Tantangan::create(array_merge($request->all(), [
        'guru_id' => Auth::id()
    ]));

    return redirect()->route('guru.tantangan.show', $tantangan)
        ->with('success', 'Tantangan berhasil dibuat! Tambahkan soal sekarang.');
}

public function show(Tantangan $tantangan)
{
    if ($tantangan->guru_id !== Auth::id()) {
        abort(403);
    }

    $tantangan->load(['soal', 'mapel', 'kelas', 'jawabanSiswa.siswa', 'nilaiTantangan.siswa']);
    
    $siswaCount = DB::table('siswa_kelas')
        ->where('kelas_id', $tantangan->kelas_id)
        ->count();

    return view('guru.tantangan.show', compact('tantangan', 'siswaCount'));
}



    public function edit(Tantangan $tantangan)
    {
        if ($tantangan->guru_id !== Auth::id()) {
            abort(403);
        }

        $kelas = Kelas::all();
        $mapelGuru = Mapel::whereHas('guruMapel', function($q) {
            $q->where('guru_id', Auth::id());
        })->get();

        return view('guru.tantangan.edit', compact('tantangan', 'kelas', 'mapelGuru'));
    }

    public function update(Request $request, Tantangan $tantangan)
    {
        if ($tantangan->guru_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tipe' => 'required|in:pg,essay,matching',
            'batas_waktu' => 'required|date|after:now',
            'poin' => 'required|integer|min:1|max:1000'
        ]);

        $tantangan->update($request->all());

        return redirect()->route('guru.tantangan.index')
            ->with('success', 'Tantangan berhasil diupdate!');
    }

    public function destroy(Tantangan $tantangan)
    {
        if ($tantangan->guru_id !== Auth::id()) {
            abort(403);
        }

        $tantangan->delete();
        return back()->with('success', 'Tantangan berhasil dihapus!');
    }
}
