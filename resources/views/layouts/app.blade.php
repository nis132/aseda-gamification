<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gamifikasi SMPN 2 Semen')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
    /* ============================================================
       DESIGN TOKENS — satu sumber kebenaran untuk seluruh aplikasi
       Ubah di sini, berlaku ke semua halaman.
    ============================================================ */
    :root {
        /* Brand */
        --clr-primary:        #6366f1;
        --clr-primary-dark:   #4f52d6;
        --clr-primary-light:  #eef0ff;
        --clr-primary-rgb:    99, 102, 241;

        /* Sidebar */
        --sidebar-bg:         linear-gradient(160deg, #4f46e5 0%, #7c3aed 100%);
        --sidebar-width:      270px;
        --sidebar-mini-width: 72px;

        /* Surface */
        --bg-body:            #f5f6fa;
        --bg-card:            #ffffff;
        --bg-muted:           #f8fafc;

        /* Text */
        --txt-primary:        #1e1e2d;
        --txt-secondary:      #64748b;
        --txt-tertiary:       #94a3b8;

        /* Border */
        --border-color:       #e8ecf0;
        --border-radius-sm:   8px;
        --border-radius-md:   12px;
        --border-radius-lg:   16px;
        --border-radius-xl:   20px;

        /* Shadow */
        --shadow-xs:          0 1px 3px rgba(0,0,0,0.06);
        --shadow-sm:          0 4px 12px rgba(0,0,0,0.06);
        --shadow-md:          0 8px 24px rgba(0,0,0,0.08);
        --shadow-lg:          0 16px 40px rgba(0,0,0,0.10);

        /* Transition */
        --transition:         0.2s ease;
        --transition-sidebar: 0.3s cubic-bezier(0.4, 0, 0.2, 1);

        /* Semantic colors */
        --clr-success:        #10b981;
        --clr-warning:        #f59e0b;
        --clr-danger:         #ef4444;
        --clr-info:           #3b82f6;
    }

    /* ============================================================
       BASE
    ============================================================ */
    *, *::before, *::after { box-sizing: border-box; }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-body);
        color: var(--txt-primary);
        font-size: 0.9rem;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    /* ============================================================
       REUSABLE CARDS
    ============================================================ */
    .card {
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        background: var(--bg-card);
        transition: box-shadow var(--transition), transform var(--transition);
    }

    /* OVERRIDE: card-header tidak boleh gradient global —
       setiap halaman boleh atur sendiri via class spesifik */
    .card-header {
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-color);
        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0 !important;
        padding: 1rem 1.25rem;
        color: var(--txt-primary);
    }

    /* Variasi card-header bergradien — gunakan class ini secara eksplisit */
    .card-header-gradient {
        background: linear-gradient(135deg, var(--clr-primary) 0%, #7c3aed 100%);
        color: #fff;
        border-bottom: none;
    }
    .card-header-gradient * { color: #fff !important; }

    /* Stat card: hover lift */
    .card-stat {
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        transition: box-shadow var(--transition), transform var(--transition);
    }
    .card-stat:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
    }

    /* ============================================================
       TYPOGRAPHY SCALE
    ============================================================ */
    .text-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--txt-secondary);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        line-height: 1;
        color: var(--txt-primary);
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--txt-primary);
        margin-bottom: 0.25rem;
    }

    /* ============================================================
       BUTTONS — konsisten, tidak ada padding override via !important
    ============================================================ */
    .btn {
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1.1rem;
        transition: all var(--transition);
        border: none;
    }

    .btn-primary {
        background: var(--clr-primary);
        color: #fff;
    }
    .btn-primary:hover {
        background: var(--clr-primary-dark);
        color: #fff;
        box-shadow: 0 4px 12px rgba(var(--clr-primary-rgb), 0.35);
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        border: 1.5px solid var(--clr-primary) !important;
        color: var(--clr-primary);
        background: transparent;
    }
    .btn-outline-primary:hover {
        background: var(--clr-primary-light);
        color: var(--clr-primary-dark);
    }

    .btn-light {
        background: var(--bg-muted);
        border: 1px solid var(--border-color) !important;
        color: var(--txt-primary);
    }
    .btn-light:hover {
        background: #fff;
        border-color: var(--clr-primary) !important;
        color: var(--clr-primary);
    }

    /* Action buttons (tabel: edit/hapus) — no !important needed */
    .btn-action {
        padding: 0.3rem 0.65rem;
        font-size: 0.78rem;
        border-radius: var(--border-radius-sm);
    }

    /* Hover lift (quick action cards) */
    .hover-lift {
        transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md) !important;
        border-color: var(--clr-primary) !important;
        background-color: #fff !important;
    }

    /* ============================================================
       FORMS
    ============================================================ */
    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--txt-secondary);
        margin-bottom: 0.4rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        padding: 0.55rem 0.9rem;
        font-size: 0.875rem;
        color: var(--txt-primary);
        background-color: var(--bg-card);
        transition: border-color var(--transition), box-shadow var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--clr-primary);
        box-shadow: 0 0 0 3px rgba(var(--clr-primary-rgb), 0.12);
        outline: none;
        background-color: var(--bg-card);
    }

    .form-control::placeholder { color: var(--txt-tertiary); }

    /* ============================================================
       TABLES
    ============================================================ */
    .table {
        font-size: 0.875rem;
        color: var(--txt-primary);
    }

    .table thead th {
        background: var(--bg-muted);
        border-bottom: 2px solid var(--border-color);
        color: var(--txt-secondary);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.85rem 1rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 0.9rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: var(--txt-primary);
    }

    .table tbody tr:last-child td { border-bottom: none; }

    .table-hover tbody tr:hover {
        background-color: rgba(var(--clr-primary-rgb), 0.03);
    }

    /* ============================================================
       BADGES
    ============================================================ */
    .badge {
        font-weight: 600;
        font-size: 0.7rem;
        letter-spacing: 0.02em;
        border-radius: 6px;
        padding: 0.3em 0.65em;
    }

    /* ============================================================
       SIDEBAR
    ============================================================ */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 1050;
        transition: width var(--transition-sidebar), transform var(--transition-sidebar);
    }

    .sidebar.mini { width: var(--sidebar-mini-width); }

    /* Header logo */
    .sidebar-header {
        padding: 1.5rem 1rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        overflow: hidden;
        min-height: 76px;
    }

    .sidebar-logo {
        width: 42px;
        height: 42px;
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        backdrop-filter: blur(8px);
    }

    .sidebar-logo img {
        width: 28px;
        height: 28px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    .sidebar-brand {
        overflow: hidden;
        white-space: nowrap;
        transition: opacity var(--transition-sidebar), width var(--transition-sidebar);
    }

    .sidebar-brand-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
        display: block;
        line-height: 1.2;
    }

    .sidebar-brand-sub {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.55);
        display: block;
    }

    .sidebar.mini .sidebar-brand { opacity: 0; width: 0; }

    /* Nav */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0.75rem 0;
        scrollbar-width: none;
    }
    .sidebar-nav::-webkit-scrollbar { display: none; }

    .nav-section-label {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.35);
        padding: 0.75rem 1.25rem 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        transition: opacity var(--transition-sidebar);
    }
    .sidebar.mini .nav-section-label { opacity: 0; }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: rgba(255,255,255,0.7) !important;
        padding: 0.65rem 1rem;
        margin: 2px 0.625rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        transition: all var(--transition);
        position: relative;
    }

    .nav-link .nav-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        flex-shrink: 0;
        font-size: 0.95rem;
        background: rgba(255,255,255,0.07);
        transition: background var(--transition), color var(--transition);
    }

    .nav-link .nav-text {
        overflow: hidden;
        transition: opacity var(--transition-sidebar), width var(--transition-sidebar);
    }

    .sidebar.mini .nav-link .nav-text { opacity: 0; width: 0; }

    .nav-link:hover {
        color: #fff !important;
        background: rgba(255,255,255,0.1);
    }

    .nav-link:hover .nav-icon {
        background: rgba(255,255,255,0.18);
        color: #fff;
    }

    .nav-link.active {
        color: #fff !important;
        background: rgba(255,255,255,0.15);
        font-weight: 600;
    }

    .nav-link.active .nav-icon {
        background: rgba(255,255,255,0.25);
        color: #fff;
    }

    /* Active indicator strip */
    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 60%;
        background: #fff;
        border-radius: 0 3px 3px 0;
    }

    /* Sidebar tooltip (mini mode) */
    .nav-link[data-tooltip] { position: relative; }

    .sidebar.mini .nav-link[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(var(--sidebar-mini-width) - 10px);
        top: 50%;
        transform: translateY(-50%);
        background: rgba(15, 15, 30, 0.92);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity var(--transition), transform var(--transition);
        z-index: 9999;
        box-shadow: var(--shadow-md);
        transform: translateY(-50%) translateX(-4px);
    }

    .sidebar.mini .nav-link[data-tooltip]:hover::after {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }

    /* Sidebar footer */
    .sidebar-footer {
        padding: 0.75rem 0.625rem;
        border-top: 1px solid rgba(255,255,255,0.08);
    }

    .sidebar-footer .nav-link { margin: 0; }

    /* ============================================================
       MAIN CONTENT
    ============================================================ */
    .main-content {
        min-height: 100vh;
        transition: margin-left var(--transition-sidebar);
        display: flex;
        flex-direction: column;
    }

    body.is-logged-in .main-content {
        margin-left: var(--sidebar-width);
    }

    body.is-logged-in .main-content.expanded {
        margin-left: var(--sidebar-mini-width);
    }

    /* ============================================================
       NAVBAR
    ============================================================ */
    .navbar-custom {
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-color);
        padding: 0.6rem 0;
        box-shadow: var(--shadow-xs);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar-custom .breadcrumb {
        margin-bottom: 0;
        font-size: 0.82rem;
    }

    .navbar-custom .breadcrumb-item.active {
        color: var(--txt-primary);
        font-weight: 600;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--txt-tertiary);
    }

    .role-badge {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.3em 0.7em;
        border-radius: 6px;
        background: var(--clr-primary-light);
        color: var(--clr-primary);
    }

    /* ============================================================
       PAGE LAYOUT HELPERS
    ============================================================ */
    .page-wrapper { padding: 1.5rem 1.75rem; flex: 1; }

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    /* ============================================================
       STAT ICON (reusable)
    ============================================================ */
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .stat-icon-primary { background: var(--clr-primary-light); color: var(--clr-primary); }
    .stat-icon-success { background: #d1fae5; color: var(--clr-success); }
    .stat-icon-warning { background: #fef3c7; color: var(--clr-warning); }
    .stat-icon-danger  { background: #fee2e2; color: var(--clr-danger); }
    .stat-icon-info    { background: #dbeafe; color: var(--clr-info); }

    /* ============================================================
       ICON SHAPE (tabel)
    ============================================================ */
    .icon-shape {
        width: 38px;
        height: 38px;
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--bg-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 1.75rem;
        color: var(--txt-tertiary);
    }

    .empty-state h6 {
        font-weight: 600;
        color: var(--txt-primary);
        margin-bottom: 0.4rem;
    }

    .empty-state p {
        font-size: 0.85rem;
        color: var(--txt-secondary);
        margin-bottom: 1.25rem;
    }

    /* ============================================================
       TOAST / ALERT
    ============================================================ */
    .toast-container-custom {
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        max-width: 360px;
    }

    .toast-custom {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-lg);
        padding: 1rem 1.1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        animation: slideInRight 0.3s ease;
    }

    .toast-custom.success { border-left: 4px solid var(--clr-success); }
    .toast-custom.error   { border-left: 4px solid var(--clr-danger); }
    .toast-custom.warning { border-left: 4px solid var(--clr-warning); }

    .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .toast-custom.success .toast-icon { background: #d1fae5; color: var(--clr-success); }
    .toast-custom.error   .toast-icon { background: #fee2e2; color: var(--clr-danger); }
    .toast-custom.warning .toast-icon { background: #fef3c7; color: var(--clr-warning); }

    .toast-body { flex: 1; min-width: 0; }
    .toast-title { font-size: 0.82rem; font-weight: 700; color: var(--txt-primary); display: block; }
    .toast-msg   { font-size: 0.8rem; color: var(--txt-secondary); margin-top: 2px; }

    .toast-close {
        background: none;
        border: none;
        color: var(--txt-tertiary);
        font-size: 0.9rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        flex-shrink: 0;
        transition: color var(--transition);
    }
    .toast-close:hover { color: var(--txt-primary); }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(16px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideOutRight {
        from { opacity: 1; transform: translateX(0); }
        to   { opacity: 0; transform: translateX(16px); }
    }

    /* ============================================================
       MODAL
    ============================================================ */
    .modal-content {
        border: none;
        border-radius: var(--border-radius-xl);
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.1rem 1.4rem;
        border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1rem 1.4rem;
        border-radius: 0 0 var(--border-radius-xl) var(--border-radius-xl);
    }

    /* ============================================================
       LOADING SCREEN
    ============================================================ */
    #pageLoader {
        position: fixed;
        inset: 0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        flex-direction: column;
        gap: 1rem;
    }

    .loader-spinner {
        width: 44px;
        height: 44px;
        border: 3px solid var(--clr-primary-light);
        border-top-color: var(--clr-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .fade-out {
        opacity: 0;
        transition: opacity 0.35s ease;
        pointer-events: none;
    }

    /* ============================================================
       MOBILE OVERLAY
    ============================================================ */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 1040;
        backdrop-filter: blur(2px);
    }

    .sidebar-overlay.show { display: block; }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
            width: var(--sidebar-width) !important;
        }

        .sidebar.show { transform: translateX(0); }
        .main-content { margin-left: 0 !important; }
        .page-wrapper { padding: 1rem; }
    }

    @media (max-width: 575.98px) {
        .page-wrapper { padding: 0.875rem 0.875rem; }
        .stat-number { font-size: 1.6rem; }
    }

    /* ============================================================
       MISC UTILITIES
    ============================================================ */
    .border-dashed { border-style: dashed !important; }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Focus visible — aksesibilitas keyboard */
    :focus-visible {
        outline: 2px solid var(--clr-primary);
        outline-offset: 2px;
        border-radius: 4px;
    }
    </style>
</head>

<body class="{{ Auth::check() ? 'is-logged-in' : 'is-guest' }}">

{{-- LOADING SCREEN --}}
<div id="pageLoader">
    <div class="loader-spinner"></div>
    <span style="font-size: 0.82rem; color: #94a3b8; font-weight: 500;">Memuat sistem...</span>
</div>

@auth
{{-- MOBILE OVERLAY --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- SIDEBAR --}}
<aside class="sidebar shadow-lg" id="mainSidebar">

    {{-- Header --}}
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('storage/logo_aseda.webp') }}" alt="ASEDA">
        </div>
        <div class="sidebar-brand">
            <span class="sidebar-brand-name">SMPN 2 Semen</span>
            <span class="sidebar-brand-sub">Sistem Gamifikasi</span>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>

        {{-- Dashboard --}}
        @php
            $dashRoute = match(auth()->user()->role) {
                'admin' => route('admin.dashboard'),
                'siswa' => route('siswa.dashboard'),
                default => route('guru.dashboard'),
            };
        @endphp
        <a class="nav-link {{ request()->routeIs('*dashboard*') ? 'active' : '' }}"
           href="{{ $dashRoute }}"
           data-tooltip="Dashboard">
            <span class="nav-icon"><i class="fas fa-th-large"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>

        @if(auth()->user()->role === 'admin')
            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}" data-tooltip="Kelola User">
                <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                <span class="nav-text">Kelola User</span>
            </a>
            <a class="nav-link {{ request()->is('admin/kelas*') ? 'active' : '' }}"
               href="{{ route('admin.kelas.index') }}" data-tooltip="Kelola Kelas">
                <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <span class="nav-text">Kelola Kelas</span>
            </a>
            <a class="nav-link {{ request()->is('admin/mapel*') ? 'active' : '' }}"
               href="{{ route('admin.mapel.index') }}" data-tooltip="Mata Pelajaran">
                <span class="nav-icon"><i class="fas fa-book"></i></span>
                <span class="nav-text">Mata Pelajaran</span>
            </a>

        @elseif(auth()->user()->role === 'guru')
            <a class="nav-link {{ request()->is('guru/materi*') ? 'active' : '' }}"
               href="/guru/materi" data-tooltip="Kelola Materi">
                <span class="nav-icon"><i class="fas fa-book-open"></i></span>
                <span class="nav-text">Kelola Materi</span>
            </a>
            <a class="nav-link {{ request()->is('guru/tantangan*') ? 'active' : '' }}"
               href="/guru/tantangan" data-tooltip="Kelola Tantangan">
                <span class="nav-icon"><i class="fas fa-dice-d20"></i></span>
                <span class="nav-text">Kelola Tantangan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('guru.rekap*') ? 'active' : '' }}"
               href="{{ route('guru.rekap.index') }}" data-tooltip="Rekap Nilai">
                <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                <span class="nav-text">Rekap Nilai</span>
            </a>
            <a class="nav-link {{ request()->routeIs('guru.profil*') ? 'active' : '' }}"
               href="{{ route('guru.profil') }}" data-tooltip="Edit Profil">
                <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                <span class="nav-text">Profil</span>
            </a>

        @else
            <a class="nav-link {{ request()->routeIs('siswa.materi') ? 'active' : '' }}"
               href="{{ route('siswa.materi') }}" data-tooltip="Materi">
                <span class="nav-icon"><i class="fas fa-journal-whills"></i></span>
                <span class="nav-text">Materi</span>
            </a>
            <a class="nav-link {{ request()->routeIs('siswa.tantangan') ? 'active' : '' }}"
               href="{{ route('siswa.tantangan') }}" data-tooltip="Tantangan">
                <span class="nav-icon"><i class="fas fa-medal"></i></span>
                <span class="nav-text">Tantangan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}"
               href="{{ route('leaderboard') }}" data-tooltip="Leaderboard">
                <span class="nav-icon"><i class="fas fa-trophy"></i></span>
                <span class="nav-text">Leaderboard</span>
            </a>
            <a class="nav-link {{ request()->routeIs('siswa.badge.validasi') ? 'active' : '' }}"
               href="{{ route('siswa.badge.validasi') }}" data-tooltip="Koleksi Badge">
                <span class="nav-icon"><i class="fas fa-shield-alt"></i></span>
                <span class="nav-text">Koleksi Badge</span>
            </a>
            <a class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}"
               href="{{ route('siswa.profil') }}" data-tooltip="Profil">
                <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                <span class="nav-text">Profil</span>
            </a>
        @endif
    </div>

    {{-- Footer: Logout --}}
    <div class="sidebar-footer">
        <button type="button" class="nav-link border-0 w-100 text-start"
                style="background: none; cursor: pointer;"
                onclick="showLogoutModal()"
                data-tooltip="Keluar">
            <span class="nav-icon" style="background: rgba(239,68,68,0.15); color: #f87171;">
                <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="nav-text" style="color: rgba(255,255,255,0.65);">Keluar</span>
        </button>
    </div>
