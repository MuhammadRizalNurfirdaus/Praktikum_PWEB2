@extends('layouts.admin_app')

@section('title', 'Detail Pesan: ' . Str::limit($contactMessage->subject, 30))

@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        {{-- HAPUS ATAU UBAH JUDUL INI JIKA TIDAK PERLU JUDUL HALAMAN TERPISAH DARI NAVBAR ATAS --}}
        {{-- <h1 class="h2">Detail Pesan Kontak</h1> --}}
        <h1 class="h2">Detail Pesan: <span class="fw-normal">{{ Str::limit($contactMessage->subject, 40) }}</span></h1>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Pesan
        </a>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Detail Pesan & Item --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengirim & Pesan</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4 col-md-3">Subjek:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $contactMessage->subject }}</dd>

                        <dt class="col-sm-4 col-md-3">Dari:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $contactMessage->name }}</dd>

                        <dt class="col-sm-4 col-md-3">Email:</dt>
                        <dd class="col-sm-8 col-md-9"><a
                                href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></dd>

                        <dt class="col-sm-4 col-md-3">Dikirim Oleh:</dt>
                        <dd class="col-sm-8 col-md-9">
                            @if ($contactMessage->user)
                                Pengguna Terdaftar: <a
                                    href="{{ route('admin.users.edit', $contactMessage->user_id) }}">{{ $contactMessage->user->name }}</a>
                            @else
                                <span class="fst-italic">Tamu</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 col-md-3">Tanggal Kirim:</dt>
                        <dd class="col-sm-8 col-md-9">{{ $contactMessage->created_at->format('d M Y, H:i:s') }}
                            ({{ $contactMessage->created_at->diffForHumans() }})</dd>
                    </dl>
                    <hr>
                    <h6 class="fw-semibold mt-3">Isi Pesan:</h6>
                    <div class="p-3 bg-light rounded border" style="white-space: pre-wrap; min-height: 150px;">
                        {{ $contactMessage->message }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Update Status Pesan --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                {{-- PERBAIKAN TEKS HEADER KARTU --}}
                <div class="card-header">
                    <h5 class="mb-0">Update Status Pesan Kontak</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.update', $contactMessage->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Saat Ini:
                                <span
                                    class="badge rounded-pill
                                @switch($contactMessage->status)
                                    @case('Baru') bg-primary @break
                                    @case('Dibaca') bg-info text-dark @break
                                    @case('Dibalas') bg-success @break
                                    @case('Ditutup') bg-secondary @break
                                    @default bg-light text-dark
                                @endswitch">
                                    {{ $contactMessage->status }}
                                </span>
                            </label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                                required>
                                @foreach ($messageStatuses as $statusOption)
                                    <option value="{{ $statusOption }}"
                                        {{ $contactMessage->status == $statusOption ? 'selected' : '' }}>
                                        {{ $statusOption }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                    <hr>
                    <form action="{{ route('admin.contacts.destroy', $contactMessage->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus pesan ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash3"></i>
                            Hapus Pesan Ini</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        dt {
            font-weight: 600;
            margin-bottom: .5rem;
            /* Sedikit spasi bawah untuk dt */
        }

        dd {
            margin-bottom: 1rem;
            /* Spasi bawah untuk dd */
        }
    </style>
@endpush
