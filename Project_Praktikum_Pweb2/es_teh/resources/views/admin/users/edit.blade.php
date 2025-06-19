@extends('layouts.admin_app')

@section('title', 'Edit Pengguna: ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Pengguna: <span class="fw-normal">{{ $user->name }}</span></h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Detail Pengguna
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-3 col-form-label">Nama <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-3 col-form-label">Email <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="role" class="col-sm-3 col-form-label">Peran <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="role" id="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    {{ Auth::id() === $user->id && $user->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1 ? 'disabled' : '' }}>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (Auth::id() === $user->id && $user->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1)
                                    <small class="form-text text-muted">Anda tidak dapat mengubah peran admin
                                        terakhir.</small>
                                @endif
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <p class="text-muted mb-2">Kosongkan field password jika tidak ingin mengubahnya.</p>
                        <div class="mb-3 row">
                            <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password_confirmation" class="col-sm-3 col-form-label">Konfirmasi Password
                                Baru</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Informasi Akun
                    </div>
                    <div class="card-body">
                        <p><strong>Email Terverifikasi:</strong>
                            @if ($user->email_verified_at)
                                <span class="badge bg-success">Ya</span>
                                ({{ $user->email_verified_at->format('d M Y, H:i') }})
                            @else
                                <span class="badge bg-warning text-dark">Belum</span>
                            @endif
                        </p>
                        <p><strong>Tanggal Bergabung:</strong><br>{{ $user->created_at->format('d M Y, H:i:s') }}</p>
                        <p><strong>Terakhir Update:</strong><br>{{ $user->updated_at->format('d M Y, H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
