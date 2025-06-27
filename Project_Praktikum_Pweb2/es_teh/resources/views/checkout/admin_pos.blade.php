@extends('layouts.app') {{-- Bisa menggunakan layout publik agar navbar tetap sama --}}

@section('title', 'Admin - Buat Transaksi Langsung (POS)')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="text-center mb-4">
                    <h1 class="display-6 fw-bold">Transaksi Langsung (POS)</h1>
                    <p class="text-muted">Buat catatan pesanan untuk pelanggan yang membeli langsung di toko.</p>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold">Ringkasan Pesanan</div>
                    <ul class="list-group list-group-flush">
                        @foreach ($cartItems as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $item['name'] }} ({{ $item['size'] }}) x {{ $item['quantity'] }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <span class="fw-bold">Total Pembayaran</span>
                            <strong class="fs-5 text-primary">Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
                        </li>
                    </ul>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Detail Transaksi</div>
                    <div class="card-body">
                        <form action="{{ route('checkout.placeOrder') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Pelanggan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                    id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                                    placeholder="Contoh: Budi" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check2-circle"></i> Selesaikan & Catat Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
