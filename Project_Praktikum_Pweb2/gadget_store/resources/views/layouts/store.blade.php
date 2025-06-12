<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf- ત્ર">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Gadget Store')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    {{-- Jika menggunakan Vite (default di Laravel baru) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Jika Anda menggunakan CSS/JS kustom langsung dari public --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    {{-- <script src="{{ asset('js/script.js') }}" defer></script> --}}

    @stack('styles') {{-- Untuk menambahkan CSS spesifik per halaman --}}
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                {{-- Ganti dengan logo Anda --}}
                                <h1 class="text-xl font-bold">{{ config('app.name', 'Gadget Store') }}</h1>
                            </a>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5
              {{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900 focus:outline-none focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300' }}
              transition duration-150 ease-in-out">
                                {{ __('Home') }}
                            </a>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5
              {{ request()->routeIs('products.index') ? 'border-indigo-500 text-gray-900 focus:outline-none focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300' }}
              transition duration-150 ease-in-out">
                                {{ __('Produk') }}
                            </a>
                            {{-- Tambahkan link kategori di sini jika perlu --}}
                        </div>
                    </div>

                    <!-- Login/Register Links -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                            @endif
                        @endauth
                    </div>
                    <!-- Hamburger (untuk mobile) bisa ditambahkan jika perlu -->
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-auto py-6 text-center text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name', 'Gadget Store') }}. All rights reserved.
        </footer>
    </div>
    @stack('scripts') {{-- Untuk menambahkan JS spesifik per halaman --}}
</body>

</html>