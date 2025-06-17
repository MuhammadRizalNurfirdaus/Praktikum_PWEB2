{{-- AWAL DARI FILE resources/views/categories/show.blade.php (VERSI KUSTOM LENGKAP) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kategori: <span class="text-indigo-600 dark:text-indigo-400">{{ $category->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Detail Kategori --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-8 border border-gray-200 dark:border-gray-700">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $category->name }}</h3>
                    @if ($category->description)
                        <p class="mt-2 text-gray-700 dark:text-gray-300 leading-relaxed">{{ $category->description }}</p>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('categories.edit', $category->slug) }}"
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Edit
                            Kategori Ini</a>
                    </div>
                </div>
            </div>

            {{-- Daftar Produk dalam Kategori Ini --}}
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Produk dalam Kategori
                "{{ $category->name }}":</h3>

            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"> {{-- Menambahkan xl:grid-cols-4 --}}
                    @foreach ($products as $product)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out transform hover:-translate-y-1 sm:rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                            <a href="{{ route('products.show', $product->id) }}" class="block">
                                @if ($product->image_path)
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-56 object-cover">
                                @else
                                    <div
                                        class="w-full h-56 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-500 dark:text-gray-400">Gambar tidak tersedia</span>
                                    </div>
                                @endif
                            </a>
                            <div class="p-5 flex flex-col flex-grow">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate mb-1">
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="hover:text-indigo-600 dark:hover:text-indigo-400"
                                        title="{{ $product->name }}">{{ $product->name }}</a>
                                </h4>
                                <p class="text-gray-700 dark:text-gray-300 mt-1 font-bold text-xl mb-2">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Stok: {{ $product->stock }}
                                </p>
                                <div class="mt-auto pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p>Belum ada produk dalam kategori ini.</p>
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    « Kembali ke Daftar Kategori
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- AKHIR DARI FILE resources/views/categories/show.blade.php (VERSI KUSTOM LENGKAP) --}}
