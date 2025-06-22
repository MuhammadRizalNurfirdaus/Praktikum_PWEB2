@extends('layouts.admin_app')
@section('title', 'Kelola Pesan Kontak')
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pesan Masuk & Keluhan</h1>
    </div>

    {{-- Form Filter & Pencarian --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5"><label for="search_msg" class="form-label form-label-sm">Cari Pesan</label><input
                        type="text" name="search" id="search_msg" class="form-control form-control-sm"
                        placeholder="Subjek, Nama, atau Email..." value="{{ $searchQuery ?? '' }}"></div>
                <div class="col-md-4"><label for="status_filter_msg" class="form-label form-label-sm">Filter
                        Status</label><select name="status" id="status_filter_msg" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach ($messageStatuses as $status)
                            <option value="{{ $status }}" {{ ($statusFilter ?? '') == $status ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end"><button class="btn btn-primary btn-sm w-100 me-1"
                        type="submit"><i class="bi bi-funnel-fill"></i> Filter</button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm w-auto"
                            title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($messages->count() > 0)
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Subjek</th>
                            <th>Dari</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $key => $message)
                            <tr class="{{ $message->status == 'Baru' ? 'fw-bold' : '' }}">
                                <td>{{ $messages->firstItem() + $key }}</td>
                                <td><a
                                        href="{{ route('admin.contacts.show', $message->id) }}">{{ Str::limit($message->subject, 40) }}</a>
                                </td>
                                <td>{{ $message->name }} @if ($message->user_id)
                                        <span class="badge bg-light text-dark">User</span>
                                    @else
                                        <span class="badge bg-secondary">Tamu</span>
                                    @endif
                                </td>
                                <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                                <td><span
                                        class="badge bg-{{ $message->status == 'Baru' ? 'primary' : ($message->status == 'Dibaca' ? 'info text-dark' : ($message->status == 'Dibalas' ? 'success' : 'secondary')) }}">{{ $message->status }}</span>
                                </td>
                                <td>{{ $message->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn btn-info btn-sm"
                                        title="Lihat Detail"><i class="bi bi-eye-fill"></i></a>
                                    <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">@csrf
                                        @method('DELETE')<button type="submit" class="btn btn-danger btn-sm"
                                            title="Hapus"><i class="bi bi-trash3-fill"></i></button></form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($messages->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center pt-2">{{ $messages->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info text-center mt-3">Tidak ada pesan masuk yang sesuai dengan filter atau belum ada pesan
            sama sekali.</div>
    @endif
@endsection
