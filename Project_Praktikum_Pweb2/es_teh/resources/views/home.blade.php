@extends('layouts.app')
@section('title', 'Selamat Datang di Es Teh Poci Aji Manis')
@section('content')
    {{-- ... (Bagian Hero Banner) ... --}}
    <div class="container-fluid px-0 mb-5">
        <div
            style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('images/banner-esteh.jpg') }}') no-repeat center center; background-size: cover; height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; color: white;">
            <div>
                <h1 class="display-3 fw-bold">Segarkan Harimu!</h1>
                <p class="lead fs-4 col-md-8 mx-auto">Nikmati Es Teh Poci Aji Manis dengan berbagai varian rasa dan ukuran
                    yang menyegarkan.</p>
                <a href="{{ route('products.index.public_list') }}" class="btn btn-primary btn-lg mt-3 px-4 shadow">Lihat
                    Semua Produk</a>
            </div>
        </div>
    </div>

    <div class="container">
        {{-- Pilihan Populer / Featured Products --}}
        @if (isset($featuredProducts) && $featuredProducts->count() > 0)
            <section class="py-4">
                <h2 class="text-center mb-4 display-6 fw-bold">Pilihan Populer</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4"> {{-- Diubah menjadi 3 kolom untuk md --}}
                    @foreach ($featuredProducts as $product)
                        {{-- ... (Struktur card produk, sama seperti sebelumnya) ... --}}
                        <div class="col d-flex align-items-stretch">
                            <div class="card h-100 shadow-hover border-light rounded-3 overflow-hidden">
                                <a href="{{ route('products.show.public_detail', $product->id) }}"
                                    class="text-decoration-none">
                                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                            alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                            style="height: 220px;"><i class="bi bi-image-alt fs-1"></i></div>
                                    @endif
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-semibold"><a
                                            href="{{ route('products.show.public_detail', $product->id) }}"
                                            class="text-dark text-decoration-none stretched-link">{{ $product->name }}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-1"><small>Ukuran: {{ $product->size }}</small></p>
                                    <p class="card-text fs-5 fw-bold text-primary mb-3">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="mt-auto">
                                        @auth
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-grid">
                                                @csrf <input type="hidden" name="quantity" value="1"> <button
                                                    type="submit" class="btn btn-success btn-sm"><i
                                                        class="bi bi-cart-plus"></i> Keranjang</button></form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm d-grid"><i
                                                    class="bi bi-box-arrow-in-right"></i> Login</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @else
            <p class="text-center text-muted">Tidak ada pilihan populer saat ini.</p>
        @endif

        {{-- Yang Baru dari Kami / Newest Products --}}
        @if (isset($newestProducts) && $newestProducts->count() > 0)
            <section class="py-5 bg-light rounded-3 my-5 p-4 shadow-sm">
                <h2 class="text-center mb-4 display-6 fw-bold">Yang Baru dari Kami!</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4"> {{-- Diubah menjadi 3 kolom untuk md --}}
                    @foreach ($newestProducts as $product)
                        {{-- ... (Struktur card produk, sama seperti sebelumnya) ... --}}
                        <div class="col d-flex align-items-stretch">
                            <div class="card h-100 shadow-hover border-0 overflow-hidden">
                                <a href="{{ route('products.show.public_detail', $product->id) }}"
                                    class="text-decoration-none">
                                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                            alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-white text-muted border"
                                            style="height: 220px;"><i class="bi bi-image-alt fs-1"></i></div>
                                    @endif
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-semibold"><a
                                            href="{{ route('products.show.public_detail', $product->id) }}"
                                            class="text-dark text-decoration-none stretched-link">{{ $product->name }}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-1"><small>Ukuran: {{ $product->size }}</small></p>
                                    <p class="card-text fs-5 fw-bold text-primary mb-3">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="mt-auto">
                                        @auth
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-grid">
                                                @csrf <input type="hidden" name="quantity" value="1"> <button
                                                    type="submit" class="btn btn-success btn-sm"><i
                                                        class="bi bi-cart-plus"></i> Keranjang</button></form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm d-grid"><i
                                                    class="bi bi-box-arrow-in-right"></i> Login</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @else
            <p class="text-center text-muted">Tidak ada produk baru saat ini.</p>
        @endif

        {{-- ... (Seksi "Kenapa Memilih..." tetap sama) ... --}}
        <section class="py-5 text-center">
            <h2 class="display-6 fw-bold">Kenapa Memilih Es Teh Poci Aji Manis?</h2>
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="p-3"> <i class="bi bi-droplet-half fs-1 text-primary"></i>
                        <h4 class="mt-2 fw-semibold">Segar Alami</h4>
                        <p class="text-muted">Dibuat dari daun teh pilihan berkualitas tinggi.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3"> <i class="bi bi-hand-thumbs-up-fill fs-1 text-primary"></i>
                        <h4 class="mt-2 fw-semibold">Rasa Mantap</h4>
                        <p class="text-muted">Kombinasi rasa teh yang khas dan manis yang pas.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3"> <i class="bi bi-award-fill fs-1 text-primary"></i>
                        <h4 class="mt-2 fw-semibold">Kualitas Terjamin</h4>
                        <p class="text-muted">Proses pembuatan yang higienis dan terjaga.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        /* ... (CSS Anda yang sudah ada) ... */
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
