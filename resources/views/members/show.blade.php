@extends('layouts.app')

@section('title', 'Detail Anggota - ' . $member->name)
@section('page-title', 'Detail Data Anggota')
@section('page-subtitle', 'Informasi profil anggota dan rekam jejak peminjaman buku.')

@section('content')
<div class="row g-4">
    <!-- Profil Anggota -->
    <div class="col-lg-4">
        <div class="card text-center p-4">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 shadow" style="width: 80px; height: 80px; font-size: 2rem; font-weight: 700;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $member->name }}</h5>
            <span class="badge bg-light text-dark border mb-3">
                <code>{{ $member->member_code }}</code>
            </span>

            <div class="d-flex justify-content-center gap-2 mb-3">
                @if($member->status === 'aktif')
                    <span class="badge badge-subtle-success px-3 py-1">Anggota Aktif</span>
                @else
                    <span class="badge badge-subtle-secondary px-3 py-1">Nonaktif</span>
                @endif
                <span class="badge badge-subtle-primary px-3 py-1">{{ $member->gender }}</span>
            </div>

            <div class="p-3 bg-light rounded-3 text-start small mb-3">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">No. Telepon / WA:</span>
                    <strong class="text-dark">{{ $member->phone }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Email:</span>
                    <strong class="text-dark">{{ $member->email ?: '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Tanggal Bergabung:</span>
                    <strong class="text-dark">{{ $member->joined_date->format('d/m/Y') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Alamat:</span>
                    <span class="text-dark text-end ms-2">{{ $member->address ?: '-' }}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('members.card', $member) }}" class="btn btn-info text-white btn-sm flex-grow-1" target="_blank">
                    <i class="bi bi-person-vcard me-1"></i> Cetak Kartu
                </a>
                <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('members.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Sirkulasi Anggota -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Peminjaman Buku</span>
                <span class="badge bg-light text-secondary">{{ $member->borrows->count() }} Transaksi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Buku Dipinjam</th>
                            <th>Tgl Pinjam / Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($member->borrows as $borrow)
                            <tr>
                                <td>
                                    <a href="{{ route('borrows.show', $borrow) }}" class="fw-semibold text-primary text-decoration-none">
                                        {{ $borrow->transaction_code }}
                                    </a>
                                </td>
                                <td>
                                    @foreach($borrow->details as $d)
                                        <div class="text-truncate" style="max-width: 220px;" title="{{ $d->book->title }}">
                                            &bull; {{ $d->book->title }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</div>
                                    <small class="text-muted">Tempo: {{ \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    {{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    @if($borrow->status === 'dikembalikan')
                                        <span class="badge badge-subtle-success">Dikembalikan</span>
                                    @elseif($borrow->status === 'terlambat')
                                        <span class="badge badge-subtle-danger">Terlambat</span>
                                    @else
                                        <span class="badge badge-subtle-primary">Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrow->late_fee > 0)
                                        <span class="text-danger fw-semibold">Rp {{ number_format($borrow->late_fee, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Anggota ini belum pernah melakukan transaksi peminjaman.
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
