@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                @include('user.partials.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Riwayat Pesanan Saya</h2>
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-cart-plus"></i> Lanjut Belanja
                    </a>
                </div>

                @if (isset($orders) && $orders->count() > 0)
                    <div class="list-group shadow-sm">
                        @foreach ($orders as $order)
                            {{-- AWAL DARI ITEM PESANAN --}}
                            <div
                                class="list-group-item list-group-item-action flex-column align-items-start p-3 mb-2 rounded-3 border">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 fw-bold">
                                        <a href="{{ route('user.orders.show', $order->id) }}"
                                            class="text-decoration-none text-primary">{{ $order->order_number }}</a>
                                    </h5>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                </div>
                                <p class="mb-1">Total Pesanan: <span class="fw-semibold">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <small class="me-3">Status:
                                            <span
                                                class="badge rounded-pill
                                            @switch($order->status)
                                                @case('pending') bg-secondary @break
                                                @case('menunggu_pembayaran_qr') bg-info text-dark @break
                                                @case('menunggu_pembayaran_va') bg-info text-dark @break
                                                @case('menunggu_pembayaran_ewallet') bg-info text-dark @break
                                                @case('processing') bg-primary @break
                                                @case('shipped') bg-primary @break
                                                @case('delivered') bg-success @break
                                                @case('paid') bg-success @break
                                                @case('cancelled') bg-danger @break
                                                @case('failed') bg-danger @break
                                                @default bg-light text-dark @break
                                            @endswitch">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </small>
                                    </div>
                                    <div class="order-actions">
                                        <a href="{{ route('user.orders.show', $order->id) }}"
                                            class="btn btn-outline-info btn-sm py-1">Lihat Detail</a>
                                        @if ($order->status === 'delivered' && !$order->feedback_submitted_at)
                                            <button type="button" class="btn btn-primary btn-sm py-1"
                                                data-bs-toggle="modal" data-bs-target="#feedbackModal-{{ $order->id }}">
                                                <i class="bi bi-star-fill"></i> Beri Ulasan
                                            </button>
                                        @elseif($order->feedback_submitted_at)
                                            <button type="button" class="btn btn-outline-success btn-sm py-1 disabled"><i
                                                    class="bi bi-check-circle"></i> Ulasan Terkirim</button>
                                        @endif
                                        @if (in_array($order->status, ['delivered', 'cancelled', 'failed']))
                                            <form action="{{ route('user.orders.archive', $order->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Anda yakin ingin menghapus riwayat pesanan ini dari daftar Anda?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm py-1"
                                                    title="Hapus Riwayat"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- AKHIR DARI ITEM PESANAN --}}

                            {{-- MODAL FEEDBACK (Tetap di dalam loop @foreach) --}}
                            @if ($order->status === 'delivered' && !$order->feedback_submitted_at)
                                <div class="modal fade" id="feedbackModal-{{ $order->id }}" tabindex="-1"
                                    aria-labelledby="feedbackModalLabel-{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('user.orders.feedback.store', $order->id) }}" method="POST"
                                            id="feedbackForm-{{ $order->id }}"
                                            onsubmit="return validateFeedbackForm(this);">
                                            @csrf
                                            <div class="modal-content rounded-3 shadow">
                                                <div class="modal-header border-bottom-0">
                                                    <h5 class="modal-title fw-bold"
                                                        id="feedbackModalLabel-{{ $order->id }}">Beri Ulasan untuk
                                                        #{{ $order->order_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body py-0">
                                                    <p class="text-muted small">Bagaimana pengalaman Anda? Ulasan Anda
                                                        sangat berarti bagi kami.</p>

                                                    <div class="mb-3 text-center">
                                                        <label class="form-label d-block">Rating Produk: <span
                                                                class="text-danger">*</span></label>
                                                        <div class="rating-stars">
                                                            <i class="bi bi-star-fill star" data-value="1"></i><i
                                                                class="bi bi-star-fill star" data-value="2"></i><i
                                                                class="bi bi-star-fill star" data-value="3"></i><i
                                                                class="bi bi-star-fill star" data-value="4"></i><i
                                                                class="bi bi-star-fill star" data-value="5"></i>
                                                        </div>
                                                        <input type="hidden" name="product_rating" class="rating-value"
                                                            value="{{ old('product_rating', 0) }}" required>
                                                        <div class="rating-error text-danger small mt-1"
                                                            style="display: none;">Rating produk harus diisi.</div>
                                                    </div>

                                                    <div class="mb-3 text-center">
                                                        <label class="form-label d-block">Rating Kurir: <span
                                                                class="text-danger">*</span></label>
                                                        <div class="rating-stars">
                                                            <i class="bi bi-star-fill star" data-value="1"></i><i
                                                                class="bi bi-star-fill star" data-value="2"></i><i
                                                                class="bi bi-star-fill star" data-value="3"></i><i
                                                                class="bi bi-star-fill star" data-value="4"></i><i
                                                                class="bi bi-star-fill star" data-value="5"></i>
                                                        </div>
                                                        <input type="hidden" name="kurir_rating" class="rating-value"
                                                            value="{{ old('kurir_rating', 0) }}" required>
                                                        <div class="rating-error text-danger small mt-1"
                                                            style="display: none;">Rating kurir harus diisi.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="feedback-{{ $order->id }}"
                                                            class="form-label">Tulis Komentar (Opsional):</label>
                                                        <textarea name="feedback" id="feedback-{{ $order->id }}" class="form-control" rows="4"
                                                            placeholder="Ceritakan lebih detail tentang pengalaman Anda...">{{ old('feedback') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer flex-nowrap p-0">
                                                    <button type="button"
                                                        class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"
                                                        data-bs-dismiss="modal"><strong>Batal</strong></button>
                                                    <button type="submit"
                                                        class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 text-primary"><strong>Kirim
                                                            Ulasan</strong></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if ($orders->hasPages())
                        <div class="mt-4 d-flex justify-content-center">{{ $orders->links() }}</div>
                    @endif
                @else
                    <div class="card shadow-sm text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-journal-richtext fs-1 text-muted mb-3"></i>
                            <h4 class="card-title">Anda Belum Memiliki Riwayat Pesanan</h4>
                            <p class="card-text text-muted">Semua pesanan yang Anda buat akan muncul di sini.</p>
                            <a href="{{ route('products.index.public_list') }}" class="btn btn-primary mt-2"><i
                                    class="bi bi-cup-straw"></i> Mulai Belanja</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* CSS UNTUK RATING BINTANG -- SANGAT SEDERHANA, KONTROL DI JS */
        .rating-stars {
            display: inline-flex;
            font-size: 2.5rem;
        }

        .rating-stars i.star {
            color: #e4e5e9;
            /* Warna bintang default (abu-abu) */
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
            padding: 0 0.1em;
        }

        .rating-stars i.star:hover {
            transform: scale(1.15);
            /* Efek hover sederhana */
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Rating script loaded. Attaching click listener to document.");

            // Gunakan event delegation pada document untuk menangani semua klik
            document.addEventListener('click', function(event) {
                // Cek jika yang diklik adalah elemen bintang di dalam container rating
                if (event.target.matches('.rating-stars .star')) {
                    const clickedStar = event.target;
                    const starsContainer = clickedStar.parentElement;
                    const ratingInput = starsContainer
                    .nextElementSibling; // Mengambil input hidden setelahnya
                    const allStars = starsContainer.querySelectorAll('.star');
                    const clickedValue = parseInt(clickedStar.dataset.value);

                    // Set nilai input hidden
                    ratingInput.value = clickedValue;
                    console.log(`Rating set to: ${clickedValue} for input [name=${ratingInput.name}]`);

                    // Update warna semua bintang di container yang sama
                    allStars.forEach(star => {
                        const starValue = parseInt(star.dataset.value);
                        if (starValue <= clickedValue) {
                            star.style.color = '#ffc107'; // Warna kuning
                        } else {
                            star.style.color = '#e4e5e9'; // Warna abu-abu
                        }
                    });

                    // Sembunyikan pesan error jika ada
                    const errorDiv = ratingInput.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('rating-error')) {
                        errorDiv.style.display = 'none';
                    }
                }
            });

            // Validasi frontend sederhana
            window.validateFeedbackForm = function(form) {
                let isValid = true;
                form.querySelectorAll('.rating-value').forEach(input => {
                    const errorDiv = input.nextElementSibling;
                    if (!input.value || input.value == "0") {
                        if (errorDiv) errorDiv.style.display = 'block';
                        isValid = false;
                    } else {
                        if (errorDiv) errorDiv.style.display = 'none';
                    }
                });
                if (!isValid) {
                    alert('Harap berikan rating untuk Produk dan Kurir.');
                }
                return isValid;
            }
        });
    </script>
@endpush
