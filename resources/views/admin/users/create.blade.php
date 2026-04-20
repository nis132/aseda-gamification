@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient-primary text-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h4 mb-0 fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Tambah User Baru
                        </h2>
                        <p class="mb-0 small opacity-75">Pilih jenis user yang ingin ditambahkan</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-white">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- ✅ ALERT GLOBAL --}}
            @if(session('success'))
                <div class="alert alert-success m-3">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger m-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger m-3">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

<div class="bg-light p-3 border-bottom">

    <!-- ADMIN -->
    <div id="admin-tools" class="role-tools">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.export.admin') }}" class="btn btn-danger">
                <i class="fas fa-download me-1"></i> Template Admin
            </a>

            <form action="{{ route('admin.import.admin') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls" class="form-control" required>
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-file-import me-1"></i> Import Admin
                </button>
            </form>
        </div>
    </div>

    <!-- GURU -->
    <div id="guru-tools" class="role-tools d-none">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.export.guru') }}" class="btn btn-success">
                <i class="fas fa-download me-1"></i> Template Guru
            </a>

            <form action="{{ route('admin.import.guru') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls" class="form-control" required>
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-file-import me-1"></i> Import Guru
                </button>
            </form>
        </div>
    </div>

    <!-- SISWA -->
    <div id="siswa-tools" class="role-tools d-none">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.export.siswa') }}" class="btn btn-primary">
                <i class="fas fa-download me-1"></i> Template Siswa
            </a>

            <form action="{{ route('admin.import.siswa') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls" class="form-control" required>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-file-import me-1"></i> Import Siswa
                </button>
            </form>
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
                <div class="tab-content p-4" id="userTabContent">
                    <!-- TAB ADMIN -->
                    <div class="tab-pane fade show active" id="admin" role="tabpanel">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf
                            <input type="hidden" name="role" value="admin">
                            
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-danger">Nama Lengkap Admin</label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama') }}" placeholder="Contoh: Admin Utama" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-danger">Username Admin</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-danger text-white"><i class="fas fa-user-shield"></i></span>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                               value="{{ old('username') }}" placeholder="admin.master" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-danger">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Minimal 6 karakter" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-danger">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           placeholder="Ulangi password" required>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-danger px-4 fw-bold">
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
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-success">Nama Lengkap Guru</label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso S.Pd" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-success">Username Guru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white"><i class="fas fa-chalkboard-teacher"></i></span>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                               value="{{ old('username') }}" placeholder="budi.guru" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-success">Mata Pelajaran</label>
                                        <select name="mapel_id" class="form-select">
                                            <option value="">Pilih Mata Pelajaran</option>
                                            @foreach($mapel as $m)
                                                <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                                    {{ $m->nama_mapel }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-success">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-success">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4 fw-bold">
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
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Nama Lengkap Siswa</label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                           value="{{ old('nama') }}" placeholder="Contoh: Andi Wijaya" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Username Siswa</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-user-graduate"></i></span>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                               value="{{ old('username') }}" placeholder="andi7a" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label class="form-label fw-bold text-primary">Kelas</label>
                                        <select name="kelas_id" class="form-select">
                                            <option value="">Pilih Kelas</option>
                                            @foreach($kelas as $k)
                                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                </div>
                            </div>

                            <div class="row bg-light rounded-3 p-3 mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-success">Poin Awal</label>
                                    <input type="number" name="total_poin" class="form-control" value="{{ old('total_poin', 0) }}" min="0" max="1000">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-bold text-warning">Level Awal</label>
                                    <input type="number" name="level" class="form-control" value="{{ old('level', 1) }}" min="1" max="50">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
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

.card {
    font-size: 14px;
}

label {
    margin-bottom: 6px;
}

.form-control, .form-select {
    padding: 8px 10px;
}

.btn {
    font-size: 14px;
    padding: 8px 16px;
}

.nav-tabs .nav-link {
    border: none;
    padding: 10px 16px;
    color: #6c757d;
    font-size: 14px;
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const adminTab = document.getElementById('admin-tab');
    const guruTab = document.getElementById('guru-tab');
    const siswaTab = document.getElementById('siswa-tab');

    const adminTools = document.getElementById('admin-tools');
    const guruTools = document.getElementById('guru-tools');
    const siswaTools = document.getElementById('siswa-tools');

    function hideAll() {
        adminTools.classList.add('d-none');
        guruTools.classList.add('d-none');
        siswaTools.classList.add('d-none');
    }

    adminTab.addEventListener('click', function () {
        hideAll();
        adminTools.classList.remove('d-none');
    });

    guruTab.addEventListener('click', function () {
        hideAll();
        guruTools.classList.remove('d-none');
    });

    siswaTab.addEventListener('click', function () {
        hideAll();
        siswaTools.classList.remove('d-none');
    });

    // ✅ AUTO PINDAH TAB SAAT ERROR
    const oldRole = "{{ old('role') }}";

    if (oldRole === 'guru') {
        guruTab.click();
    } else if (oldRole === 'siswa') {
        siswaTab.click();
    } else {
        adminTab.click();
    }

});
</script>
@endsection