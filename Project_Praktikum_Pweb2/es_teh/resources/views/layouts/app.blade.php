<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Es Teh Poci Aji Manis')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Pastikan versi sesuai atau gunakan versi terbaru --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}} {{-- Aktifkan jika Anda punya file custom.css --}}

    <style>
        body {
            padding-top: 70px;
            /* Sesuaikan dengan tinggi navbar */
            background-image: url("{{ asset('images/background.png') }}");
            /* Pastikan gambar ini ada di public/images */
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
            /* Membuat konten mengisi ruang yang tersedia */
            background-color: rgba(255, 255, 255, 0.93);
            /* Sedikit lebih opaque */
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            /* Jarak dari navbar */
            margin-bottom: 20px;
            /* Jarak ke footer */
        }

        .footer {
            background-color: #f8f9fa;
            /* Warna footer terang */
            color: #343a40;
            /* Teks footer gelap */
            padding: 20px 0;
            text-align: center;
        }

        .product-img-thumb {
            max-height: 80px;
            max-width: 80px;
            object-fit: cover;
        }

        .product-img-display {
            max-height: 350px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        /* Custom Navbar Style */
        .navbar-custom-main {
            background-color: #FFFDE7;
            /* Warna kuning sangat pucat (krem) - SESUAIKAN */
        }

        .navbar-custom-main .navbar-brand,
        .navbar-custom-main .nav-link {
            color: #4A5568;
            /* Warna teks gelap keabu-abuan - SESUAIKAN */
        }

        .navbar-custom-main .navbar-brand:hover,
        .navbar-custom-main .nav-link:hover,
        .navbar-custom-main .nav-link.active {
            color: #1A202C;
            /* Warna teks lebih gelap saat hover/active - SESUAIKAN */
        }

        .navbar-custom-main .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(74, 85, 104, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-custom-main .navbar-brand img {
            margin-right: 8px;
            /* Spasi antara logo dan teks brand */
        }

        .navbar-nav .nav-item .badge {
            /* Styling untuk badge di navbar */
            font-size: 0.65em;
            position: relative;
            top: -8px;
            left: -3px;
        }
    </style>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}} {{-- Jika Anda menggunakan Vite --}}
</head>

<body class="font-sans antialiased">
    <nav class="navbar navbar-expand-lg navbar-custom-main fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Es Teh Poci" width="35" height="35"
                    class="d-inline-block align-text-top">
                Es Teh Poci Aji Manis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavContent"
                aria-controls="navbarNavContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') || request()->routeIs('products.index.public_list') ? 'active' : '' }}"
                            href="{{ route('products.index.public_list') }}">Produk Kami</a>
                    </li>
                    {{-- Tambah link "Tentang Kami", "Kontak", dll. untuk user umum di sini --}}
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center"> {{-- align-items-center untuk vertical alignment --}}
                    {{-- AWAL LINK KERANJANG --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}"
                            href="{{ route('cart.index') }}">
                            <i class="bi bi-cart-fill fs-5"></i> {{-- Ikon lebih besar --}}
                            Keranjang
                            @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                <span class="badge rounded-pill bg-danger">{{ count(Session::get('cart')) }}</span>
                            @else
                                <span class="badge rounded-pill bg-secondary">0</span>
                            @endif
                        </a>
                    </li>
                    {{-- AKHIR LINK KERANJANG --}}

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                    href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                                    href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @if (Auth::user()->role === 'admin' && Route::has('admin.dashboard'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                    href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdownUserMenu" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUserMenu">
                                @if (Route::has('profile.edit'))
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
                                    </a>
                                @endif

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

    <div class="container main-container">
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
    </div>

    <footer class="footer mt-auto">
        <div class="container">
            <span class="text-dark">© {{ date('Y') }} Es Teh Poci Aji Manis. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') {{-- Untuk script JS tambahan dari halaman spesifik --}}
</body>

</html>
