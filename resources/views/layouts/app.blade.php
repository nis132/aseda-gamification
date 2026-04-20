<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Laravel -->

    <title>@yield('title','Gamifikasi SMPN 2 Semen')</title> <!-- Judul dinamis -->

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animasi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- FONT POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* APPLY FONT */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fb;
    }

    :root {
        --sidebar-width: 280px;
        --sidebar-mini-width: 85px;
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --transition-speed: 0.3s;
    }

    /* ===== GLOBAL IMPROVEMENT ===== */
    .card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .card-header {
        border: none;
        background: var(--primary-gradient);
        color: white !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .card-header h2,
    .card-header p {
        color: white !important;
    }

    .card-header p {
        opacity: 0.9;
    }

    /* NAVBAR */
    .navbar {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .breadcrumb-item a {
        color: #6c757d;
    }

    .breadcrumb-item.active {
        font-weight: 600;
        color: #343a40;
    }

    /* TAB */
    .nav-tabs {
        padding: 10px;
        gap: 8px;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        color: #6c757d;
        font-weight: 600;
        transition: 0.2s;
    }

    .nav-tabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }

    .nav-tabs .nav-link:hover {
        background: #f1f3f9;
    }

    /* BUTTON */
    .btn {
        border-radius: 10px;
        font-weight: 500;
    }

    .btn-lg {
        border-radius: 12px;
    }

    /* 🔥 FIX 1: HILANGKAN BORDER TOMBOL AKSI */
    .btn-outline-warning,
    .btn-outline-danger {
        border: none !important;
    }

    .btn-outline-warning:hover,
    .btn-outline-danger:hover {
        transform: scale(1.05);
    }

    /* 🔥 FIX 2: TOMBOL AKSI BIAR TIDAK KEPOTONG */
    td .btn {
        padding: 6px 10px;
    }

    td .btn + .btn {
        margin-left: 6px;
    }

    td {
        white-space: nowrap;
    }

    td:last-child {
        text-align: center;
    }

    /* INPUT */
    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #e0e3eb;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.15rem rgba(102,126,234,0.2);
    }

    /* ===== LOADING SCREEN ===== */
    #pageLoader {
        position: fixed; inset: 0; background: white;
        display: flex; align-items: center; justify-content: center;
        z-index: 9999; flex-direction: column;
    }

    .loader-icon {
        font-size: 60px;
        color: #667eea;
        animation: spin 1.4s linear infinite;
    }

    @keyframes spin {
        from {transform: rotate(0)}
        to {transform: rotate(360deg)}
    }

    .fade-out {
        opacity: 0;
        transition: opacity .4s;
    }

    /* ===== SIDEBAR CORE ===== */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--primary-gradient);
        transition: width var(--transition-speed), transform var(--transition-speed);
        z-index: 1050;
        left: 0; top: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebar.mini { width: var(--sidebar-mini-width); }

    .sidebar.mini .sidebar-text,
    .sidebar.mini .sidebar-header h5,
    .sidebar.mini .sidebar-header .badge {
        display: none;
    }

    .sidebar.mini .logo-container {
        width: 50px;
        height: 50px;
        margin-right: 0;
    }

    .sidebar.mini .sidebar-header {
        justify-content: center;
        padding: 1.5rem 0.5rem;
    }

    .sidebar-header {
        padding: 1.5rem 1.2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo-container {
        width: 70px;
        height: 70px;
        background: white;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .logo-container img {
        width: 85%;
        height: 85%;
        object-fit: contain;
    }

    /* NAV LINK */
    .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        border-radius: 10px;
        margin: 4px 15px;
        padding: 12px;
        display: flex;
        align-items: center;
        transition: 0.2s;
    }

    /* 🔥 FIX 3: JARAK ICON & TEXT SIDEBAR */
    .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .nav-link:hover,
    .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        color: white !important;
    }

    /* CONTENT */
    .main-content {
        min-height: 100vh;
        transition: margin-left var(--transition-speed);
    }

    body.is-logged-in .main-content {
        margin-left: var(--sidebar-width);
    }

    body.is-logged-in .main-content.expanded {
        margin-left: var(--sidebar-mini-width);
    }

    body.is-guest .main-content {
        margin-left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    /* MOBILE */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
            width: var(--sidebar-width) !important;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
</head>

<body class="bg-light {{ Auth::check() ? 'is-logged-in' : 'is-guest' }}">

<!-- LOADER -->
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
            <div class="badge bg-white bg-opacity-10 mx-3 my-2 sidebar-text py-2 text-uppercase">
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
            <button class="btn btn-link text-white text-decoration-none w-100 p-0 nav-link">
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

    <!-- NOTIF GLOBAL -->
    @if(session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index:9999;">
            <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-3 animate__animated animate__fadeInDown">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

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

// 🔥 AUTO CLOSE NOTIF
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);

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
                    
                    <img src="{{ asset('storage/badge/' . $badgeItem->badge->icon) }}" 
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