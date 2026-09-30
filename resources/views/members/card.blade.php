<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota - {{ $member->name }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            padding: 2rem;
            color: #1e293b;
        }

        .card-container {
            max-width: 480px;
            margin: 0 auto;
        }

        /* Front Card */
        .member-card {
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(30, 58, 138, 0.25);
            color: #ffffff;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-pattern {
            position: absolute;
            right: -40px;
            bottom: -40px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .card-pattern-2 {
            position: absolute;
            right: 60px;
            top: -50px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            pointer-events: none;
        }

        .card-barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 42px;
            letter-spacing: 2px;
            line-height: 1;
        }

        /* Back Card */
        .back-card {
            width: 100%;
            height: 280px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        @media print {
            body {
                background-color: transparent !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .card-container {
                max-width: 100% !important;
                margin: 0 !important;
            }
            .member-card, .back-card {
                box-shadow: none !important;
                break-inside: avoid;
                margin-bottom: 20px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="card-container">
        <!-- Action Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-printer-fill me-1"></i> Cetak Kartu Anggota
            </button>
        </div>

        <!-- FRONT CARD -->
        <div class="member-card mb-4">
            <div class="card-pattern"></div>
            <div class="card-pattern-2"></div>

            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2" style="border-color: rgba(255,255,255,0.2) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-white text-primary p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-book-half fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold tracking-wide" style="font-size: 0.9rem; text-transform: uppercase;">Perpustakaan Digital Mandiri</div>
                        <div style="font-size: 0.65rem; opacity: 0.85;">KARTU TANDA ANGGOTA RESMI</div>
                    </div>
                </div>
                <div class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.65rem; font-weight: 700;">
                    SIP MEMBER
                </div>
            </div>

            <!-- Body Profile -->
            <div class="d-flex align-items-center gap-3 my-auto py-2">
                <div class="rounded-3 bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white" style="width: 70px; height: 85px; border: 2px dashed rgba(255,255,255,0.4);">
                    <i class="bi bi-person-fill fs-1 opacity-75"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1 text-white">{{ $member->name }}</h5>
                    <div class="fw-semibold text-warning" style="font-size: 0.85rem; letter-spacing: 1px;">
                        {{ $member->member_code }}
                    </div>
                    <div style="font-size: 0.72rem; opacity: 0.9; margin-top: 4px;">
                        <div><i class="bi bi-telephone-fill me-1"></i> {{ $member->phone }}</div>
                        <div><i class="bi bi-calendar-check-fill me-1"></i> Sejak: {{ $member->joined_date->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Footer Barcode & Info -->
            <div class="d-flex justify-content-between align-items-end pt-2 border-top" style="border-color: rgba(255,255,255,0.15) !important;">
                <div>
                    <div class="card-barcode">{{ $member->member_code }}</div>
                    <small style="font-size: 0.65rem; opacity: 0.75;">Status: {{ strtoupper($member->status) }}</small>
                </div>
                <div class="text-end" style="font-size: 0.65rem; opacity: 0.8;">
                    <div>Berlaku Selama Menjadi Pemustaka</div>
                    <div>SIP DIGITAL SYSTEM</div>
                </div>
            </div>
        </div>

        <!-- BACK CARD -->
        <div class="back-card">
            <div>
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-2" style="font-size: 0.85rem;">
                    KETENTUAN PEMEGANG KARTU PERPUSTAKAAN
                </h6>
                <ol class="ps-3 mb-0 text-muted" style="font-size: 0.72rem; line-height: 1.6;">
                    <li>Kartu ini adalah bukti keanggotaan sah pada Perpustakaan Digital Mandiri.</li>
                    <li>Wajib dibawa setiap kali melakukan transaksi peminjaman atau pengembalian buku.</li>
                    <li>Maksimal peminjaman adalah 3 buku dengan batas waktu 7 (tujuh) hari kalender.</li>
                    <li>Keterlambatan pengembalian buku dikenakan denda sesuai tarif yang berlaku.</li>
                    <li>Pemegang kartu bertanggung jawab penuh atas keutuhan buku yang dipinjam.</li>
                    <li>Jika kartu ini hilang atau rusak, segera laporkan ke bagian layanan sirkulasi.</li>
                </ol>
            </div>

            <div class="d-flex justify-content-between align-items-end pt-2 border-top">
                <div class="text-muted" style="font-size: 0.65rem;">
                    <div>Layanan Sirkulasi: (021) 4786-9021</div>
                    <div>Email: layanan@sip-perpustakaan.sch.id</div>
                </div>
                <div class="text-center" style="font-size: 0.65rem;">
                    <div class="text-muted mb-4">Kepala Perpustakaan,</div>
                    <div class="fw-bold text-dark text-decoration-underline">Dra. Hj. Nurjanah, M.Hum</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
