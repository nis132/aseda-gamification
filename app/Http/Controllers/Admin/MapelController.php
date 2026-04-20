<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\User; 
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $mapel = Mapel::withCount('guru')
            ->when($request->search, function($q) use ($request) {
                $q->where('nama_mapel', 'like', '%' . $request->search . '%');
            })->paginate(15);

        $guru = User::where('role', 'guru')->get();
        
        return view('admin.mapel.index', compact('mapel', 'guru'));
    }

public function create()
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }
    
    $guru = User::where('role', 'guru')->get(['id', 'nama']);
    return view('admin.mapel.create', compact('guru'));
}

public function store(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $request->validate([
        'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        'guru_id' => 'nullable|exists:users,id'
    ]);

    $mapel = Mapel::create($request->only('nama_mapel'));
    
    if ($request->filled('guru_id')) {
        $mapel->guru()->attach($request->guru_id);
    }

    $guruNama = $request->guru_id ? User::find($request->guru_id)->nama : 'Belum ditugaskan';
    
    return redirect()->route('admin.mapel.index')
        ->with('success', "Mapel '{$request->nama_mapel}' berhasil dibuat! Guru: $guruNama");
}

    public function show(Mapel $mapel)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.mapel.show', compact('mapel'));
    }

    public function edit(Mapel $mapel)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $guru = User::where('role', 'guru')->get(['id', 'nama']);

        return view('admin.mapel.edit', compact('mapel', 'guru'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel,' . $mapel->id,
            'guru_id' => 'nullable|array',
            'guru_id.*' => 'exists:users,id',
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel
        ]);

        $mapel->guru()->sync($request->guru_id ?? []);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran ' . $request->nama_mapel . ' berhasil diupdate!');
    }

    public function destroy(Mapel $mapel)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // ✅ UPDATE: Hapus relasi guru dulu sebelum hapus mapel
        $mapel->guru()->detach();
        $nama = $mapel->nama_mapel;
        $mapel->delete();

        return back()->with('success', 'Mata pelajaran ' . $nama . ' berhasil dihapus!');
    }

    public function assignGuru(Request $request, Mapel $mapel)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'guru_id' => 'required|exists:users,id|in:' . User::where('role', 'guru')->pluck('id')->implode(',')
        ]);

        // Attach guru (hindari duplicate)
        $mapel->guru()->syncWithoutDetaching($request->guru_id);

        return back()->with('success', 'Guru berhasil ditugaskan ke ' . $mapel->nama_mapel);
    }

    // ✅ METHOD BARU: Hapus Guru dari Mapel
    public function removeGuru(Request $request, Mapel $mapel, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($user->role !== 'guru') {
            return back()->with('error', 'Bukan guru!');
        }

        $mapel->guru()->detach($user->id);
        return back()->with('success', $user->nama . ' dihapus dari ' . $mapel->nama_mapel);
    }
}
