@extends('layouts.app')

@section('title', $product->name . ' - Es Teh Poci Aji Manis')

@section('content')
    <div class="container py-5">
        {{-- ... (breadcrumb dan bagian atas) ... --}}
        <nav aria-label="breadcrumb mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('products.index.public_list') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }} ({{ $product->size }})</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-6">
                {{-- ... (tampilan gambar produk) ... --}}
                <div class="text-center shadow-sm rounded-3 overflow-hidden p-3 bg-light">
                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid product-img-display"
                            alt="{{ $product->name }}" style="max-height: 500px; border-radius: 0.25rem;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded"
                            style="height: 250px; border: 1px dashed #ccc;">
                            <i class="bi bi-image-alt fs-1"></i>
                            <span class="ms-2">Gambar tidak tersedia</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                {{-- ... (detail nama, ukuran, harga, deskripsi) ... --}}
                <h1 class="display-5 fw-bold">{{ $product->name }}</h1>
                <p class="text-muted fs-5 mb-2">Ukuran: {{ $product->size }}</p>
                <p class="fs-2 fw-bolder text-primary mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <h5 class="fw-semibold">Deskripsi Produk:</h5>
                <p class="text-muted lh-lg" style="white-space: pre-wrap;">
                    {{ $product->description ?: 'Deskripsi produk tidak tersedia saat ini. Nikmati kesegaran alami Es Teh Poci Aji Manis!' }}
                </p>


                {{-- AWAL PERUBAHAN UNTUK AUTH --}}
                @auth
                    <div class="mt-4 pt-3 border-top">
                        <h5 class="fw-semibold mb-3">Pesan Sekarang:</h5>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-auto">
                                    <label for="quantity" class="col-form-label fw-medium">Jumlah:</label>
                                </div>
                                <div class="col-auto" style="max-width: 120px;">
                                    <input type="number" id="quantity" name="quantity" class="form-control text-center"
                                        value="1" min="1" max="99">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                                <i class="bi bi-cart-plus-fill"></i> Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-4 pt-3 border-top">
                        <h5 class="fw-semibold mb-3">Pesan Sekarang:</h5>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Menambahkan ke Keranjang
                        </a>
                        <p class="text-muted mt-2 text-center"><small>Anda perlu login untuk dapat memesan produk.</small></p>
                    </div>
                @endauth
                {{-- AKHIR PERUBAHAN UNTUK AUTH --}}

                <div class="mt-4">
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-img-display {
            object-fit: contain;
        }

        dt {
            font-weight: 600;
        }

        dd p {
            margin-bottom: 0;
        }
    </style>
@endpush

@push('scripts')
@endpush
