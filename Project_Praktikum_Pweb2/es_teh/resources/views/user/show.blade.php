@extends('layouts.app')
@section('title', 'Detail Pesanan: ' . $order->order_number)
@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">Detail Pesanan: <span class="fw-normal">{{ $order->order_number }}</span></h2>
                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi Pesanan</h5>
                        <span
                            class="badge rounded-pill fs-6 bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'pending' || $order->status === 'processing' ? 'warning text-dark' : ($order->status === 'shipped' ? 'primary' : 'secondary')) }}">
                            Status: {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        {{-- ... (Tampilkan detail pesanan seperti di admin.orders.show, tapi dari perspektif user) ... --}}
                        <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        <p><strong>Nama Penerima:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Alamat Pengiriman:</strong><br>{{ nl2br(e($order->shipping_address)) }}</p>
                        <p><strong>No. Telepon:</strong> {{ $order->customer_phone ?: '-' }}</p>
                        <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method ?: '-' }}</p>
                        <p><strong>Status Pembayaran:</strong>
                            <span
                                class="badge rounded-pill bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning text-dark' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        @if ($order->notes)
                            <p><strong>Catatan:</strong><br>{{ nl2br(e($order->notes)) }}</p>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Item yang Dipesan</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            {{-- ... (Tabel item pesanan seperti di admin.orders.show) ... --}}
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product->name ?? 'Produk Dihapus' }}
                                            @if (isset($item->product->size))
                                                <small class="text-muted">({{ $item->product->size }})</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold table-light">
                                    <td colspan="3" class="text-end fs-5">Total Pesanan:</td>
                                    <td class="text-end fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if (in_array($order->status, ['shipped']))
                    {{-- Hanya tampilkan jika status 'shipped' atau status lain yang relevan --}}
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Konfirmasi Penerimaan Pesanan</h5>
                            <p class="card-text">Apakah Anda sudah menerima pesanan ini?</p>
                            <form action="{{ route('user.orders.confirmReception', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle-fill"></i> Ya, Pesanan Sudah Diterima
                                </button>
                            </form>
                            <small class="form-text text-muted d-block mt-2">Dengan mengklik tombol ini, status pesanan akan
                                diubah menjadi "delivered".</small>
                        </div>
                    </div>
                @elseif($order->status === 'delivered')
                    <div class="alert alert-success text-center">
                        <i class="bi bi-patch-check-fill fs-3 me-2"></i> Pesanan ini telah Anda konfirmasi diterima pada
                        {{ $order->updated_at->format('d M Y, H:i') }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
