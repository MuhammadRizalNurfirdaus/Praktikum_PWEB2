@extends('layouts.app') {{-- Menggunakan layout publik utama --}}

@section('title', 'Profil Saya - ' . Auth::user()->name)

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-3">
                {{-- Sidebar Mini User (jika Anda ingin konsisten dengan halaman user lain) --}}
                @include('user.partials.sidebar') {{-- Pastikan partial ini ada --}}
            </div>
            <div class="col-md-9">
                <h2 class="mb-4">Profil Saya</h2>

                {{-- Form Update Informasi Profil --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informasi Profil</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Update informasi profil dan alamat email akun Anda.</p>

                        {{-- Pesan status untuk update profil --}}
                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Profil berhasil diperbarui.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name', 'updateProfileInformation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email"
                                    class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email', 'updateProfileInformation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p class="text-sm text-muted">
                                            Alamat email Anda belum terverifikasi.
                                            <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">
                                                Klik di sini untuk mengirim ulang email verifikasi.
                                            </button>
                                        </p>
                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 fw-medium text-sm text-success">
                                                Link verifikasi baru telah dikirim ke alamat email Anda.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-4">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Form Update Password --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Update Password</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap
                            aman.</p>

                        {{-- Pesan status untuk update password --}}
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Password berhasil diperbarui.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="post" action="{{ route('password.update') }}"> {{-- Breeze menggunakan route 'password.update' --}}
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input id="current_password" name="current_password" type="password"
                                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input id="password" name="password" type="password"
                                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    class="form-control" autocomplete="new-password">
                            </div>

                            <div class="d-flex align-items-center gap-4">
                                <button type="submit" class="btn btn-primary">Simpan Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Form Hapus Akun (Opsional) --}}
                {{-- Breeze juga menyediakan ini, tapi bisa jadi lebih kompleks untuk diintegrasikan tanpa komponen Blade Breeze --}}
                {{--
            <div class="card shadow-sm">
                <div class="card-header bg-danger-subtle">
                    <h5 class="mb-0 text-danger">Hapus Akun</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.</p>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>
            --}}
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Akun (jika Anda mengaktifkan tombol Hapus Akun) --}}
    {{--
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionModalLabel">Anda yakin ingin menghapus akun Anda?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Harap masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.</p>
                    <div class="mt-3">
                        <label for="password_delete" class="form-label visually-hidden">Password</label>
                        <input id="password_delete" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password Anda" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>
--}}

    {{-- Form untuk resend email verification (jika diperlukan) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>
@endsection

@push('styles')
    <style>
        /* Tambahkan style khusus jika perlu */
        .card-header h5 {
            font-weight: 600;
        }
    </style>
@endpush
