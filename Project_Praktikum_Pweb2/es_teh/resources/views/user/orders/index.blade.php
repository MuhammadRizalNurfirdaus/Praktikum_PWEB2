@extends('layouts.app') {{-- Menggunakan layout publik utama --}}

@section('title', 'Riwayat Pesanan Saya')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                {{-- Memanggil partial sidebar user --}}
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Riwayat Pesanan Saya</h2>
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-cart-plus"></i> Lanjut Belanja
                    </a>
                </div>

                @if ($orders->count() > 0)
                    <div class="list-group shadow-sm">
                        @foreach ($orders as $order)
                            <a href="{{ route('user.orders.show', $order->id) }}"
                                class="list-group-item list-group-item-action flex-column align-items-start mb-2 rounded-3 border-light-subtle">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 fw-bold text-primary">No. Pesanan: {{ $order->order_number }}</h5>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <p class="mb-1">Total: <span class="fw-semibold">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <small class="me-3">Status Pesanan:
                                            <span
                                                class="badge rounded-pill
                                            @switch($order->status)
                                                @case('pending') bg-warning text-dark @break
                                                @case('processing') bg-info text-dark @break
                                                @case('shipped') bg-primary @break
                                                @case('delivered') bg-success @break
                                                @case('paid') bg-success @break
                                                @case('cancelled') bg-danger @break
                                                @case('failed') bg-danger @break
                                                @default bg-secondary @break
                                            @endswitch">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </small>
                                        <small>Status Pembayaran:
                                            <span
                                                class="badge rounded-pill bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning text-dark' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </small>
                                    </div>
                                    <span class="btn btn-sm btn-outline-dark disabled py-0 px-2">Lihat Detail <i
                                            class="bi bi-chevron-right small"></i></span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Paginasi --}}
                    @if ($orders->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div class="card shadow-sm text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-journal-richtext fs-1 text-muted mb-3"></i>
                            <h4 class="card-title">Anda Belum Memiliki Riwayat Pesanan</h4>
                            <p class="card-text text-muted">Semua pesanan yang Anda buat akan muncul di sini.</p>
                            <a href="{{ route('products.index.public_list') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-cup-straw"></i> Mulai Belanja Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .list-group-item-action:hover,
        .list-group-item-action:focus {
            background-color: #f8f9fa;
            /* Warna hover lembut */
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .10) !important;
            transition: all .2s ease-in-out;
        }

        .list-group-item {
            transition: all .2s ease-in-out;
        }
    </style>
@endpush
