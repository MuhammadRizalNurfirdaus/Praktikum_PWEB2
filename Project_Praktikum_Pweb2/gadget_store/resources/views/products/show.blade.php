@extends('layouts.store')

@section('title', $product->name)

@section('content')
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="md:flex">
            <!-- Galeri Gambar Produk -->
            <div class="md:w-1/2 p-4">
                @if($product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto object-contain rounded-lg shadow mb-4" style="max-height: 500px;">
                    {{-- Tambahkan thumbnail untuk galeri jika ada banyak gambar --}}
                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-20 object-cover rounded cursor-pointer border hover:border-indigo-500">
                                {{-- Tambahkan JS untuk mengganti gambar utama saat thumbnail diklik --}}
                            @endforeach
                        </div>
                    @endif
                @else
                    <img src="{{ asset('images/products_dummy/sample-image-1.jpg') }}" alt="{{ $product->name }}" class="w-full h-auto object-contain rounded-lg shadow">
                @endif
            </div>

            <!-- Detail Produk -->
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <p class="text-sm text-gray-500 mb-1">
                    Brand: <span class="font-semibold">{{ $product->brand->name ?? 'N/A' }}</span> |
                    Kategori: <span class="font-semibold">{{ $product->category->name ?? 'N/A' }}</span>
                </p>
                 @if ($product->sku)
                    <p class="text-sm text-gray-500 mb-4">SKU: {{ $product->sku }}</p>
                @endif

                <p class="text-3xl font-extrabold text-indigo-700 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                @if($product->sale_price && $product->sale_price < $product->price)
                    <p class="text-lg text-gray-500 line-through mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="text-2xl font-bold text-red-600 mb-4">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                @endif

                <div class="mb-4">
                    <p class="text-gray-700 text-sm mb-1">Stok:
                        @if($product->stock_quantity > 0)
                            <span class="text-green-600 font-semibold">{{ $product->stock_quantity }} tersedia</span>
                        @else
                            <span class="text-red-600 font-semibold">Stok Habis</span>
                        @endif
                    </p>
                    <p class="text-gray-700 text-sm">Kondisi: <span class="capitalize">{{ $product->condition }}</span></p>
                    @if($product->warranty_info)
                         <p class="text-gray-700 text-sm">Garansi: {{ $product->warranty_info }}</p>
                    @endif
                </div>

                <div class="prose max-w-none text-gray-700 mb-6">
                    <h3 class="text-xl font-semibold mb-2">Deskripsi Singkat</h3>
                    {!! nl2br(e($product->short_description)) !!}
                </div>

                {{-- Tombol Add to Cart & Aksi lainnya --}}
                <div class="mt-6">
                    {{-- Form untuk Add to Cart akan ditambahkan di sini --}}
                    <button type="button" class="w-full bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock_quantity > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Deskripsi Panjang & Spesifikasi -->
        <div class="mt-8 p-6 border-t border-gray-200">
            <div class="prose max-w-none mb-8">
                <h3 class="text-2xl font-semibold mb-3">Deskripsi Lengkap</h3>
                {!! nl2br(e($product->long_description)) !!}
            </div>

            @if($product->specifications->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-2xl font-semibold mb-3">Spesifikasi</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        @foreach($product->specifications as $spec)
                            <li><strong>{{ $spec->name }}:</strong> {{ $spec->value }} {{ $spec->unit }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Review Produk (akan ditambahkan nanti) -->
        {{-- <div class="mt-8 p-6 border-t border-gray-200">
            <h3 class="text-2xl font-semibold mb-3">Ulasan Produk</h3>
            @forelse($product->reviews as $review)
                <div class="border-b py-2">
                    <strong>{{ $review->user->name }}</strong> ({{ $review->rating }}/5)
                    <p>{{ $review->comment }}</p>
                </div>
            @empty
                <p>Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div> --}}

        <!-- Produk Terkait -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-12 p-6 border-t border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $related)
                        <div class="bg-gray-50 shadow rounded-lg overflow-hidden">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <img src="{{ $related->images->first() ? asset('storage/' . $related->images->first()->image_path) : asset('images/products_dummy/sample-image-1.jpg') }}" alt="{{ $related->name }}" class="w-full h-48 object-cover">
                            </a>
                            <div class="p-3">
                                <h4 class="text-md font-semibold text-gray-900 mb-1 truncate">
                                    <a href="{{ route('products.show', $related->slug) }}" class="hover:text-indigo-600">{{ $related->name }}</a>
                                </h4>
                                <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection