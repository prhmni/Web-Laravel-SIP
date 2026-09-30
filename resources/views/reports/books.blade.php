@extends('layouts.app')

@section('title', 'Laporan Koleksi Buku')
@section('page-title', 'Laporan Koleksi Buku')
@section('page-subtitle', 'Rekapitulasi inventaris buku, ketersediaan eksemplar fisik, dan sebaran kategori.')

@section('content')
<!-- Filter Panel -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('reports.books') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4 col-sm-6">
                <label class="form-label small fw-semibold text-muted">Filter Kategori</label>
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-6">
                <label class="form-label small fw-semibold text-muted">Ketersediaan Stok</label>
                <select name="stock" class="form-select form-select-sm">
                    <option value="">-- Semua Kondisi Stok --</option>
                    <option value="available" {{ $stockFilter === 'available' ? 'selected' : '' }}>Tersedia untuk Dipinjam</option>
                    <option value="empty" {{ $stockFilter === 'empty' ? 'selected' : '' }}>Stok Habis / Sedang Dipinjam Semua</option>
                </select>
            </div>
            <div class="col-md-4 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                    <i class="bi bi-filter me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('reports.print-books', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </a>
            </div>
        </form>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">TOTAL JUDUL BUKU</span>
            <h4 class="fw-bold my-1 text-dark">{{ $totalTitles }}</h4>
            <span class="small text-muted">Judul berbeda</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">TOTAL EKSEMPLAR FISIK</span>
            <h4 class="fw-bold my-1 text-primary">{{ $totalTotalStock }}</h4>
            <span class="small text-muted">Seluruh eksemplar terdata</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">TERSEDIA DI RAK</span>
            <h4 class="fw-bold my-1 text-success">{{ $totalAvailable }}</h4>
            <span class="small text-success">Siap untuk dipinjam</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">SEDANG DIPINJAM</span>
            <h4 class="fw-bold my-1 text-warning">{{ $totalBorrowed }}</h4>
            <span class="small text-muted">Sedang di luar perpustakaan</span>
        </div>
    </div>
</div>

<!-- Books Catalog Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold"><i class="bi bi-journal-bookmark me-2 text-primary"></i> Data Inventaris Koleksi</span>
        <span class="badge bg-light text-secondary">Total: {{ $books->total() }} Judul</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Buku</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Penulis / Penerbit</th>
                    <th>Tahun</th>
                    <th>Lokasi Rak</th>
                    <th class="text-center" style="width: 100px;">Total Stok</th>
                    <th class="text-center" style="width: 110px;">Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td><code>{{ $book->book_code }}</code></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $book->title }}</div>
                            @if($book->isbn)
                                <small class="text-muted">ISBN: {{ $book->isbn }}</small>
                            @endif
                        </td>
                        <td><span class="badge badge-subtle-primary">{{ $book->category->name }}</span></td>
                        <td>
                            <div>{{ $book->author }}</div>
                            <small class="text-muted">{{ $book->publisher }}</small>
                        </td>
                        <td>{{ $book->publish_year }}</td>
                        <td>{{ $book->shelf_location ?: '-' }}</td>
                        <td class="text-center fw-bold">{{ $book->stock }}</td>
                        <td class="text-center">
                            @if($book->available_stock > 0)
                                <span class="badge badge-subtle-success px-2 py-1">
                                    {{ $book->available_stock }} Buku
                                </span>
                            @else
                                <span class="badge badge-subtle-danger px-2 py-1">Habis (0)</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            Tidak ada koleksi buku yang sesuai dengan filter.
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
