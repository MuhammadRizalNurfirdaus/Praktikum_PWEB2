<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Es Teh Poci Aji Manis') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Breeze menggunakan Vite, pastikan Anda sudah npm install & npm run build/dev --}}

    <style>
        body {
            background-image: url("{{ asset('images/background.png') }}");
            /* Background yang sama */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .auth-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .auth-logo img {
            height: 6rem;
            /* 96px */
            width: auto;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 bg-opacity-50">
        <div>
            <a href="/" class="auth-logo">
                {{-- Ganti dengan logo Anda --}}
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg auth-card">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
