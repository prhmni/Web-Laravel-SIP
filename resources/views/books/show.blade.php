@extends('layouts.app')

@section('title', 'Detail Buku - ' . $book->title)
@section('page-title', 'Detail Koleksi Buku')
@section('page-subtitle', 'Informasi lengkap dan rekam jejak peminjaman buku.')

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Info Buku -->
    <div class="col-lg-4">
        <div class="card text-center p-4">
            <img src="{{ $book->cover_url }}" alt="Cover" class="rounded shadow mx-auto mb-3" style="width: 160px; height: 230px; object-fit: cover; border: 1px solid #e2e8f0;">
            <h5 class="fw-bold text-dark mb-1">{{ $book->title }}</h5>
            <p class="text-muted small mb-3">{{ $book->author }}</p>

            <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge badge-subtle-primary">{{ $book->category->name }}</span>
                <span class="badge bg-light text-secondary border"><code>{{ $book->book_code }}</code></span>
            </div>

            <div class="p-3 bg-light rounded-3 text-start small mb-3">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Nomor ISBN:</span>
                    <strong class="text-dark">{{ $book->isbn ?: '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Penerbit:</span>
                    <strong class="text-dark">{{ $book->publisher }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Tahun Terbit:</span>
                    <strong class="text-dark">{{ $book->publish_year }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Lokasi Rak:</span>
                    <strong class="text-dark">{{ $book->shelf_location ?: '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Stok Fisik:</span>
                    <strong class="text-dark">{{ $book->stock }} Eksemplar</strong>
                </div>
            </div>

            <!-- Status Ketersediaan -->
            <div class="alert {{ $book->available_stock > 0 ? 'alert-success' : 'alert-danger' }} py-2 mb-3 text-center small border-0">
                <i class="bi {{ $book->available_stock > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                @if($book->available_stock > 0)
                    Tersedia <strong>{{ $book->available_stock }}</strong> buku untuk dipinjam
                @else
                    Semua stok buku sedang dipinjam
                @endif
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                    <i class="bi bi-pencil me-1"></i> Edit Buku
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Sinopsis & Riwayat Peminjaman -->
    <div class="col-lg-8">
        <!-- Sinopsis -->
        <div class="card mb-4">
            <div class="card-header">
                <span class="fw-bold"><i class="bi bi-card-text me-2 text-primary"></i> Sinopsis / Deskripsi</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0" style="line-height: 1.7;">
                    {{ $book->description ?: 'Belum ada sinopsis atau deskripsi untuk buku ini.' }}
                </p>
            </div>
        </div>

        <!-- Riwayat Peminjaman Buku Ini -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Sirkulasi Peminjaman</span>
                <span class="badge bg-light text-secondary">{{ $book->borrowDetails->count() }} Kali Dipinjam</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($book->borrowDetails as $detail)
                            <tr>
                                <td>
                                    <a href="{{ route('borrows.show', $detail->borrow) }}" class="text-decoration-none fw-semibold">
                                        {{ $detail->borrow->transaction_code }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $detail->borrow->member->name }}</div>
                                    <small class="text-muted">{{ $detail->borrow->member->member_code }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($detail->borrow->borrow_date)->format('d/m/Y') }}</td>
                                <td>
                                    {{ $detail->return_date ? \Carbon\Carbon::parse($detail->return_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    @if($detail->status === 'dikembalikan')
                                        <span class="badge badge-subtle-success">Dikembalikan</span>
                                    @else
                                        <span class="badge badge-subtle-primary">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada rekam peminjaman untuk buku ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
