<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','Gamifikasi SMPN 2 Semen')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-mini-width: 85px;
            --primary-gradient: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            --transition-speed: 0.3s;
        }

        /* ===== LOADING SCREEN ===== */
        #pageLoader {
            position: fixed; inset: 0; background: white;
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; flex-direction: column;
        }
        .loader-icon { font-size: 60px; color: #667eea; animation: spin 1.4s linear infinite; }
        @keyframes spin { from {transform: rotate(0)} to {transform: rotate(360deg)} }
        .fade-out { opacity: 0; transition: opacity .4s; }

        /* ===== SIDEBAR CORE ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-gradient);
            transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1), transform var(--transition-speed);
            z-index: 1050;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Mode Mini (Desktop) */
        .sidebar.mini { width: var(--sidebar-mini-width); }
        .sidebar.mini .sidebar-text, 
        .sidebar.mini .sidebar-header h5,
        .sidebar.mini .sidebar-header .badge {
            display: none;
        }
        .sidebar.mini .logo-container { width: 50px; height: 50px; margin-right: 0; }
        .sidebar.mini .sidebar-header { justify-content: center; padding: 1.5rem 0.5rem; }

        /* Header Sidebar */
        .sidebar-header {
            padding: 1.5rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-grow: 1;
        }

        .logo-container {
            width: 70px; height: 70px;
            background: white; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            transition: all var(--transition-speed);
            padding: 5px;
        }
        .logo-container img { max-width: 100%; height: auto; }

        /* Hamburger Button */
        .toggle-sidebar-btn {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: 0.2s;
        }
        .toggle-sidebar-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Scrollable Nav Area */
        .sidebar-nav-container {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-top: 10px;
        }

        .sidebar-nav-container::-webkit-scrollbar { width: 4px; }
        .sidebar-nav-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            border-radius: 10px;
            margin: 4px 15px;
            padding: 12px;
            display: flex; align-items: center;
            white-space: nowrap;
            transition: 0.2s;
        }
        .nav-link i { width: 35px; text-align: center; font-size: 1.2rem; flex-shrink: 0; }
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        /* ===== LAYOUT CONTENT FIX ===== */
        .main-content {
            min-height: 100vh;
            transition: margin-left var(--transition-speed);
        }

        /* Hanya beri margin jika user login (ada sidebar) */
        body.is-logged-in .main-content {
            margin-left: var(--sidebar-width);
        }
        body.is-logged-in .main-content.expanded {
            margin-left: var(--sidebar-mini-width);
        }

        /* Pusatkan konten secara vertikal & horizontal jika di halaman login (guest) */
        body.is-guest .main-content {
            margin-left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* Responsive Mobile */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-width) !important; }
            .sidebar.show { transform: translateX(0); }
            .main-content, body.is-logged-in .main-content { margin-left: 0 !important; }
            .sidebar-overlay {
                position: fixed; inset: 0; background: rgba(0,0,0,0.5);
                display: none; z-index: 1040;
            }
            .sidebar-overlay.show { display: block; }
            .toggle-sidebar-btn { display: none; }
        }
    </style>
</head>

<body class="bg-light {{ Auth::check() ? 'is-logged-in' : 'is-guest' }}">

<div id="pageLoader">
    <i class="fas fa-graduation-cap loader-icon"></i>
    <div class="mt-3 fw-bold text-muted">Memuat Sistem Gamifikasi...</div>
</div>

@auth
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar position-fixed shadow-lg" id="mainSidebar">
    
    <div class="sidebar-header">
        <div class="logo-section">
            <div class="logo-container">
                <img src="{{ asset('storage/logo_aseda.webp') }}" alt="ASEDA">
            </div>
            <h6 class="text-white fw-bold mt-2 mb-0 sidebar-text">SMPN 2 Semen</h6>
        </div>

        <div class="toggle-sidebar-btn d-none d-lg-flex" id="desktopToggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <div class="sidebar-nav-container">
        <nav class="nav flex-column">
            <div class="badge bg-white bg-opacity-10 mx-3 my-2 sidebar-text py-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">
                Role: {{ auth()->user()->role }}
            </div>

            <a class="nav-link {{ request()->routeIs('*dashboard*') ? 'active':'' }}" 
               href="{{ auth()->user()->role=='admin' ? route('admin.dashboard') : (auth()->user()->role=='siswa' ? route('siswa.dashboard') : route('guru.dashboard')) }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            @if(auth()->user()->role=='admin')
                <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span class="sidebar-text">Kelola User</span></a>
                <a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-chalkboard"></i> <span class="sidebar-text">Kelola Kelas</span></a>
                <a class="nav-link" href="{{ route('admin.mapel.index') }}"><i class="fas fa-book"></i> <span class="sidebar-text">Mata Pelajaran</span></a>
            @elseif(auth()->user()->role=='guru')
                <a class="nav-link" href="/guru/materi"><i class="fas fa-book-open"></i> <span class="sidebar-text">Kelola Materi</span></a>
                <a class="nav-link" href="/guru/tantangan"><i class="fas fa-dice-d20"></i> <span class="sidebar-text">Kelola Tantangan</span></a>
            @else
                <a class="nav-link" href="{{ route('siswa.materi') }}"><i class="fas fa-book"></i> <span class="sidebar-text">Materi</span></a>
                <a class="nav-link" href="{{ route('siswa.tantangan') }}"><i class="fas fa-tasks"></i> <span class="sidebar-text">Tantangan</span></a>
                <a class="nav-link" href="{{ route('leaderboard') }}"><i class="fas fa-trophy"></i> <span class="sidebar-text">Leaderboard</span></a>
                <a class="nav-link" href="{{ route('siswa.profil') }}"><i class="fas fa-user"></i> <span class="sidebar-text">Profil</span></a>
            @endif
        </nav>
    </div>

    <div class="p-3 border-top border-white border-opacity-10 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-link text-white text-decoration-none w-100 p-0 nav-link" style="border:none;">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-text">Keluar</span>
            </button>
        </form>
    </div>
