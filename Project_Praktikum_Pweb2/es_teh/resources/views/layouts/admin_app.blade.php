<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel - Es Teh Poci')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f7f6;
            /* Warna background body sedikit abu-abu */
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background-color: #2c3e50;
            /* Sidebar gelap (biru tua) */
            color: #ecf0f1;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding-top: 1rem;
            overflow-y: auto;
            transition: margin-left 0.3s;
            z-index: 1030;
            /* Di atas konten, di bawah navbar jika ada yang fixed */
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.15rem 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .sidebar-brand img {
            max-height: 35px;
            margin-right: 10px;
        }

        .admin-sidebar .nav-link {
            color: #bdc3c7;
            /* Warna link abu-abu muda */
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .admin-sidebar .nav-link i.bi {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            /* Agar ikon sejajar */
            text-align: center;
        }

        .admin-sidebar .nav-link:hover {
            background-color: #34495e;
            /* Warna hover sedikit lebih terang */
            color: #fff;
        }

        .admin-sidebar .nav-link.active {
            background-color: #1abc9c;
            /* Warna aktif (hijau toska) */
            color: #fff;
            font-weight: 600;
        }

        .admin-sidebar .sidebar-heading {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            color: #7f8c8d;
            /* Warna heading abu-abu gelap */
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .admin-main-content {
            margin-left: 260px;
            /* Sesuaikan dengan lebar sidebar */
            width: calc(100% - 260px);
            /* Sisa lebar untuk konten */
            padding: 0;
            transition: margin-left 0.3s;
            display: flex;
            flex-direction: column;
        }

        .admin-navbar-top {
            background-color: #fff;
            /* Navbar atas putih */
            border-bottom: 1px solid #e0e0e0;
            padding: 0.75rem 1.5rem;
            position: sticky;
            /* Membuat navbar atas tetap terlihat saat scroll */
            top: 0;
            z-index: 1020;
            /* Di bawah sidebar jika sidebar fixed dan di atas konten */
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-navbar-top .navbar-toggler-sidebar {
            /* Tombol untuk toggle sidebar di mobile */
            border: none;
            font-size: 1.5rem;
            color: #333;
        }

        .admin-content-area {
            padding: 1.5rem;
            flex-grow: 1;
            /* Agar konten mengisi sisa ruang vertikal */
        }

        .admin-footer {
            text-align: center;
            padding: 1rem;
            background-color: #fff;
            border-top: 1px solid #e0e0e0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Card styling untuk dashboard */
        .dashboard-card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
            transition: transform .2s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-card .card-icon {
            font-size: 2.5rem;
            opacity: 0.7;
        }

        /* Untuk Mobile - Sidebar menjadi bisa ditoggle */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: -260px;
                /* Sembunyikan sidebar */
            }

            .admin-sidebar.active {
                margin-left: 0;
                /* Tampilkan sidebar */
            }

            .admin-main-content {
                margin-left: 0;
                width: 100%;
            }

            .admin-main-content.sidebar-active {
                /* Bisa tambahkan overlay atau efek lain saat sidebar aktif di mobile */
            }
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="admin-sidebar" id="adminSidebar">
            <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"> <!-- Ganti dengan path logo Anda -->
                <span>Admin Es Teh</span>
            </a>

            <ul class="nav flex-column">
                <li class="sidebar-heading">Utama</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-heading">Manajemen Konten</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                        href="{{ route('admin.products.index') }}">
                        <i class="bi bi-cup-straw"></i> Kelola Produk
                    </a>
                </li>
                                <li class="sidebar-heading mt-3">Manajemen Akses</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people-fill"></i> Kelola Pengguna
                    </a>
                </li>
                {{-- Tambahkan menu lain di sini sesuai contoh --}}
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-journal-text"></i> Artikel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-images"></i> Galeri
                    </a>
                </li> --}}
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="admin-main-content" id="mainContent">
            <!-- Top Navbar -->
            <header class="admin-navbar-top">
                <button class="navbar-toggler-sidebar d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="ms-auto"> <!-- Untuk elemen di kanan navbar -->
                    <ul class="navbar-nav flex-row align-items-center">
                        @auth
                            <li class="nav-item me-3">
                                <span class="navbar-text">
                                    Halo, {{ Auth::user()->name }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </div>
            </header>

            <!-- Page Content Area -->
            <main class="admin-content-area">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops! Ada beberapa masalah:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer Admin -->
            <footer class="admin-footer">
                Admin Panel © {{ date('Y') }} Es Teh Poci Aji Manis. All rights reserved.
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk toggle sidebar di mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const adminSidebar = document.getElementById('adminSidebar');
            const mainContent = document.getElementById('mainContent');

            if (sidebarToggle && adminSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    adminSidebar.classList.toggle('active');
                    // Optional: add class to main content to push it or overlay it
                    // mainContent.classList.toggle('sidebar-active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
