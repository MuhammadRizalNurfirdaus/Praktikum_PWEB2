<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Es Teh Poci Aji Manis</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles (Anda bisa tambahkan CSS sendiri atau menggunakan Vite) -->
    <style>
        body { font-family: 'Nunito', sans-serif; margin: 0; background-color: #f7fafc; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { max-width: 150px; margin-bottom: 10px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .product-card { background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 20px; text-align: center; }
        .product-card img { max-width: 100%; height: 200px; object-fit: contain; margin-bottom: 15px; border-radius: 4px; }
        .product-card h3 { margin-top: 0; font-size: 1.25rem; }
        .product-card .price { font-size: 1.1rem; color: #e53e3e; font-weight: bold; margin-bottom: 5px; }
        .product-card .size { font-size: 0.9rem; color: #718096; margin-bottom: 10px; }
    </style>
    {{-- Jika Anda menggunakan Vite untuk CSS --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Es Teh Poci">
            <h1>Selamat Datang di Es Teh Poci Aji Manis</h1>
            <p>Nikmati kesegaran Es Teh Poci Aji Manis dalam berbagai ukuran!</p>
        </div>

        @if(isset($products) && $products->count() > 0)
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card">
                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }} {{ $product->size }}">
                        <h3>{{ $product->name }}</h3>
                        <p class="size">Ukuran: {{ $product->size }}</p>
                        <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if($product->description)
                            <p>{{ $product->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="text-align:center;">Belum ada produk yang tersedia.</p>
        @endif
    </div>
</body>
</html>