@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient-primary text-white py-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h3 mb-0 fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Tambah User Baru
                        </h2>
                        <p class="mb-0 opacity-75">Pilih jenis user yang ingin ditambahkan</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-white">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs border-0 mb-0 bg-light" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button">
                            <i class="fas fa-user-shield text-danger me-2"></i>Admin
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="guru-tab" data-bs-toggle="tab" data-bs-target="#guru" type="button">
                            <i class="fas fa-chalkboard-teacher text-success me-2"></i>Guru
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="siswa-tab" data-bs-toggle="tab" data-bs-target="#siswa" type="button">
                            <i class="fas fa-user-graduate text-primary me-2"></i>Siswa
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-5" id="userTabContent">
                    <!-- TAB ADMIN -->
                    <div class="tab-pane fade show active" id="admin" role="tabpanel">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf
                            <input type="hidden" name="role" value="admin">
                            
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-danger">👑 Nama Lengkap Admin</label>
                                    <input type="text" name="nama" class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama') }}" placeholder="Contoh: Admin Utama" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-danger">🆔 Username Admin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-danger text-white"><i class="fas fa-user-shield"></i></span>
                                        <input type="text" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                               value="{{ old('username') }}" placeholder="admin.master" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-danger">🔐 Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           placeholder="Minimal 6 karakter" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-danger">🔗 Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                           placeholder="Ulangi password" required>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-danger btn-lg px-5 fw-bold shadow-lg">
                                    <i class="fas fa-user-plus me-2"></i>Buat Admin
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB GURU -->
                    <div class="tab-pane fade" id="guru" role="tabpanel">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf
                            <input type="hidden" name="role" value="guru">
                            
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success">👨‍🏫 Nama Lengkap Guru</label>
                                    <input type="text" name="nama" class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso S.Pd" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success">🆔 Username Guru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="fas fa-chalkboard-teacher"></i></span>
                                        <input type="text" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                               value="{{ old('username') }}" placeholder="budi.guru" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success">📚 Mata Pelajaran</label>
                                    <input type="text" name="mapel" class="form-control form-control-lg" 
                                           placeholder="Matematika, IPA, Bahasa Indonesia, dll">
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success">📱 No. HP</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" 
                                           placeholder="081234567890">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success">🔐 Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success">🔗 Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" required>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-lg px-5 fw-bold shadow-lg">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Buat Guru
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB SISWA -->
                    <div class="tab-pane fade" id="siswa" role="tabpanel">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf
                            <input type="hidden" name="role" value="siswa">
                            
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-primary">👦 Nama Lengkap Siswa</label>
                                    <input type="text" name="nama" class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama') }}" placeholder="Contoh: Andi Wijaya" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-primary">🆔 Username Siswa</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-user-graduate"></i></span>
                                        <input type="text" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                               value="{{ old('username') }}" placeholder="andi7a" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label fw-bold text-primary">🆔 NIS</label>
                                    <input type="text" name="nis" class="form-control form-control-lg" 
                                           placeholder="12345678">
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label fw-bold text-primary">📚 Kelas</label>
                                    <select name="kelas" class="form-select form-control-lg">
                                        <option value="">Pilih Kelas</option>
                                        <option value="7A">7A</option>
                                        <option value="7B">7B</option>
                                        <option value="8A">8A</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label class="form-label fw-bold text-primary">📅 Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" class="form-control form-control-lg" max="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-primary">🔐 Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-primary">🔗 Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" required>
                                </div>
                            </div>

                            <!-- Poin & Level Default Siswa -->
                            <div class="row bg-light rounded-3 p-3 mb-4">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-success">⭐ Poin Awal</label>
                                    <input type="number" name="total_poin" class="form-control form-control-lg" value="0" min="0" max="1000">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-warning">🎮 Level Awal</label>
                                    <input type="number" name="level" class="form-control form-control-lg" value="1" min="1" max="50">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-lg">
                                    <i class="fas fa-user-graduate me-2"></i>Buat Siswa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
.nav-tabs .nav-link {
    border: none;
    padding: 16px 24px;
    color: #6c757d;
}
.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}
.nav-tabs .nav-link:hover {
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
}
</style>
@endsection
