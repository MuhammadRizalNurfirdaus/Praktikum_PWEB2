@extends('layouts.admin_app') {{-- Atau layouts.kurir_app jika ada --}}
@section('title', 'Detail Pengiriman: ' . $order->order_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Pengiriman: {{ $order->order_number }}</h1>
        <a href="{{ route('kurir.orders.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i>
            Kembali ke Daftar</a>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Detail Pengiriman --}}
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Status Saat Ini:</dt>
                        <dd class="col-sm-8"><span class="badge fs-6 bg-primary">{{ ucfirst($order->status) }}</span></dd>
                        <dt class="col-sm-4">Pelanggan:</dt>
                        <dd class="col-sm-8">{{ $order->customer_name }}</dd>
                        <dt class="col-sm-4">Alamat:</dt>
                        <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $order->shipping_address }}</dd>
                        <dt class="col-sm-4">No. Telepon:</dt>
                        <dd class="col-sm-8">{{ $order->customer_phone }}</dd>
                        <dt class="col-sm-4">Catatan:</dt>
                        <dd class="col-sm-8">{{ $order->notes ?: '-' }}</dd>
                    </dl>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Kuantitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Produk Dihapus' }} ({{ $item->product->size ?? '' }})
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Update Status & Bukti Kirim --}}
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Update Status Pengiriman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kurir.orders.updateStatus', $order->id) }}" method="POST"
                        enctype="multipart/form-data"> {{-- PENTING: enctype --}}
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Ubah Status Menjadi:</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                                onchange="toggleProofUpload(this.value)">
                                @foreach ($kurirAllowedStatuses as $statusOption)
                                    <option value="{{ $statusOption }}"
                                        {{ $order->status == $statusOption ? 'disabled' : '' }}>
                                        {{ ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Bukti Pengiriman (Awalnya tersembunyi) --}}
                        <div id="proofOfDeliverySection" class="mb-3" style="display: none;">
                            <label for="proof_of_delivery" class="form-label">Upload Bukti Pengiriman (Foto) <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="proof_of_delivery" id="proof_of_delivery"
                                class="form-control @error('proof_of_delivery') is-invalid @enderror">
                            @error('proof_of_delivery')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Contoh: foto paket di depan pintu, atau tanda tangan
                                penerima.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Update
                                Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleProofUpload(selectedStatus) {
            const proofSection = document.getElementById('proofOfDeliverySection');
            const proofInput = document.getElementById('proof_of_delivery');
            if (selectedStatus === 'delivered') {
                proofSection.style.display = 'block';
                proofInput.required = true; // Wajibkan upload bukti jika status 'delivered'
            } else {
                proofSection.style.display = 'none';
                proofInput.required = false; // Tidak wajib untuk status lain
            }
        }
        // Panggil saat halaman dimuat untuk memeriksa status awal dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            if (statusSelect) {
                toggleProofUpload(statusSelect.value);
            }
        });
    </script>
@endpush
