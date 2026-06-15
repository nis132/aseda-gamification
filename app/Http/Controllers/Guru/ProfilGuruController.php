<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilGuruController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->load([
            'mengajar.kelas',
            'mengajar.mapel',
            'tantangan',
            'materi',
        ]);

        // Ambil data mengajar dari guru_mapel_kelas
        $mengajar = $guru->mengajar->load(['kelas', 'mapel']);

        // Statistik ringkasan
        $stats = [
            'total_tantangan' => $guru->tantangan()->count(),
            'total_materi'    => $guru->materi()->count(),
            'total_mapel'     => $mengajar->unique('mapel_id')->count(),
            'total_kelas'     => $mengajar->unique('kelas_id')->count(),
        ];

        // Daftar kelas unik yang diajar beserta mapelnya
        $kelasAjar = $mengajar->groupBy('kelas_id');

        return view('guru.profil', compact('guru', 'stats', 'kelasAjar'));
    }

    public function update(Request $request)
    {
        $guru = auth()->user();

        $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($guru->id)],
            'nip'      => ['nullable', 'string', 'max:30', Rule::unique('users')->ignore($guru->id)],
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah dipakai pengguna lain.',
            'nip.unique'        => 'NIP sudah dipakai pengguna lain.',
        ]);

        $guru->update([
            'nama'     => $request->nama,
            'username' => $request->username,
            'nip'      => $request->nip,
        ]);

        return redirect()->route('guru.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $guru = auth()->user();

        $request->validate([
            'password_lama'          => ['required'],
            'password_baru'          => ['required', 'min:6', 'confirmed'],
            'password_baru_confirmation' => ['required'],
        ], [
            'password_lama.required'   => 'Password lama wajib diisi.',
            'password_baru.required'   => 'Password baru wajib diisi.',
            'password_baru.min'        => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, $guru->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
        }

        $guru->update(['password' => $request->password_baru]);

        return redirect()->route('guru.profil')->with('success', 'Password berhasil diperbarui.');
    }
}