@extends('layouts.admin_app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Produk</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning me-2">
                <i class="bi bi-pencil-square"></i> Edit Produk
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Produk
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ $product->name }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="Gambar Produk: {{ $product->name }}"
                            class="img-fluid rounded shadow-sm product-img-display"
                            style="max-height: 300px; border: 1px solid #eee; padding: 5px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded"
                            style="height: 250px; border: 1px dashed #ccc;">
                            <i class="bi bi-image-alt fs-1"></i>
                            <span class="ms-2">Gambar tidak tersedia</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <dl class="row">
                        <dt class="col-sm-3">Nama Produk:</dt>
                        <dd class="col-sm-9">{{ $product->name }}</dd>

                        <dt class="col-sm-3">Ukuran:</dt>
                        <dd class="col-sm-9">{{ $product->size }}</dd>

                        <dt class="col-sm-3">Harga:</dt>
                        <dd class="col-sm-9">Rp {{ number_format($product->price, 0, ',', '.') }}</dd>

                        <dt class="col-sm-3">Deskripsi:</dt>
                        <dd class="col-sm-9">
                            <p style="white-space: pre-wrap;">{{ $product->description ?: '-' }}</p>
                        </dd>

                        <dt class="col-sm-3 text-muted">Ditambahkan Pada:</dt>
                        <dd class="col-sm-9 text-muted">{{ $product->created_at->format('d M Y, H:i:s') }}
                            ({{ $product->created_at->diffForHumans() }})</dd>

                        <dt class="col-sm-3 text-muted">Terakhir Diupdate:</dt>
                        <dd class="col-sm-9 text-muted">{{ $product->updated_at->format('d M Y, H:i:s') }}
                            ({{ $product->updated_at->diffForHumans() }})</dd>
                    </dl>
                    <hr>
                    <div class="mt-3">
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('PERINGATAN!\nApakah Anda yakin ingin menghapus produk {{ $product->name }} ({{ $product->size }}) ini?\nTindakan ini tidak dapat diurungkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash3-fill"></i> Hapus Produk Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-img-display {
            object-fit: contain;
            /* Memastikan seluruh gambar terlihat tanpa terpotong */
        }

        dt {
            font-weight: 600;
        }

        dd p {
            margin-bottom: 0;
            /* Mengurangi margin bawah pada paragraf deskripsi */
        }
    </style>
@endpush