</aside>
@endauth

{{-- MAIN CONTENT --}}
<div class="main-content" id="mainContent">

    @auth
    {{-- NAVBAR --}}
    <nav class="navbar navbar-light navbar-custom">
        <div class="container-fluid px-3 gap-3">
            <button class="btn btn-light btn-sm d-flex align-items-center justify-content-center"
                    style="width:36px; height:36px; border-radius:8px; padding:0;"
                    id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars" style="font-size:0.85rem;"></i>
            </button>

            <ol class="breadcrumb mb-0 me-auto">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none" style="color: var(--txt-tertiary);">
                        <i class="fas fa-home" style="font-size:0.8rem;"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>

            <div class="d-flex align-items-center gap-3">
                @isset($level)
                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size:0.72rem;">
                        <i class="fas fa-star me-1"></i> Level {{ $level }}
                    </span>
                @endisset
                <span class="role-badge">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </nav>
    @endauth

    {{-- TOAST NOTIFICATIONS --}}
    <div class="toast-container-custom">
        @if(session('success'))
            <div class="toast-custom success" id="toast-success">
                <div class="toast-icon"><i class="fas fa-check"></i></div>
                <div class="toast-body">
                    <span class="toast-title">Berhasil</span>
                    <span class="toast-msg">{{ session('success') }}</span>
                </div>
                <button class="toast-close" onclick="dismissToast(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-custom error" id="toast-error">
                <div class="toast-icon"><i class="fas fa-times"></i></div>
                <div class="toast-body">
                    <span class="toast-title">Gagal</span>
                    <span class="toast-msg">{{ session('error') }}</span>
                </div>
                <button class="toast-close" onclick="dismissToast(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="toast-custom warning" id="toast-warning">
                <div class="toast-icon"><i class="fas fa-exclamation"></i></div>
                <div class="toast-body">
                    <span class="toast-title">Perhatian</span>
                    <span class="toast-msg">{{ session('warning') }}</span>
                </div>
                <button class="toast-close" onclick="dismissToast(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="page-wrapper">
        @yield('content')
    </div>
