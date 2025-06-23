@extends('layouts.admin_app')

@section('title', 'Log Semua Aktivitas')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Log Semua Aktivitas Sistem</h1>
        {{-- Tombol kembali ke Dashboard --}}
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Tambahkan form filter jika diperlukan di masa depan --}}
    {{-- <div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activities.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="log_name_filter" class="form-label form-label-sm">Filter Tipe Log:</label>
                    <select name="log_name" id="log_name_filter" class="form-select form-select-sm">
                        <option value="">Semua Tipe</option>
                        <option value="Pesanan" {{ request('log_name') == 'Pesanan' ? 'selected' : '' }}>Pesanan</option>
                        <option value="Kontak" {{ request('log_name') == 'Kontak' ? 'selected' : '' }}>Kontak</option>
                        <option value="Pengguna" {{ request('log_name') == 'Pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="Produk" {{ request('log_name') == 'Produk' ? 'selected' : '' }}>Produk</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="date_filter" class="form-label form-label-sm">Filter Tanggal:</label>
                    <input type="date" name="date" id="date_filter" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div> --}}


    @if (isset($activities) && $activities->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-light-subtle">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Log Aktivitas</span>
                    <span class="text-muted small">Menampilkan {{ $activities->firstItem() }} - {{ $activities->lastItem() }}
                        dari {{ $activities->total() }} entri</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 40%;">Deskripsi Aktivitas</th>
                            <th style="width: 15%;">Tipe Log</th>
                            <th style="width: 20%;">Dilakukan Oleh</th>
                            <th style="width: 20%;">Waktu</th>
                            {{-- <th style="width: 10%;" class="text-center">Detail</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $key => $activity)
                            <tr>
                                <td>{{ $activities->firstItem() + $key }}</td>
                                <td>
                                    @php
                                        $link = null;
                                        if ($activity->subject_type && $activity->subject_id && $activity->subject) {
                                            if ($activity->subject_type === \App\Models\Order::class) {
                                                $link = route('admin.orders.show', $activity->subject_id);
                                            } elseif ($activity->subject_type === \App\Models\ContactMessage::class) {
                                                $link = route('admin.contacts.show', $activity->subject_id);
                                            } elseif ($activity->subject_type === \App\Models\User::class) {
                                                $link = route('admin.users.edit', $activity->subject_id);
                                            } elseif ($activity->subject_type === \App\Models\Product::class) {
                                                $link = route('admin.products.show', $activity->subject_id);
                                            }
                                        }
                                    @endphp
                                    {{ $activity->description }}
                                    @if ($link)
                                        <a href="{{ $link }}" class="small text-info ms-1"
                                            title="Lihat detail subjek terkait"><i class="bi bi-link-45deg"></i></a>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $activity->log_name ?? 'Default' }}</span></td>
                                <td>
                                    @if ($activity->causer)
                                        {{ $activity->causer->name }} <span
                                            class="text-muted">({{ ucfirst($activity->causer->role) }})</span>
                                    @else
                                        <span class="text-muted fst-italic">Sistem / Tamu</span>
                                    @endif
                                </td>
                                <td title="{{ $activity->created_at->format('d M Y, H:i:s') }}">
                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                                {{-- <td class="text-center">
                        <button class="btn btn-outline-secondary btn-sm py-0 px-1" data-bs-toggle="modal" data-bs-target="#activityPropertiesModal-{{$activity->id}}" title="Lihat Properti">
                            <i class="bi bi-info-circle"></i>
                        </button>
                        <!-- Modal untuk Properti (contoh) -->
                        <div class="modal fade" id="activityPropertiesModal-{{$activity->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Properti Aktivitas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body text-start"><pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre></div>
                                </div>
                            </div>
                        </div>
                    </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($activities->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center pt-2">
                        {{ $activities->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info text-center">Tidak ada log aktivitas yang tercatat.</div>
    @endif
@endsection
@push('styles')
    <style>
        .table th,
        .table td {
            font-size: 0.875rem;
            /* Ukuran font tabel lebih kecil */
        }

        .table-responsive {
            max-height: 70vh;
            /* Batasi tinggi tabel agar bisa di-scroll jika banyak data */
        }
    </style>
@endpush
