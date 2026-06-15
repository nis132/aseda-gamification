<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\GuruMapelKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        // Ambil semua mapel yang diampu guru login
        $mapelIds = GuruMapelKelas::where('guru_id', auth()->id())
            ->pluck('mapel_id');

        // Tampilkan semua materi di mapel yang sama — bukan hanya milik sendiri
        // agar guru lain yang mengampu mapel yang sama bisa melihat
        $materis = Materi::with('mapel', 'guru', 'kelas')
            ->whereIn('mapel_id', $mapelIds)
            ->latest()
            ->paginate(12);

        return view('guru.materi.index', compact('materis'));
    }

    public function create()
    {
        $relasi = GuruMapelKelas::with(['mapel', 'kelas'])
            ->where('guru_id', auth()->id())
            ->get();

        return view('guru.materi.create', compact('relasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'               => 'required|max:255',
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
            'deskripsi'           => 'required',
            'file_materi'         => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'video_url'           => 'nullable|url|max:500',
            'link_referensi'      => 'nullable|url|max:500',
            'bab' => 'required|integer|min:1|max:8', 'level_required' => 'nullable|integer|min:1|max:8',
        ]);

        $relasi = GuruMapelKelas::where('id', $request->guru_mapel_kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        $data = [
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'mapel_id'       => $relasi->mapel_id,
            'kelas_id'       => $relasi->kelas_id,
            'guru_id'        => auth()->id(),
            'video_url'      => $request->video_url,
            'link_referensi' => $request->link_referensi,
            'bab' => $request->bab, 'level_required' => $request->bab,
        ];

        if ($request->hasFile('file_materi')) {
            $data['file_url'] = $request->file('file_materi')->store('materi', 'public');
        }

        Materi::create($data);

        return redirect('/guru/materi')->with('success', 'Materi berhasil ditambahkan!');
    }

public function edit(Materi $materi)
{
    if ($materi->guru_id !== auth()->id()) {
        return redirect()->route('guru.materi.show', $materi)
            ->with('error', 'Hanya pembuat materi yang dapat mengedit.');
    }

    $relasi = GuruMapelKelas::with(['mapel', 'kelas'])
        ->where('guru_id', auth()->id())
        ->get();

    return view('guru.materi.edit', compact('materi', 'relasi'));
}

public function update(Request $request, Materi $materi)
{
    if ($materi->guru_id !== auth()->id()) {
        return redirect()->route('guru.materi.show', $materi)
            ->with('error', 'Hanya pembuat materi yang dapat mengedit.');
    }

        $request->validate([
            'judul'               => 'required|max:255',
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
            'deskripsi'           => 'required',
            'file_materi'         => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'video_url'           => 'nullable|url|max:500',
            'link_referensi'      => 'nullable|url|max:500',
            'bab'                 => 'required|integer|min:1|max:8',
        ]);

        $relasi = GuruMapelKelas::where('id', $request->guru_mapel_kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        $data = [
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'mapel_id'       => $relasi->mapel_id,
            'kelas_id'       => $relasi->kelas_id,
            'video_url'      => $request->video_url,
            'link_referensi' => $request->link_referensi,
            'bab'            => $request->bab,
            'level_required' => $request->bab,
        ];

        if ($request->hasFile('file_materi')) {

            if ($materi->file_url) {
                Storage::disk('public')->delete($materi->file_url);
            }

            $data['file_url'] = $request->file('file_materi')
                ->store('materi', 'public');
        }

        $materi->update($data);

        return redirect('/guru/materi')
            ->with('success', 'Materi berhasil diupdate!');
    }

    public function kirimKeKelas(Request $request, Materi $materi)
    {
        // Cek guru mengampu mapel yang sama
        $boleh = GuruMapelKelas::where('guru_id', auth()->id())
            ->where('mapel_id', $materi->mapel_id)
            ->exists();

        if (!$boleh) {
            abort(403, 'Anda tidak mengampu mata pelajaran ini.');
        }

        $request->validate([
            'guru_mapel_kelas_id' => 'required|exists:guru_mapel_kelas,id',
        ]);

        // Pastikan relasi ini milik guru yang login
        $relasi = GuruMapelKelas::where('id', $request->guru_mapel_kelas_id)
            ->where('guru_id', auth()->id())
            ->where('mapel_id', $materi->mapel_id)
            ->firstOrFail();

        // Cek apakah materi yang sama sudah pernah dikirim ke kelas ini
        // (duplikasi berdasarkan judul + mapel + kelas)
        $sudahAda = Materi::where('judul', $materi->judul)
            ->where('mapel_id', $relasi->mapel_id)
            ->where('kelas_id', $relasi->kelas_id)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Materi dengan judul yang sama sudah ada di kelas ' . $relasi->kelas->nama_kelas . '.');
        }

        // Duplikasi materi — tercatat sebagai milik guru yang mengirim
        Materi::create([
            'judul'      => $materi->judul,
            'deskripsi'  => $materi->deskripsi,
            'file_url'   => $materi->file_url, // pakai file yang sama
            'mapel_id'   => $relasi->mapel_id,
            'kelas_id'   => $relasi->kelas_id,
            'guru_id'    => auth()->id(),
        ]);

        return back()->with('success', 'Materi berhasil dikirim ke kelas ' . $relasi->kelas->nama_kelas . '!');
    }

    public function show(Materi $materi)
    {
        // Cukup cek apakah guru mengampu mapel yang sama — tidak harus pemilik
        $boleh = GuruMapelKelas::where('guru_id', auth()->id())
            ->where('mapel_id', $materi->mapel_id)
            ->exists();

        if (!$boleh) {
            abort(403, 'Anda tidak mengampu mata pelajaran ini.');
        }

        return view('guru.materi.show', compact('materi'));
    }

public function destroy(Materi $materi)
{
    if ($materi->guru_id !== auth()->id()) {
        return redirect()->route('guru.materi.show', $materi)
            ->with('error', 'Hanya pemilik materi yang dapat menghapus.');
    }

    if ($materi->file_url) {
        Storage::disk('public')->delete($materi->file_url);
    }

    $materi->delete();

    return redirect('/guru/materi')->with('success', 'Materi berhasil dihapus!');
}
}