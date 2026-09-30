@extends('layouts.app')

@section('title', 'Sirkulasi Peminjaman & Pengembalian')
@section('page-title', 'Sirkulasi Transaksi')
@section('page-subtitle', 'Pencatatan sirkulasi digital peminjaman dan pengembalian buku.')

@section('content')
<!-- Filter Status Tabs & Statistik Singkat -->
<div class="row g-3 mb-3">
    <div class="col-md-4 col-sm-6">
        <a href="{{ route('borrows.index', ['status' => 'aktif']) }}" class="card text-decoration-none p-3 h-100 {{ $status === 'aktif' ? 'border-primary shadow-sm' : '' }}">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">SEDANG DIPINJAM</span>
                    <h4 class="fw-bold my-1 text-primary">{{ $activeCount }}</h4>
                    <span class="small text-muted">Buku belum dikembalikan</span>
                </div>
                <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 col-sm-6">
        <a href="{{ route('borrows.index', ['status' => 'terlambat']) }}" class="card text-decoration-none p-3 h-100 {{ $status === 'terlambat' ? 'border-danger shadow-sm' : '' }}">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">MELEWATI JATUH TEMPO</span>
                    <h4 class="fw-bold my-1 text-danger">{{ $overdueCount }}</h4>
                    <span class="small text-danger">Perlu penagihan / denda</span>
                </div>
                <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 col-sm-6">
        <a href="{{ route('borrows.index', ['status' => 'dikembalikan']) }}" class="card text-decoration-none p-3 h-100 {{ $status === 'dikembalikan' ? 'border-success shadow-sm' : '' }}">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">SELESAI DIKEMBALIKAN</span>
                    <h4 class="fw-bold my-1 text-success">{{ $returnedCount }}</h4>
                    <span class="small text-muted">Riwayat transaksi selesai</span>
                </div>
                <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check2-circle fs-4"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header p-3">
        <form action="{{ route('borrows.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Filter Status -->
            <div class="col-md-3 col-sm-6">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Semua yang Sedang Dipinjam</option>
                    <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Dipinjam (Belum Jatuh Tempo)</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat (Melewati Jatuh Tempo)</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Selesai Dikembalikan</option>
                </select>
            </div>

            <!-- Search Field -->
            <div class="col-md-5 col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode transaksi, nama anggota, atau judul buku..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if($search || $status)
                        <a href="{{ route('borrows.index') }}" class="btn btn-outline-danger" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>

            <!-- Tombol Peminjaman Baru -->
            <div class="col-md-4 text-md-end text-start mt-2 mt-md-0">
                <a href="{{ route('borrows.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Catat Peminjaman Baru
                </a>
            </div>
        </form>
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
                    <th>Jatuh Tempo</th>
                    <th class="text-center" style="width: 130px;">Status</th>
                    <th style="width: 140px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $index => $b)
                    <tr>
                        <td>{{ $borrows->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('borrows.show', $b) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $b->transaction_code }}
                            </a>
                            <div class="text-muted small">Petugas: {{ $b->user->name }}</div>
                        </td>
                        <td>
                            <a href="{{ route('members.show', $b->member) }}" class="fw-semibold text-dark text-decoration-none">
                                {{ $b->member->name }}
                            </a>
                            <div>
                                <small class="text-muted"><i class="bi bi-card-text me-1"></i> {{ $b->member->member_code }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @foreach($b->details as $detail)
                                    <div class="text-truncate" style="max-width: 250px;" title="{{ $detail->book->title }}">
                                        <i class="bi bi-book text-muted me-1"></i> {{ $detail->book->title }}
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                        <td>
                            <div class="fw-medium {{ $b->status === 'terlambat' ? 'text-danger' : 'text-dark' }}">
                                {{ \Carbon\Carbon::parse($b->due_date)->format('d/m/Y') }}
                            </div>
                            @if($b->status === 'terlambat')
                                @php
                                    $diff = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($b->due_date));
                                @endphp
                                <small class="text-danger fw-semibold">Lewat {{ $diff }} hari</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($b->status === 'dikembalikan')
                                <span class="badge badge-subtle-success px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Dikembalikan
                                </span>
                                @if($b->late_fee > 0)
                                    <div class="text-danger small mt-1">Denda: Rp {{ number_format($b->late_fee, 0, ',', '.') }}</div>
                                @endif
                            @elseif($b->status === 'terlambat')
                                <span class="badge badge-subtle-danger px-2 py-1">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Terlambat
                                </span>
                            @else
                                <span class="badge badge-subtle-primary px-2 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Dipinjam
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('borrows.show', $b) }}" class="btn btn-outline-secondary" title="Detail Transaksi">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('borrows.receipt', $b) }}" class="btn btn-outline-info" title="Cetak Bukti Transaksi" target="_blank">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                @if($b->status !== 'dikembalikan')
                                    <a href="{{ route('borrows.return', $b) }}" class="btn btn-success text-white" title="Proses Pengembalian">
                                        <i class="bi bi-arrow-return-left me-1"></i> Kembali
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-6 text-secondary d-block mb-2"></i>
                            Tidak ada transaksi sirkulasi yang sesuai kriteria.
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
