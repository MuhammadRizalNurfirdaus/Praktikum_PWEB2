@extends('layouts.app')
@section('title', 'Riwayat Pesanan Saya')
@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar') {{-- Menggunakan partial sidebar --}}
            </div>
            <div class="col-md-9">
                <h2 class="mb-4">Riwayat Pesanan Saya</h2>
                @if ($orders->count() > 0)
                    <div class="list-group shadow-sm">
                        @foreach ($orders as $order)
                            <a href="{{ route('user.orders.show', $order->id) }}"
                                class="list-group-item list-group-item-action flex-column align-items-start mb-2 rounded-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 fw-bold">No. Pesanan: {{ $order->order_number }}</h5>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <p class="mb-1">Total: <span class="fw-semibold">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>Status Pesanan:
                                        <span
                                            class="badge rounded-pill bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'pending' || $order->status === 'processing' ? 'warning text-dark' : ($order->status === 'shipped' ? 'primary' : 'secondary')) }}">
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
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">Anda belum memiliki riwayat pesanan.</div>
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-primary">Mulai Belanja</a>
                @endif
            </div>
        </div>
    </div>
@endsection
