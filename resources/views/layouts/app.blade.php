<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gamifikasi SMPN 2 Semen')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar { 
            min-height: 100vh; 
            background: var(--primary-gradient);
            transition: all 0.3s; 
            width: var(--sidebar-width);
        }

        /* Scrollbar lebih halus & tidak jelek */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.4);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.6);
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.9); 
            border-radius: 12px; 
            margin: 6px 16px; 
            padding: 14px 20px;
            font-weight: 500;
            position: relative;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            background: rgba(255,255,255,0.25); 
            color: white; 
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .sidebar .nav-link i {
            width: 20px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover i {
            transform: scale(1.2);
        }

        
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .navbar-top { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        /* LOGOUT BUTTON STYLING */
        .logout-section {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .logout-btn {
            transition: all 0.3s ease !important;
            border: 2px solid rgba(255,255,255,0.3) !important;
            color: rgba(255,255,255,0.95) !important;
            background: rgba(255,255,255,0.1) !important;
            font-weight: 600 !important;
            border-radius: 12px !important;
            padding: 14px 20px !important;
            width: 100% !important;
            text-align: left !important;
        }
        .logout-btn:hover:not(:disabled) {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(220,53,69,0.4);
        }
        .logout-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        @media (max-width: 992px) { 
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .sidebar.show { transform: translateX(0); }
        }
        .guest-layout { margin-left: 0 !important; }
    </style>
</head>
<body class="bg-light">

    {{-- SIDEBAR - DYNAMIC BY ROLE --}}
    @auth
    <div class="sidebar position-fixed d-none d-lg-flex flex-column p-4 shadow-lg z-3">
        <!-- Logo SMPN 2 Semen -->
        <div class="text-white text-center mb-5 pb-4 border-bottom border-white border-opacity-25">
            <div class="bg-white bg-opacity-20 rounded-circle mx-auto mb-3 p-4 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                <i class="fas fa-graduation-cap fa-2x"></i>
            </div>
            <h4 class="mb-1 fw-bold">SMPN 2 Semen</h4>
            <div class="badge bg-white bg-opacity-30 px-3 py-1 rounded-pill fs-6">
                {{ ucfirst(auth()->user()->role) }}
            </div>
        </div>

        <!-- NAVIGATION DYNAMIC -->
        <nav class="nav flex-column flex-grow-1">
            
            {{-- DASHBOARD COMMON --}}
            <a class="nav-link {{ request()->routeIs('*dashboard*') ? 'active' : '' }}" 
               href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : (auth()->user()->role == 'siswa' ? route('siswa.dashboard') : route('guru.dashboard')) }}">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>

            {{-- ADMIN MENU - SESUAI PROPOSAL --}}
            @if(auth()->user()->role == 'admin')
                <div class="dropdown-divider bg-white bg-opacity-25 my-3"></div>
                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-3"></i> Kelola User
                </a>
                <a class="nav-link {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}" href="{{ route('admin.kelas.index') }}">
                    <i class="fas fa-chalkboard me-3"></i> Kelola Kelas
                </a>
                <a class="nav-link {{ request()->routeIs('admin.mapel*') ? 'active' : '' }}" href="{{ route('admin.mapel.index') }}">
                    <i class="fas fa-book me-3"></i> Mata Pelajaran
                </a>

            {{-- GURU MENU - SESUAI PROPOSAL --}}
            @elseif(auth()->user()->role == 'guru')
                <div class="dropdown-divider bg-success bg-opacity-25 my-3"></div>
                <a class="nav-link {{ request()->is('guru/materi*') ? 'active' : '' }}" href="/guru/materi">
                    <i class="fas fa-book-open me-3"></i> Kelola Materi
                </a>
                <a class="nav-link {{ request()->is('guru/tantangan*') ? 'active' : '' }}" href="/guru/tantangan">
                    <i class="fas fa-dice-d20 me-3"></i> Kelola Tantangan
                </a>

            {{-- SISWA MENU - SESUAI PROPOSAL --}}
            @else
                <div class="dropdown-divider bg-white bg-opacity-25 my-3"></div>
                <a class="nav-link {{ request()->routeIs('siswa.materi*') ? 'active' : '' }}" href="{{ route('siswa.materi') }}">
                    <i class="fas fa-book me-3"></i> Materi
                </a>
                <a class="nav-link {{ request()->routeIs('siswa.tantangan*') ? 'active' : '' }}" href="{{ route('siswa.tantangan') }}">
                    <i class="fas fa-tasks me-3"></i> Tantangan
                </a>
                <a class="nav-link {{ request()->routeIs('siswa.leaderboard') ? 'active' : '' }}" href="{{ route('siswa.leaderboard') }}">
                    <i class="fas fa-trophy me-3"></i> Leaderboard
                </a>
                <a class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}" href="{{ route('siswa.profil') }}">
                    <i class="fas fa-user me-3"></i> Profil
                </a>
            @endif
        </nav>

        {{-- ========== LOGOUT SECTION - SEMUA ROLE ========= --}}
        <div class="logout-section">            
            {{-- LOGOUT FORM - FIX 405 ERROR --}}
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Keluar ({{ ucfirst(auth()->user()->role) }})
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Toggle -->
    <button class="btn btn-primary position-fixed p-3 m-3 d-lg-none z-4 rounded-circle shadow-lg" 
            id="sidebarToggle" style="left: 20px; top: 20px;">
        <i class="fas fa-bars fs-5"></i>
    </button>
    @endauth

    {{-- FLASH MESSAGES --}}
    <x-alert-messages />
    {{-- MAIN CONTENT --}}
    <div class="main-content pt-4 {{ auth()->check() ? '' : 'guest-layout' }}">
        @auth
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-top bg-white mb-4 mx-4 rounded-4 shadow-sm">
            <div class="container-fluid px-0">
                <!-- Breadcrumb DYNAMIC -->
                <div class="navbar-nav me-auto flex-grow-1">
                    <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : (auth()->user()->role == 'siswa' ? route('siswa.dashboard') : route('guru.dashboard')) }}">
                                    <i class="fas fa-home text-muted"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                        </ol>
                    </nav>
                </div>

                <!-- User Dropdown (NO LOGOUT - ONLY SIDEBAR) -->
                <div class="navbar-nav ms-auto">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center p-2 text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="avatar-sm bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 45px; height: 45px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="d-none d-md-block">
                                <div class="fw-bold">{{ auth()->user()->nama ?? auth()->user()->name }}</div>
                                <small class="text-muted text-capitalize">{{ auth()->user()->role }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                            <li>
                                <span class="dropdown-item py-2 px-3">
                                    <strong>{{ auth()->user()->nama ?? auth()->user()->name }}</strong><br>
                                    <small class="text-muted">{{ auth()->user()->username }}</small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Role: {{ ucfirst(auth()->user()->role) }}</h6></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <!-- Content -->
        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show rounded-3 mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show rounded-3 mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar')?.classList.toggle('show');
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('#sidebarToggle')) {
                document.querySelector('.sidebar')?.classList.remove('show');
            }
        });

        // 🔥 LOGOUT SMOOTH - FIX MACET & 405 ERROR
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const role = this.querySelector('span')?.textContent || '{{ ucfirst(auth()->user()->role ?? "Pengguna") }}';
                    
                    if (confirm(`Yakin keluar sebagai ${role}?`)) {
                        // Loading state
                        this.disabled = true;
                        this.innerHTML = `
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            <span>Memproses logout...</span>
                        `;
                        
                        // Submit form
                        this.closest('form').submit();
                    }
                });
            }
        });
    </script>
</body>
</html>
