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

        $kelas = Kelas::when($request->search, function($q) use ($request) {
            $q->where('nama_kelas', 'like', '%' . $request->search . '%');
        })->paginate(15);

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
}
