<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // CHECK ROLE DI SINI
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin!');
        }

        $stats = [
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_guru' => User::where('role', 'guru')->count(),
            'total_admin' => User::where('role', 'admin')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
