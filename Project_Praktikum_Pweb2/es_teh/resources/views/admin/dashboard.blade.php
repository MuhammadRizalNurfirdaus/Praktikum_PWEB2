@extends('layouts.admin_app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Ringkasan</h1>
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="row g-4">
        {{-- Card Total Produk --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-primary h-100">
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">TOTAL PRODUK</h5>
                                <h3 class="mb-0 fw-bold">{{ $totalProducts ?? 0 }}</h3>
                            </div>
                            <div class="card-icon text-white-50"><i class="bi bi-cup-straw" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="card-footer text-white bg-primary bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto"><span>Lihat
                            Detail</span><i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        {{-- Card Pesanan Baru --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-success h-100">
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">PESANAN BARU (Pending)</h5>
                                <h3 class="mb-0 fw-bold">{{ $newOrdersCount ?? 0 }}</h3>
                            </div>
                            <div class="card-icon text-white-50"><i class="bi bi-cart-check-fill"
                                    style="font-size: 2.5rem;"></i></div>
                        </div>
                    </div>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                        class="card-footer text-white bg-success bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto"><span>Lihat
                            Detail</span><i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        {{-- Card Pengguna Terdaftar --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-info h-100">
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">PENGGUNA TERDAFTAR</h5>
                                <h3 class="mb-0 fw-bold">{{ $totalUsers ?? 0 }}</h3>
                            </div>
                            <div class="card-icon text-white-50"><i class="bi bi-people-fill"
                                    style="font-size: 2.5rem;"></i></div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}"
                        class="card-footer text-white bg-info bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto"><span>Lihat
                            Detail</span><i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        {{-- Card Total Pendapatan --}}
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card text-bg-warning h-100">
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">TOTAL PENDAPATAN (Selesai)</h5>
                                <h3 class="mb-0 fw-bold">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                            </div>
                            <div class="card-icon text-white-50"><i class="bi bi-cash-coin" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
                        class="card-footer text-white bg-warning bg-opacity-75 text-decoration-none d-flex justify-content-between align-items-center mt-auto"><span>Lihat
                            Detail</span><i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris untuk Aktivitas Terkini dan Tautan Cepat --}}
    <div class="row mt-4 g-4">
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card h-100 shadow-sm">
                <div class="card-header fw-semibold">
                    Aktivitas Terkini
                </div>
                <div class="card-body" style="min-height: 250px; max-height: 400px; overflow-y: auto;">
                    @if (isset($recentActivities) && $recentActivities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($recentActivities as $activity)
                                <li class="list-group-item d-flex justify-content-between align-items-start px-0 py-2">
                                    <div class="w-100">
                                        @php
                                            $icon = 'bi-bell-fill text-secondary';
                                            $link = null;
                                            $subjectText = '';
                                            if (
                                                $activity->subject_type &&
                                                $activity->subject_id &&
                                                $activity->subject
                                            ) {
                                                $subjectModel = $activity->subject;
                                                if ($activity->subject_type === \App\Models\Order::class) {
                                                    $icon = 'bi-receipt-cutoff text-success';
                                                    $link = route('admin.orders.show', $activity->subject_id);
                                                } elseif (
                                                    $activity->subject_type === \App\Models\ContactMessage::class
                                                ) {
                                                    $icon = 'bi-envelope-paper-fill text-info';
                                                    $link = route('admin.contacts.show', $activity->subject_id);
                                                } elseif ($activity->subject_type === \App\Models\User::class) {
                                                    $icon = 'bi-person-plus-fill text-primary';
                                                    $link = route('admin.users.edit', $activity->subject_id);
                                                } elseif ($activity->subject_type === \App\Models\Product::class) {
                                                    $icon = 'bi-cup-straw text-purple';
                                                    $link = route('admin.products.show', $activity->subject_id);
                                                }
                                            }
                                        @endphp
                                        <i class="bi {{ $icon }} me-2"></i>
                                        <span>{{ $activity->description }}</span>
                                        @if ($link)
                                            <a href="{{ $link }}"
                                                class="fw-medium text-decoration-none small ms-1">(Lihat Detail)</a>
                                        @endif
                                        @if ($activity->causer)
                                            <small class="d-block text-muted fst-italic" style="font-size: 0.8em;">
                                                oleh: {{ $activity->causer->name }}
                                                ({{ ucfirst($activity->causer->role) }})
                                            </small>
                                        @endif
                                    </div>
                                    <small class="text-muted ms-2 text-nowrap flex-shrink-0"
                                        title="{{ $activity->created_at->format('d M Y H:i:s') }}">
                                        {{ $activity->created_at->diffForHumans(null, false, true, 1) }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mt-3 text-center">Belum ada aktivitas terkini yang signifikan.</p>
                    @endif
                </div>
                {{-- AWAL PERBAIKAN: Komentari atau hapus link ke route admin.activities --}}
                @if (isset($recentActivities) && $recentActivities->count() >= 5)
                    {{-- Atau batas take() di controller --}}
                    <div class="card-footer text-center bg-light py-2">
                        {{-- PERBAIKI LINK INI --}}
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-outline-secondary">Lihat
                            Semua Aktivitas</a>
                        {{-- <small class="text-muted d-block mt-1">Menampilkan beberapa aktivitas terbaru.</small> --}}
                    </div>
                @endif
                {{-- AKHIR PERBAIKAN --}}
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100 shadow-sm">
                <div class="card-header fw-semibold">
                    Tautan Cepat
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action"> <i
                            class="bi bi-plus-circle-fill me-2 text-success"></i> Tambah Produk Baru </a>
                    <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action"> <i
                            class="bi bi-receipt-cutoff me-2 text-primary"></i> Lihat Semua Pesanan </a>
                    <a href="{{ route('admin.contacts.index', ['status' => 'Baru']) }}"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-chat-left-dots-fill me-2 text-info"></i> Pesan Kontak Baru</span>
                        @if (isset($newContactMessagesCount) && $newContactMessagesCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $newContactMessagesCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action"> <i
                            class="bi bi-people-fill me-2 text-secondary"></i> Kelola Pengguna </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    {{-- ... (CSS Anda yang sudah ada) ... --}}
    <style>
        .dashboard-card .card-footer {
            font-size: 0.9rem;
            background-color: rgba(0, 0, 0, 0.1) !important;
        }

        .list-group-item i.bi {
            width: 1.5em;
            text-align: center;
        }

        .text-purple {
            color: purple;
        }
    </style>
@endpush
