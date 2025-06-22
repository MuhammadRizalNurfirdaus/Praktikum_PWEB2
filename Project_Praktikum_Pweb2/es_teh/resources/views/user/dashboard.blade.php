@extends('layouts.app') {{-- Menggunakan layout publik --}}

@section('title', 'Dashboard Saya')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                {{-- Sidebar Mini User --}}
                <div class="list-group shadow-sm">
                    <a href="{{ route('user.dashboard') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door-fill me-2"></i> Dashboard Saya
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill me-2"></i> Profil Saya
                    </a>
                    <a href="{{ route('user.orders.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('user.orders.index') ? 'active' : '' }}">
                        <i class="bi bi-receipt-cutoff me-2"></i> Riwayat Pesanan
                        @if ($pendingOrdersCount > 0)
                            <span class="badge bg-warning text-dark float-end">{{ $pendingOrdersCount }}</span>
                        @endif
                    </a>
                    {{-- Tambahkan link lain jika perlu --}}
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Selamat Datang Kembali, {{ $user->name }}!</h4>
                    </div>
                    <div class="card-body">
                        <p>Ini adalah halaman dashboard Anda. Dari sini Anda bisa melihat ringkasan aktivitas dan mengelola
                            pesanan Anda.</p>

                        <h5 class="mt-4 mb-3">Pesanan Terbaru Anda:</h5>
                        @if ($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>No. Pesanan</th>
                                            <th>Tanggal</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td><a
                                                        href="{{ route('user.orders.show', $order->id) }}">{{ $order->order_number }}</a>
                                                </td>
                                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                                <td class="text-end">Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge rounded-pill bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'pending' || $order->status === 'processing' ? 'warning text-dark' : 'secondary') }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end"><a href="{{ route('user.orders.show', $order->id) }}"
                                                        class="btn btn-outline-primary btn-sm">Lihat</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-2">
                                <a href="{{ route('user.orders.index') }}">Lihat Semua Pesanan »</a>
                            </div>
                        @else
                            <p class="text-muted">Anda belum memiliki riwayat pesanan.</p>
                            <a href="{{ route('products.index.public_list') }}" class="btn btn-primary">Mulai Belanja
                                Sekarang</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
