@extends('layouts.app')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Data Buku')
@section('page-subtitle', 'Perbarui informasi dan ketersediaan stok buku.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Buku: {{ $book->title }}</span>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <!-- Kode Buku & ISBN -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kode Buku <span class="text-danger">*</span></label>
                            <input type="text" name="book_code" class="form-control" value="{{ old('book_code', $book->book_code) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nomor ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn) }}">
                        </div>

                        <!-- Judul Buku -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
                        </div>

                        <!-- Kategori Buku -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kategori Buku <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Penulis / Pengarang -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Penulis / Pengarang <span class="text-danger">*</span></label>
                            <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}" required>
                        </div>

                        <!-- Penerbit & Tahun Terbit -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Penerbit <span class="text-danger">*</span></label>
                            <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                            <input type="number" name="publish_year" class="form-control" value="{{ old('publish_year', $book->publish_year) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                        </div>

                        <!-- Jumlah Stok & Lokasi Rak -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jumlah Total Stok Fisik <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock) }}" min="1" required>
                            <small class="text-muted">Saat ini: <strong>{{ $book->available_stock }}</strong> tersedia, <strong>{{ $book->stock - $book->available_stock }}</strong> sedang dipinjam.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Lokasi Rak Penyimpanan</label>
                            <input type="text" name="shelf_location" class="form-control" value="{{ old('shelf_location', $book->shelf_location) }}">
                        </div>

                        <!-- Deskripsi / Sinopsis -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Sinopsis / Keterangan Buku</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $book->description) }}</textarea>
                        </div>

                        <!-- Upload Cover & Live Preview -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Foto Cover Buku</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <img id="coverPreview" src="{{ $book->cover_url }}" alt="Preview" class="rounded shadow-sm border" style="width: 80px; height: 110px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="cover_image" class="form-control" accept="image/*" onchange="previewImage(this)">
                                    <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah foto cover.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('books.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Perbarui Buku
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
