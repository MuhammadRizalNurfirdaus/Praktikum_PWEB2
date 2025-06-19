@extends('layouts.admin_app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Kelola Pengguna</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            {{-- Tombol Tambah Pengguna Baru biasanya tidak diperlukan karena user registrasi sendiri.
             Jika Anda ingin mengaktifkannya, pastikan route 'admin.users.create' ada dan controllernya siap.
             Untuk sekarang, kita biarkan ini sebagai komentar Blade yang benar. --}}
            {{--
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-plus-circle-fill"></i> Tambah Pengguna Baru
        </a>
        --}}
        </div>
    </div>

    {{-- Form Pencarian --}}
    <div class="mb-3 card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md">
                    <label for="search" class="visually-hidden">Cari Pengguna</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm"
                        placeholder="Cari berdasarkan nama, email, atau peran..." value="{{ request('search') }}">
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if (request('search'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger btn-sm"
                            title="Hapus Filter Pencarian"><i class="bi bi-x-lg"></i> Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>


    @if ($users->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header">
                Daftar Pengguna Terdaftar
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 30%;">Nama</th>
                            <th scope="col" style="width: 30%;">Email</th>
                            <th scope="col" style="width: 15%;">Peran</th>
                            <th scope="col" style="width: 10%;">Tgl Bergabung</th>
                            <th scope="col" style="width: 10%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $key }}</td>
                                <td>
                                    <i class="bi bi-person-circle me-1"></i> {{ $user->name }}
                                    @if ($user->id === Auth::id())
                                        <span class="badge bg-info ms-1">Anda</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill
                                @if ($user->role === 'admin') bg-danger
                                @elseif($user->role === 'kurir') bg-warning text-dark
                                @else bg-secondary @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center table-actions">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit Pengguna">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    {{-- Tombol hapus dengan proteksi --}}
                                    @if (Auth::id() !== $user->id && !($user->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1))
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Pengguna"
                                                onclick="return confirm('PERINGATAN!\nApakah Anda yakin ingin menghapus pengguna {{ $user->name }}?\nTindakan ini tidak dapat diurungkan.')">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm disabled"
                                            title="Tidak dapat menghapus pengguna ini">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-warning text-center mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @if (request('search'))
                Tidak ada pengguna yang cocok dengan pencarian "<strong>{{ request('search') }}</strong>".
            @else
                Belum ada pengguna terdaftar selain Anda (jika Anda adalah admin pertama).
            @endif
        </div>
    @endif
@endsection

@push('styles')
    <style>
        /* CSS Khusus untuk halaman ini jika diperlukan */
        .table-actions form {
            margin: 0;
            /* Reset margin jika ada */
        }

        .table-actions .btn {
            padding: 0.25rem 0.5rem;
            /* Ukuran tombol lebih kecil */
            font-size: 0.8rem;
        }

        .badge.rounded-pill {
            padding-right: .8em;
            padding-left: .8em;
        }
    </style>
@endpush
