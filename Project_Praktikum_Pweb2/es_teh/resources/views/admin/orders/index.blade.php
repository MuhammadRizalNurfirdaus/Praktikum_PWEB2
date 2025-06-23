@extends('layouts.admin_app') {{-- Menggunakan layout admin --}}

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Pesanan</h1>
    {{-- Tombol tambah pesanan manual oleh admin (opsional, bisa diaktifkan jika ada fiturnya) --}}
    {{-- 
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="#" class="btn btn-sm btn-outline-primary disabled">
            <i class="bi bi-plus-circle-fill"></i> Tambah Pesanan Manual
        </a>
    </div>
    --}}
    {{-- AKHIR PERBAIKAN KOMENTAR --}}
    </div>

    {{-- Form Filter & Pencarian --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search_order" class="form-label form-label-sm fw-medium">Cari Pesanan:</label>
                    <input type="text" name="search" id="search_order" class="form-control form-control-sm"
                        placeholder="No. Pesanan, Nama, atau Email Pelanggan..." value="{{ $searchQuery ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label for="status_filter" class="form-label form-label-sm fw-medium">Filter Status Pesanan:</label>
                    <select name="status" id="status_filter" class="form-select form-select-sm">
                        <option value="">Semua Status Pesanan</option>
                        @if (isset($orderStatuses) && is_array($orderStatuses))
                            @foreach ($orderStatuses as $statusValue)
                                <option value="{{ $statusValue }}"
                                    {{ ($statusFilter ?? '') == $statusValue ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100 me-2" type="submit"
                        style="padding-top: 0.45rem; padding-bottom: 0.45rem;">
                        <i class="bi bi-funnel-fill"></i> Filter / Cari
                    </button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm"
                            title="Reset Filter" style="padding-top: 0.45rem; padding-bottom: 0.45rem;">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if (isset($orders) && $orders->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-light-subtle">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-medium">Daftar Pesanan</span>
                    <span class="text-muted small">Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                        {{ $orders->total() }} hasil</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 15%;">No. Pesanan</th>
                            <th scope="col" style="width: 20%;">Pelanggan</th>
                            <th scope="col" style="width: 15%;">Tgl Pesan</th>
                            <th scope="col" class="text-end" style="width: 15%;">Total</th>
                            <th scope="col" class="text-center" style="width: 10%;">Pembayaran</th>
                            <th scope="col" class="text-center" style="width: 10%;">Status Pesanan</th>
                            <th scope="col" class="text-center" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                <td>{{ $orders->firstItem() + $key }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="fw-bold text-decoration-none">{{ $order->order_number }}</a>
                                </td>
                                <td>
                                    {{ $order->customer_name }}
                                    @if ($order->user)
                                        <br><small class="text-muted">{{ $order->user->email }}</small>
                                    @elseif($order->customer_email)
                                        <br><small class="text-muted">{{ $order->customer_email }}</small>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill
                                    @if ($order->payment_status === 'paid') bg-success
                                    @elseif(in_array($order->payment_status, ['menunggu_qr', 'menunggu_va', 'menunggu_ewallet'])) bg-info text-dark
                                    @elseif($order->payment_status === 'unpaid') bg-warning text-dark
                                    @else bg-danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill
                                    @switch($order->status)
                                        @case('pending') bg-secondary @break
                                        @case('menunggu_pembayaran_qr') bg-info text-dark @break
                                        @case('menunggu_pembayaran_va') bg-info text-dark @break
                                        @case('menunggu_pembayaran_ewallet') bg-info text-dark @break
                                        @case('processing') bg-primary @break
                                        @case('shipped') bg-info @break
                                        @case('delivered') bg-success @break
                                        @case('paid') bg-success @break
                                        @case('cancelled') bg-danger @break
                                        @case('failed') bg-danger @break
                                        @default bg-light text-dark @break
                                    @endswitch">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm"
                                        title="Lihat Detail Pesanan">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('PERINGATAN!\nAnda akan menghapus pesanan {{ $order->order_number }}.\nTindakan ini tidak dapat diurungkan. Lanjutkan?')">
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
                        {{ $orders->appends(['search' => $searchQuery ?? '', 'status' => $statusFilter ?? ''])->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-warning text-center mt-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-2 align-middle"></i>
            @if (request('search') || request('status'))
                Tidak ada pesanan yang cocok dengan kriteria filter atau pencarian Anda.
            @else
                Belum ada pesanan yang masuk ke dalam sistem.
            @endif
            <br>
            <a href="{{ route('admin.orders.index') }}" class="alert-link small mt-2 d-inline-block">Reset semua filter</a>
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
            margin-bottom: .25rem;
        }

        .badge.rounded-pill {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }
    </style>
@endpush
