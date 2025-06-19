@extends('layouts.admin_app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Ringkasan</h1>
    </div>

    <div class="row g-4">
        {{-- Card untuk Total Produk --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-primary h-100"> {{-- Tambahkan h-100 --}}
                <div class="card-body d-flex flex-column"> {{-- Tambahkan d-flex flex-column --}}
                    <div class="flex-grow-1"> {{-- Wrapper untuk konten agar footer bisa di bawah --}}
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">TOTAL PRODUK</h5>
                                <h3 class="mb-0 fw-bold">
                                    {{ \App\Models\Product::count() }} {{-- Data dinamis --}}
                                </h3>
                            </div>
                            <div class="card-icon text-white-50">
                                <i class="bi bi-cup-straw" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="card-footer text-white bg-primary bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card untuk Pesanan --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-success h-100"> {{-- Tambahkan h-100 --}}
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">PESANAN BARU</h5>
                                <h3 class="mb-0 fw-bold">0</h3> {{-- Ganti dengan data dinamis nanti --}}
                            </div>
                            <div class="card-icon text-white-50">
                                <i class="bi bi-cart-check-fill" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="card-footer text-white bg-success bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card untuk Pengguna --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-info h-100"> {{-- Tambahkan h-100 --}}
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">PENGGUNA TERDAFTAR</h5>
                                <h3 class="mb-0 fw-bold">
                                    {{ \App\Models\User::count() }} {{-- Data dinamis --}}
                                </h3>
                            </div>
                            <div class="card-icon text-white-50">
                                <i class="bi bi-people-fill" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="card-footer text-white bg-info bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card untuk Pendapatan --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-warning h-100"> {{-- Tambahkan h-100 --}}
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">TOTAL PENDAPATAN</h5>
                                <h3 class="mb-0 fw-bold">Rp 0</h3> {{-- Ganti dengan data dinamis nanti --}}
                            </div>
                            <div class="card-icon text-white-50">
                                <i class="bi bi-cash-coin" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="card-footer text-white bg-warning bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto">
                        <span>Lihat Detail</span>
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisa Konten Dashboard --}}
    <div class="row mt-4">
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header fw-semibold">
                    Aktivitas Terkini (Contoh)
                </div>
                <div class="card-body" style="min-height: 200px;">
                    <p class="text-muted">Belum ada aktivitas terkini.</p>
                    {{-- Nanti diisi dengan log aktivitas atau data dinamis --}}
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header fw-semibold">
                    Tautan Cepat (Contoh)
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action"><i
                            class="bi bi-plus-circle me-2"></i> Tambah Produk Baru</a>
                    <a href="#" class="list-group-item list-group-item-action disabled text-muted"><i
                            class="bi bi-tag me-2"></i> Tambah Kategori (Contoh)</a>
                    <a href="#" class="list-group-item list-group-item-action disabled text-muted"><i
                            class="bi bi-people me-2"></i> Kelola Pengguna (Contoh)</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Jika ada script JS khusus untuk halaman dashboard --}}
@endpush
