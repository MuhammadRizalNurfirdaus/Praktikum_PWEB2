@extends('layouts.app') {{-- Menggunakan layout publik utama --}}

@section('title', 'Detail Pesanan: ' . $order->order_number)

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                {{-- Memanggil partial sidebar user --}}
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-light px-3 py-2 rounded-3 shadow-sm mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"
                                        class="text-decoration-none">Dashboard Saya</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.orders.index') }}"
                                        class="text-decoration-none">Riwayat Pesanan</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>

                <h2 class="mb-4">Detail Pesanan <span class="fw-light">#{{ $order->order_number }}</span></h2>

                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light-subtle">
                        <h5 class="mb-0">Informasi Pesanan</h5>
                        <span
                            class="badge rounded-pill fs-6
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
                            Status: {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong class="text-muted">Tanggal Pesan:</strong></p>
                                <p>{{ $order->created_at->format('d F Y, H:i') }}
                                    ({{ $order->created_at->diffForHumans() }})</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong class="text-muted">Status Pembayaran:</strong></p>
                                <p>
                                    <span
                                        class="badge rounded-pill fs-6 bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'unpaid' ? 'warning text-dark' : 'danger') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong class="text-muted">Nama Penerima:</strong></p>
                                <p>{{ $order->customer_name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong class="text-muted">No. Telepon:</strong></p>
                                <p>{{ $order->customer_phone ?: '-' }}</p>
                            </div>
                            <div class="col-12 mb-3">
                                <p class="mb-1"><strong class="text-muted">Alamat Pengiriman:</strong></p>
                                <p style="white-space: pre-wrap;">{{ $order->shipping_address }}</p>
                            </div>
                            @if ($order->notes)
                                <div class="col-12">
                                    <p class="mb-1"><strong class="text-muted">Catatan Tambahan:</strong></p>
                                    <p style="white-space: pre-wrap;" class="fst-italic">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light-subtle">
                        <h5 class="mb-0">Item yang Dipesan</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:10%;">Gambar</th>
                                    <th>Produk</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    <tr>
                                        <td>
                                            @if ($item->product && $item->product->image_path && Storage::disk('public')->exists($item->product->image_path))
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                                    alt="{{ $item->product->name }}" class="img-fluid rounded border"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded text-white d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image-alt fs-4"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->product->name ?? 'Produk Telah Dihapus' }}
                                            @if (isset($item->product->size))
                                                <small class="d-block text-muted">({{ $item->product->size }})</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold">Rp
                                            {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada item dalam pesanan
                                            ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold table-light">
                                    <td colspan="4" class="text-end fs-5 border-0">Total Pesanan:</td>
                                    <td class="text-end fs-5 border-0">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if (in_array($order->status, ['shipped']))
                    {{-- Hanya tampilkan tombol konfirmasi jika status 'shipped' --}}
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Konfirmasi Penerimaan Pesanan</h5>
                            <p class="card-text text-muted">Apakah Anda sudah menerima semua item dalam pesanan ini dengan
                                baik?</p>
                            <form action="{{ route('user.orders.confirmReception', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                    <i class="bi bi-check-circle-fill"></i> Ya, Pesanan Sudah Diterima
                                </button>
                            </form>
                            <small class="form-text text-muted d-block mt-2">Dengan mengklik tombol ini, status pesanan akan
                                diubah menjadi "delivered".</small>
                        </div>
                    </div>
                @elseif($order->status === 'delivered')
                    <div class="alert alert-success text-center shadow-sm" role="alert">
                        <i class="bi bi-patch-check-fill fs-3 me-2"></i> Pesanan ini telah Anda konfirmasi diterima pada
                        {{ $order->updated_at->format('d F Y, H:i') }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-img-display {
            object-fit: contain;
            /* Memastikan seluruh gambar terlihat tanpa terpotong */
        }

        .breadcrumb {
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: #0d6efd;
            /* Warna link breadcrumb */
        }

        .card-body dl dt {
            /* Styling untuk definition list */
            font-weight: 600;
            margin-bottom: .3rem;
        }

        .card-body dl dd {
            margin-bottom: .8rem;
        }
    </style>
@endpush
