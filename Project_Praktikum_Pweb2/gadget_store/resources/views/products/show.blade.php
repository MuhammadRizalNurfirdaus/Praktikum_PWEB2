{{-- AWAL DARI FILE resources/views/products/show.blade.php (VERSI KUSTOM LENGKAP) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Produk: <span class="text-indigo-600 dark:text-indigo-400">{{ $product->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- max-w-4xl agar tidak terlalu lebar --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                        {{-- Kolom Gambar --}}
                        <div class="md:col-span-1">
                            @if ($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                    class="w-full h-auto max-h-[400px] object-contain rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            @else
                                <div
                                    class="w-full h-64 bg-gray-100 dark:bg-gray-700 flex items-center justify-center rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400">Gambar tidak tersedia</span>
                                </div>
                            @endif
                        </div>

                        {{-- Kolom Detail Teks --}}
                        <div class="md:col-span-2">
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h3>
                            <p class="text-2xl text-indigo-600 dark:text-indigo-400 font-semibold mb-6">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            @if ($product->category)
                                <div class="mb-4">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Kategori:</span>
                                    <a href="{{ route('categories.show', $product->category->slug) }}"
                                        class="ml-1 text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $product->category->name }}
                                    </a>
                                </div>
                            @endif

                            <div class="mb-4">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">Stok:</span>
                                <span class="ml-1 text-gray-600 dark:text-gray-400">{{ $product->stock }}
                                    {{ $product->stock > 0 ? '' : '(Habis)' }}</span>
                            </div>

                            @if ($product->description)
                                <div class="mb-6">
                                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">Deskripsi:</h4>
                                    <div
                                        class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            @endif

                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-3">
                                <a href="{{ route('products.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    « Kembali ke Daftar
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Edit Produk
                                </a>
                                {{-- Tombol Tambah ke Keranjang bisa ditambahkan di sini nanti --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- AKHIR DARI FILE resources/views/products/show.blade.php (VERSI KUSTOM LENGKAP) --}}
