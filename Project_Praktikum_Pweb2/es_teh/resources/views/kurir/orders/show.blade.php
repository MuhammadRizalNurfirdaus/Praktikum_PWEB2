@extends('layouts.admin_app') {{-- Atau layouts.kurir_app --}}
@section('title', 'Detail Pengiriman: ' . $order->order_number)
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Pengiriman: {{ $order->order_number }}</h1>
        <a href="{{ route('kurir.orders.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>
    {{-- Tampilkan detail pesanan seperti di admin.orders.show --}}
    <p><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
    <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
    <p><strong>Status Saat Ini:</strong> {{ ucfirst($order->status) }}</p>
    {{-- ... detail item ... --}}
    <hr>
    <h5>Update Status Pengiriman</h5>
    <form action="{{ route('kurir.orders.updateStatus', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="status" class="form-label">Status Baru:</label>
            <select name="status" id="status" class="form-select">
                @foreach($kurirAllowedStatuses as $statusOption)
                    <option value="{{ $statusOption }}" {{ $order->status == $statusOption ? 'selected disabled' : '' }}>
                        {{ ucfirst($statusOption) }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Status</button>
    </form>
@endsection