</aside>
@endauth

<div class="main-content" id="mainContent">
    @auth
    <nav class="navbar navbar-light bg-white mb-4 mx-4 mt-4 rounded-4 shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-light d-lg-none shadow-sm me-3" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-nav me-auto">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home text-muted"></i></a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>
            
            <div class="navbar-nav ms-auto">
                @isset($level)
                <span class="badge bg-warning text-dark">Level {{ $level }}</span>
                @endisset
            </div>
        </div>
    </nav>
    @endauth

    <div class="container-fluid px-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const sidebar = document.getElementById('mainSidebar');
const content = document.getElementById('mainContent');
const desktopToggle = document.getElementById('desktopToggle');
const mobileToggle = document.getElementById('mobileToggle');
const overlay = document.getElementById('sidebarOverlay');

desktopToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('mini');
    content.classList.toggle('expanded');
});

mobileToggle?.addEventListener('click', () => {
    sidebar.classList.add('show');
    overlay.classList.add('show');
});

overlay?.addEventListener('click', () => {
    sidebar.classList.remove('show');
    overlay.classList.remove('show');
});

window.addEventListener('load', function(){
    const loader = document.getElementById("pageLoader");
    loader.classList.add("fade-out");
    setTimeout(() => { loader.style.display = "none" }, 400);
});

// PWA & PUSH NOTIF
window.addEventListener('load', function() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/sw.js').then(function(reg) {
            console.log('ServiceWorker registered');
            @auth
                checkAndSubscribe(reg);
            @endauth
        }).catch(err => console.error('SW Error:', err));
    }
});

async function checkAndSubscribe(registration) {
    const permission = await Notification.requestPermission();
    if (permission !== 'granted') return;

    let subscription = await registration.pushManager.getSubscription();

    if (!subscription) {
        const publicKey = "{{ env('VAPID_PUBLIC_KEY') }}";
        subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(publicKey)
        });

        await sendSubscriptionToBackend(subscription);
    }
}

async function sendSubscriptionToBackend(subscription) {
    await fetch('/notifications/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(subscription)
    });
    console.log('Push notification enabled.');
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
</script>
@auth
    @php
        // Ambil SEMUA badge baru
        $newBadges = \App\Models\SiswaBadge::where('siswa_id', auth()->id())
                    ->where('is_new', 1)
                    ->with('badge')
                    ->get();
    @endphp

    @if($newBadges->count())
        <style>
            .badge-overlay-global {
                position: fixed; 
                top: 0; left: 0; 
                width: 100%; height: 100%;
                background: rgba(0,0,0,0.85); 
                z-index: 10000;
                display: flex; 
                align-items: center; 
                justify-content: center;
                backdrop-filter: blur(8px);
            }
            .badge-content-global {
                text-align: center; 
                color: white;
            }
            .badge-img-new {
                width: 200px; 
                height: 200px; 
                object-fit: contain;
                filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.5));
            }
            .text-gold { 
                color: #ffd700; 
                font-weight: bold; 
            }
        </style>

        @foreach($newBadges as $badgeItem)
            <div id="globalBadgePopup-{{ $badgeItem->id }}" class="badge-overlay-global">
                <div class="badge-content-global animate__animated animate__jackInTheBox">
                    
                    <h1 class="text-gold display-4 mb-0">SELAMAT!</h1>
                    <p class="fs-4 mb-4">Kamu mendapatkan badge baru:</p>
                    
                    {{-- ✅ FIX VARIABEL + PATH --}}
                    <img src="{{ asset('storage/badges/' . $badgeItem->badge->icon) }}" 
                         class="badge-img-new mb-3">
                    
                    <h2 class="mb-4">{{ $badgeItem->badge->nama_badge }}</h2>
                    
                    <button onclick="closeBadgePopup({{ $badgeItem->id }})" 
                        class="btn btn-warning btn-lg px-5 rounded-pill fw-bold">
                        TERIMA KASIH!
                    </button>

                </div>
            </div>
        @endforeach

        <script>
            function closeBadgePopup(siswaBadgeId) {
                fetch(`/siswa/badge/mark-as-seen/${siswaBadgeId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    const overlay = document.getElementById('globalBadgePopup-' + siswaBadgeId);
                    
                    overlay.classList.add('animate__animated', 'animate__fadeOut');

                    setTimeout(() => {
                        overlay.remove();
                    }, 500);
                });
            }
        </script>
    @endif
@endauth
</body>
</html>