@extends('layouts.store')

@section('title', 'Semua Produk')

@section('content')
<div class="flex flex-col md:flex-row gap-8">
    <!-- Sidebar Filter (Opsional) -->
    <aside class="md:w-1/4 space-y-6">
        <form method="GET" action="{{ route('products.index') }}">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Kategori</h3>
                <select name="category" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Brand</h3>
                <select name="brand" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Brand</option>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Urutkan</h3>
                <select name="sort" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </div>
            @if(request()->has('category') || request()->has('brand') || request()->has('sort'))
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Reset Filter</a>
            </div>
            @endif
        </form>
    </aside>

    <!-- Daftar Produk Utama -->
    <main class="md:w-3/4">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Semua Produk</h1>
        @if($products->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <a href="{{ route('products.show', $product->slug) }}">
                    <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('images/products_dummy/sample-image-1.jpg') }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                </a>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">
                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">{{ $product->name }}</a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-1">{{ $product->brand->name ?? 'N/A' }}</p>
                    <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginasi -->
        <div class="mt-8">
            {{ $products->appends(request()->query())->links() }} {{-- appends() untuk menjaga filter saat paginasi --}}
        </div>
        @else
        <p class="text-gray-600 text-center py-10">Tidak ada produk yang ditemukan.</p>
        @endif
    </main>
</div>
@endsection