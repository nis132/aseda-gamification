<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('admin.users.create');
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
            'level' => 'nullable|integer|min:1|max:100'
        ], [
            'username.unique' => 'Username sudah digunakan!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'total_poin' => $validated['total_poin'] ?? 0,
            'level' => $validated['level'] ?? 1
        ]);

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
        return view('admin.users.edit', compact('user'));
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
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'total_poin' => $validated['total_poin'] ?? 0,
            'level' => $validated['level'] ?? 1
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

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
}
