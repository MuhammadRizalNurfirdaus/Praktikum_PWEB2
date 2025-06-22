@extends('layouts.admin_app')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Pesanan</h1>
        {{-- Tombol tambah pesanan manual oleh admin (opsional) --}}
        {{--
    <a href="#" class="btn btn-sm btn-outline-primary disabled">
        <i class="bi bi-plus-circle-fill"></i> Tambah Pesanan Manual
    </a>
    --}}
    </div>

    {{-- Form Filter & Pencarian --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search_order" class="form-label form-label-sm">Cari Pesanan</label>
                    <input type="text" name="search" id="search_order" class="form-control form-control-sm"
                        placeholder="No. Pesanan atau Nama Pelanggan..." value="{{ $searchQuery ?? '' }}">
                    {{-- Tambah ?? '' untuk default --}}
                </div>
                <div class="col-md-4">
                    <label for="status_filter" class="form-label form-label-sm">Filter Status</label>
                    <select name="status" id="status_filter" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach ($orderStatuses as $status)
                            <option value="{{ $status }}" {{ ($statusFilter ?? '') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}</option> {{-- Tambah ?? '' untuk default --}}
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100 me-1" type="submit"><i class="bi bi-funnel-fill"></i>
                        Filter / Cari</button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm w-auto"
                            title="Hapus Filter"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($orders && $orders->count() > 0) {{-- Tambah pengecekan $orders tidak null --}}
        <div class="card shadow-sm">
            <div class="card-header">
                Daftar Pesanan (Total: {{ $orders->total() }})
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">No. Pesanan</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Tgl Pesan</th>
                            <th scope="col" class="text-end">Total</th>
                            <th scope="col" class="text-center">Status Pembayaran</th>
                            <th scope="col" class="text-center">Status Pesanan</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                <td>{{ $orders->firstItem() + $key }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="fw-bold">{{ $order->order_number }}</a>
                                </td>
                                <td>{{ $order->customer_name ?: $order->user->name ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning text-dark' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill
                            @switch($order->status)
                                @case('pending') bg-secondary @break
                                @case('processing') bg-info text-dark @break
                                @case('shipped') bg-primary @break
                                @case('delivered') bg-success @break
                                @case('paid') bg-success @break
                                @case('cancelled') bg-danger @break
                                @case('failed') bg-danger @break
                                @default bg-light text-dark
                            @endswitch">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm"
                                        title="Lihat Detail Pesanan">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus Pesanan"><i
                                                class="bi bi-trash3-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($orders->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center pt-2">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-warning text-center mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @if (request('search') || request('status'))
                Tidak ada pesanan yang cocok dengan filter atau pencarian Anda.
            @else
                Belum ada pesanan yang masuk.
            @endif
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .table-actions .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            margin-left: 0.25rem;
        }

        .table.align-middle td,
        .table.align-middle th {
            vertical-align: middle;
        }

        .form-label-sm {
            font-size: .875em;
            /* Sedikit lebih kecil untuk label form filter */
            margin-bottom: .25rem;
        }
    </style>
@endpush
