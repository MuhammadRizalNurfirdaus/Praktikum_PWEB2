@extends('layouts.app')

@section('title', 'Keranjang Belanja Anda')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4 display-5 fw-bold text-center">Keranjang Belanja Anda</h1>

        {{-- Hanya tampilkan pesan dari session jika ada --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Pesan error dari session akan ditampilkan di sini --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- PERUBAHAN LOGIKA TAMPILAN KONTEN KERANJANG --}}
        @if (!empty($cartItems))
            {{-- Tampilkan tabel keranjang jika ada item --}}
            <div class="table-responsive shadow-sm rounded mb-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width:10%">Gambar</th>
                            <th scope="col" style="width:35%">Produk</th>
                            <th scope="col" style="width:15%" class="text-center">Harga Satuan</th>
                            <th scope="col" style="width:15%" class="text-center">Kuantitas</th>
                            <th scope="col" style="width:15%" class="text-center">Subtotal</th>
                            <th scope="col" style="width:10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $id => $details)
                            <tr>
                                <td>
                                    @if ($details['image_path'] && Storage::disk('public')->exists($details['image_path']))
                                        <img src="{{ asset('storage/' . $details['image_path']) }}"
                                            alt="{{ $details['name'] }}" class="img-fluid rounded" style="max-width: 70px;">
                                    @else
                                        <div class="bg-secondary rounded text-white d-flex align-items-center justify-content-center"
                                            style="width: 70px; height: 70px;">
                                            <i class="bi bi-image-alt fs-3"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h5 class="mb-0">{{ $details['name'] }}</h5>
                                    @if (isset($details['size']))
                                        <small class="text-muted">Ukuran: {{ $details['size'] }}</small>
                                    @endif
                                </td>
                                <td class="text-center">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('cart.update', $id) }}" method="POST"
                                        id="update-cart-form-{{ $id }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}"
                                            class="form-control form-control-sm text-center quantity-input"
                                            style="width: 80px; display: inline-block;" min="1" max="99"
                                            data-product-id="{{ $id }}">
                                    </form>
                                </td>
                                <td class="text-center fw-bold">Rp
                                    {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Item"><i
                                                class="bi bi-trash3-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5 border-top-0">Total Belanja:</td>
                            <td colspan="2" class="text-center fw-bold fs-5 border-top-0">Rp
                                {{ number_format($totalPrice, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <form action="{{ route('cart.clear') }}" method="POST"
                    onsubmit="return confirm('Anda yakin ingin mengosongkan keranjang?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger"><i class="bi bi-cart-x-fill"></i> Kosongkan
                        Keranjang</button>
                </form>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg shadow">
                    Lanjut ke Checkout <i class="bi bi-arrow-right-circle-fill"></i>
                </a>
            </div>
        @else
            {{-- Hanya tampilkan pesan default "Keranjang kosong" jika TIDAK ADA pesan error dari session --}}
            {{-- dan juga tidak ada pesan sukses (meskipun tidak relevan di sini jika keranjang kosong) --}}
            @if (!session('error') && !session('success'))
                <div class="text-center py-5">
                    <i class="bi bi-cart3 fs-1 text-muted"></i>
                    <h3 class="mt-3">Keranjang belanja Anda masih kosong.</h3>
                    <p class="text-muted">Yuk, mulai belanja produk es teh favoritmu!</p>
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-cup-straw"></i> Mulai Belanja
                    </a>
                </div>
            @endif
        @endif
        {{-- AKHIR PERUBAHAN LOGIKA TAMPILAN KONTEN KERANJANG --}}
    </div>
@endsection

@push('scripts')
    {{-- ... (JavaScript untuk auto-submit kuantitas tetap sama) ... --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(function(input) {
                let debounceTimer;
                input.addEventListener('change', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const productId = this.dataset.productId;
                        const form = document.getElementById('update-cart-form-' +
                            productId);
                        if (form) {
                            form.submit();
                        }
                    }, 750);
                });
                input.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