</div>

{{-- MODAL KONFIRMASI LOGOUT --}}
<x-modal id="logoutConfirmModal" title="Konfirmasi Keluar" type="warning" icon="fa-sign-out-alt">
    <div class="text-center">
        <p class="mb-0">Apakah Anda yakin ingin mengakhiri sesi ini?</p>
        <p class="small mt-1 mb-0" style="color: var(--txt-secondary);">
            Anda harus login kembali untuk mengakses sistem.
        </p>
    </div>

    <x-slot:footer>
        <div class="d-flex justify-content-center w-100 gap-2">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                Batal
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger rounded-pill px-4">
                    <i class="fas fa-sign-out-alt me-2"></i>Ya, Keluar
                </button>
            </form>
        </div>
    </x-slot:footer>
</x-modal>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ---- Sidebar ---- */
const sidebar   = document.getElementById('mainSidebar');
const content   = document.getElementById('mainContent');
const overlay   = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('sidebarToggle');
const MINI_KEY  = 'sidebar_mini';
const isDesktop = () => window.innerWidth >= 992;

function restoreSidebar() {
    if (!sidebar) return;
    if (isDesktop()) {
        if (localStorage.getItem(MINI_KEY) === '1') {
            sidebar.classList.add('mini');
            content?.classList.add('expanded');
        }
    } else {
        sidebar.classList.remove('show');
        overlay?.classList.remove('show');
    }
}

