@extends('layouts.admin_app') {{-- Menggunakan layout admin sebagai dasar --}}

@section('title', 'Kurir Dashboard - Es Teh Poci')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Kurir Dashboard</h1>
    </div>

    <div class="row g-4">
        {{-- Card Pengiriman Aktif --}}
        <div class="col-md-6 col-xl-4"> {{-- Anda bisa sesuaikan col-xl- jika ingin 2 kartu per baris di layar besar --}}
            <div class="card dashboard-card text-bg-info h-100"> {{-- Tambahkan h-100 --}}
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">PENGIRIMAN AKTIF</h5>
                                <h3 class="mb-0 fw-bold">
                                    {{ $activeShipmentsCount ?? 0 }}
                                </h3>
                            </div>
                            <div class="card-icon text-white-50">
                                <i class="bi bi-truck" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('kurir.orders.index', ['status' => 'shipped']) }}"
                        class="card-footer text-white bg-info bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto">
                        <span>Lihat Daftar Pengiriman</span>
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card Pengiriman Selesai Hari Ini --}}
        <div class="col-md-6 col-xl-4">
            <div class="card dashboard-card text-bg-success h-100"> {{-- Tambahkan h-100 --}}
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">PENGIRIMAN SELESAI (HARI INI)</h5>
                                <h3 class="mb-0 fw-bold">
                                    {{ $completedTodayCount ?? 0 }}
                                </h3>
                            </div>
                            <div class="card-icon text-white-50">
                                <i class="bi bi-check2-circle" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('kurir.orders.index', ['status' => 'delivered', 'date_filter' => 'today']) }}"
                        class="card-footer text-white bg-success bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto">
                        <span>Lihat Riwayat Hari Ini</span>
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>
        {{-- Tambahkan card lain jika perlu, misal total pengiriman bulan ini, dll. --}}
    </div>

    <div class="mt-5">
        <h4 class="mb-3">Tugas Pengiriman Anda:</h4>
        <p class="lead">Silakan cek menu <a href="{{ route('kurir.orders.index') }}"
                class="fw-semibold text-decoration-none">Daftar Pengiriman</a> untuk melihat detail pesanan yang perlu
            diantar dan mengubah statusnya.</p>
        {{-- Di sini Anda bisa menambahkan daftar singkat beberapa pesanan berikutnya yang paling mendesak --}}
        {{-- Contoh:
    <div class="card shadow-sm mt-3">
        <div class="card-header">Pengiriman Berikutnya</div>
        <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action">Pesanan #INV-XXXXX - Status: Shipped</a>
            <a href="#" class="list-group-item list-group-item-action">Pesanan #INV-YYYYY - Status: Processing</a>
        </div>
    </div>
    --}}
    </div>
@endsection

@push('styles')
    <style>
        .dashboard-card .card-footer {
            font-size: 0.9rem;
            background-color: rgba(0, 0, 0, 0.1) !important;
        }

        /* Memastikan kartu dalam satu baris memiliki tinggi yang sama jika menggunakan h-100 */
        .row.g-4>.col-md-6,
        .row.g-4>.col-xl-4 {
            display: flex;
            /* Membuat kolom flex */
            align-items: stretch;
            /* Membuat item (kartu) meregang mengisi tinggi kolom */
        }

        .card.h-100 {
            display: flex;
            flex-direction: column;
            /* Konten kartu juga flex kolom */
        }

        .card.h-100 .card-body {
            flex-grow: 1;
            /* Body kartu mengisi sisa ruang */
        }
    </style>
@endpush
