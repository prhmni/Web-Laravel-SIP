<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi - {{ $borrow->transaction_code }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            padding: 2rem;
        }

        .receipt-card {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .table-receipt th {
            background-color: #f1f5f9;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 0.6rem 0.75rem;
        }

        .table-receipt td {
            font-size: 0.85rem;
            padding: 0.6rem 0.75rem;
        }

        @media print {
            body {
                background-color: transparent !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                border: none !important;
                box-shadow: none !important;
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <!-- Action Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print border-bottom pb-3">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-printer-fill me-1"></i> Cetak Bukti Transaksi
            </button>
        </div>

        <!-- Kop Transaksi Perpustakaan -->
        <div class="text-center border-bottom pb-3 mb-4">
            <h5 class="fw-bold text-dark mb-0 text-uppercase">Perpustakaan Digital Mandiri</h5>
            <div class="text-muted small">Jl. Pemuda No. 128, Rawamangun, Jakarta Timur &bull; Telp: (021) 4786-9021</div>
            <div class="text-muted small">Website: http://localhost/SIP/public &bull; Email: layanan@sip-perpustakaan.sch.id</div>
        </div>

        <div class="text-center mb-3">
            <h6 class="fw-bold text-dark text-uppercase letter-spacing-1 mb-1">
                BUKTI TRANSAKSI {{ $borrow->status === 'dikembalikan' ? 'PENGEMBALIAN' : 'PEMINJAMAN' }} BUKU
            </h6>
            <span class="badge bg-light text-dark border px-2 py-1">
                NO: <strong>{{ $borrow->transaction_code }}</strong>
            </span>
        </div>

        <!-- Data Peminjam & Transaksi -->
        <div class="row g-2 mb-3 small">
            <div class="col-6">
                <div><span class="text-muted">No. Anggota:</span> <strong>{{ $borrow->member->member_code }}</strong></div>
                <div><span class="text-muted">Nama Peminjam:</span> <strong>{{ $borrow->member->name }}</strong></div>
                <div><span class="text-muted">Telepon:</span> {{ $borrow->member->phone }}</div>
            </div>
            <div class="col-6 text-end">
                <div><span class="text-muted">Tanggal Pinjam:</span> <strong>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</strong></div>
                <div><span class="text-muted">Batas Jatuh Tempo:</span> <strong>{{ \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y') }}</strong></div>
                @if($borrow->return_date)
                    <div><span class="text-muted">Tanggal Kembali:</span> <strong class="text-success">{{ \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') }}</strong></div>
                @endif
                <div><span class="text-muted">Petugas:</span> {{ $borrow->user->name }}</div>
            </div>
        </div>

        <!-- Tabel Koleksi Buku -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-receipt mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Kode & Judul Buku</th>
                        <th>Kategori</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrow->details as $index => $detail)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $detail->book->title }}</div>
                                <small class="text-muted">Kode: {{ $detail->book->book_code }} | Rak: {{ $detail->book->shelf_location ?: '-' }}</small>
                            </td>
                            <td>{{ $detail->book->category->name }}</td>
                            <td class="text-center">
                                @if($detail->status === 'dikembalikan')
                                    <span class="text-success fw-bold">Kembali</span>
                                @else
                                    <span class="text-primary fw-bold">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Denda & Catatan -->
        @if($borrow->late_fee > 0 || $borrow->notes)
            <div class="p-2 bg-light rounded-2 small mb-4">
                @if($borrow->late_fee > 0)
                    <div class="d-flex justify-content-between text-danger fw-bold">
                        <span>Denda Keterlambatan ({{ $borrow->late_days }} hari):</span>
                        <span>Rp {{ number_format($borrow->late_fee, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($borrow->notes)
                    <div class="text-muted mt-1">Catatan: {{ $borrow->notes }}</div>
                @endif
            </div>
        @endif

        <!-- Tanda Tangan -->
        <div class="row pt-4 text-center small">
            <div class="col-6">
                <div>Peminjam,</div>
                <div style="height: 60px;"></div>
                <div class="fw-bold text-decoration-underline">{{ $borrow->member->name }}</div>
            </div>
            <div class="col-6">
                <div>Petugas Perpustakaan,</div>
                <div style="height: 60px;"></div>
                <div class="fw-bold text-decoration-underline">{{ $borrow->user->name }}</div>
            </div>
        </div>
    </div>
</body>
</html>
