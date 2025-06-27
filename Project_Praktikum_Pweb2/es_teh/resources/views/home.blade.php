@extends('layouts.app')

@section('title', 'Selamat Datang di Es Teh Poci Aji Manis')

@section('content')
    {{-- Halaman ini tidak menggunakan .main-container default agar elemen seperti banner bisa full-width --}}

    {{-- Bagian Hero Banner (full-width) --}}
    <div class="hero-banner text-center d-flex align-items-center justify-content-center mb-5">
        <div class="hero-content">
            <h1 class="display-3 fw-bolder text-dark">Segarkan Harimu!</h1>
            <p class="lead fs-4 col-lg-9 mx-auto text-dark-emphasis">Nikmati Es Teh Poci Aji Manis dengan berbagai varian
                rasa dan ukuran yang menyegarkan.</p>
            <a href="{{ route('products.index.public_list') }}" class="btn btn-primary btn-lg mt-3 px-5 shadow">Lihat Semua
                Produk</a>
        </div>
    </div>

    {{-- Konten utama sekarang berada di dalam container --}}
    <div class="container">
        {{-- Pilihan Populer / Featured Products --}}
        @if (isset($featuredProducts) && $featuredProducts->count() > 0)
            <section class="py-5">
                <h2 class="text-center mb-4 display-6 fw-bold">Pilihan Populer</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                    @foreach ($featuredProducts as $product)
                        {{-- ... (Struktur card produk, tidak berubah) ... --}}
                        <div class="col d-flex align-items-stretch">
                            <div class="card h-100 shadow-hover border-light rounded-3 overflow-hidden"> <a
                                    href="{{ route('products.show.public_detail', $product->id) }}"
                                    class="text-decoration-none">
                                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                            alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                            style="height: 250px;"><i class="bi bi-image-alt fs-1"></i></div>
                                    @endif
                                </a>
                                <div class="card-body d-flex flex-column p-4">
                                    <h5 class="card-title fw-bold"><a
                                            href="{{ route('products.show.public_detail', $product->id) }}"
                                            class="text-dark text-decoration-none stretched-link">{{ $product->name }}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-2"><small>Ukuran: {{ $product->size }}</small></p>
                                    <p class="card-text fs-4 fw-bolder text-primary mb-3">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="mt-auto"> @auth <form action="{{ route('cart.add', $product->id) }}"
                                                method="POST" class="d-grid"> @csrf <input type="hidden" name="quantity"
                                                    value="1"> <button type="submit" class="btn btn-success"><i
                                                        class="bi bi-cart-plus"></i> Keranjang</button></form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-secondary d-grid"><i
                                                class="bi bi-box-arrow-in-right"></i> Login untuk Pesan</a> @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Yang Baru dari Kami / Newest Products --}}
        @if (isset($newestProducts) && $newestProducts->count() > 0)
            <section class="py-5 bg-white rounded-3 my-5 p-4 shadow">
                <h2 class="text-center mb-4 display-6 fw-bold">Yang Baru dari Kami!</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                    @foreach ($newestProducts as $product)
                        {{-- ... (Struktur card produk, tidak berubah) ... --}}
                        <div class="col d-flex align-items-stretch">
                            <div class="card h-100 shadow-hover border-0 overflow-hidden"> <a
                                    href="{{ route('products.show.public_detail', $product->id) }}"
                                    class="text-decoration-none">
                                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                            alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted border"
                                            style="height: 250px;"><i class="bi bi-image-alt fs-1"></i></div>
                                    @endif
                                </a>
                                <div class="card-body d-flex flex-column p-4">
                                    <h5 class="card-title fw-bold"><a
                                            href="{{ route('products.show.public_detail', $product->id) }}"
                                            class="text-dark text-decoration-none stretched-link">{{ $product->name }}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-2"><small>Ukuran: {{ $product->size }}</small></p>
                                    <p class="card-text fs-4 fw-bolder text-primary mb-3">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="mt-auto"> @auth <form action="{{ route('cart.add', $product->id) }}"
                                                method="POST" class="d-grid"> @csrf <input type="hidden" name="quantity"
                                                    value="1"> <button type="submit" class="btn btn-success"><i
                                                        class="bi bi-cart-plus"></i> Keranjang</button></form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-secondary d-grid"><i
                                                class="bi bi-box-arrow-in-right"></i> Login untuk Pesan</a> @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Kenapa Memilih Kami --}}
        <section class="py-5 text-center">
            <h2 class="display-6 fw-bold">Kenapa Memilih Es Teh Poci Aji Manis?</h2>
            <div class="row mt-4">
                {{-- ... (Konten Kenapa Memilih Kami, tidak berubah) ... --}}
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

    </div> {{-- AKHIR DARI <div class="container"> --}}

    {{-- LOKASI KAMI dengan Google Maps (full-width) --}}
    <section class="py-5 bg-light"> {{-- Memberi background abu-abu muda untuk seksi ini --}}
        <div class="container">
            <h2 class="text-center mb-4 display-6 fw-bold">Temukan Kami</h2>
        </div>
        {{-- Peta tidak dibungkus container agar bisa full-width --}}
        <div class="map-responsive shadow-lg">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3960.223109351179!2d108.56986907499694!3d-6.982976993017886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNsKwNTgnNTguNyJTIDEwOMKwMzQnMjAuOCJF!5e0!3m2!1sid!2sid!4v1719523297059!5m2!1sid!2sid"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* ... (CSS Anda yang sudah ada, tidak perlu diubah) ... */
        .hero-banner {
            position: relative;
            background-image: url('{{ asset('images/background.png') }}');
            background-size: cover;
            background-position: center;
            height: 50vh;
            min-height: 400px;
        }

        .hero-content {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 2.5rem;
            border-radius: .75rem;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .shadow-hover:hover {
            transition: box-shadow .2s ease-in-out, transform .2s ease-in-out;
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .15) !important;
            transform: translateY(-5px);
        }

        .card-title a:hover {
            color: #0d6efd !important;
        }

        .card-img-top {
            transition: transform .3s ease-in-out;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
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

        .map-responsive {
            overflow: hidden;
            padding-bottom: 56.25%;
            position: relative;
            height: 0;
        }

        .map-responsive iframe {
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            position: absolute;
        }
    </style>
@endpush
