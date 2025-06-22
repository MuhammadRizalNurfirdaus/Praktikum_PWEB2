@extends('layouts.admin_app')

@section('title', 'Edit Produk: ' . $product->name)

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Produk: <span class="fw-normal">{{ $product->name }}</span></h1>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        {{-- PENTING: enctype --}}
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Kolom Kiri: Detail Produk --}}
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">Detail Produk</div>
                    <div class="card-body">
                        {{-- Nama, Ukuran, Harga, Deskripsi seperti di form create --}}
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-3 col-form-label">Nama Produk <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="size" class="col-sm-3 col-form-label">Ukuran <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="size" id="size"
                                    class="form-select @error('size') is-invalid @enderror" required>
                                    <option value="">Pilih Ukuran</option>
                                    <option value="Small" {{ old('size', $product->size) == 'Small' ? 'selected' : '' }}>
                                        Small</option>
                                    <option value="Medium" {{ old('size', $product->size) == 'Medium' ? 'selected' : '' }}>
                                        Meduim</option>
                                    <option value="Large" {{ old('size', $product->size) == 'Large' ? 'selected' : '' }}>
                                        Large</option>
                                </select>
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="price" class="col-sm-3 col-form-label">Harga (Rp) <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $product->price) }}" required
                                    min="0" step="100">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Manajemen Gambar --}}
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">Gambar Produk</div>
                    <div class="card-body">
                        @if ($product->image_path)
                            <div class="mb-3 text-center">
                                <label class="form-label d-block">Gambar Saat Ini:</label>
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                    alt="Gambar Produk {{ $product->name }}" class="img-fluid rounded mb-2"
                                    style="max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                            </div>
                        @else
                            <p class="text-muted text-center">Belum ada gambar untuk produk ini.</p>
                        @endif

                        <div class="mb-3">
                            <label for="image_action" class="form-label">Tindakan untuk Gambar:</label>
                            <select name="image_action" id="image_action" class="form-select">
                                <option value="keep" selected>Pertahankan Gambar Saat Ini</option>
                                <option value="replace">Ganti dengan Gambar Baru</option>
                                @if ($product->image_path)
                                    <option value="delete">Hapus Gambar Saat Ini (Kosongkan)</option>
                                @endif
                            </select>
                        </div>

                        <div id="newImageUpload" style="display: none;"> {{-- Awalnya disembunyikan --}}
                            <label for="product_image_file" class="form-label">Upload Gambar Baru:</label>
                            <input type="file" class="form-control @error('product_image_file') is-invalid @enderror"
                                id="product_image_file" name="product_image_file" onchange="previewEditImage(event)">
                            <small class="form-text text-muted">Format: JPG, PNG, GIF, SVG. Maks: 2MB.</small>
                            @error('product_image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="imageEditPreview" src="#" alt="Preview Gambar Baru"
                                class="img-fluid rounded mt-2" style="display:none; max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12 text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Produk</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const imageActionSelect = document.getElementById('image_action');
        const newImageUploadDiv = document.getElementById('newImageUpload');
        const imageEditPreview = document.getElementById('imageEditPreview');
        const productFile = document.getElementById('product_image_file');

        if (imageActionSelect) {
            imageActionSelect.addEventListener('change', function() {
                if (this.value === 'replace') {
                    newImageUploadDiv.style.display = 'block';
                } else {
                    newImageUploadDiv.style.display = 'none';
                    productFile.value = ''; // Reset input file jika tidak ganti
                    imageEditPreview.src = '#';
                    imageEditPreview.style.display = 'none';
                }
            });
        }

        function previewEditImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                imageEditPreview.src = reader.result;
                imageEditPreview.style.display = 'block';
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                imageEditPreview.src = '#';
                imageEditPreview.style.display = 'none';
            }
        }

        // Inisialisasi tampilan saat halaman dimuat (jika ada old input)
        document.addEventListener('DOMContentLoaded', function() {
            if (imageActionSelect && imageActionSelect.value === 'replace') {
                newImageUploadDiv.style.display = 'block';
            }
        });
    </script>
@endpush
