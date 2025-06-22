@extends('layouts.admin_app') {{-- Atau layouts.kurir_app jika dibuat --}}
@section('title', 'Daftar Pengiriman Kurir')
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pengiriman Saya</h1>
    </div>
    @if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead><tr><th>No. Pesanan</th><th>Pelanggan</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ Str::limit($order->shipping_address, 50) }}</td>
                    <td><span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></td>
                    <td><a href="{{ route('kurir.orders.show', $order->id) }}" class="btn btn-sm btn-primary">Detail & Update</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
    @else
    <p>Tidak ada pengiriman yang perlu ditangani saat ini.</p>
    @endif
@endsection