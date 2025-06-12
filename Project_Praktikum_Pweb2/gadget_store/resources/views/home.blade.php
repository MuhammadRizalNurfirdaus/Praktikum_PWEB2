@extends('layouts.store') {{-- Atau layouts.app / layouts.guest jika pakai Breeze dan sesuai --}}

@section('title', 'Selamat Datang')

@section('content')
    <div class="space-y-12">
        <!-- Produk Unggulan -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Produk Unggulan</h2>
            @if($featuredProducts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProducts as $product)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <a href="{{ route('products.show', $product->slug) }}">
                                {{-- Asumsi Anda punya accessor primary_image di Model Product --}}
                                {{-- atau ambil dari relasi images().first() --}}
                                <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('images/products_dummy/sample-image-1.jpg') }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                            </a>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">{{ $product->name }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 mb-1">{{ $product->brand->name ?? 'N/A' }}</p>
                                <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                {{-- Tambahkan tombol "Add to Cart" di sini nanti --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Belum ada produk unggulan saat ini.</p>
            @endif
        </section>

        <!-- Produk Terbaru -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Produk Terbaru</h2>
            @if($latestProducts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($latestProducts as $product)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <a href="{{ route('products.show', $product->slug) }}">
                                 <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('images/products_dummy/sample-image-1.jpg') }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                            </a>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">{{ $product->name }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 mb-1">{{ $product->brand->name ?? 'N/A' }}</p>
                                <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Belum ada produk terbaru saat ini.</p>
            @endif
        </section>
    </div>
@endsection