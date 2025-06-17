{{-- AWAL DARI FILE resources/views/dashboard.blade.php (VERSI KUSTOM LENGKAP) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Welcome Message --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-8 border border-gray-200 dark:border-gray-700">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl md:text-3xl font-bold text-indigo-700 dark:text-indigo-400">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Kelola semua aspek toko gadget Anda dari panel admin ini.</p>
                </div>
            </div>

            {{-- Quick Stats & Links --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                <!-- Card Produk -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-grow">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Produk</p>
                                <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1">{{ \App\Models\Product::count() }}</p>
                            </div>
                            <div class="flex-shrink-0 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-xl p-4 shadow-lg">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('products.index') }}" class="group inline-flex items-center justify-center w-full px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors">
                                Kelola Produk
                                <svg class="ml-2 h-5 w-5 transform transition-transform duration-150 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Kategori -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 sm:rounded-lg border border-gray-200 dark:border-gray-700">
                   <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-grow">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Kategori</p>
                                <p class="text-4xl font-extrabold text-gray-800 dark:text-white mt-1">{{ \App\Models\Category::count() }}</p>
                            </div>
                             <div class="flex-shrink-0 bg-gradient-to-tr from-green-500 to-teal-600 rounded-xl p-4 shadow-lg">
                                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2zm0 0v10a2 2 0 002 2h5a2 2 0 002-2V5a2 2 0 00-2-2H7z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M16 3h.01M16 7h.01M16 11h.01M16 15h.01M16 19h.01" transform="translate(-1.5 -1.5) scale(1.25)"/></svg>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('categories.index') }}" class="group inline-flex items-center justify-center w-full px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-green-500 transition-colors">
                                Kelola Kategori
                                <svg class="ml-2 h-5 w-5 transform transition-transform duration-150 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div> {{-- Penutup div.grid --}}
        </div> {{-- Penutup div.max-w-7xl --}}
    </div> {{-- Penutup div.py-12 --}}
</x-app-layout>
{{-- AKHIR DARI FILE resources/views/dashboard.blade.php (VERSI KUSTOM LENGKAP) --}}