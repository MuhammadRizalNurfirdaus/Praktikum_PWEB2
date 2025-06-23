<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Es Teh Poci Aji Manis')</title>

    {{-- CDN Stylesheets --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}} {{-- Aktifkan jika punya custom.css --}}

    <style>
        body {
            padding-top: 70px;
            background-image: url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Figtree', sans-serif;
        }

        .main-container {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.93);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .footer {
            background-color: #f8f9fa;
            color: #343a40;
            padding: 20px 0;
            text-align: center;
        }

        .navbar-custom-main {
            background-color: #FFFDE7;
            /* Warna navbar publik */
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .navbar-custom-main .navbar-brand-static,
        .navbar-custom-main .nav-link {
            color: #4A5568;
        }

        .navbar-custom-main .navbar-brand-static:hover,
        .navbar-custom-main .nav-link:hover,
        .navbar-custom-main .nav-link.active {
            color: #1A202C;
        }

        .navbar-custom-main .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(74, 85, 104, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-custom-main .navbar-brand-static img.brand-logo-public {
            max-height: 38px;
            max-width: 38px;
            margin-right: 10px;
            background-color: #ffffff;
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            vertical-align: middle;
        }

        .navbar-nav .nav-item .badge {
            font-size: 0.65em;
            position: relative;
            top: -9px;
            left: -4px;
            padding: 0.3em 0.5em;
        }

        .dropdown-item i.bi {
            margin-right: 0.6rem;
            vertical-align: -0.125em;
            width: 1.25em;
            text-align: center;
            font-size: 1rem;
        }

        .dropdown-menu {
            min-width: 13rem;
            margin-top: 0.5rem !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <nav class="navbar navbar-expand-lg navbar-custom-main fixed-top shadow-sm">
        <div class="container">
            {{-- Brand sebagai non-link --}}
            <div class="navbar-brand d-flex align-items-center navbar-brand-static">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Es Teh Poci" class="brand-logo-public">
                <span style="line-height: 1.2;">Es Teh Poci Aji Manis</span>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavContent"
                aria-controls="navbarNavContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index.public_list') ? 'active' : '' }}"
                            href="{{ route('products.index.public_list') }}">Produk Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.page') ? 'active' : '' }}"
                            href="{{ route('contact.page') }}">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('cart.index') ? 'active' : '' }}"
                            href="{{ route('cart.index') }}">
                            <i class="bi bi-cart-fill fs-5"></i>
                            <span class="d-none d-md-inline ms-1">Keranjang</span>
                            @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                <span
                                    class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
                                    {{ count(Session::get('cart')) }}
                                    <span class="visually-hidden">item di keranjang</span>
                                </span>
                            @else
                                <span
                                    class="badge rounded-pill bg-secondary position-absolute top-0 start-100 translate-middle">
                                    0
                                    <span class="visually-hidden">item di keranjang</span>
                                </span>
                            @endif
                        </a>
                    </li>
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                    href="{{ route('login') }}">{{ _('Login') }}</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                                    href="{{ route('register') }}">{{ _('Register') }}</a></li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownUserMenu" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person-circle me-1 fs-5"></i> {{ Str::words(Auth::user()->name, 1, '') }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                                aria-labelledby="navbarDropdownUserMenu">
                                @if (Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i
                                            class="bi bi-shield-lock-fill"></i> Panel Admin</a>
                                @elseif(Auth::user()->isKurir())
                                    <a class="dropdown-item" href="{{ route('kurir.dashboard') }}"><i
                                            class="bi bi-truck"></i> Panel Kurir</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('user.dashboard') }}"><i
                                            class="bi bi-speedometer2"></i> Dashboard Saya</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                        class="bi bi-person-badge-fill"></i> Profil Saya</a>
                                @if (Auth::user()->isUser())
                                    <a class="dropdown-item" href="{{ route('user.orders.index') }}"><i
                                            class="bi bi-list-check"></i> Riwayat Pesanan</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-public').submit();"><i
                                        class="bi bi-box-arrow-right"></i> Log Out</a>
                                <form id="logout-form-public" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf</form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

    {{-- AWAL BAGIAN FLASH MESSAGES --}}
    <div class="container main-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!} {{-- Menggunakan {!! !!} agar HTML di pesan bisa dirender (misal <strong>) --}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Pesan khusus untuk QR (jika ada dan berbeda dari 'success' biasa) --}}
        {{-- Jika Anda menggabungkan pesan QR ke dalam 'success', bagian ini mungkin tidak perlu --}}
        @if (session('success_with_qr'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {!! session('success_with_qr') !!}
                {{-- Bagian untuk menampilkan QR dan detail pesanan bisa tetap di checkout.index.blade.php
                     atau dipindahkan ke sini jika ingin ditampilkan di semua halaman setelah redirect QR.
                     Untuk sekarang, kita biarkan logika QR ada di checkout.index.blade.php --}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Whoops! Ada beberapa masalah dengan input Anda:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- AKHIR BAGIAN FLASH MESSAGES --}}

        @yield('content') {{-- Konten spesifik halaman akan dirender di sini --}}
    </div>

    <footer class="footer mt-auto">
        <div class="container">
            <span class="text-dark">© {{ date('Y') }} Es Teh Poci Aji Manis. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    @stack('scripts')
</body>

</html>
