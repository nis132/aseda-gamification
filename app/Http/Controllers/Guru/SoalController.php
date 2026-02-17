<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\Tantangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    public function create($tantanganId)
    {
        $tantangan = Tantangan::findOrFail($tantanganId);
        if ($tantangan->guru_id !== Auth::id()) abort(403);

        return view('guru.soal.create', compact('tantangan'));
    }

    public function store(Request $request, $tantanganId)
    {
        $tantangan = Tantangan::findOrFail($tantanganId);
        if ($tantangan->guru_id !== Auth::id()) abort(403);

        $request->validate([
            'pertanyaan' => 'required',
            'tipe' => 'required|in:pg,essay,matching',
            'opsi_a' => 'required_if:tipe,pg,matching',
            'opsi_b' => 'required_if:tipe,pg,matching',
            'opsi_c' => 'nullable',
            'opsi_d' => 'nullable',
            'jawaban_benar' => 'required_if:tipe,pg'
        ]);

        $data = $request->only([
            'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban_benar'
        ]);

        $tantangan->soal()->create($data);

        return redirect()->route('guru.tantangan.show', $tantangan)
            ->with('success', '✅ Soal berhasil ditambahkan!');
    }

    public function destroy(Soal $soal)
    {
        if ($soal->tantangan->guru_id !== Auth::id()) {
            abort(403);
        }
        $soal->delete();

        return back()->with('success', '✅ Soal berhasil dihapus!');
    }
}
