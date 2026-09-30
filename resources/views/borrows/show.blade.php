@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $borrow->transaction_code)
@section('page-title', 'Detail Transaksi Sirkulasi')
@section('page-subtitle', 'Rincian peminjaman dan rekapitulasi pengembalian buku.')

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Ringkasan Transaksi & Anggota -->
    <div class="col-lg-4">
        <!-- Status Box -->
        <div class="card mb-4 text-center p-4">
            <span class="text-muted small fw-semibold">NOMOR TRANSAKSI</span>
            <h4 class="fw-bold text-dark my-1">{{ $borrow->transaction_code }}</h4>

            <div class="my-3">
                @if($borrow->status === 'dikembalikan')
                    <span class="badge badge-subtle-success fs-6 px-3 py-2">
                        <i class="bi bi-check2-circle me-1"></i> Telah Dikembalikan
                    </span>
                @elseif($borrow->status === 'terlambat')
                    <span class="badge badge-subtle-danger fs-6 px-3 py-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Melewati Jatuh Tempo
                    </span>
                @else
                    <span class="badge badge-subtle-primary fs-6 px-3 py-2">
                        <i class="bi bi-hourglass-split me-1"></i> Sedang Dipinjam
                    </span>
                @endif
            </div>

            <div class="p-3 bg-light rounded-3 text-start small mb-3">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Tanggal Pinjam:</span>
                    <strong>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d F Y') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Jatuh Tempo:</span>
                    <strong class="{{ $borrow->status === 'terlambat' ? 'text-danger' : '' }}">
                        {{ \Carbon\Carbon::parse($borrow->due_date)->format('d F Y') }}
                    </strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Tanggal Dikembalikan:</span>
                    <strong>{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d F Y') : '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Petugas Layanan:</span>
                    <strong>{{ $borrow->user->name }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Denda Keterlambatan:</span>
                    <strong class="{{ $borrow->late_fee > 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format($borrow->late_fee, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-column gap-2">
                @if($borrow->status !== 'dikembalikan')
                    <a href="{{ route('borrows.return', $borrow) }}" class="btn btn-success">
                        <i class="bi bi-arrow-return-left me-1"></i> Proses Pengembalian Buku
                    </a>
                @endif
                <a href="{{ route('borrows.receipt', $borrow) }}" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-printer me-1"></i> Cetak Bukti Transaksi
                </a>
                <a href="{{ route('borrows.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Info Anggota Peminjam -->
        <div class="card p-3">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge text-primary me-2"></i> Data Pemustaka
            </h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: 700; font-size: 1.25rem;">
                    {{ strtoupper(substr($borrow->member->name, 0, 1)) }}
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ $borrow->member->name }}</h6>
                    <small class="text-muted">{{ $borrow->member->member_code }}</small>
                </div>
            </div>
            <div class="small text-muted">
                <div class="mb-1"><i class="bi bi-telephone me-2"></i> {{ $borrow->member->phone }}</div>
                @if($borrow->member->email)
                    <div class="mb-1"><i class="bi bi-envelope me-2"></i> {{ $borrow->member->email }}</div>
                @endif
                @if($borrow->member->address)
                    <div><i class="bi bi-geo-alt me-2"></i> {{ $borrow->member->address }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Rincian Buku Yang Dipinjam -->
    <div class="col-lg-8">
        @if($borrow->status === 'terlambat')
            <div class="alert alert-danger d-flex align-items-center gap-3 border-0 shadow-sm mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-2 text-danger"></i>
                <div>
                    <h6 class="fw-bold mb-1">Transaksi Ini Melewati Batas Waktu!</h6>
                    <p class="small mb-0">
                        Keterlambatan telah berjalan selama <strong>{{ $currentLateDays }} hari</strong>. Estimasi denda saat ini adalah <strong>Rp {{ number_format($estimatedFine, 0, ',', '.') }}</strong> (Rp {{ number_format($feePerDay, 0, ',', '.') }}/hari per buku).
                    </p>
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-journals me-2 text-primary"></i> Daftar Buku yang Dipinjam</span>
                <span class="badge badge-subtle-primary">{{ $borrow->details->count() }} Buku</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 60px;">Cover</th>
                            <th>Judul Buku</th>
                            <th>Kategori & Rak</th>
                            <th>Status Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrow->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ $detail->book->cover_url }}" alt="Cover" class="rounded shadow-sm" style="width: 40px; height: 55px; object-fit: cover; border: 1px solid #e2e8f0;">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $detail->book->title }}</div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-light text-secondary border"><code>{{ $detail->book->book_code }}</code></span>
                                        <small class="text-muted">{{ $detail->book->author }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div><span class="badge badge-subtle-primary">{{ $detail->book->category->name }}</span></div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $detail->book->shelf_location ?: '-' }}</small>
                                </td>
                                <td>
                                    @if($detail->status === 'dikembalikan')
                                        <span class="badge badge-subtle-success">Dikembalikan</span>
                                    @else
                                        <span class="badge badge-subtle-primary">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Catatan Tambahan -->
        <div class="card">
            <div class="card-header">
                <span class="fw-bold"><i class="bi bi-card-text me-2 text-primary"></i> Catatan Transaksi</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">
                    {{ $borrow->notes ?: 'Tidak ada catatan tambahan untuk transaksi ini.' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
