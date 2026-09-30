<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $sidebarData['libraryName'] ?? 'SIP Perpustakaan' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sip-primary: #2563eb;
            --sip-primary-hover: #1d4ed8;
            --sip-sidebar-bg: #0b1327;
            --sip-sidebar-border: rgba(255, 255, 255, 0.08);
            --sip-bg: #f8fafc;
            --sip-card-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--sip-bg);
            color: #334155;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ==========================================================================
           Modern Dark Sidebar Styling (Custom Sleek Scrollbar & Floating Pill Nav)
           ========================================================================== */
        #sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0d1527 0%, #080d19 100%);
            height: 100vh;
            color: #94a3b8;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sip-sidebar-border);
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.28);
        }

        /* Brand Header */
        .sidebar-brand-wrapper {
            padding: 1.25rem 1.25rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sip-sidebar-border);
            flex-shrink: 0;
            background: rgba(13, 21, 39, 0.65);
            backdrop-filter: blur(10px);
        }

        .brand-logo-box {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }

        .live-dot-pulse {
            width: 7px;
            height: 7px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: livePulse 2s infinite;
        }

        @keyframes livePulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Sidebar Navigation Scroll Container (Sleek Dark Scrollbar) */
        .sidebar-nav-scroll {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 0.75rem 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }

        .sidebar-nav-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
        }

        .sidebar-nav-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Section Titles */
        .sidebar-section-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #64748b;
            padding: 0.85rem 0.85rem 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Feature Links (Pill Style) */
        .nav-feature-item {
            margin: 3px 6px;
            border-radius: 12px;
            padding: 0.62rem 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.22s ease-in-out;
            border: 1px solid transparent;
            position: relative;
        }

        .nav-feature-item:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.06);
            transform: translateX(4px);
        }

        .nav-feature-item.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.25) 0%, rgba(59, 130, 246, 0.1) 100%);
            border-color: rgba(59, 130, 246, 0.35);
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.16);
        }

        .nav-feature-item.active::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 22px;
            background: #3b82f6;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px #3b82f6;
        }

        /* Themed Feature Icon Badges */
        .nav-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-right: 0.75rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            flex-shrink: 0;
        }

        .icon-dashboard { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border-color: rgba(59, 130, 246, 0.2); }
        .icon-books { background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border-color: rgba(99, 102, 241, 0.2); }
        .icon-categories { background: rgba(16, 185, 129, 0.15); color: #34d399; border-color: rgba(16, 185, 129, 0.2); }
        .icon-members { background: rgba(14, 165, 233, 0.15); color: #38bdf8; border-color: rgba(14, 165, 233, 0.2); }
        .icon-circulation { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border-color: rgba(245, 158, 11, 0.2); }
        .icon-borrow-new { background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.2); }
        .icon-rep-trans { background: rgba(244, 63, 94, 0.15); color: #fb7185; border-color: rgba(244, 63, 94, 0.2); }
        .icon-rep-books { background: rgba(168, 85, 247, 0.15); color: #c084fc; border-color: rgba(168, 85, 247, 0.2); }
        .icon-settings { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.2); }

        .nav-feature-item:hover .nav-icon-badge,
        .nav-feature-item.active .nav-icon-badge {
            transform: scale(1.08);
        }

        /* Sidebar Mini Quick Status Card */
        .sidebar-widget {
            margin: 0.85rem 0.5rem 0.5rem;
            padding: 0.85rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* Sidebar User Footer */
        .sidebar-user-footer {
            flex-shrink: 0;
            padding: 0.9rem 1rem;
            border-top: 1px solid var(--sip-sidebar-border);
            background: rgba(6, 11, 22, 0.85);
            backdrop-filter: blur(8px);
        }

        .user-avatar-gradient {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .user-online-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background-color: #10b981;
            border: 2px solid #080d19;
            border-radius: 50%;
        }

        .logout-hover-btn {
            transition: all 0.2s ease;
            background: transparent;
        }

        .logout-hover-btn:hover {
            color: #ef4444 !important;
            background-color: rgba(239, 68, 68, 0.12);
            transform: scale(1.08);
        }

        /* ==========================================================================
           Main Layout & Content Area
           ========================================================================== */
        #content-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--sip-card-border);
            padding: 0.85rem 1.75rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
        }

        /* Cards & Components */
        .card {
            border: 1px solid var(--sip-card-border);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 2px 4px rgba(0,0,0,0.02);
            background-color: #ffffff;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 1.25rem;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: #2563eb;
            font-weight: 500;
            border-radius: 9px;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1rem;
        }

        .table td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Subtle Badges */
        .badge-subtle-primary { background-color: #dbeafe; color: #1e40af; }
        .badge-subtle-success { background-color: #dcfce7; color: #166534; }
        .badge-subtle-warning { background-color: #fef3c7; color: #92400e; }
        .badge-subtle-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-subtle-secondary { background-color: #f1f5f9; color: #475569; }

        /* Responsive Layout */
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: -280px;
            }
            #sidebar.show {
                margin-left: 0;
            }
            #content-wrapper {
                margin-left: 0;
            }
        }

        @media print {
            #sidebar, .top-navbar, .no-print, .btn, .pagination {
                display: none !important;
            }
            #content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside id="sidebar">
        <!-- Brand Header -->
        <div class="sidebar-brand-wrapper">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-logo-box">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="fw-bold text-white fs-6 tracking-wide">{{ $sidebarData['libraryName'] ?? 'SIP Perpustakaan' }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="live-dot-pulse"></span>
                        <span class="text-secondary" style="font-size: 0.72rem; letter-spacing: 0.02em;">Sistem Informasi Digital</span>
                        <span class="badge bg-primary bg-opacity-25 text-info border border-primary border-opacity-30 px-1.5 py-0.5" style="font-size: 0.62rem; font-weight: 700;">PRO</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable Navigation Features -->
        <div class="sidebar-nav-scroll">
            <!-- Menu Utama -->
            <div class="sidebar-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-feature-item {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-dashboard"><i class="bi bi-grid-1x2-fill"></i></span>
                    <span>Dashboard</span>
                </div>
            </a>

            <!-- Master Data -->
            <div class="sidebar-section-title">Master Data</div>
            <a href="{{ route('books.index') }}" class="nav-feature-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-books"><i class="bi bi-journals"></i></span>
                    <span>Koleksi Buku</span>
                </div>
                <span class="badge bg-secondary bg-opacity-25 text-light rounded-pill px-2 py-0.5" style="font-size: 0.72rem;" title="Total Koleksi">
                    {{ $sidebarData['totalBooks'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('categories.index') }}" class="nav-feature-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-categories"><i class="bi bi-tags-fill"></i></span>
                    <span>Kategori Buku</span>
                </div>
                <span class="badge bg-secondary bg-opacity-25 text-light rounded-pill px-2 py-0.5" style="font-size: 0.72rem;" title="Total Kategori">
                    {{ $sidebarData['totalCategories'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('members.index') }}" class="nav-feature-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-members"><i class="bi bi-people-fill"></i></span>
                    <span>Data Anggota</span>
                </div>
                <span class="badge bg-secondary bg-opacity-25 text-light rounded-pill px-2 py-0.5" style="font-size: 0.72rem;" title="Anggota Aktif">
                    {{ $sidebarData['totalMembers'] ?? 0 }}
                </span>
            </a>

            <!-- Sirkulasi Transaksi -->
            <div class="sidebar-section-title">Sirkulasi</div>
            <a href="{{ route('borrows.index') }}" class="nav-feature-item {{ request()->routeIs('borrows.index') || request()->routeIs('borrows.show') || request()->routeIs('borrows.return') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-circulation"><i class="bi bi-arrow-left-right"></i></span>
                    <span>Peminjaman & Kembali</span>
                </div>
                @if(($sidebarData['overdueBorrows'] ?? 0) > 0)
                    <span class="badge bg-danger text-white rounded-pill px-2 py-0.5 shadow-sm" style="font-size: 0.72rem;" title="{{ $sidebarData['overdueBorrows'] }} Transaksi Terlambat">
                        <i class="bi bi-exclamation-circle-fill me-0.5"></i> {{ $sidebarData['overdueBorrows'] }}
                    </span>
                @elseif(($sidebarData['activeBorrows'] ?? 0) > 0)
                    <span class="badge bg-primary bg-opacity-25 text-info border border-primary border-opacity-30 rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                        {{ $sidebarData['activeBorrows'] }}
                    </span>
                @endif
            </a>

            <a href="{{ route('borrows.create') }}" class="nav-feature-item {{ request()->routeIs('borrows.create') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-borrow-new"><i class="bi bi-plus-circle-fill"></i></span>
                    <span>Peminjaman Baru</span>
                </div>
                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-30 rounded-pill px-2 py-0.5" style="font-size: 0.65rem; font-weight: 700;">
                    + BARU
                </span>
            </a>

            <!-- Laporan & Arsip -->
            <div class="sidebar-section-title">Laporan & Arsip</div>
            <a href="{{ route('reports.transactions') }}" class="nav-feature-item {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-rep-trans"><i class="bi bi-file-earmark-bar-graph-fill"></i></span>
                    <span>Laporan Transaksi</span>
                </div>
            </a>

            <a href="{{ route('reports.books') }}" class="nav-feature-item {{ request()->routeIs('reports.books') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-rep-books"><i class="bi bi-file-earmark-spreadsheet-fill"></i></span>
                    <span>Laporan Koleksi</span>
                </div>
            </a>

            <!-- Sistem -->
            <div class="sidebar-section-title">Sistem</div>
            <a href="{{ route('settings.index') }}" class="nav-feature-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <span class="nav-icon-badge icon-settings"><i class="bi bi-gear-wide-connected"></i></span>
                    <span>Pengaturan Sistem</span>
                </div>
            </a>

            <!-- Mini Quick Status Card -->
            <div class="sidebar-widget">
                <div class="d-flex align-items-center justify-content-between mb-1.5">
                    <span class="text-secondary fw-semibold" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em;">Layanan Sirkulasi</span>
                    <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 px-1.5 py-0.5" style="font-size: 0.65rem;">
                        <span class="live-dot-pulse" style="width: 5px; height: 5px;"></span> Buka
                    </span>
                </div>
                <div class="d-flex align-items-center justify-content-between text-secondary pt-1" style="font-size: 0.72rem;">
                    <span>Denda: <strong class="text-light">Rp {{ number_format($sidebarData['lateFeePerDay'] ?? 1000, 0, ',', '.') }}/hari</strong></span>
                    <span>Maks: <strong class="text-light">{{ $sidebarData['loanDurationDays'] ?? 7 }} hari</strong></span>
                </div>
            </div>
        </div>

        <!-- User Profile Card in Sidebar Footer -->
        <div class="sidebar-user-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                    <div class="position-relative">
                        <div class="user-avatar-gradient">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="user-online-badge"></span>
                    </div>
                    <div class="overflow-hidden" style="max-width: 155px;">
                        <div class="text-white text-truncate fw-semibold" style="font-size: 0.85rem;" title="{{ Auth::user()->name ?? 'Petugas' }}">
                            {{ Auth::user()->name ?? 'Petugas' }}
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-0.5">
                            <span class="badge {{ (Auth::user()->role ?? '') === 'admin' ? 'bg-danger bg-opacity-25 text-danger border border-danger border-opacity-30' : 'bg-primary bg-opacity-25 text-info border border-primary border-opacity-30' }}" style="font-size: 0.64rem; padding: 2px 6px;">
                                {{ (Auth::user()->role ?? '') === 'admin' ? 'Administrator' : 'Petugas' }}
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm text-secondary p-1.5 border-0 rounded-3 logout-hover-btn" title="Keluar dari Sistem" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div id="content-wrapper">
        <!-- Top Navigation Bar -->
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle" type="button" title="Buka Navigasi">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h5>
                    <small class="text-muted">@yield('page-subtitle', 'Sistem Informasi Perpustakaan Digital')</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2.5">
                <!-- Real-time Digital Clock Widget -->
                <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-light border text-muted shadow-sm" style="font-size: 0.8rem;">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span id="liveRealtimeClock">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <!-- Notifikasi Keterlambatan Sirkulasi Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border position-relative rounded-pill px-2.5 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Sirkulasi">
                        <i class="bi bi-bell fs-6 {{ ($sidebarData['overdueBorrows'] ?? 0) > 0 ? 'text-danger' : 'text-secondary' }}"></i>
                        @if(($sidebarData['overdueBorrows'] ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                                {{ $sidebarData['overdueBorrows'] }}
                                <span class="visually-hidden">transaksi terlambat</span>
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" style="width: 320px; border-radius: 14px;">
                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                            <span class="fw-bold text-dark small"><i class="bi bi-bell-fill text-warning me-1.5"></i> Notifikasi Sirkulasi</span>
                            <span class="badge {{ ($sidebarData['overdueBorrows'] ?? 0) > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill">
                                {{ $sidebarData['overdueBorrows'] ?? 0 }} Terlambat
                            </span>
                        </div>
                        <div class="p-2" style="max-height: 280px; overflow-y: auto;">
                            @if(isset($sidebarData['overdueList']) && $sidebarData['overdueList']->count() > 0)
                                @foreach($sidebarData['overdueList'] as $od)
                                    <a href="{{ route('borrows.return', $od) }}" class="dropdown-item p-2 rounded-3 mb-1 text-wrap border-bottom-0 hover-light">
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded-3 mt-0.5">
                                                <i class="bi bi-exclamation-octagon-fill fs-6"></i>
                                            </span>
                                            <div class="overflow-hidden flex-grow-1">
                                                <div class="fw-semibold text-dark small">{{ $od->member->name }}</div>
                                                <div class="text-muted text-truncate" style="font-size: 0.75rem;">
                                                    {{ $od->transaction_code }} &bull; {{ $od->details->count() }} Buku
                                                </div>
                                                <div class="text-danger small mt-0.5" style="font-size: 0.72rem;">
                                                    Jatuh tempo: {{ \Carbon\Carbon::parse($od->due_date)->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="p-3 text-center text-muted small">
                                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                                    Semua peminjaman tertib, tidak ada transaksi terlambat!
                                </div>
                            @endif
                        </div>
                        <div class="p-2 border-top text-center bg-light" style="border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                            <a href="{{ route('borrows.index', ['status' => 'terlambat']) }}" class="small text-primary text-decoration-none fw-semibold">
                                Lihat Semua Transaksi Sirkulasi &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <div class="vr d-none d-md-block" style="height: 24px;"></div>

                <!-- Tombol Aksi Cepat Pinjam Buku -->
                <a href="{{ route('borrows.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    <span class="d-none d-sm-inline">Pinjam Buku</span>
                </a>
            </div>
        </header>

        <!-- Flash Alert Messages -->
        <div class="container-fluid px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-circle me-1"></i> Terdapat kesalahan pada input form:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Main Page Body -->
        <main class="flex-grow-1 container-fluid px-4 py-3">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted" style="font-size: 0.8rem;">
            &copy; {{ date('Y') }} <strong>{{ $sidebarData['libraryName'] ?? 'SIP Perpustakaan' }}</strong> &mdash; Sistem Informasi Digital Terpadu.
        </footer>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-box-arrow-right fs-2"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Konfirmasi Keluar</h6>
                    <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari sistem perpustakaan?</p>
                    <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center gap-2">
                        @csrf
                        <button type="button" class="btn btn-sm btn-light px-3 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger px-3 rounded-pill">Ya, Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle for Mobile View
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Live Real-Time Clock
        function updateLiveClock() {
            const clockEl = document.getElementById('liveRealtimeClock');
            if (!clockEl) return;
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            clockEl.textContent = `${dateStr} • ${timeStr} WIB`;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
    @stack('scripts')
</body>
</html>
