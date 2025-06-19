@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Checkout Pesanan Anda</h1>
            <p class="lead text-muted">Selesaikan pesanan Anda dengan mengisi detail di bawah ini.</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.placeOrder') }}" method="POST">
            @csrf
            <div class="row g-5">
                {{-- Kolom Kiri: Detail Pengiriman & Pembayaran --}}
                <div class="col-md-7 col-lg-7 order-md-first">
                    <h4 class="mb-3">Detail Pengiriman</h4>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="customer_name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                        id="customer_name" name="customer_name"
                                        value="{{ old('customer_name', $user->name ?? '') }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="customer_email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email"
                                        value="{{ $user->email ?? '' }}" readonly disabled>
                                    <small class="text-muted">Email akan digunakan untuk konfirmasi pesanan.</small>
                                </div>

                                <div class="col-12">
                                    <label for="customer_phone" class="form-label">Nomor Telepon <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror"
                                        id="customer_phone" name="customer_phone"
                                        value="{{ old('customer_phone', $user->phone_number ?? '') }}"
                                        placeholder="08xxxxxxxxxx" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="shipping_address" class="form-label">Alamat Pengiriman Lengkap <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address"
                                        name="shipping_address" rows="3" placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota, Kode Pos"
                                        required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2"
                                        placeholder="Contoh: Tidak pakai es, Gula sedikit">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3">Metode Pembayaran</h4>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            {{-- Untuk sekarang, kita hardcode atau tidak ada pilihan --}}
                            <div class="my-3">
                                <div class="form-check">
                                    <input id="cod" name="payment_method" type="radio" class="form-check-input"
                                        value="COD" checked required>
                                    <label class="form-check-label" for="cod">Bayar di Tempat (COD)</label>
                                </div>
                                {{-- Tambahkan metode pembayaran lain nanti --}}
                            </div>
                            @error('payment_method')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Ringkasan Pesanan --}}
                <div class="col-md-5 col-lg-5">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Ringkasan Pesanan Anda</span>
                        <span class="badge bg-primary rounded-pill">{{ count($cartItems) }} item</span>
                    </h4>
                    <div class="card shadow-sm mb-4">
                        <ul class="list-group list-group-flush">
                            @foreach ($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">{{ $item['name'] }} <small class="text-muted">x
                                                {{ $item['quantity'] }}</small></h6>
                                        <small class="text-muted">{{ $item['size'] ?? '' }}</small>
                                    </div>
                                    <span class="text-muted">Rp
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span class="fw-bold">Total (IDR)</span>
                                <strong class="fs-5">Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
                            </li>
                        </ul>
                    </div>
                    <button class="w-100 btn btn-primary btn-lg shadow" type="submit">
                        <i class="bi bi-shield-check-fill"></i> Buat Pesanan Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
