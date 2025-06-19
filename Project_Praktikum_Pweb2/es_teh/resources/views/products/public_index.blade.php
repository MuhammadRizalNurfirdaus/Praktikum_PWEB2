@extends('layouts.app')

@section('title', 'Produk Es Teh Poci Aji Manis')

@section('content')
    <div class="container py-4">
        {{-- ... (Bagian header dan form pencarian tetap sama) ... --}}
        <div class="text-center mb-5">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Es Teh Poci" style="max-height: 80px;" class="mb-3 img-fluid">
            <h1 class="display-5 fw-bold">Produk Unggulan Kami</h1>
            <p class="lead text-muted">Nikmati kesegaran Es Teh Poci Aji Manis dalam berbagai pilihan ukuran.</p>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('products.index.public_list') }}" method="GET">
                    <div class="input-group input-group-lg shadow-sm rounded">
                        <input type="text" name="search" class="form-control" placeholder="Cari Es Teh favoritmu..."
                            value="{{ request('search') }}" aria-label="Cari Produk">
                        <button class="btn btn-primary px-4" type="submit" id="button-search">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                    @if (request('search'))
                        <div class="text-center mt-2">
                            <a href="{{ route('products.index.public_list') }}"
                                class="text-decoration-none text-muted small">
                                <i class="bi bi-x-circle"></i> Hapus filter pencarian "{{ request('search') }}"
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>


        @if ($products->count() > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($products as $product)
                    <div class="col d-flex align-items-stretch">
                        <div class="card h-100 shadow-hover border-light rounded-3 overflow-hidden">
                            <a href="{{ route('products.show.public_detail', $product->id) }}" class="text-decoration-none">
                                @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                        alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                        style="height: 250px;">
                                        <i class="bi bi-image-alt fs-1"></i>
                                        <span class="ms-2 small">Gambar Segera</span>
                                    </div>
                                @endif
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-semibold">
                                    <a href="{{ route('products.show.public_detail', $product->id) }}"
                                        class="text-dark text-decoration-none stretched-link">{{ $product->name }}</a>
                                </h5>
                                <p class="card-text text-muted mb-1"><small>Ukuran: {{ $product->size }}</small></p>
                                <p class="card-text fs-5 fw-bold text-primary mb-3">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>

                                <div class="mt-auto"> {{-- Mendorong tombol ke bawah --}}
                                    {{-- AWAL PERUBAHAN UNTUK AUTH --}}
                                    @auth
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-grid">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm d-grid">
                                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Memesan
                                        </a>
                                    @endauth
                                    {{-- AKHIR PERUBAHAN UNTUK AUTH --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            {{-- ... (pesan jika produk tidak ada) ... --}}
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted mb-3"></i>
                <h3 class="mt-3">Oops! Produk Tidak Ditemukan</h3>
                @if (request('search'))
                    <p class="text-muted">Kami tidak dapat menemukan produk dengan kata kunci
                        "<strong>{{ request('search') }}</strong>".</p>
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-outline-secondary mt-2">
                        <i class="bi bi-arrow-clockwise"></i> Tampilkan Semua Produk
                    </a>
                @else
                    <p class="text-muted">Saat ini belum ada produk yang tersedia. Silakan cek kembali nanti.</p>
                @endif
            </div>
        @endif
    </div>

    @push('styles')
        {{-- ... (style Anda) ... --}}
        <style>
            .shadow-hover:hover {
                transition: box-shadow .2s ease-in-out, transform .2s ease-in-out;
                box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .1) !important;
                transform: translateY(-3px);
            }

            .card-title a:hover {
                color: #0d6efd !important;
            }

            .card-img-top {
                transition: transform .3s ease-in-out;
            }

            .card:hover .card-img-top {
                transform: scale(1.03);
            }

            .stretched-link::after {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 1;
                content: "";
            }
        </style>
    @endpush

    @push('scripts')
    @endpush
@endsection
