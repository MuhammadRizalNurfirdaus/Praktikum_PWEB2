{{-- AWAL DARI FILE resources/views/layouts/app.blade.php (VERSI KUSTOM LENGKAP) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gadget Store') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- STYLE UNTUK BACKGROUND --}}
    <style>
        body.custom-background {
            background-image: url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #f3f4f6;
            /* Warna fallback jika gambar gagal load (untuk light mode) */
        }

        /* Fallback warna background untuk dark mode jika gambar gagal load */
        html.dark body.custom-background {
            background-color: #111827;
            /* Warna abu-abu gelap, sesuaikan jika perlu */
        }
    </style>
    {{-- AKHIR STYLE BACKGROUND --}}
</head>

<body class="font-sans antialiased custom-background">
    <div class="min-h-screen bg-gray-100/80 dark:bg-gray-900/75"> {{-- Opacity agar background terlihat (sesuaikan 80/75 sesuai selera) --}}

        @include('layouts.navigation') {{-- Ini meng-include navigation.blade.php --}}

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow-lg border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="pb-12"> {{-- Menambahkan padding bottom pada main content --}}
            {{ $slot }}
        </main>

    </div> {{-- Penutup div.min-h-screen --}}
</body>

</html>
{{-- AKHIR DARI FILE resources/views/layouts/app.blade.php (VERSI KUSTOM LENGKAP) --}}
