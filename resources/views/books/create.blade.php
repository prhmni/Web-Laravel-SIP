@extends('layouts.app')

@section('title', 'Tambah Buku Baru')
@section('page-title', 'Tambah Buku Baru')
@section('page-subtitle', 'Masukkan informasi detail buku ke dalam katalog perpustakaan.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-journal-plus me-2 text-primary"></i> Formulir Input Buku</span>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <!-- Kode Buku & ISBN -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kode Buku <span class="text-danger">*</span></label>
                            <input type="text" name="book_code" class="form-control" value="{{ old('book_code', $suggestedCode) }}" required placeholder="contoh: BK-0001">
                            <small class="text-muted">Kode unik identifikasi buku dalam sistem.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nomor ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}" placeholder="contoh: 978-602-032-478-4">
                            <small class="text-muted">Nomor ISBN standar internasional (opsional).</small>
                        </div>

                        <!-- Judul Buku -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Masukkan judul buku lengkap">
                        </div>

                        <!-- Kategori Buku -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kategori Buku <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Penulis / Pengarang -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Penulis / Pengarang <span class="text-danger">*</span></label>
                            <input type="text" name="author" class="form-control" value="{{ old('author') }}" required placeholder="Nama penulis / pengarang buku">
                        </div>

                        <!-- Penerbit & Tahun Terbit -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Penerbit <span class="text-danger">*</span></label>
                            <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}" required placeholder="Nama penerbit buku">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                            <input type="number" name="publish_year" class="form-control" value="{{ old('publish_year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                        </div>

                        <!-- Jumlah Stok & Lokasi Rak -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jumlah Stok Fisik (Eksemplar) <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', 1) }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Lokasi Rak Penyimpanan</label>
                            <input type="text" name="shelf_location" class="form-control" value="{{ old('shelf_location') }}" placeholder="contoh: Rak A-02 / Lantai 2">
                        </div>

                        <!-- Deskripsi / Sinopsis -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Sinopsis / Keterangan Buku</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Ringkasan cerita atau deskripsi isi buku...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Upload Cover & Live Preview -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Foto Cover Buku (Opsional)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <img id="coverPreview" src="{{ asset('images/book-placeholder.svg') }}" alt="Preview" class="rounded shadow-sm border" style="width: 80px; height: 110px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="cover_image" class="form-control" accept="image/*" onchange="previewImage(this)">
                                    <small class="text-muted d-block mt-1">Format gambar: JPG, PNG, WEBP. Maksimum 2 MB.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('books.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Buku
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
