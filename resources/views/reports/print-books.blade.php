<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Koleksi Buku - Perpustakaan Digital</title>
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

        <!-- Kop Surat -->
        <div class="text-center border-bottom pb-3 mb-4" style="border-width: 2px !important; border-color: #0f172a !important;">
            <h4 class="fw-bold mb-0 text-uppercase tracking-wider">Perpustakaan Digital Mandiri</h4>
            <div class="small text-muted">Jl. Pemuda No. 128, Rawamangun, Jakarta Timur &bull; Telp: (021) 4786-9021</div>
            <div class="small text-muted">Email: layanan@sip-perpustakaan.sch.id &bull; Website: http://localhost/SIP/public</div>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center mb-4">
            <h5 class="fw-bold text-uppercase mb-1">LAPORAN DATA INVENTARIS KOLEKSI BUKU</h5>
            <div class="text-muted small">
                Kategori: <strong>{{ $categoryName }}</strong> &bull; Total Judul: <strong>{{ $books->count() }} Judul</strong> &bull; Total Eksemplar: <strong>{{ $books->sum('stock') }} Buku</strong>
            </div>
        </div>

        <!-- Tabel Data Buku -->
        <table class="table table-print mb-4">
            <thead>
                <tr class="text-center">
                    <th style="width: 35px;">No</th>
                    <th>Kode Buku</th>
                    <th>Judul Buku & ISBN</th>
                    <th>Kategori</th>
                    <th>Penulis / Penerbit</th>
                    <th>Tahun</th>
                    <th>Lokasi Rak</th>
                    <th style="width: 70px;">Total</th>
                    <th style="width: 70px;">Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $book)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $book->book_code }}</td>
                        <td>
                            <strong>{{ $book->title }}</strong>
                            @if($book->isbn)
                                <div class="text-muted" style="font-size: 0.7rem;">ISBN: {{ $book->isbn }}</div>
                            @endif
                        </td>
                        <td>{{ $book->category->name }}</td>
                        <td>
                            <div>{{ $book->author }}</div>
                            <small class="text-muted">{{ $book->publisher }}</small>
                        </td>
                        <td class="text-center">{{ $book->publish_year }}</td>
                        <td class="text-center">{{ $book->shelf_location ?: '-' }}</td>
                        <td class="text-center fw-bold">{{ $book->stock }}</td>
                        <td class="text-center fw-bold {{ $book->available_stock > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $book->available_stock }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            Tidak ada data koleksi buku.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="7" class="text-end">REKAPITULASI TOTAL FISIK:</td>
                    <td class="text-center">{{ $books->sum('stock') }}</td>
                    <td class="text-center text-success">{{ $books->sum('available_stock') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Pengesahan -->
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
                <div>Petugas Bagian Pengadaan & Inventaris</div>
                <div style="height: 65px;"></div>
                <div class="fw-bold text-decoration-underline">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="text-muted">{{ ucfirst(Auth::user()->role ?? 'Petugas') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