toggleBtn?.addEventListener('click', () => {
    if (isDesktop()) {
        const isMini = sidebar.classList.toggle('mini');
        content?.classList.toggle('expanded', isMini);
        localStorage.setItem(MINI_KEY, isMini ? '1' : '0');
    } else {
        const isOpen = sidebar.classList.toggle('show');
        overlay?.classList.toggle('show', isOpen);
    }
});

overlay?.addEventListener('click', () => {
    sidebar?.classList.remove('show');
    overlay.classList.remove('show');
});

restoreSidebar();

/* ---- Page Loader ---- */
window.addEventListener('load', () => {
    const loader = document.getElementById('pageLoader');
    if (!loader) return;
    loader.classList.add('fade-out');
    setTimeout(() => loader.style.display = 'none', 400);
});

/* ---- Toast auto-dismiss (durasi dinamis berdasar panjang teks) ---- */
function dismissToast(btn) {
    const toast = btn.closest('.toast-custom');
    if (!toast) return;
    toast.style.animation = 'slideOutRight 0.25s ease forwards';
    setTimeout(() => toast.remove(), 260);
}

document.querySelectorAll('.toast-custom').forEach(toast => {
    const msg = toast.querySelector('.toast-msg')?.textContent || '';
    const delay = Math.max(3000, 2000 + msg.length * 60);
    setTimeout(() => {
        if (document.contains(toast)) dismissToast(toast.querySelector('.toast-close'));
    }, delay);
});

