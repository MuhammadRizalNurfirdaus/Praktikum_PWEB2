@extends('layouts.app')

@section('title', $product->name . ' - Es Teh Poci Aji Manis')

@section('content')
    <div class="container py-4"> {{-- Mengembalikan container di sini untuk padding halaman --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light px-3 py-2 rounded-3 shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('products.index.public_list') }}"
                        class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }} ({{ $product->size }})</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-6">
                        <div class="text-center p-3 bg-white rounded border">
                            @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                    class="img-fluid product-img-display" alt="{{ $product->name }}"
                                    style="max-height: 450px; border-radius: 0.25rem;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded"
                                    style="height: 300px; border: 1px dashed #ccc;">
                                    <i class="bi bi-image-alt fs-1"></i>
                                    <span class="ms-2">Gambar tidak tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h1 class="display-5 fw-bold mb-3">{{ $product->name }}</h1>
                        <p class="text-muted fs-5 mb-2">Ukuran: <span
                                class="fw-medium text-dark">{{ $product->size }}</span></p>
                        <p class="fs-2 fw-bolder text-primary mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                        <div class="mb-4">
                            <h5 class="fw-semibold border-bottom pb-2 mb-3">Deskripsi Produk:</h5>
                            {{-- PERBAIKAN UNTUK DESKRIPSI --}}
                            <div class="product-description text-muted lh-lg">
                                {!! nl2br(
                                    e(
                                        $product->description ?:
                                        'Deskripsi produk tidak tersedia saat ini. Nikmati kesegaran alami Es Teh Poci Aji Manis!',
                                    ),
                                ) !!}
                            </div>
                        </div>

                        @auth
                            <div class="mt-4 pt-3 border-top">
                                <h5 class="fw-semibold mb-3">Pesan Sekarang:</h5>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-auto">
                                            <label for="quantity" class="col-form-label fw-medium">Jumlah:</label>
                                        </div>
                                        <div class="col-auto" style="max-width: 100px;">
                                            <input type="number" id="quantity" name="quantity"
                                                class="form-control text-center form-control-lg" value="1" min="1"
                                                max="99">
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
                                <p class="text-muted mt-2 text-center"><small>Anda perlu login untuk dapat memesan
                                        produk.</small></p>
                            </div>
                        @endauth

                        <div class="mt-4">
                            <a href="{{ route('products.index.public_list') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian Produk Terkait (Opsional) --}}
        {{-- <div class="mt-5 pt-5 border-top"> ... </div> --}}
    </div>
@endsection

@push('styles')
    <style>
        .product-img-display {
            object-fit: contain;
        }

        dt {
            /* Jika menggunakan <dl> lagi */
            font-weight: 600;
        }

        .product-description {
            /* white-space: pre-wrap;  -- nl2br sudah menangani baris baru -- */
            word-wrap: break-word;
            /* Memastikan kata yang panjang akan pindah baris */
            overflow-wrap: break-word;
            /* Alias untuk word-wrap */
        }

        .breadcrumb {
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: #0d6efd;
            /* Warna link breadcrumb */
        }

        .card-body .row>div {
            /* Memberi sedikit ruang jika ada banyak detail */
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    {{-- Script JS jika diperlukan --}}
@endpush
