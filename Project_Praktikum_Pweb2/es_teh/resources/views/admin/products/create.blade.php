@extends('layouts.admin_app')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tambah Produk Baru</h1>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"> {{-- PENTING: enctype --}}
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">Detail Produk</div>
                    <div class="card-body">
                        {{-- Nama Produk --}}
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-3 col-form-label">Nama Produk <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', 'Es Teh Poci Aji Manis') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Ukuran --}}
                        <div class="mb-3 row">
                            <label for="size" class="col-sm-3 col-form-label">Ukuran <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="size" id="size"
                                    class="form-select @error('size') is-invalid @enderror" required>
                                    <option value="">Pilih Ukuran</option>
                                    <option value="Sedang" {{ old('size') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Besar" {{ old('size') == 'Besar' ? 'selected' : '' }}>Besar</option>
                                </select>
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-3 row">
                            <label for="price" class="col-sm-3 col-form-label">Harga (Rp) <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price') }}" required min="0"
                                    step="100">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3 row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">Gambar Produk</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="product_image_file" class="form-label">Upload Gambar <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('product_image_file') is-invalid @enderror"
                                id="product_image_file" name="product_image_file" required onchange="previewImage(event)">
                            <small class="form-text text-muted">Format: JPG, PNG, GIF, SVG. Maks: 2MB.</small>
                            @error('product_image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <img id="imagePreview" src="#" alt="Preview Gambar" class="img-fluid rounded mt-2"
                            style="display:none; max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12 text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const imagePreview = document.getElementById('imagePreview');
            reader.onload = function() {
                imagePreview.src = reader.result;
                imagePreview.style.display = 'block';
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        }
    </script>
@endpush
