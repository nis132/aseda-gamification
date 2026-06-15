@extends('layouts.app')
@section('title', 'Profil Guru')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="mb-0" style="color: var(--txt-secondary); font-size: 0.85rem;">
            Kelola informasi akun dan keamanan Anda
        </p>
    </div>
</div>

<div class="row g-4">

    {{-- ============================================================
         KOLOM KIRI — Avatar + Statistik + Kelas Ajar
    ============================================================ --}}
    <div class="col-lg-4 d-flex flex-column gap-4">

        {{-- KARTU IDENTITAS --}}
        <div class="card border-0 overflow-hidden">
            {{-- Banner gradient --}}
            <div style="height: 80px; background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);"></div>

            <div class="card-body text-center" style="margin-top: -40px;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($guru->nama) }}&background=6366f1&color=fff&size=128&bold=true"
                     class="rounded-circle mb-3"
                     style="width: 80px; height: 80px; border: 4px solid #fff; box-shadow: var(--shadow-md);">

                <h5 class="fw-bold mb-0" style="color: var(--txt-primary);">{{ $guru->nama }}</h5>
                <p class="mb-1" style="font-size: 0.8rem; color: var(--txt-secondary);">
                    NIP. {{ $guru->nip ?? '-' }}
                </p>
                <span class="badge" style="background: #d1fae5; color: #065f46;">Guru Aktif</span>

                <hr style="border-color: var(--border-color); margin: 1rem 0;">

                {{-- Info cepat --}}
                <div class="d-flex flex-column gap-2 text-start">
                    <div class="d-flex align-items-center gap-2"
                         style="font-size: 0.82rem; color: var(--txt-secondary);">
                        <i class="fas fa-user" style="width:16px; color: var(--clr-primary);"></i>
                        <span>{{ $guru->username }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2"
                         style="font-size: 0.82rem; color: var(--txt-secondary);">
                        <i class="fas fa-id-badge" style="width:16px; color: var(--clr-primary);"></i>
                        <span>{{ ucfirst($guru->role) }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2"
                         style="font-size: 0.82rem; color: var(--txt-secondary);">
                        <i class="fas fa-chalkboard-teacher" style="width:16px; color: var(--clr-primary);"></i>
                        <span>{{ $stats['total_kelas'] }} Kelas Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="card border-0">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">
                    <i class="fas fa-chart-pie me-2" style="color: var(--clr-primary);"></i>
                    Statistik Mengajar
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center"
                             style="background: var(--clr-primary-light);">
                            <div class="fw-bold" style="font-size: 1.5rem; color: var(--clr-primary);">
                                {{ $stats['total_tantangan'] }}
                            </div>
                            <div class="text-label" style="color: var(--clr-primary);">Tantangan</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center"
                             style="background: #d1fae5;">
                            <div class="fw-bold" style="font-size: 1.5rem; color: var(--clr-success);">
                                {{ $stats['total_materi'] }}
                            </div>
                            <div class="text-label" style="color: var(--clr-success);">Materi</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center"
                             style="background: #fef3c7;">
                            <div class="fw-bold" style="font-size: 1.5rem; color: var(--clr-warning);">
                                {{ $stats['total_mapel'] }}
                            </div>
                            <div class="text-label" style="color: var(--clr-warning);">Mapel</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center"
                             style="background: #dbeafe;">
                            <div class="fw-bold" style="font-size: 1.5rem; color: var(--clr-info);">
                                {{ $stats['total_kelas'] }}
                            </div>
                            <div class="text-label" style="color: var(--clr-info);">Kelas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KELAS YANG DIAJAR --}}
        @if($kelasAjar->count())
        <div class="card border-0">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" style="font-size: 0.875rem;">
                    <i class="fas fa-chalkboard me-2" style="color: var(--clr-primary);"></i>
                    Kelas yang Diajar
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-2">
                    @foreach($kelasAjar as $kelasId => $items)
                        @php $kelas = $items->first()->kelas; @endphp
                        <div class="p-3 rounded-2"
                             style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                            <div class="fw-bold mb-1" style="font-size: 0.83rem; color: var(--txt-primary);">
                                <i class="fas fa-door-open me-1" style="color: var(--clr-primary); font-size: 0.75rem;"></i>
                                Kelas {{ $kelas->nama_kelas }}
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($items as $item)
                                    <span class="badge"
                                          style="background: var(--clr-primary-light); color: var(--clr-primary); font-size: 0.68rem;">
                                        {{ $item->mapel->nama_mapel }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ============================================================
         KOLOM KANAN — Form Edit Profil + Ganti Password
    ============================================================ --}}
    <div class="col-lg-8 d-flex flex-column gap-4">

        {{-- FORM EDIT PROFIL --}}
        <div class="card border-0">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="stat-icon stat-icon-primary" style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h6 class="mb-0 fw-bold">Edit Informasi Profil</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('guru.profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $guru->nama) }}"
                                   placeholder="Masukkan nama lengkap">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: var(--bg-muted); border-color: var(--border-color); font-size: 0.82rem; color: var(--txt-secondary);">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input type="text"
                                       name="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username', $guru->username) }}"
                                       placeholder="Username login">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text"
                                   name="nip"
                                   class="form-control @error('nip') is-invalid @enderror"
                                   value="{{ old('nip', $guru->nip) }}"
                                   placeholder="Nomor Induk Pegawai">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mapel (read-only, informatif) --}}
                        @php $mapelList = $guru->mengajar->unique('mapel_id'); @endphp
                        @if($mapelList->count())
                        <div class="col-12">
                            <label class="form-label">Mata Pelajaran yang Diajar</label>
                            <div class="p-3 rounded-2 d-flex flex-wrap gap-2"
                                 style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                                @foreach($mapelList as $item)
                                    <span class="badge"
                                          style="background: var(--clr-primary-light); color: var(--clr-primary); font-size: 0.75rem; padding: 0.4em 0.8em;">
                                        <i class="fas fa-book me-1"></i>{{ $item->mapel->nama_mapel }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="form-text" style="font-size: 0.75rem;">
                                Hubungi admin untuk mengubah penugasan mata pelajaran.
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- FORM GANTI PASSWORD --}}
        <div class="card border-0">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="stat-icon stat-icon-warning" style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                    <i class="fas fa-lock"></i>
                </div>
                <h6 class="mb-0 fw-bold">Ganti Password</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('guru.profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Password Lama</label>
                            <div class="input-group">
                                <input type="password"
                                       name="password_lama"
                                       id="passLama"
                                       class="form-control @error('password_lama') is-invalid @enderror"
                                       placeholder="Masukkan password saat ini">
                                <button type="button"
                                        class="input-group-text btn-toggle-pass"
                                        data-target="passLama"
                                        style="background: var(--bg-muted); border-color: var(--border-color); cursor: pointer;">
                                    <i class="fas fa-eye" style="font-size:0.82rem; color: var(--txt-tertiary);"></i>
                                </button>
                            </div>
                            @error('password_lama')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password"
                                       name="password_baru"
                                       id="passBaru"
                                       class="form-control @error('password_baru') is-invalid @enderror"
                                       placeholder="Min. 6 karakter">
                                <button type="button"
                                        class="input-group-text btn-toggle-pass"
                                        data-target="passBaru"
                                        style="background: var(--bg-muted); border-color: var(--border-color); cursor: pointer;">
                                    <i class="fas fa-eye" style="font-size:0.82rem; color: var(--txt-tertiary);"></i>
                                </button>
                            </div>
                            @error('password_baru')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password"
                                       name="password_baru_confirmation"
                                       id="passKonfirm"
                                       class="form-control"
                                       placeholder="Ulangi password baru">
                                <button type="button"
                                        class="input-group-text btn-toggle-pass"
                                        data-target="passKonfirm"
                                        style="background: var(--bg-muted); border-color: var(--border-color); cursor: pointer;">
                                    <i class="fas fa-eye" style="font-size:0.82rem; color: var(--txt-tertiary);"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Indikator kekuatan password --}}
                        <div class="col-12">
                            <div class="d-flex gap-1 mb-1" id="strengthBars">
                                <div class="strength-bar" style="height:4px; flex:1; border-radius:4px; background: var(--border-color);"></div>
                                <div class="strength-bar" style="height:4px; flex:1; border-radius:4px; background: var(--border-color);"></div>
                                <div class="strength-bar" style="height:4px; flex:1; border-radius:4px; background: var(--border-color);"></div>
                                <div class="strength-bar" style="height:4px; flex:1; border-radius:4px; background: var(--border-color);"></div>
                            </div>
                            <span id="strengthLabel" style="font-size: 0.72rem; color: var(--txt-tertiary);"></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-key me-2"></i>Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- INFO AKUN (read-only) --}}
        <div class="card border-0">
            <div class="card-header d-flex align-items-center gap-2">
                <div class="stat-icon stat-icon-info" style="width:32px; height:32px; font-size:0.85rem; border-radius:8px;">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h6 class="mb-0 fw-bold">Informasi Akun</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-2" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                            <div class="text-label mb-1">ID Pengguna</div>
                            <div class="fw-bold" style="font-size: 0.88rem; color: var(--txt-primary);">
                                #{{ str_pad($guru->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-2" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                            <div class="text-label mb-1">Role</div>
                            <div class="fw-bold" style="font-size: 0.88rem; color: var(--txt-primary);">
                                {{ ucfirst($guru->role) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-2" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                            <div class="text-label mb-1">Akun Dibuat</div>
                            <div class="fw-bold" style="font-size: 0.88rem; color: var(--txt-primary);">
                                {{ $guru->created_at?->translatedFormat('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-2" style="background: var(--bg-muted); border: 1px solid var(--border-color);">
                            <div class="text-label mb-1">Terakhir Diperbarui</div>
                            <div class="fw-bold" style="font-size: 0.88rem; color: var(--txt-primary);">
                                {{ $guru->updated_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle show/hide password
document.querySelectorAll('.btn-toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

// Password strength indicator
const passBaru    = document.getElementById('passBaru');
const bars        = document.querySelectorAll('.strength-bar');
const strengthLbl = document.getElementById('strengthLabel');

const levels = [
    { color: 'var(--clr-danger)',  label: 'Lemah' },
    { color: 'var(--clr-warning)', label: 'Cukup' },
    { color: 'var(--clr-info)',    label: 'Sedang' },
    { color: 'var(--clr-success)', label: 'Kuat' },
];

passBaru?.addEventListener('input', () => {
    const val = passBaru.value;
    let score  = 0;
    if (val.length >= 6)               score++;
    if (val.length >= 10)              score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val))     score++;

    bars.forEach((bar, i) => {
        bar.style.background = i < score ? levels[score - 1].color : 'var(--border-color)';
        bar.style.transition = 'background 0.3s ease';
    });

    strengthLbl.textContent  = val.length ? levels[score - 1]?.label ?? '' : '';
    strengthLbl.style.color  = val.length ? levels[score - 1]?.color ?? '' : 'var(--txt-tertiary)';
});
</script>
@endpush