<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Guru\TantanganController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Guru\SoalController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\Guru\PenilaianController;
    use App\Exports\AdminTemplateExport;
use App\Exports\GuruTemplateExport;
use App\Exports\SiswaTemplateExport;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// 🔥 DUAL LOGOUT ROUTE - FIX 405 ERROR SELAMANYA
Route::middleware('auth')->group(function () {

    // POST logout (untuk form)
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    })->name('logout');

    // GET logout (untuk manual URL & fallback)
    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    })->name('logout.get');

});


// Admin Routes - CHECK ROLE DI CONTROLLER
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('users/export-template', 
        [UserController::class, 'exportTemplate']
    )->name('users.export-template');

    Route::post('/users/import', 
        [UserController::class, 'import']
    )->name('users.import');

    Route::resource('users', UserController::class);

    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    

    // Kelas
    Route::get('/kelas', [App\Http\Controllers\Admin\KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [App\Http\Controllers\Admin\KelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [App\Http\Controllers\Admin\KelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{kelas}/edit', [App\Http\Controllers\Admin\KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kelas}', [App\Http\Controllers\Admin\KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [App\Http\Controllers\Admin\KelasController::class, 'destroy'])->name('kelas.destroy');
    Route::get('/kelas/{kelas}', 
        [KelasController::class, 'show']
    )->name('kelas.show');


    // Mapel - FULL CRUD
    Route::get('/mapel', [App\Http\Controllers\Admin\MapelController::class, 'index'])->name('mapel.index');
    Route::get('/mapel/create', [App\Http\Controllers\Admin\MapelController::class, 'create'])->name('mapel.create');
    Route::post('/mapel', [App\Http\Controllers\Admin\MapelController::class, 'store'])->name('mapel.store');
    Route::get('/mapel/{mapel}', [App\Http\Controllers\Admin\MapelController::class, 'show'])->name('mapel.show');
    Route::get('/mapel/{mapel}/edit', [App\Http\Controllers\Admin\MapelController::class, 'edit'])->name('mapel.edit');
    Route::put('/mapel/{mapel}', [App\Http\Controllers\Admin\MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel/{mapel}', [App\Http\Controllers\Admin\MapelController::class, 'destroy'])->name('mapel.destroy');

    Route::post('/mapel/{mapel}/assign-guru', [MapelController::class, 'assignGuru'])->name('mapel.assignGuru');
    Route::delete('/mapel/{mapel}/guru/{user}', [MapelController::class, 'removeGuru'])->name('mapel.removeGuru');

        // EXPORT
    Route::get('/export-admin', [UserController::class, 'exportAdmin'])->name('export.admin');
    Route::get('/export-guru', [UserController::class, 'exportGuru'])->name('export.guru');
    Route::get('/export-siswa', [UserController::class, 'exportSiswa'])->name('export.siswa');

    // IMPORT
    Route::post('/import-admin', [UserController::class, 'importAdmin'])->name('import.admin');
    Route::post('/import-guru', [UserController::class, 'importGuru'])->name('import.guru');
    Route::post('/import-siswa', [UserController::class, 'importSiswa'])->name('import.siswa');

});


Route::middleware(['auth'])->group(function () {

    // Guru Routes
    Route::prefix('guru')->name('guru.')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/tantangan', [App\Http\Controllers\Guru\TantanganController::class, 'index'])->name('tantangan.index');
        Route::get('/tantangan/create', [App\Http\Controllers\Guru\TantanganController::class, 'create'])->name('tantangan.create');
        Route::post('/tantangan', [App\Http\Controllers\Guru\TantanganController::class, 'store'])->name('tantangan.store');
        Route::get('/tantangan/{tantangan}', [App\Http\Controllers\Guru\TantanganController::class, 'show'])->name('tantangan.show');
        Route::post('guru/tantangan/{tantangan}/publish', [TantanganController::class, 'publish'])->name('guru.tantangan.publish');
        Route::get('/tantangan/{tantangan}/edit', [App\Http\Controllers\Guru\TantanganController::class, 'edit'])->name('tantangan.edit');
        Route::put('/tantangan/{tantangan}', [App\Http\Controllers\Guru\TantanganController::class, 'update'])->name('tantangan.update');
        Route::delete('/tantangan/{tantangan}', [App\Http\Controllers\Guru\TantanganController::class, 'destroy'])->name('tantangan.destroy');

        // ✅ SOAL ROUTES - TAMBAH INI
        Route::get('/tantangan/{tantangan}/soal/create', [App\Http\Controllers\Guru\SoalController::class, 'create'])->name('soal.create');
        Route::post('/tantangan/{tantangan}/soal', [App\Http\Controllers\Guru\SoalController::class, 'store'])->name('soal.store');
        Route::delete('/soal/{soal}', [App\Http\Controllers\Guru\SoalController::class, 'destroy'])->name('soal.destroy');

        Route::post('guru/tantangan/{tantangan}/soal', [SoalController::class, 'store'])->name('guru.soal.store');
        Route::get('guru/tantangan/{tantangan}/soal/create', [SoalController::class, 'create'])->name('guru.soal.create');
        Route::post('tantangan/{tantangan}/soal', [SoalController::class, 'store'])->name('soal.store');
        Route::post('tantangan/{tantangan}/publish', [TantanganController::class, 'publish']);

        // Kelas
        Route::resource('kelas', KelasController::class)->only(['index', 'show']);

        // Materi - RESOURCE FULL CRUD
        Route::resource('materi', MateriController::class);

        // Tantangan - RESOURCE FULL CRUD
        Route::resource('tantangan', TantanganController::class);

        // Soal (nested dengan tantangan)
        Route::resource('tantangan.soal', SoalController::class)->shallow();
        Route::get('/tantangan/{tantangan}/soal/create', [App\Http\Controllers\Guru\SoalController::class, 'create'])->name('soal.create');
        Route::post('/tantangan/{tantangan}/soal', [App\Http\Controllers\Guru\SoalController::class, 'store'])->name('soal.store');
        Route::get('/tantangan/{tantangan}/soal/{soal}/edit', [App\Http\Controllers\Guru\SoalController::class, 'edit'])->name('soal.edit');
        Route::put('/tantangan/{tantangan}/soal/{soal}', [App\Http\Controllers\Guru\SoalController::class, 'update'])->name('soal.update');
        Route::delete('/soal/{soal}', [App\Http\Controllers\Guru\SoalController::class, 'destroy'])->name('soal.destroy');
    Route::get('/materi', [App\Http\Controllers\Guru\MateriController::class, 'index'])->name('materi');
    Route::get('/materi/create', [App\Http\Controllers\Guru\MateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [App\Http\Controllers\Guru\MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [App\Http\Controllers\Guru\MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [App\Http\Controllers\Guru\MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [App\Http\Controllers\Guru\MateriController::class, 'destroy'])->name('materi.destroy');
    });

    Route::get('guru/tantangan/{id}/nilai', 
    [PenilaianController::class, 'index'])->name('guru.nilai.index');

Route::post('guru/tantangan/{id}/nilai/{siswa}',
    [PenilaianController::class, 'simpanNilai'])->name('guru.nilai.simpan');

Route::get('guru/tantangan/{id}/nilai/{siswa}',
    [PenilaianController::class, 'detail'])
    ->name('guru.nilai.detail');
});


// SISWA ROUTES - Gamifikasi SMPN 2 Semen
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/materi', [SiswaController::class, 'materi'])->name('materi');
        Route::get('/tantangan', [SiswaController::class, 'tantangan'])->name('tantangan');
        Route::get('/tantangan/{tantangan}/kerjakan', [SiswaController::class, 'kerjakan'])->name('tantangan.kerjakan');
            // 🔥 FIX 405: GET fallback untuk direct access

Route::match(['POST', 'GET'], '/tantangan/{tantangan}/submit', [SiswaController::class, 'submit'])->name('tantangan.submit');        
        Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');

            Route::get('/materi', [SiswaController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [SiswaController::class, 'materiShow'])->name('materi.show');
    });

    Route::get('/leaderboard', 
    [LeaderboardController::class, 'index']
)->name('leaderboard');

Route::get('/badge', [BadgeController::class, 'index'])
    ->name('badge');
