<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\User;
use App\Models\Kelas;
use App\Models\GuruMapelKelas;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $mapel = Mapel::with(['guruMapelKelas.guru', 'guruMapelKelas.kelas'])
            ->when($request->search, fn($q) => $q->where('nama_mapel', 'like', '%'.$request->search.'%'))
            ->paginate(15);

        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $guru  = User::where('role', 'guru')->orderBy('nama')->get(['id', 'nama']);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.mapel.create', compact('guru', 'kelas'));
    }

    public function edit(Mapel $mapel)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $mapel->load('guruMapelKelas.guru', 'guruMapelKelas.kelas');
        $guru  = User::where('role', 'guru')->orderBy('nama')->get(['id', 'nama']);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.mapel.edit', compact('mapel', 'guru', 'kelas'));
    }

    public function store(Request $request)
{
    if (!auth()->user()->isAdmin()) abort(403);

    $request->validate([
        'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        'pairs'      => 'nullable|array',
    ], [
        'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
        'nama_mapel.string'   => 'Nama mata pelajaran harus berupa teks.',
        'nama_mapel.max'      => 'Nama mata pelajaran maksimal 100 karakter.',
        'nama_mapel.unique'   => 'Mata pelajaran dengan nama ini sudah terdaftar.',
    ]);

    $mapel = Mapel::create(['nama_mapel' => $request->nama_mapel]);
    $this->savePairs($mapel->id, $request->input('pairs', []));

    return redirect()->route('admin.mapel.index')
        ->with('success', 'Mata pelajaran berhasil ditambahkan!');
}

public function update(Request $request, Mapel $mapel)
{
    if (!auth()->user()->isAdmin()) abort(403);

    $request->validate([
        'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel,'.$mapel->id,
        'pairs'      => 'nullable|array',
    ], [
        'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
        'nama_mapel.string'   => 'Nama mata pelajaran harus berupa teks.',
        'nama_mapel.max'      => 'Nama mata pelajaran maksimal 100 karakter.',
        'nama_mapel.unique'   => 'Nama mata pelajaran sudah digunakan oleh mapel lain.',
    ]);

    $mapel->update(['nama_mapel' => $request->nama_mapel]);
    GuruMapelKelas::where('mapel_id', $mapel->id)->delete();
    $this->savePairs($mapel->id, $request->input('pairs', []));

    return redirect()->route('admin.mapel.index')
        ->with('success', 'Mata pelajaran berhasil diperbarui!');
}

public function destroy(Mapel $mapel)
{
    if (!auth()->user()->isAdmin()) abort(403);

    GuruMapelKelas::where('mapel_id', $mapel->id)->delete();
    $mapel->delete();

    return back()->with('success', 'Mata pelajaran berhasil dihapus!');
}

    /**
     * Simpan dari format pairs[guru_id][] = kelas_id.
     * Tiap pasangan guru-kelas = 1 baris di guru_mapel_kelas.
     * Guru yang tidak ada di $pairs (tidak centang apapun) tidak disimpan.
     */
    private function savePairs(int $mapelId, array $pairs): void
    {
        foreach ($pairs as $guruId => $kelasIds) {
            if (empty($kelasIds)) continue;

            foreach ($kelasIds as $kelasId) {
                GuruMapelKelas::create([
                    'guru_id'  => $guruId,
                    'mapel_id' => $mapelId,
                    'kelas_id' => $kelasId,
                ]);
            }
        }
    }
}