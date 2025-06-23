@extends('layouts.app')

@section('title', 'Pesanan Berhasil Dibuat!')

@section('content')
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($successMessage)
                    <div class="alert alert-success shadow-sm p-4 rounded-3">
                        <i class="bi bi-check-circle-fill fs-1 text-success mb-3 d-block"></i>
                        <h2 class="alert-heading fw-bold">Pesanan Berhasil!</h2>
                        <p class="fs-5">{!! $successMessage !!}</p> {{-- Menggunakan {!! !!} karena pesan mungkin mengandung HTML (<strong>) --}}
                        <hr>
                        <p class="mb-0">
                            Anda dapat melihat detail pesanan Anda di halaman
                            <a href="{{ route('user.orders.show', $order->id) }}" class="alert-link fw-semibold">Riwayat
                                Pesanan Saya</a>.
                        </p>
                    </div>
                @else
                    {{-- Fallback jika tidak ada pesan sukses spesifik --}}
                    <div class="alert alert-warning">
                        Tidak ada detail pesanan untuk ditampilkan.
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('products.index.public_list') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left-circle"></i> Lanjut Belanja
                    </a>
                    @if (isset($order))
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg ms-2">
                            <i class="bi bi-speedometer2"></i> Ke Dashboard Saya
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
