<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- ... (head content Anda sudah baik, pastikan Bootstrap & Bootstrap Icons ada) ... --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Es Teh Poci')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700&display=swap" rel="stylesheet">

    <style>
        {{-- ... (CSS Anda yang sudah ada untuk body, admin-wrapper, admin-sidebar, admin-main-content, admin-content-area, admin-footer, dashboard-card, media query tetap sama) ... --}} body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f7f6;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background-color: #2c3e50;
            color: #ecf0f1;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding-top: 0;
            overflow-y: auto;
            transition: margin-left 0.3s;
            z-index: 1031;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .sidebar-brand img.brand-logo {
            max-height: 40px;
            max-width: 40px;
            margin-right: 12px;
            background-color: #ffffff;
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .admin-sidebar .sidebar-brand span {
            line-height: 1.2;
        }

        .admin-sidebar .nav-link {
            color: #bdc3c7;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .nav-link i.bi {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .admin-sidebar .nav-link:hover {
            background-color: #34495e;
            color: #fff;
            border-left-color: #1abc9c;
        }

        .admin-sidebar .nav-link.active {
            background-color: #1abc9c;
            color: #fff;
            font-weight: 600;
            border-left-color: #fff;
        }

        .admin-sidebar .sidebar-heading {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
        }

        .admin-main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 0;
            transition: margin-left 0.3s;
            display: flex;
            flex-direction: column;
        }

        /* === CSS UNTUK NAVBAR ATAS === */
        .admin-navbar-top {
            background-color: #343a40;
            /* Warna gelap untuk navbar atas */
            color: #f8f9fa;
            /* Teks terang */
            border-bottom: 1px solid #454d55;
            padding: 0.65rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            width: 100%;
            display: flex;
            justify-content: space-between;
            /* Ini akan mendorong elemen anak ke ujung-ujung */
            align-items: center;
        }

        .admin-navbar-top .navbar-toggler-sidebar {
            border: none;
            font-size: 1.5rem;
            color: #f8f9fa;
            background-color: transparent;
            padding: 0;
            /* Hapus padding agar lebih rapat */
            margin-right: 1rem;
            /* Jarak antara tombol burger dan elemen berikutnya (jika ada) */
        }

        /* Hapus .navbar-panel-title jika tidak digunakan lagi */
        /* .admin-navbar-top .navbar-panel-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #f8f9fa;
            margin-right: auto; // Ini yang mendorong ke kanan, tapi sekarang kita pakai space-between
        } */
        .admin-navbar-top .navbar-user-section {
            display: flex;
            align-items: center;
        }

        .admin-navbar-top .navbar-user-section .nav-link-custom {
            color: #adb5bd;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .admin-navbar-top .navbar-user-section .nav-link-custom:hover {
            color: #ffffff;
        }

        .admin-navbar-top .navbar-user-section .nav-link-custom i.bi {
            margin-right: 0.35rem;
            vertical-align: -0.1em;
        }

        .admin-navbar-top .navbar-user-section .user-info-text {
            color: #f8f9fa;
            margin-right: 1rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .admin-navbar-top .navbar-user-section .user-info-text i.bi {
            margin-right: 0.35rem;
        }

        /* === AKHIR CSS NAVBAR ATAS === */

        .admin-content-area {
            padding: 1.5rem;
            flex-grow: 1;
        }

        .admin-footer {
            text-align: center;
            padding: 1rem;
            background-color: #fff;
            border-top: 1px solid #e0e0e0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: -260px;
            }

            .admin-sidebar.active {
                margin-left: 0;
            }

            .admin-main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        {{-- Sidebar tetap sama --}}
        <nav class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo">
                <span>
                    @auth
                        @if (Auth::user()->isAdmin())
                            Admin Es Teh
                        @elseif(Auth::user()->isKurir())
                            Kurir Es Teh
                        @else
                            Es Teh Panel
                        @endif
                    @else
                        Admin Es Teh
                    @endauth
                </span>
            </div>
            <ul class="nav flex-column">
                @auth
                    @if (Auth::user()->isAdmin())
                        <li class="sidebar-heading">Utama</li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
                        <li class="sidebar-heading">Manajemen Konten</li>
                        <li class="nav-item"><a
                                class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                                href="{{ route('admin.products.index') }}"><i class="bi bi-cup-straw"></i> Kelola Produk</a>
                        </li>
                        <li class="sidebar-heading mt-3">Manajemen Transaksi</li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt-cutoff"></i> Manajemen
                                Pesanan</a></li>
                        <li class="sidebar-heading mt-3">Komunikasi</li>
                        <li class="nav-item"><a
                                class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
                                href="{{ route('admin.contacts.index') }}"><i class="bi bi-chat-left-text-fill"></i> Kelola
                                Pesan</a></li>
                        <li class="sidebar-heading">Manajemen Akses</li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill"></i> Kelola
                                Pengguna</a></li>
                    @elseif(Auth::user()->isKurir())
                        <li class="sidebar-heading">Utama</li>
                        <li class="nav-item"><a
                                class="nav-link {{ request()->routeIs('kurir.dashboard') ? 'active' : '' }}"
                                href="{{ route('kurir.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard
                                Kurir</a></li>
                        <li class="sidebar-heading">Pengiriman</li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('kurir.orders.*') ? 'active' : '' }}"
                                href="{{ route('kurir.orders.index') }}"><i class="bi bi-truck"></i> Daftar Pengiriman</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </nav>

        <div class="admin-main-content" id="mainContent">
            {{-- AWAL PERUBAHAN STRUKTUR NAVBAR ATAS --}}
            <header class="admin-navbar-top">
                {{-- Tombol Burger untuk toggle sidebar di mobile (tetap di kiri) --}}
                <button class="navbar-toggler-sidebar d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                {{-- KOSONGKAN ATAU BERI ELEMEN SPACER JIKA PERLU --}}
                {{-- Jika Anda ingin tombol burger mendorong elemen kanan, biarkan elemen ini kosong
                     atau beri elemen dengan flex-grow-1 jika justify-content: space-between tidak cukup --}}
                <div class="me-auto">
                    {{-- Tidak ada teks "Admin Panel Es Teh" lagi di sini --}}
                </div>


                {{-- Bagian Kanan Navbar (User Info, Lihat Situs, Logout) --}}
                <div class="navbar-user-section">
                    @auth
                        <div class="user-info-text">
                            <i class="bi bi-person-circle"></i>  {{--   untuk spasi kecil --}}
                            Halo, <strong>{{ Str::before(Auth::user()->name, ' ') }}</strong>!
                        </div>
                        <a href="{{ route('home') }}" class="nav-link-custom ms-3" target="_blank"
                            title="Lihat Situs Publik">
                            <i class="bi bi-eye-fill"></i> Lihat Situs
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="ms-3">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link-custom p-0 text-decoration-none"
                                title="Logout" style="color: #adb5bd;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </header>
            {{-- AKHIR PERUBAHAN STRUKTUR NAVBAR ATAS --}}

            <main class="admin-content-area">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button> </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>Whoops! Ada
                            beberapa masalah:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul> <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </main>
            <footer class="admin-footer"> Admin Panel © {{ date('Y') }} Es Teh Poci Aji Manis. All rights
                reserved. </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const adminSidebar = document.getElementById('adminSidebar');
            if (sidebarToggle && adminSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    adminSidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
