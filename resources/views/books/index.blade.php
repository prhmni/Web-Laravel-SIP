@extends('layouts.app')

@section('title', 'Koleksi Buku')
@section('page-title', 'Manajemen Koleksi Buku')
@section('page-subtitle', 'Katalog buku, ketersediaan stok fisik, dan lokasi rak perpustakaan.')

@section('content')
<div class="card mb-4">
    <div class="card-header p-3">
        <form action="{{ route('books.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Filter Kategori -->
            <div class="col-md-3 col-sm-6">
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Field -->
            <div class="col-md-5 col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, ISBN, penulis, atau kode buku..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if($search || $categoryId)
                        <a href="{{ route('books.index') }}" class="btn btn-outline-danger" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>

            <!-- Tombol Tambah Buku -->
            <div class="col-md-4 text-md-end text-start mt-2 mt-md-0">
                <a href="{{ route('books.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
                </a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 70px;">Cover</th>
                    <th>Judul Buku & Kode</th>
                    <th>Kategori</th>
                    <th>Penulis / Penerbit</th>
                    <th>Lokasi Rak</th>
                    <th class="text-center" style="width: 140px;">Status Stok</th>
                    <th style="width: 120px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td>
                            <img src="{{ $book->cover_url }}" alt="Cover" class="rounded shadow-sm" style="width: 48px; height: 64px; object-fit: cover; border: 1px solid #e2e8f0;">
                        </td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                {{ $book->title }}
                            </a>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-light text-secondary border"><code>{{ $book->book_code }}</code></span>
                                @if($book->isbn)
                                    <small class="text-muted">ISBN: {{ $book->isbn }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-subtle-primary">{{ $book->category->name }}</span>
                        </td>
                        <td>
                            <div class="text-dark fw-medium">{{ $book->author }}</div>
                            <small class="text-muted">{{ $book->publisher }} ({{ $book->publish_year }})</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-geo-alt text-primary me-1"></i> {{ $book->shelf_location ?: '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($book->available_stock > 0)
                                <div class="badge badge-subtle-success mb-1 px-2 py-1">
                                    Tersedia {{ $book->available_stock }} / {{ $book->stock }}
                                </div>
                            @else
                                <div class="badge badge-subtle-danger mb-1 px-2 py-1">
                                    Stok Habis (0 / {{ $book->stock }})
                                </div>
                            @endif
                            <div class="progress" style="height: 4px;">
                                @php
                                    $pct = $book->stock > 0 ? ($book->available_stock / $book->stock) * 100 : 0;
                                @endphp
                                <div class="progress-bar {{ $pct < 30 ? 'bg-danger' : ($pct < 70 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal{{ $book->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Konfirmasi Hapus Buku -->
                            <div class="modal fade text-start" id="deleteBookModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body p-4 text-center">
                                            <i class="bi bi-exclamation-triangle text-danger display-4 mb-3 d-block"></i>
                                            <h6 class="fw-bold mb-2">Hapus Buku?</h6>
                                            <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus buku <strong>{{ $book->title }}</strong>?</p>
                                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-flex justify-content-center gap-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-light px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-danger px-3">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-journals display-6 text-secondary d-block mb-2"></i>
                            Tidak ada koleksi buku yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($books->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end p-3">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection
