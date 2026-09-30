@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Perpustakaan')
@section('page-subtitle', 'Ringkasan data koleksi, anggota, dan aktivitas sirkulasi digital.')

@section('content')
<!-- Row 1: KPI Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Total Buku -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">TOTAL KOLEKSI</span>
                    <h3 class="fw-bold my-1 text-dark">{{ number_format($totalBooks) }}</h3>
                    <span class="small text-muted"><i class="bi bi-book me-1"></i> {{ $totalTitles }} Judul Buku</span>
                </div>
                <div class="rounded-4 p-3 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-journal-bookmark fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Anggota -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">ANGGOTA AKTIF</span>
                    <h3 class="fw-bold my-1 text-dark">{{ number_format($totalMembers) }}</h3>
                    <span class="small text-success"><i class="bi bi-person-check me-1"></i> Terdaftar Aktif</span>
                </div>
                <div class="rounded-4 p-3 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Peminjaman Berjalan -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">PEMINJAMAN AKTIF</span>
                    <h3 class="fw-bold my-1 text-dark">{{ number_format($activeBorrows) }}</h3>
                    <span class="small text-primary"><i class="bi bi-arrow-repeat me-1"></i> Sedang Dipinjam</span>
                </div>
                <div class="rounded-4 p-3 bg-info bg-opacity-10 text-info">
                    <i class="bi bi-arrow-left-right fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Terlambat & Denda -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">JATUH TEMPO / DENDA</span>
                    <h3 class="fw-bold my-1 {{ $overdueBorrows > 0 ? 'text-danger' : 'text-dark' }}">{{ $overdueBorrows }}</h3>
                    <span class="small text-muted">Denda: <strong class="text-dark">Rp {{ number_format($totalFines, 0, ',', '.') }}</strong></span>
                </div>
                <div class="rounded-4 p-3 {{ $overdueBorrows > 0 ? 'bg-danger bg-opacity-10 text-danger' : 'bg-warning bg-opacity-10 text-warning' }}">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Charts and Quick Actions -->
<div class="row g-3 mb-4">
    <!-- Chart Transaksi 6 Bulan -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark"><i class="bi bi-graph-up me-2 text-primary"></i> Tren Sirkulasi Peminjaman & Pengembalian</span>
                <span class="badge bg-light text-secondary">6 Bulan Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="circulationChart" style="max-height: 280px; width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts & Info -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span class="fw-bold text-dark"><i class="bi bi-lightning-charge me-2 text-warning"></i> Menu Cepat</span>
            </div>
            <div class="card-body d-flex flex-column gap-2 justify-content-center">
                <a href="{{ route('borrows.create') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between p-3 rounded-3 text-start">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-plus-circle-fill fs-4 text-primary"></i>
                        <div>
                            <div class="fw-bold">Catat Peminjaman Baru</div>
                            <small class="text-muted">Proses peminjaman buku anggota</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('books.create') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-between p-3 rounded-3 text-start">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-book fs-4 text-secondary"></i>
                        <div>
                            <div class="fw-bold">Tambah Buku Baru</div>
                            <small class="text-muted">Input judul atau cetakan baru</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('members.create') }}" class="btn btn-outline-success d-flex align-items-center justify-content-between p-3 rounded-3 text-start">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-person-plus-fill fs-4 text-success"></i>
                        <div>
                            <div class="fw-bold">Registrasi Anggota</div>
                            <small class="text-muted">Pendaftaran anggota & cetak kartu</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('reports.transactions') }}" class="btn btn-outline-dark d-flex align-items-center justify-content-between p-3 rounded-3 text-start">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-printer-fill fs-4 text-dark"></i>
                        <div>
                            <div class="fw-bold">Cetak Laporan</div>
                            <small class="text-muted">Rekap transaksi bulanan</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Transaksi Terkini & Buku Terpopuler -->
<div class="row g-3">
    <!-- Transaksi Terkini -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i> Transaksi Sirkulasi Terkini</span>
                <a href="{{ route('borrows.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam / Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBorrows as $borrow)
                            <tr>
                                <td>
                                    <a href="{{ route('borrows.show', $borrow) }}" class="fw-semibold text-primary text-decoration-none">
                                        {{ $borrow->transaction_code }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $borrow->member->name }}</div>
                                    <small class="text-muted">{{ $borrow->member->member_code }}</small>
                                </td>
                                <td>
                                    @foreach($borrow->details as $detail)
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $detail->book->title }}">
                                            &bull; {{ $detail->book->title }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</div>
                                    <small class="text-muted">Jatuh Tempo: {{ \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    @if($borrow->status === 'dikembalikan')
                                        <span class="badge badge-subtle-success px-2 py-1"><i class="bi bi-check-circle me-1"></i> Dikembalikan</span>
                                    @elseif($borrow->status === 'terlambat')
                                        <span class="badge badge-subtle-danger px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i> Terlambat</span>
                                    @else
                                        <span class="badge badge-subtle-primary px-2 py-1"><i class="bi bi-clock me-1"></i> Dipinjam</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($borrow->status !== 'dikembalikan')
                                        <a href="{{ route('borrows.return', $borrow) }}" class="btn btn-sm btn-outline-success" title="Kembalikan Buku">
                                            <i class="bi bi-arrow-return-left"></i> Kembali
                                        </a>
                                    @else
                                        <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-sm btn-light text-muted" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Buku Terpopuler -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span class="fw-bold text-dark"><i class="bi bi-fire me-2 text-danger"></i> Buku Paling Sering Dipinjam</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($popularBooks as $index => $book)
                        <li class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <span class="fw-bold fs-5 text-muted" style="width: 20px;">{{ $index + 1 }}</span>
                                <div class="text-truncate">
                                    <div class="fw-semibold text-dark text-truncate" title="{{ $book->title }}">
                                        {{ $book->title }}
                                    </div>
                                    <small class="text-muted">{{ $book->author }} &bull; {{ $book->category->name }}</small>
                                </div>
                            </div>
                            <span class="badge badge-subtle-primary rounded-pill px-2 py-1">
                                {{ $book->borrow_details_count }}x
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-4 text-muted">Belum ada data peminjaman buku.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('circulationChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartMonths) !!},
                datasets: [
                    {
                        label: 'Peminjaman',
                        data: {!! json_encode($chartBorrows) !!},
                        backgroundColor: 'rgba(37, 99, 235, 0.85)',
                        borderColor: '#2563eb',
                        borderRadius: 6,
                        borderWidth: 1
                    },
                    {
                        label: 'Pengembalian',
                        data: {!! json_encode($chartReturns) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.85)',
                        borderColor: '#22c55e',
                        borderRadius: 6,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