/* ---- Logout Modal ---- */
function showLogoutModal() {
    const modal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'));
    modal.show();
}

/* ---- Disable HTML5 native validation (pakai custom) ---- */
document.querySelectorAll('form').forEach(f => f.setAttribute('novalidate', true));

/* ---- PWA / Push Notifications (tidak diubah) ---- */
window.addEventListener('load', function () {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
    navigator.serviceWorker.register('/sw.js').then(function (reg) {
        @auth
        const activate = () => checkAndSubscribe(reg);
        if (reg.active) activate();
        else {
            const w = reg.installing || reg.waiting;
            w?.addEventListener('statechange', () => { if (w.state === 'activated') activate(); });
        }
        @endauth
    }).catch(e => console.error('[SW]', e));
});

async function checkAndSubscribe(reg) {
    const perm = await Notification.requestPermission();
    if (perm !== 'granted') return;
    try {
        let sub = await reg.pushManager.getSubscription();
        if (sub) {
            const valid = await verifySubscription(sub);
            if (!valid) { await sub.unsubscribe(); sub = null; }
        }
        if (!sub) {
            const key = "{{ env('VAPID_PUBLIC_KEY') }}";
            if (!key) return;
            sub = await reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: urlBase64ToUint8Array(key) });
            await sendSubscriptionToBackend(sub);
        }
    } catch (e) { console.error('[Push]', e); }
}

async function verifySubscription(sub) {
    try {
        const r = await fetch('/notifications/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ endpoint: sub.endpoint })
        });
        return (await r.json()).valid === true;
    } catch { return true; }
}

async function sendSubscriptionToBackend(sub) {
    await fetch('/notifications/subscribe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(sub)
    });
}

function urlBase64ToUint8Array(b64) {
    const pad = '='.repeat((4 - b64.length % 4) % 4);
    const raw = atob((b64 + pad).replace(/-/g, '+').replace(/_/g, '/'));
    return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
}
</script>

@stack('scripts')
</body>
</html>