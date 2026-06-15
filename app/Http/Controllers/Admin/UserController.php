<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;
use App\Exports\GuruExport;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\GuruMapelKelas;
use Maatwebsite\Excel\Validators\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin!');
        }

        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%')
                ->orWhere('nis', 'like', '%' . $request->search . '%')
                ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $mapel = \App\Models\Mapel::all();
        $kelas = \App\Models\Kelas::all();

        return view('admin.users.create', compact('mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'nullable|required_if:role,siswa|string|max:20|unique:users,nis',
            'nip' => 'nullable|required_if:role,guru|string|max:30|unique:users,nip',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,guru,siswa',
            'level' => 'nullable|integer|min:1|max:100',
            'mapel_id' => 'nullable|exists:mapel,id',
            'kelas_id' => 'nullable|exists:kelas,id',
        ], [
            'nama.required' => 'Nama wajib diisi!',
            'nis.required_if' => 'NIS wajib diisi untuk siswa!',
            'nis.unique' => 'NIS sudah digunakan!',
            'nip.required_if' => 'NIP wajib diisi untuk guru!',
            'nip.unique' => 'NIP sudah digunakan!',
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username sudah digunakan!',
            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
            'role.required' => 'Role wajib dipilih!',
        ]);

        try {
            $user = User::create([
                'nama' => $validated['nama'],
                'nis' => ($validated['role'] === 'siswa') ? ($validated['nis'] ?? null) : null,
                'nip' => ($validated['role'] === 'guru') ? ($validated['nip'] ?? null) : null,
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'level' => $validated['level'] ?? 1
            ]);

            if ($user->isGuru() && $request->filled('mapel_id')) {
                $user->mapel()->attach($request->mapel_id);
            }

            if ($user->isSiswa() && $request->filled('kelas_id')) {
                $user->kelas()->attach($request->kelas_id);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User ' . $validated['nama'] . ' berhasil dibuat!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi!');
        }
    }

    public function show(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $mapel = Mapel::all();
        $kelas = Kelas::all();

        return view('admin.users.edit', compact('user', 'mapel', 'kelas'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'nullable|required_if:role,siswa|string|max:20|unique:users,nis,' . $user->id,
            'nip' => 'nullable|required_if:role,guru|string|max:30|unique:users,nip,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,guru,siswa',
            'level' => 'nullable|integer|min:1|max:100',
            'password' => 'nullable|string|min:6',
            'mapel_id' => 'nullable|exists:mapel,id',
            'kelas_id' => 'nullable|exists:kelas,id',
        ], [
            'nama.required' => 'Nama wajib diisi!',
            'nis.required_if' => 'NIS wajib diisi untuk siswa!',
            'nis.unique' => 'NIS sudah digunakan!',
            'nip.required_if' => 'NIP wajib diisi untuk guru!',
            'nip.unique' => 'NIP sudah digunakan!',
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username sudah digunakan!',
            'password.min' => 'Password minimal 6 karakter!',
            'role.required' => 'Role wajib dipilih!',
        ]);

        try {
            $updateData = [
                'nama' => $validated['nama'],
                'nis' => ($user->role === 'siswa') ? ($validated['nis'] ?? null) : null,
                'nip' => ($user->role === 'guru') ? ($validated['nip'] ?? null) : null,
                'username' => $validated['username'],
                'role' => $validated['role'],
                'level' => $validated['level'] ?? 1
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

// Controller update, ganti bagian guru:
if ($user->isGuru()) {
    // Hapus dulu semua data mengajar lama
    $user->mengajar()->delete();
    
    // Isi ulang kalau ada mapel_id dan kelas_id
    if ($request->filled('mapel_id') && $request->filled('kelas_id')) {
        GuruMapelKelas::create([
            'guru_id'  => $user->id,
            'mapel_id' => $request->mapel_id,
            'kelas_id' => $request->kelas_id,
        ]);
    }
}

            if ($user->isSiswa()) {
                $user->kelas()->sync($request->kelas_id ? [$request->kelas_id] : []);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User ' . $validated['nama'] . ' berhasil diupdate!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate data. Silakan coba lagi!');
        }
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin() || auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri!');
        }

        try {
            $nama = $user->nama;
            $user->delete();

            return back()->with('success', 'User ' . $nama . ' berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data!');
        }
    }

    public function importAdmin(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new \App\Imports\AdminImport, $request->file('file'));

            return back()->with('success', 'Import berhasil!');
        } catch (ValidationException $e) {

            $errors = [];

            foreach ($e->failures() as $failure) {
                $errors[] = "Baris {$failure->row()} - {$failure->attribute()}: " . implode(', ', $failure->errors());
            }

            return back()->with('error', implode(' | ', $errors));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import data!');
        }
    }

    public function importGuru(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new \App\Imports\GuruImport(), $request->file('file'));

            return back()->with('success', 'Import berhasil!');
        } catch (ValidationException $e) {

            $errors = [];

            foreach ($e->failures() as $failure) {
                $errors[] = "Baris {$failure->row()} - {$failure->attribute()}: " . implode(', ', $failure->errors());
            }

            return back()->with('error', implode(' | ', $errors));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import data!');
        }
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new \App\Imports\SiswaImport(), $request->file('file'));

            return back()->with('success', 'Import berhasil!');
        } catch (ValidationException $e) {

            $errors = [];

            foreach ($e->failures() as $failure) {
                $errors[] = "Baris {$failure->row()} - {$failure->attribute()}: " . implode(', ', $failure->errors());
            }

            return back()->with('error', implode(' | ', $errors));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import data!');
        }
    }

    public function exportAdmin()
    {
        return Excel::download(
            new \App\Exports\AdminExport,
            'template_admin.xlsx'
        );
    }

    public function exportGuru()
    {
        return Excel::download(
            new \App\Exports\GuruExport,
            'template_guru.xlsx'
        );
    }

    public function exportSiswa()
    {
        return Excel::download(
            new \App\Exports\SiswaExport,
            'template_siswa.xlsx'
        );
    }
}