<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::with('mapel', 'guru')
            ->where('guru_id', auth()->id())
            ->latest()
            ->paginate(12);
            
        $kelas = Kelas::select('id', 'nama_kelas')->get(); // 🔥 Untuk dropdown + info UI
        
        return view('guru.materi.index', compact('materis', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::select('id', 'nama_kelas')->get();
        $mapel = Mapel::select('id', 'nama_mapel')->get(); // Sesuaikan kolom
        return view('guru.materi.create', compact('kelas', 'mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'kelas_id' => 'required|exists:kelas,id',           // 🔥 REQUIRED!
            'mapel_id' => 'required|exists:mapel,id',
            'deskripsi' => 'required',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // 🔥 SIMPAN kelas_id KE DB!
        $data = $request->only(['judul', 'kelas_id', 'mapel_id', 'deskripsi']);
        $data['guru_id'] = auth()->id();

        if ($request->hasFile('file_materi')) {
            $data['file_url'] = $request->file('file_materi')->store('materi', 'public');
        }

        Materi::create($data);

        return redirect('/guru/materi')->with('success', '✅ Materi Kelas ' . $request->kelas_id . ' berhasil ditambahkan!');
    }

public function update(Request $request, Materi $materi)
{
    $this->authorizeMateri($materi);

    $request->validate([
        'judul' => 'required|max:255',
        'kelas_id' => 'required|exists:kelas,id',           // 🔥 REQUIRED!
        'mapel_id' => 'required|exists:mapel,id',
        'deskripsi' => 'required',
        'file_materi' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
    ]);

    $data = $request->only(['judul', 'kelas_id', 'mapel_id', 'deskripsi']); // 🔥 kelas_id!

    if ($request->hasFile('file_materi')) {
        if ($materi->file_url) {
            Storage::disk('public')->delete($materi->file_url);
        }
        $data['file_url'] = $request->file('file_materi')->store('materi', 'public');
    }

    $materi->update($data);

    return redirect('/guru/materi')->with('success', ' Materi Kelas ' . $request->kelas_id . ' berhasil diupdate!');
}

public function edit(Materi $materi)
{
    $this->authorizeMateri($materi);
    $kelas = Kelas::select('id', 'nama_kelas')->get();
    $mapel = Mapel::select('id', 'nama_mapel')->get();
    return view('guru.materi.edit', compact('materi', 'kelas', 'mapel'));
}
public function show(Materi $materi)
{
    $this->authorizeMateri($materi);

    return view('guru.materi.show', compact('materi'));
}

    public function destroy(Materi $materi)
    {
        $this->authorizeMateri($materi);
        
        if ($materi->file_url) {
            Storage::disk('public')->delete($materi->file_url);
        }
        
        $materi->delete();

        return redirect('/guru/materi')->with('success', ' Materi berhasil dihapus!');
    }

    private function authorizeMateri(Materi $materi)
    {
        if ($materi->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak!');
        }
    }
}
