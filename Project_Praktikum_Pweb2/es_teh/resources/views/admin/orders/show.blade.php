@extends('layouts.admin_app')

@section('title', 'Detail Pesanan: ' . ($order->order_number ?? 'N/A'))

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        @if (isset($order))
            <h1 class="h2">Detail Pesanan: <span class="fw-normal">{{ $order->order_number }}</span></h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Pesanan
            </a>
        @else
            <h1 class="h2">Detail Pesanan Tidak Ditemukan</h1>
        @endif
    </div>

    @if (isset($order))
        <div class="row g-4">
            {{-- AWAL KOLOM KIRI (75% LEBAR) --}}
            <div class="col-lg-8">
                {{-- Kartu Informasi Pesanan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi Pesanan</h5>
                        <span
                            class="badge rounded-pill fs-6
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
                                @default bg-light text-dark
                            @endswitch">
                            Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 col-md-3">Nomor Pesanan:</dt>
                            <dd class="col-sm-8 col-md-9">{{ $order->order_number }}</dd>
                            <dt class="col-sm-4 col-md-3">Tanggal Pesan:</dt>
                            <dd class="col-sm-8 col-md-9">{{ $order->created_at->format('d M Y, H:i') }}</dd>
                            <dt class="col-sm-4 col-md-3">Nama Pelanggan:</dt>
                            <dd class="col-sm-8 col-md-9">{{ $order->customer_name }}</dd>
                            <dt class="col-sm-4 col-md-3">Kontak:</dt>
                            <dd class="col-sm-8 col-md-9">{{ $order->customer_phone }} | <a
                                    href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></dd>
                            <dt class="col-sm-4 col-md-3">Alamat Pengiriman:</dt>
                            <dd class="col-sm-8 col-md-9" style="white-space: pre-wrap;">{{ $order->shipping_address }}</dd>
                            <dt class="col-sm-4 col-md-3">Kurir Ditugaskan:</dt>
                            <dd class="col-sm-8 col-md-9">{{ $order->kurir->name ?? 'Belum Ditugaskan' }}</dd>
                            <dt class="col-sm-4 col-md-3">Metode Pembayaran:</dt>
                            <dd class="col-sm-8 col-md-9">{{ $order->payment_method ?: '-' }}</dd>
                            <dt class="col-sm-4 col-md-3">Status Pembayaran:</dt>
                            <dd class="col-sm-8 col-md-9">
                                <span
                                    class="badge rounded-pill @if ($order->payment_status === 'paid') bg-success @elseif(in_array($order->payment_status, ['menunggu_qr', 'menunggu_va', 'menunggu_ewallet'])) bg-info text-dark @elseif($order->payment_status === 'unpaid') bg-warning text-dark @else bg-danger @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </dd>
                            @if ($order->notes)
                                <dt class="col-sm-4 col-md-3">Catatan Pelanggan:</dt>
                                <dd class="col-sm-8 col-md-9" style="white-space: pre-wrap;">{{ $order->notes }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Kartu Item Pesanan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Item Pesanan</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:10%;">Gambar</th>
                                    <th>Produk</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-end">Harga</th>
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
                                                    style="width: 50px; height: 50px;"><i class="bi bi-image-alt fs-4"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $item->product->name ?? 'Produk Telah Dihapus' }} @if (isset($item->product->size))
                                                <small class="d-block text-muted">({{ $item->product->size }})</small>
                                            @endif
                                        </td>
                                        <td class="text-center">x{{ $item->quantity }}</td>
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

                {{-- Kartu Bukti Pengiriman dari Kurir (Hanya Tampil Jika Ada) --}}
                @if ($order->proof_of_delivery)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success-subtle">
                            <h5 class="mb-0"><i class="bi bi-camera-fill me-2"></i>Bukti Pengiriman dari Kurir</h5>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-muted">Gambar ini diupload oleh kurir sebagai bukti bahwa pesanan telah sampai ke
                                tujuan.</p>
                            <a href="{{ asset('storage/' . $order->proof_of_delivery) }}" data-bs-toggle="modal"
                                data-bs-target="#proofImageModal">
                                <img src="{{ asset('storage/' . $order->proof_of_delivery) }}"
                                    alt="Bukti Pengiriman untuk {{ $order->order_number }}"
                                    class="img-fluid rounded border p-2" style="max-height: 250px;">
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Kartu Feedback dari Pelanggan (Hanya Tampil Jika Ada) --}}
                @if ($order->feedback_submitted_at)
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning-subtle">
                            <h5 class="mb-0"><i class="bi bi-chat-square-quote-fill me-2"></i>Feedback dari Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Rating Produk:</strong><br>
                                    <span class="fs-5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="bi {{ $i <= $order->product_rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="ms-1 fw-bold">({{ $order->product_rating ?? 'N/A' }}/5)</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Rating Kurir:</strong><br>
                                    <span class="fs-5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="bi {{ $i <= $order->kurir_rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="ms-1 fw-bold">({{ $order->kurir_rating ?? 'N/A' }}/5)</span>
                                </div>
                            </div>
                            <p class="mb-1"><strong>Komentar:</strong></p>
                            <div class="p-3 bg-light rounded border" style="white-space: pre-wrap; min-height: 80px;">
                                {{ $order->feedback ?: 'Tidak ada komentar yang diberikan.' }}
                            </div>
                            <small class="text-muted d-block mt-2">Diberikan pada:
                                {{ $order->feedback_submitted_at->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                @endif
            </div> {{-- AKHIR DARI col-lg-8 --}}

            {{-- AWAL KOLOM KANAN (Aksi Admin) --}}
            <div class="col-lg-4">
                {{-- Card Update Status Pesanan --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Update Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">Ubah Status Pesanan:</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach ($orderStatuses as $statusOption)
                                        <option value="{{ $statusOption }}"
                                            {{ $order->status == $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>

                {{-- Card Tugaskan Kurir --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Tugaskan Kurir</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="kurir_id" class="form-label">Pilih Kurir:</label>
                                <select name="kurir_id" id="kurir_id"
                                    class="form-select @error('kurir_id') is-invalid @enderror">
                                    <option value="">-- Tidak Ditugaskan --</option>
                                    @foreach ($availableKurirs ?? [] as $kurir)
                                        <option value="{{ $kurir->id }}"
                                            {{ $order->kurir_id == $kurir->id ? 'selected' : '' }}>
                                            {{ $kurir->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kurir_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="status" value="{{ $order->status }}">
                            <button type="submit" class="btn btn-info w-100">Simpan Penugasan Kurir</button>
                        </form>
                    </div>
                </div>

                {{-- Card Konfirmasi Pembayaran (Hanya Tampil Jika Perlu) --}}
                @if ($order->payment_method === 'QRCODE' && $order->payment_status === 'menunggu_qr')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Konfirmasi Pembayaran QR</h5>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">Pesanan ini menggunakan metode pembayaran QR dan menunggu
                                konfirmasi pembayaran.</p>
                            <form action="{{ route('admin.orders.confirmQrPayment', $order->id) }}" method="POST"
                                onsubmit="return confirm('Anda yakin ingin mengonfirmasi bahwa pembayaran QR untuk pesanan ini telah diterima?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100"><i
                                        class="bi bi-check-circle-fill"></i> Konfirmasi Pembayaran QR Diterima</button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Card Aksi Lain --}}
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Aksi Lain</h5>
                    </div>
                    <div class="card-body text-center d-grid gap-2">
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-outline-info btn-sm"
                            target="_blank">
                            <i class="bi bi-receipt"></i> Lihat/Cetak Struk
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pesanan ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i
                                    class="bi bi-trash3"></i> Hapus Pesanan Ini</button>
                        </form>
                    </div>
                </div>
            </div> {{-- AKHIR DARI col-lg-4 --}}
        </div> {{-- AKHIR DARI .row --}}

        {{-- MODAL UNTUK MENAMPILKAN GAMBAR BUKTI PENGIRIMAN LEBIH BESAR --}}
        @if ($order->proof_of_delivery)
            <div class="modal fade" id="proofImageModal" tabindex="-1" aria-labelledby="proofImageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="proofImageModalLabel">Bukti Pengiriman:
                                #{{ $order->order_number }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $order->proof_of_delivery) }}" alt="Bukti Pengiriman"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-danger">
            Pesanan tidak ditemukan atau Anda tidak memiliki akses untuk melihatnya.
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .product-img-display {
            object-fit: contain;
        }

        .card-body dl dt {
            font-weight: 600;
            margin-bottom: .3rem;
        }

        .card-body dl dd {
            margin-bottom: .8rem;
        }

        .badge.fs-6 {
            padding-top: .5em;
            padding-bottom: .5em;
        }
    </style>
@endpush
