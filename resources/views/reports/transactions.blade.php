@extends('layouts.app')

@section('title', 'Laporan Transaksi Sirkulasi')
@section('page-title', 'Laporan Transaksi Sirkulasi')
@section('page-subtitle', 'Rekapitulasi transaksi peminjaman, pengembalian buku, dan penerimaan denda.')

@section('content')
<!-- Filter Panel -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('reports.transactions') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted">Dari Tanggal Pinjam</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted">Status Peminjaman</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Semua yang Sedang Dipinjam</option>
                    <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Dipinjam (Belum Jatuh Tempo)</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat (Melewati Tempo)</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Selesai Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                    <i class="bi bi-filter me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('reports.print-transactions', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-printer me-1"></i> Cetak
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Metrics for Current Filter -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">TOTAL TRANSAKSI</span>
            <h4 class="fw-bold my-1 text-dark">{{ $totalTransactions }}</h4>
            <span class="small text-muted">Pada periode filter ini</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">SELESAI DIKEMBALIKAN</span>
            <h4 class="fw-bold my-1 text-success">{{ $totalReturned }}</h4>
            <span class="small text-success">Buku telah diterima kembali</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">MASIH DIPINJAM</span>
            <h4 class="fw-bold my-1 text-primary">{{ $totalActive }}</h4>
            <span class="small text-primary">Belum dikembalikan</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3">
            <span class="text-muted small fw-semibold">TOTAL DENDA MASUK</span>
            <h4 class="fw-bold my-1 text-danger">Rp {{ number_format($totalFines, 0, ',', '.') }}</h4>
            <span class="small text-muted">Dari keterlambatan</span>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold"><i class="bi bi-table me-2 text-primary"></i> Data Rincian Laporan Transaksi</span>
        <span class="badge bg-light text-secondary">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>No. Transaksi</th>
                    <th>Peminjam (Anggota)</th>
                    <th>Buku Yang Dipinjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Jatuh Tempo</th>
                    <th>Tgl Kembali</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $index => $b)
                    <tr>
                        <td>{{ $borrows->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('borrows.show', $b) }}" class="fw-bold text-decoration-none">
                                {{ $b->transaction_code }}
                            </a>
                            <small class="d-block text-muted">{{ $b->user->name }}</small>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $b->member->name }}</div>
                            <small class="text-muted">{{ $b->member->member_code }}</small>
                        </td>
                        <td>
                            @foreach($b->details as $d)
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $d->book->title }}">
                                    &bull; {{ $d->book->title }}
                                </div>
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->due_date)->format('d/m/Y') }}</td>
                        <td>{{ $b->return_date ? \Carbon\Carbon::parse($b->return_date)->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            @if($b->status === 'dikembalikan')
                                <span class="badge badge-subtle-success">Dikembalikan</span>
                            @elseif($b->status === 'terlambat')
                                <span class="badge badge-subtle-danger">Terlambat</span>
                            @else
                                <span class="badge badge-subtle-primary">Dipinjam</span>
                            @endif
                        </td>
                        <td class="text-end fw-semibold {{ $b->late_fee > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ $b->late_fee > 0 ? 'Rp ' . number_format($b->late_fee, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            Tidak ada transaksi peminjaman pada filter periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($borrows->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end p-3">
            {{ $borrows->links() }}
        </div>
    @endif
</div>
@endsection
