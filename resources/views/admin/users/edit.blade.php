@extends('layouts.app')

@section('title', 'Edit ' . ucfirst($user->role))

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-11">
        <div class="card shadow-xl border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'guru' ? 'success' : 'primary') }} text-white py-4 position-relative">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h3 mb-1 fw-bold">
                            <i class="fas fa-user-edit me-2 {{ $user->role == 'admin' ? 'text-danger' : ($user->role == 'guru' ? 'text-success' : 'text-primary') }}"></i>
                            Edit {{ ucfirst($user->role) }}
                        </h2>
                        <p class="mb-0 opacity-90">
                            <strong>{{ $user->nama }}</strong> 
                            <span class="badge bg-white bg-opacity-20 ms-2 px-2 py-1">{{ $user->username }}</span>
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-white">
                                <i class="fas fa-list me-1"></i>Daftar User
                            </a>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-light">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Tabs berdasarkan role user -->
                <ul class="nav nav-tabs border-0 mb-0 bg-light px-4 py-2" id="editTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab">
                            <i class="fas fa-chart-bar me-2"></i>Statistik
                        </button>
                    </li>
                    @if($user->role == 'guru')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="guru-tab" data-bs-toggle="tab" data-bs-target="#guru" type="button" role="tab">
                            <i class="fas fa-chalkboard me-2"></i>Mata Pelajaran
                        </button>
                    </li>
                    @endif
                    @if($user->role == 'siswa')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="siswa-tab" data-bs-toggle="tab" data-bs-target="#siswa" type="button" role="tab">
                            <i class="fas fa-graduation-cap me-2"></i>Data Siswa
                        </button>
                    </li>
                    @endif
                </ul>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-5">
                    @csrf
                    @method('PUT')
                    
                    <!-- TAB PROFIL -->
                    <div class="tab-content" id="editTabContent">
                        <div class="tab-pane fade show active p-4 bg-light rounded-3 mb-4" id="profile" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold fs-5 text-dark">👤 Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama', $user->nama) }}" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold fs-5 text-dark">🆔 Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-secondary">
                                            <i class="fas fa-at"></i>
                                        </span>
                                        <input type="text" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                               value="{{ old('username', $user->username) }}" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold fs-5 text-dark">🔐 Password Baru</label>
                                    <div class="form-text mb-2">Kosongkan jika tidak ingin ubah password</div>
                                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           placeholder="Kosongkan untuk tetap password lama">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold fs-5 text-dark">🔗 Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <!-- TAB STATISTIK -->
                        <div class="tab-pane fade p-4 bg-light rounded-3 mb-4" id="stats" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-success fs-5">⭐ Total Poin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-coins"></i>
                                        </span>
                                        <input type="number" name="total_poin" class="form-control form-control-lg" 
                                               value="{{ old('total_poin', $user->total_poin) }}" min="0" max="999999">
                                        <span class="input-group-text text-success">XP</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-warning fs-5">🎮 Level</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-warning text-dark">
                                            <i class="fas fa-level-up-alt"></i>
                                        </span>
                                        <input type="number" name="level" class="form-control form-control-lg" 
                                               value="{{ old('level', $user->level) }}" min="1" max="100">
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="bg-primary text-white rounded-3 p-4 shadow-sm">
                                        <i class="fas fa-award fa-3x mb-3 opacity-75"></i>
                                        <h4 class="fw-bold mb-1">{{ number_format($user->total_poin) }}</h4>
                                        <small class="opacity-75">Total XP</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-warning text-dark rounded-3 p-4 shadow-sm">
                                        <i class="fas fa-layer-group fa-3x mb-3"></i>
                                        <h4 class="fw-bold mb-1">Lv.{{ $user->level }}</h4>
                                        <small>Level Saat ini</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-success text-white rounded-3 p-4 shadow-sm">
                                        <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                                        <h4 class="fw-bold mb-1">{{ ucfirst($user->role) }}</h4>
                                        <small>Role Tetap</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB GURU -->
                        @if($user->role == 'guru')
                        <div class="tab-pane fade p-4 bg-light rounded-3 mb-4" id="guru" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success fs-5">📚 Mata Pelajaran</label>
                                    <input type="text" name="mapel" class="form-control form-control-lg" 
                                           value="{{ old('mapel', $user->mapel ?? '') }}" placeholder="Matematika, IPA, dll">
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-success fs-5">📱 No. Telepon</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" 
                                           value="{{ old('phone', $user->phone ?? '') }}" placeholder="081234567890">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- TAB SISWA -->
                        @if($user->role == 'siswa')
                        <div class="tab-pane fade p-4 bg-light rounded-3 mb-4" id="siswa" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label fw-bold text-primary fs-5">🆔 NIS</label>
                                    <input type="text" name="nis" class="form-control form-control-lg" 
                                           value="{{ old('nis', $user->nis ?? '') }}" placeholder="12345678">
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <label class="form-label fw-bold text-primary fs-5">📚 Kelas</label>
                                    <select name="kelas" class="form-select form-control-lg">
                                        <option value="">Pilih Kelas</option>
                                        <option value="7A" {{ old('kelas', $user->kelas ?? '') == '7A' ? 'selected' : '' }}>7A</option>
                                        <option value="7B" {{ old('kelas', $user->kelas ?? '') == '7B' ? 'selected' : '' }}>7B</option>
                                        <option value="8A" {{ old('kelas', $user->kelas ?? '') == '8A' ? 'selected' : '' }}>8A</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-bold text-primary fs-5">📅 Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" class="form-control form-control-lg" 
                                           value="{{ old('tgl_lahir', $user->tgl_lahir ?? '') }}" max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-light border-top p-4 rounded-bottom">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <div>
                                <button type="submit" class="btn btn-success btn-lg px-5 shadow-lg me-3">
                                    <i class="fas fa-save me-2"></i>Update {{ ucfirst($user->role) }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important; }
.bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important; }
.bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important; }
.nav-tabs .nav-link { 
    padding: 12px 24px; 
    border-radius: 10px; 
    margin: 0 4px;
    border: none;
}
.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.shadow-xl { box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }
</style>
@endsection
