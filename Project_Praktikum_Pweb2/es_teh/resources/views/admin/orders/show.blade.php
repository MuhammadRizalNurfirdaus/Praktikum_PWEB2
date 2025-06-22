@extends('layouts.admin_app')

@section('title', 'Detail Pesanan: ' . $order->order_number)

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Pesanan: <span class="fw-normal">{{ $order->order_number }}</span></h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Detail Pesanan & Item --}}
        <div class="col-lg-8">
            {{-- ... (Kartu Informasi Pesanan dan Item Pesanan tetap sama seperti sebelumnya) ... --}}
            {{-- Kartu Informasi Pesanan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-light-subtle">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                    <span
                        class="badge rounded-pill fs-6 @switch($order->status) @case('pending') bg-secondary @break @case('processing') bg-info text-dark @break @case('shipped') bg-primary @break @case('delivered') bg-success @break @case('paid') bg-success @break @case('cancelled') bg-danger @break @case('failed') bg-danger @break @default bg-light text-dark @endswitch">
                        Status: {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4 col-md-3">Nomor Pesanan:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $order->order_number }}</dd>
                        <dt class="col-sm-4 col-md-3">Tanggal Pesan:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $order->created_at->format('d M Y, H:i') }}</dd>
                        <dt class="col-sm-4 col-md-3">Nama Pelanggan:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $order->customer_name }}
                            ({{ $order->user->email ?? 'Email tidak tersedia' }})</dd>
                        <dt class="col-sm-4 col-md-3">Kurir Ditugaskan:</dt>
                        <dd class="col-sm-8 col-md-9 fw-semibold {{ $order->kurir ? 'text-success' : 'text-muted' }}">
                            {{ $order->kurir->name ?? 'Belum Ditugaskan' }}
                        </dd>
                        <dt class="col-sm-4 col-md-3">No. Telepon:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $order->customer_phone ?: '-' }}</dd>
                        <dt class="col-sm-4 col-md-3">Alamat Pengiriman:</dt>
                        <dd class="col-sm-8 col-md-9" style="white-space: pre-wrap;">{{ $order->shipping_address }}</dd>
                        <dt class="col-sm-4 col-md-3">Metode Pembayaran:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $order->payment_method ?: '-' }}</dd>
                        <dt class="col-sm-4 col-md-3">Status Pembayaran:</dt>
                        <dd class="col-sm-8 col-md-9">
                            <span
                                class="badge rounded-pill bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning text-dark' }}">
                                {{ ucfirst($order->payment_status) }}
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
            <div class="card shadow-sm">
                <div class="card-header bg-light-subtle">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 align-middle">
                        {{-- ... (Konten tabel item sama seperti sebelumnya) ... --}}
                        <thead class="table-light">
                            <tr>
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
                                        @if ($item->product)
                                            {{ $item->product->name }}
                                            @if (isset($item->product->size))
                                                <small class="d-block text-muted">({{ $item->product->size }})</small>
                                            @endif
                                        @else
                                            <span class="text-danger fst-italic">Produk Telah Dihapus</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->sub_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada item dalam pesanan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="3" class="text-end fs-5 border-0">Total Pesanan:</td>
                                <td class="text-end fs-5 border-0">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Update Status & Penugasan Kurir --}}
        <div class="col-lg-4">
            {{-- AWAL FORM UPDATE STATUS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Update Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- Simpan kurir_id saat ini jika ada, agar tidak ter-reset saat hanya update status --}}
                        @if ($order->kurir_id)
                            <input type="hidden" name="kurir_id" value="{{ $order->kurir_id }}">
                        @endif
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pesanan Baru:</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                                required>
                                @foreach ($orderStatuses as $statusOption)
                                    <option value="{{ $statusOption }}"
                                        {{ $order->status == $statusOption ? 'selected' : '' }}>
                                        {{ ucfirst($statusOption) }}
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
            {{-- AKHIR FORM UPDATE STATUS --}}

            {{-- AWAL FORM PENUGASAN KURIR --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tugaskan Kurir</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- Simpan status saat ini, agar tidak ter-reset saat hanya update kurir --}}
                        <input type="hidden" name="status" value="{{ $order->status }}">
                        <div class="mb-3">
                            <label for="kurir_id" class="form-label">Pilih Kurir:</label>
                            <select name="kurir_id" id="kurir_id"
                                class="form-select @error('kurir_id') is-invalid @enderror">
                                <option value="">-- Tidak Ditugaskan --</option>
                                @foreach ($availableKurirs ?? [] as $kurir)
                                    <option value="{{ $kurir->id }}"
                                        {{ old('kurir_id', $order->kurir_id) == $kurir->id ? 'selected' : '' }}>
                                        {{ $kurir->name }} (ID: {{ $kurir->id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kurir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-info w-100">Simpan Penugasan Kurir</button>
                    </form>
                </div>
            </div>
            {{-- AKHIR FORM PENUGASAN KURIR --}}

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Lain</h5>
                </div>
                <div class="card-body text-center">
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('PERINGATAN!\nApakah Anda yakin ingin menghapus pesanan ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash3-fill"></i> Hapus Pesanan Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        dt {
            font-weight: 600;
            margin-bottom: .3rem;
        }

        dd {
            margin-bottom: .8rem;
        }
    </style>
@endpush
