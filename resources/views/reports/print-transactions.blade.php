<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Sirkulasi - Perpustakaan Digital</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            padding: 2rem;
        }

        .report-page {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .table-print th {
            background-color: #f1f5f9;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 0.5rem;
            border: 1px solid #cbd5e1;
        }

        .table-print td {
            font-size: 0.8rem;
            padding: 0.5rem;
            border: 1px solid #cbd5e1;
        }

        @media print {
            body {
                background-color: transparent !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .report-page {
                border: none !important;
                box-shadow: none !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="report-page">
        <!-- Action Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print border-bottom pb-3">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-printer-fill me-1"></i> Cetak Dokumen Laporan
            </button>
        </div>

        <!-- Kop Surat Resmi -->
        <div class="text-center border-bottom pb-3 mb-4" style="border-width: 2px !important; border-color: #0f172a !important;">
            <h4 class="fw-bold mb-0 text-uppercase tracking-wider">Perpustakaan Digital Mandiri</h4>
            <div class="small text-muted">Jl. Pemuda No. 128, Rawamangun, Jakarta Timur &bull; Telp: (021) 4786-9021</div>
            <div class="small text-muted">Email: layanan@sip-perpustakaan.sch.id &bull; Website: http://localhost/SIP/public</div>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center mb-4">
            <h5 class="fw-bold text-uppercase mb-1">LAPORAN TRANSAKSI SIRKULASI PERPUSTAKAAN</h5>
            <div class="text-muted small">
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
                @if($status)
                    &bull; Status: <strong>{{ strtoupper($status) }}</strong>
                @endif
            </div>
        </div>

        <!-- Tabel Data -->
        <table class="table table-print mb-4">
            <thead>
                <tr class="text-center">
                    <th style="width: 35px;">No</th>
                    <th>No. Transaksi</th>
                    <th>Nama Peminjam</th>
                    <th>Buku Dipinjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $index => $b)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $b->transaction_code }}</td>
                        <td>
                            <strong>{{ $b->member->name }}</strong>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $b->member->member_code }}</div>
                        </td>
                        <td>
                            @foreach($b->details as $d)
                                <div>&bull; {{ $d->book->title }} ({{ $d->book->book_code }})</div>
                            @endforeach
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($b->due_date)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $b->return_date ? \Carbon\Carbon::parse($b->return_date)->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            @if($b->status === 'dikembalikan')
                                <span class="text-success fw-bold">Kembali</span>
                            @elseif($b->status === 'terlambat')
                                <span class="text-danger fw-bold">Terlambat</span>
                            @else
                                <span class="text-primary fw-bold">Dipinjam</span>
                            @endif
                        </td>
                        <td class="text-end">
                            {{ $b->late_fee > 0 ? number_format($b->late_fee, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            Tidak ada transaksi pada periode yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="8" class="text-end">TOTAL DENDA DITERIMA:</td>
                    <td class="text-end">Rp {{ number_format($totalFines, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Pengesahan / Tanda Tangan -->
        <div class="row pt-4 text-center small">
            <div class="col-6">
                <div>Mengetahui,</div>
                <div>Kepala Perpustakaan Digital</div>
                <div style="height: 65px;"></div>
                <div class="fw-bold text-decoration-underline">Dra. Hj. Nurjanah, M.Hum</div>
                <div class="text-muted">NIP. 19780512 200501 2 004</div>
            </div>
            <div class="col-6">
                <div>Jakarta, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</div>
                <div>Petugas Layanan Sirkulasi</div>
                <div style="height: 65px;"></div>
                <div class="fw-bold text-decoration-underline">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="text-muted">{{ ucfirst(Auth::user()->role ?? 'Petugas') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
