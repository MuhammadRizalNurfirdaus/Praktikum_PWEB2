@extends('layouts.admin_app')

@section('title', 'Kelola Produk')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Kelola Produk</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle-fill"></i>
                Tambah Produk Baru
            </a>
        </div>
    </div>

    {{-- Form Pencarian --}}
    <div class="mb-3 card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md">
                    <label for="search_product" class="visually-hidden">Cari Produk</label>
                    <input type="text" name="search" id="search_product" class="form-control form-control-sm"
                        placeholder="Cari berdasarkan nama, ukuran, atau deskripsi produk..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if (request('search'))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger btn-sm"
                            title="Hapus Filter Pencarian"><i class="bi bi-x-lg"></i> Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($products->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header">
                Daftar Produk (Total: {{ $products->total() }})
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-hover mb-0 align-middle"> {{-- tambahkan align-middle --}}
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 10%;">Gambar</th>
                            <th scope="col" style="width: 30%;">Nama Produk</th>
                            <th scope="col" style="width: 15%;">Ukuran</th>
                            <th scope="col" style="width: 15%;">Harga</th>
                            <th scope="col" style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $key }}</td>
                                <td>
                                    @if ($product->image_path && Storage::disk('public')->exists($product->image_path))
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                            class="img-thumbnail product-img-thumb"
                                            style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <span class="badge bg-secondary">Tidak Ada Gambar</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->size }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info btn-sm"
                                        title="Lihat Detail Produk">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="btn btn-warning btn-sm" title="Edit Produk">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus Produk"
                                            onclick="return confirm('PERINGATAN!\nApakah Anda yakin ingin menghapus produk {{ $product->name }} ({{ $product->size }}) ini?\nTindakan ini tidak dapat diurungkan.')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center pt-2">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-warning text-center mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @if (request('search'))
                Tidak ada produk yang cocok dengan pencarian "<strong>{{ request('search') }}</strong>". <a
                    href="{{ route('admin.products.index') }}" class="alert-link">Tampilkan semua</a>.
            @else
                Belum ada produk yang ditambahkan. Silakan <a href="{{ route('admin.products.create') }}"
                    class="alert-link">tambah produk baru</a>.
            @endif
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .table-actions .btn {
            padding: 0.25rem 0.5rem;
            /* Ukuran tombol lebih kecil */
            font-size: 0.8rem;
            margin-left: 0.25rem;
            /* Sedikit spasi antar tombol aksi */
        }

        .product-img-thumb {
            border: 1px solid #dee2e6;
            /* Border tipis untuk gambar thumbnail */
            padding: 2px;
            /* Sedikit padding di dalam thumbnail */
            background-color: #fff;
        }

        .table.align-middle td,
        .table.align-middle th {
            vertical-align: middle;
        }
    </style>
@endpush
