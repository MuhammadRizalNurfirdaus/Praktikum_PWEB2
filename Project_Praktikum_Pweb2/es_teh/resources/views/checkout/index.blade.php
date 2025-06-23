@extends('layouts.app')

@section('title', 'Checkout Pesanan - Es Teh Poci Aji Manis')

@section('content')
    <div class="container py-4">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Checkout Pesanan Anda</h1>
            @if (!session('success_with_qr') && !session('success_with_va') && !session('success_with_ewallet'))
                <p class="lead text-muted">Selesaikan pesanan Anda dengan mengisi detail di bawah ini.</p>
            @endif
        </div>

        {{-- Pesan Sukses/Error Global dari Session --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Pesan Sukses Khusus untuk Metode Pembayaran yang Membutuhkan Info Tambahan --}}
        @if (session('success_with_qr'))
            <div class="alert alert-info shadow-sm">
                <h4 class="alert-heading"><i class="bi bi-qr-code-scan"></i> Pembayaran QR Code</h4>
                <p>{{ session('success_with_qr') }}</p>
                <hr>
                <div class="mt-3 text-center">
                    <p class="fw-bold">Silakan pindai QR Code di bawah ini untuk melakukan pembayaran:</p>
                    <img src="{{ asset('images/qr-code-simulasi.png') }}" alt="QR Code Pembayaran"
                        class="img-fluid mb-2 rounded" style="max-width: 250px; border: 1px solid #ccc;">
                    <p class="mb-1">Nomor Pesanan Anda: <strong
                            class="fs-5">{{ session('order_number_for_qr') }}</strong></p>
                    <p class="text-muted small">Setelah melakukan pembayaran, status pesanan Anda akan diproses oleh admin.
                    </p>
                    <a href="{{ route('user.orders.show', session('order_id_for_qr')) }}" class="btn btn-primary mt-2"><i
                            class="bi bi-receipt"></i> Lihat Detail Pesanan Saya</a>
                </div>
            </div>
        @endif
        @if (session('success_with_va'))
            <div class="alert alert-info shadow-sm">
                <h4 class="alert-heading"><i class="bi bi-credit-card-2-front-fill"></i> Pembayaran Virtual Account</h4>
                <p>{{ session('success_with_va') }}</p>
                <hr>
                <div class="mt-3 text-center">
                    <p class="fw-bold">Silakan lakukan pembayaran ke Virtual Account:</p>
                    <p>Bank: <strong class="fs-5">{{ session('va_bank_for_display') }}</strong></p>
                    <p>Nomor VA: <strong class="fs-4 text-primary">{{ session('va_number_for_display') }}</strong></p>
                    <p>Total Pembayaran: <strong class="fs-5">Rp
                            {{ number_format(session('total_amount_for_display', 0), 0, ',', '.') }}</strong></p>
                    <p>Nomor Pesanan Anda: <strong class="fs-5">{{ session('order_number_for_display') }}</strong></p>
                    <p class="text-muted small">Setelah melakukan pembayaran, status pesanan Anda akan diproses oleh admin.
                    </p>
                    <a href="{{ route('user.orders.show', session('order_id_for_display')) }}"
                        class="btn btn-primary mt-2"><i class="bi bi-receipt"></i> Lihat Detail Pesanan Saya</a>
                </div>
            </div>
        @endif
        @if (session('success_with_ewallet'))
            <div class="alert alert-info shadow-sm">
                <h4 class="alert-heading"><i class="bi bi-phone-fill"></i> Pembayaran E-Wallet (DANA)</h4>
                <p>{{ session('success_with_ewallet') }}</p>
                <hr>
                <div class="mt-3 text-center">
                    <p class="fw-bold">Silakan lakukan pembayaran ke nomor DANA berikut:</p>
                    <p class="fs-4 text-primary"><strong>083101461069</strong></p>
                    <p>(a.n. Nama Toko Anda / Nama Anda)</p> {{-- SESUAIKAN --}}
                    <p>Total Pembayaran: <strong class="fs-5">Rp
                            {{ number_format(session('total_amount_for_display', 0), 0, ',', '.') }}</strong></p>
                    <p>Nomor Pesanan Anda: <strong class="fs-5">{{ session('order_number_for_display') }}</strong></p>
                    <p class="text-muted small">Mohon konfirmasi setelah melakukan pembayaran. Status pesanan Anda akan
                        diproses oleh admin.</p>
                    <a href="{{ route('user.orders.show', session('order_id_for_display')) }}"
                        class="btn btn-primary mt-2"><i class="bi bi-receipt"></i> Lihat Detail Pesanan Saya</a>
                </div>
            </div>
        @endif

        {{-- Blok Error Validasi Global (Hanya tampil jika tidak ada pesan sukses khusus) --}}
        @if ($errors->any() && !session('success_with_qr') && !session('success_with_va') && !session('success_with_ewallet'))
            <div class="alert alert-danger mb-4 shadow-sm" role="alert">
                <h5 class="alert-heading fw-semibold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Oops! Ada beberapa
                    kesalahan pada input Anda:</h5>
                <ul class="mb-0 small ps-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Checkout (Hanya tampil jika tidak ada pesan sukses khusus dan keranjang ada isinya) --}}
        @if (
            !session('success_with_qr') &&
                !session('success_with_va') &&
                !session('success_with_ewallet') &&
                isset($cartItems) &&
                is_array($cartItems) &&
                count($cartItems) > 0)
            <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row g-4"> {{-- Menggunakan g-4 untuk jarak antar kolom --}}
                    {{-- Kolom Kiri: Detail Pengiriman & Metode Pembayaran --}}
                    <div class="col-lg-7 order-lg-first"> {{-- urutan untuk layar besar --}}
                        <h4 class="mb-3 fw-semibold">Detail Pengiriman</h4>
                        <div class="card shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="customer_name" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('customer_name') is-invalid @enderror"
                                            id="customer_name" name="customer_name"
                                            value="{{ old('customer_name', $user->name ?? '') }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{$message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="customer_email_display" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email"
                                            class="form-control bg-light @error('customer_email') is-invalid @enderror"
                                            id="customer_email_display" name="customer_email_display_disabled"
                                            value="{{ $user->email ?? '' }}" readonly disabled>
                                        <input type="hidden" name="customer_email" value="{{ $user->email ?? '' }}">
                                        {{-- Input hidden untuk mengirim email --}}
                                        <small class="text-muted">Email akan digunakan untuk konfirmasi pesanan.</small>
                                        @error('customer_email')
                                            <div class="invalid-feedback d-block">{{$message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="customer_phone" class="form-label">Nomor Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="tel"
                                            class="form-control @error('customer_phone') is-invalid @enderror"
                                            id="customer_phone" name="customer_phone"
                                            value="{{ old('customer_phone', $user->phone_number ?? '') }}"
                                            placeholder="Contoh: 081234567890" required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{$message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="shipping_address" class="form-label">Alamat Pengiriman Lengkap <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address"
                                            name="shipping_address" rows="3"
                                            placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota/Kab, Provinsi, Kode Pos" required>{{ old('shipping_address') }}</textarea>
                                        @error('shipping_address')
                                            <div class="invalid-feedback">{{$message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2"
                                            placeholder="Contoh: Tidak pakai es, gula sedikit.">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{$message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-3 fw-semibold">Pilih Metode Pembayaran</h4>
                        <div class="card shadow-sm">
                            <div class="card-body p-0"> {{-- p-0 agar list-group rapat ke card --}}
                                <div class="list-group list-group-flush payment-methods">
                                    <label class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                        for="cod">
                                        <input class="form-check-input flex-shrink-0 mt-1" type="radio"
                                            name="payment_method" id="cod" value="COD"
                                            {{ old('payment_method', 'COD') == 'COD' ? 'checked' : '' }} required>
                                        <span class="form-checked-content">
                                            <strong class="d-block"><i class="bi bi-cash-coin me-2"></i>Bayar di Tempat
                                                (COD)</strong>
                                            <small class="text-muted">Bayar tunai saat pesanan Anda tiba.</small>
                                        </span>
                                    </label>
                                    <label class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                        for="qrcode">
                                        <input class="form-check-input flex-shrink-0 mt-1" type="radio"
                                            name="payment_method" id="qrcode" value="QRCODE"
                                            {{ old('payment_method') == 'QRCODE' ? 'checked' : '' }} required>
                                        <span class="form-checked-content">
                                            <strong class="d-block"><i class="bi bi-qr-code-scan me-2"></i>QR
                                                Code</strong>
                                            <small class="text-muted">Pindai QR Code untuk melakukan pembayaran.</small>
                                        </span>
                                    </label>
                                    <label class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                        for="va">
                                        <input class="form-check-input flex-shrink-0 mt-1" type="radio"
                                            name="payment_method" id="va" value="VIRTUAL_ACCOUNT"
                                            {{ old('payment_method') == 'VIRTUAL_ACCOUNT' ? 'checked' : '' }} required>
                                        <span class="form-checked-content">
                                            <strong class="d-block"><i
                                                    class="bi bi-credit-card-2-front-fill me-2"></i>Virtual
                                                Account</strong>
                                            <small class="text-muted">Bayar ke nomor Virtual Account bank pilihan.</small>
                                        </span>
                                    </label>
                                    <div id="va_options_details" class="ps-5 py-2 bg-light border-top"
                                        style="display: {{ old('payment_method') == 'VIRTUAL_ACCOUNT' ? 'block' : 'none' }};">
                                        <div class="form-check mb-2"><input
                                                class="form-check-input @error('virtual_account_bank') is-invalid @enderror"
                                                type="radio" name="virtual_account_bank" id="va_bca" value="BCA"
                                                {{ old('virtual_account_bank') == 'BCA' ? 'checked' : '' }}><label
                                                class="form-check-label" for="va_bca">BCA Virtual Account</label></div>
                                        <div class="form-check"><input
                                                class="form-check-input @error('virtual_account_bank') is-invalid @enderror"
                                                type="radio" name="virtual_account_bank" id="va_mandiri"
                                                value="Mandiri"
                                                {{ old('virtual_account_bank') == 'Mandiri' ? 'checked' : '' }}><label
                                                class="form-check-label" for="va_mandiri">Mandiri Virtual Account</label>
                                        </div>
                                        @error('virtual_account_bank')
                                            <div class="invalid-feedback d-block">{{$message }}</div>
                                        @enderror
                                    </div>
                                    <label class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                        for="ewallet_dana">
                                        <input class="form-check-input flex-shrink-0 mt-1" type="radio"
                                            name="payment_method" id="ewallet_dana" value="EWALLET_DANA"
                                            {{ old('payment_method') == 'EWALLET_DANA' ? 'checked' : '' }} required>
                                        <span class="form-checked-content">
                                            <strong class="d-block"><i class="bi bi-phone-fill me-2"></i>E-Wallet
                                                (DANA)</strong>
                                            <small class="text-muted">Bayar menggunakan DANA ke nomor yang tertera.</small>
                                        </span>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <div class="text-danger small mt-2 p-3 border-top border-danger-subtle">
                                        {{$message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Ringkasan Pesanan & Detail Pembayaran Dinamis --}}
                    <div class="col-lg-5">
                        <h4 class="d-flex justify-content-between align-items-center mb-3 fw-semibold">
                            <span class="text-primary">Ringkasan Pesanan</span>
                            <span class="badge bg-primary rounded-pill">{{ is_array($cartItems) ? count($cartItems) : 0 }}
                                Item</span>
                        </h4>
                        <div class="card shadow-sm mb-4">
                            <ul class="list-group list-group-flush">
                                @php $calculatedTotalPrice = 0; @endphp
                                @if (is_array($cartItems))
                                    @foreach ($cartItems as $item)
                                        @php
                                            $itemSubtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                                            $calculatedTotalPrice += $itemSubtotal;
                                        @endphp
                                        <li class="list-group-item d-flex justify-content-between lh-sm">
                                            <div>
                                                <h6 class="my-0">{{ $item['name'] ?? 'N/A' }} <small
                                                        class="text-muted">x {{ $item['quantity'] ?? 0 }}</small></h6>
                                                <small class="text-muted">{{ $item['size'] ?? '' }}</small>
                                            </div>
                                            <span class="text-muted">Rp
                                                {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                @endif
                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <span class="fw-bold">Total Pembayaran</span>
                                    <strong class="fs-5 text-primary">Rp
                                        {{ number_format($calculatedTotalPrice, 0, ',', '.') }}</strong>
                                </li>
                            </ul>
                        </div>

                        {{-- Detail Pembayaran Dinamis --}}
                        <div id="payment_details_qr" class="text-center mb-3" style="display: none;">
                            <h5 class="fw-semibold mb-2">Pindai untuk Bayar:</h5>
                            <img src="{{ asset('images/qr-code-simulasi.png') }}" alt="QR Code Pembayaran"
                                class="img-fluid mb-2 rounded"
                                style="max-width: 180px; border: 1px solid #dee2e6; padding: 4px;">
                            <p class="text-muted small">Setelah pembayaran, klik "Buat Pesanan".</p>
                        </div>
                        <div id="payment_details_va" class="mb-3" style="display: none;">
                            <h5 class="fw-semibold mb-2">Detail Virtual Account:</h5>
                            <div class="alert alert-info py-2 px-3">
                                <span id="va_bank_name_display" class="fw-medium"></span> Virtual Account:<br>
                                <strong class="fs-4" id="va_number_display"></strong><br>
                                Total: <strong class="fs-5">Rp
                                    {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</strong>
                                <p class="small mt-1 mb-0">Lakukan transfer ke nomor VA di atas.</p>
                            </div>
                        </div>
                        <div id="payment_details_ewallet_dana" class="mb-3" style="display: none;">
                            <h5 class="fw-semibold mb-2">Detail Pembayaran DANA:</h5>
                            <div class="alert alert-info py-2 px-3">
                                Transfer ke nomor DANA:<br>
                                <strong class="fs-4">083101461069</strong><br>
                                <small>(a.n. Nama Toko Anda)</small><br> {{-- SESUAIKAN --}}
                                Total: <strong class="fs-5">Rp
                                    {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</strong>
                                <p class="small mt-1 mb-0">Konfirmasi setelah pembayaran.</p>
                            </div>
                        </div>

                        <button class="w-100 btn btn-primary btn-lg shadow" type="submit" id="placeOrderButton">
                            <i class="bi bi-shield-check-fill"></i> Buat Pesanan Sekarang
                        </button>
                    </div>
                </div>
            </form>
        @elseif(!session('success_with_qr') && !session('success_with_va') && !session('success_with_ewallet'))
            <div class="alert alert-warning text-center shadow-sm">
                <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
                Keranjang belanja Anda kosong. Silakan <a href="{{ route('products.index.public_list') }}"
                    class="alert-link fw-semibold">pilih produk</a> terlebih dahulu.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Blok script untuk menyembunyikan form jika ada pesan sukses khusus (QR/VA/Ewallet)
            @if (session('success_with_qr') || session('success_with_va') || session('success_with_ewallet'))
                const checkoutForm = document.getElementById('checkoutForm');
                if (checkoutForm) {
                    const formElementsToHide = checkoutForm.querySelectorAll(
                    '.row.g-4 > div'); // Targetkan kolom utama form
                    formElementsToHide.forEach(element => {
                        element.style.display = 'none';
                    });
                    const mainTitleDiv = document.querySelector('.display-5.fw-bold')
                    .parentElement; // Targetkan div pembungkus judul
                    if (mainTitleDiv && mainTitleDiv.textContent.includes("Checkout Pesanan Anda")) {
                        mainTitleDiv.style.display = 'none';
                    }
                }
            @endif

            // Logika untuk menampilkan detail pembayaran dinamis
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const vaOptionsDetailsDiv = document.getElementById('va_options_details');
            const vaBankRadios = document.querySelectorAll('input[name="virtual_account_bank"]');

            const paymentDetailsQrDiv = document.getElementById('payment_details_qr');
            const paymentDetailsVaDiv = document.getElementById('payment_details_va');
            const paymentDetailsEwalletDanaDiv = document.getElementById('payment_details_ewallet_dana');
            const vaBankNameSpan = document.getElementById('va_bank_name_display'); // ID diubah agar tidak konflik
            const vaNumberSpan = document.getElementById('va_number_display'); // ID diubah

            function hideAllPaymentDetails() {
                if (paymentDetailsQrDiv) paymentDetailsQrDiv.style.display = 'none';
                if (paymentDetailsVaDiv) paymentDetailsVaDiv.style.display = 'none';
                if (paymentDetailsEwalletDanaDiv) paymentDetailsEwalletDanaDiv.style.display = 'none';
                if (vaOptionsDetailsDiv) vaOptionsDetailsDiv.style.display = 'none';
            }

            function showPaymentDetails() {
                hideAllPaymentDetails();
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

                if (selectedPaymentMethod) {
                    if (selectedPaymentMethod.value === 'QRCODE') {
                        if (paymentDetailsQrDiv) paymentDetailsQrDiv.style.display = 'block';
                    } else if (selectedPaymentMethod.value === 'VIRTUAL_ACCOUNT') {
                        if (vaOptionsDetailsDiv) vaOptionsDetailsDiv.style.display = 'block';
                        const selectedVaBank = document.querySelector('input[name="virtual_account_bank"]:checked');
                        if (selectedVaBank) {
                            if (vaBankNameSpan) vaBankNameSpan.textContent = selectedVaBank.value;
                            if (vaNumberSpan) vaNumberSpan.textContent = (selectedVaBank.value === 'BCA' ?
                                '88081234567890' : '99091234567890'); // Nomor VA Simulasi
                            if (paymentDetailsVaDiv) paymentDetailsVaDiv.style.display = 'block';
                        } else {
                            if (paymentDetailsVaDiv) paymentDetailsVaDiv.style.display =
                            'none'; // Sembunyikan jika bank belum dipilih
                        }
                    } else if (selectedPaymentMethod.value === 'EWALLET_DANA') {
                        if (paymentDetailsEwalletDanaDiv) paymentDetailsEwalletDanaDiv.style.display = 'block';
                    }
                }
            }

            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', showPaymentDetails);
            });

            vaBankRadios.forEach(radio => {
                radio.addEventListener('change', showPaymentDetails);
            });

            // Panggil saat load untuk menampilkan detail jika sudah ada old input atau pilihan default
            // atau jika ada error validasi dan user sudah memilih metode sebelumnya
            showPaymentDetails();
        });
    </script>
@endpush
