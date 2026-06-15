<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
public function index(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

$kelas = Kelas::withCount('siswa')
    ->when($request->search, function($q) use ($request) {
        $q->where('nama_kelas', 'like', '%' . $request->search . '%');
    })
    ->paginate(15);

    return view('admin.kelas.index', compact('kelas'));
}
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.kelas.create');
    }

public function store(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $request->validate([
        'nama_kelas' => 'required|string|max:10|unique:kelas,nama_kelas'
    ], [
        // Pesan kustom langsung di sini
        'nama_kelas.required' => 'Nama kelas wajib diisi.',
        'nama_kelas.unique'   => 'Nama kelas sudah terdaftar.',
        'nama_kelas.max'      => 'Nama kelas maksimal 10 karakter.',
    ]);

    Kelas::create($request->only('nama_kelas'));

    return redirect()->route('admin.kelas.index')
        ->with('success', 'Kelas ' . $request->nama_kelas . ' berhasil dibuat!');
}

    public function edit(Kelas $kelas)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:10|unique:kelas,nama_kelas,' . $kelas->id
        ]);

        $kelas->update($request->only('nama_kelas'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas ' . $request->nama_kelas . ' berhasil diupdate!');
    }

    public function destroy(Kelas $kelas)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $kelas->delete();
        return back()->with('success', 'Kelas dihapus!');
    }
    public function show(Kelas $kelas)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $siswa = $kelas->siswa()->where('role', 'siswa')->get();

    return view('admin.kelas.show', compact('kelas', 'siswa'));
}

}
