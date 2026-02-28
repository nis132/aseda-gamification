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


class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin!');
        }

        $query = User::query();

        // Filter berdasarkan role (optional)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
public function create()
{
    $mapel = \App\Models\Mapel::all();
    $kelas = \App\Models\Kelas::all();

    return view('admin.users.create', compact('mapel', 'kelas'));
}


    /**
     * Store a newly created user
     */
public function store(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:admin,guru,siswa',
        'total_poin' => 'nullable|integer|min:0|max:999999',
        'level' => 'nullable|integer|min:1|max:100',

        // 🔥 VALIDASI PIVOT
        'mapel_id' => 'nullable|exists:mapel,id',
        'kelas_id' => 'nullable|exists:kelas,id',
    ], [
        'username.unique' => 'Username sudah digunakan!',
        'password.confirmed' => 'Konfirmasi password tidak cocok!',
    ]);

    // ✅ SIMPAN USER DULU
    $user = User::create([
        'nama' => $validated['nama'],
        'username' => $validated['username'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'total_poin' => $validated['total_poin'] ?? 0,
        'level' => $validated['level'] ?? 1
    ]);

    // ✅ JIKA GURU → INSERT KE guru_mapel
    if ($user->isGuru() && $request->filled('mapel_id')) {
        $user->mapel()->attach($request->mapel_id);
    }

    // ✅ JIKA SISWA → INSERT KE siswa_kelas
    if ($user->isSiswa() && $request->filled('kelas_id')) {
        $user->kelas()->attach($request->kelas_id);
    }

    return redirect()->route('admin.users.index')
        ->with('success', 'User ' . $validated['nama'] . ' berhasil dibuat!');
}


    /**
     * Display the specified user (show detail)
     */
    public function show(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
public function edit(User $user)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $mapel = Mapel::all();
    $kelas = Kelas::all();

    return view('admin.users.edit', compact('user', 'mapel', 'kelas'));
}

    /**
     * Update the specified user
     */
public function update(Request $request, User $user)
{
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'role' => 'required|in:admin,guru,siswa',
        'total_poin' => 'nullable|integer|min:0|max:999999',
        'level' => 'nullable|integer|min:1|max:100',
        'password' => 'nullable|string|min:6',

        // TAMBAHAN PIVOT
        'mapel_id' => 'nullable|exists:mapel,id',
        'kelas_id' => 'nullable|exists:kelas,id',
    ]);

    $updateData = [
        'nama' => $validated['nama'],
        'username' => $validated['username'],
        'role' => $validated['role'],
        'total_poin' => $validated['total_poin'] ?? 0,
        'level' => $validated['level'] ?? 1
    ];

    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    $user->update($updateData);

    /*
    |--------------------------------------------------------------------------
    | SYNC RELASI
    |--------------------------------------------------------------------------
    */

    // Jika Guru → sync mapel
    if ($user->isGuru()) {
        $user->mapel()->sync($request->mapel_id ? [$request->mapel_id] : []);
    }

    // Jika Siswa → sync kelas
    if ($user->isSiswa()) {
        $user->kelas()->sync($request->kelas_id ? [$request->kelas_id] : []);
    }

    return redirect()->route('admin.users.index')
        ->with('success', 'User ' . $validated['nama'] . ' berhasil diupdate!');
}
    /**
     * Remove the specified user (hard delete)
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin() || auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri!');
        }

        $nama = $user->nama;
        $user->delete(); // Hard delete karena no soft delete

        return back()->with('success', 'User ' . $nama . ' berhasil dihapus!');
    }


public function importAdmin(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls']);
    Excel::import(new \App\Imports\AdminImport, $request->file('file'));
    return back()->with('success', 'Admin berhasil diimport');
}

public function importGuru(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls']);
    Excel::import(new \App\Imports\GuruImport, $request->file('file'));
    return back()->with('success', 'Guru berhasil diimport');
}

public function importSiswa(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls']);
    Excel::import(new \App\Imports\SiswaImport, $request->file('file'));
    return back()->with('success', 'Siswa berhasil diimport');
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