@extends('layouts.app')

@section('title', 'Proses Pengembalian Buku')
@section('page-title', 'Proses Pengembalian Buku')
@section('page-subtitle', 'Penerimaan kembali buku pinjaman, kalkulasi denda, dan pembaruan stok.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-arrow-return-left me-2 text-success"></i> Konfirmasi Pengembalian Buku</span>
                <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('borrows.process-return', $borrow) }}" method="POST">
                    @csrf

                    <!-- Ringkasan Singkat Transaksi -->
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-3">
                                <span class="text-muted small">No. Transaksi</span>
                                <div class="fw-bold text-dark">{{ $borrow->transaction_code }}</div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <span class="text-muted small">Peminjam</span>
                                <div class="fw-bold text-dark">{{ $borrow->member->name }}</div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <span class="text-muted small">Tanggal Pinjam</span>
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <span class="text-muted small">Jatuh Tempo</span>
                                <div class="fw-bold text-danger">{{ \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Buku Yang Dikembalikan -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Buku Yang Dikembalikan ({{ $borrow->details->count() }} Judul):</label>
                        <div class="list-group">
                            @foreach($borrow->details as $d)
                                <div class="list-group-item d-flex align-items-center justify-content-between p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-book-half fs-4 text-primary"></i>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $d->book->title }}</div>
                                            <small class="text-muted">Kode: <code>{{ $d->book->book_code }}</code> &bull; Lokasi: {{ $d->book->shelf_location ?: 'Rak Umum' }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        <i class="bi bi-check-lg me-1"></i> Akan Masuk Stok (+1)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Input Tanggal Kembali & Kalkulasi Denda Real-time -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Pengembalian Fisik <span class="text-danger">*</span></label>
                            <input type="date" name="return_date" id="returnDateInput" class="form-control" value="{{ old('return_date', date('Y-m-d')) }}" required>
                            <small class="text-muted">Tanggal saat buku diserahkan ke perpustakaan.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Total Denda Keterlambatan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Rp</span>
                                <input type="number" name="late_fee" id="lateFeeInput" class="form-control fw-bold" value="{{ old('late_fee', $totalLateFee) }}" min="0" required>
                            </div>
                            <small class="text-muted" id="lateCalculationNote">
                                Tarif: Rp {{ number_format($feePerDay, 0, ',', '.') }}/hari per buku. Terlambat: <strong id="lateDaysText">{{ $lateDays }}</strong> hari.
                            </small>
                        </div>
                    </div>

                    <!-- Catatan Kondisi Buku -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Catatan Pengembalian (Kondisi Buku, Pembayaran Denda, dll.)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="contoh: Semua buku dikembalikan dalam keadaan rapi dan bersih. Denda lunas.">{{ old('notes', $borrow->notes) }}</textarea>
                    </div>

                    <div class="pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Selesaikan Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const returnDateInput = document.getElementById('returnDateInput');
        const lateFeeInput = document.getElementById('lateFeeInput');
        const lateDaysText = document.getElementById('lateDaysText');
        const dueDate = new Date("{{ $borrow->due_date }}");
        const feePerDay = {{ $feePerDay }};
        const bookCount = {{ $borrow->details->count() }};

        function recalculate() {
            const returnDate = new Date(returnDateInput.value);
            // Reset hours for accurate day diff
            dueDate.setHours(0,0,0,0);
            returnDate.setHours(0,0,0,0);

            let diffTime = returnDate - dueDate;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 0) {
                lateDaysText.innerText = diffDays;
                const calculatedFee = diffDays * feePerDay * bookCount;
                lateFeeInput.value = calculatedFee;
            } else {
                lateDaysText.innerText = "0";
                lateFeeInput.value = 0;
            }
        }

        returnDateInput.addEventListener('change', recalculate);
    });
</script>
@endpush
