@extends('layouts.app')

@section('title', 'Pencatatan Peminjaman Buku')
@section('page-title', 'Pencatatan Peminjaman Buku')
@section('page-subtitle', 'Pilih anggota pemustaka dan buku yang akan dipinjam.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-journal-arrow-up me-2 text-primary"></i> Formulir Transaksi Peminjaman</span>
                <a href="{{ route('borrows.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('borrows.store') }}" method="POST" id="borrowForm">
                    @csrf
                    <!-- Kode Transaksi & Petugas -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                        <div class="col-md-6">
                            <span class="text-muted small">No. Transaksi Baru:</span>
                            <div class="fw-bold text-dark fs-6">{{ $transactionCode }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="text-muted small">Petugas Pelayan:</span>
                            <div class="fw-bold text-dark">{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
                        </div>
                    </div>

                    <!-- Pilih Anggota Pemustaka -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Pilih Anggota Pemustaka <span class="text-danger">*</span></label>
                        <select name="member_id" class="form-select select2" required>
                            <option value="">-- Pilih Nama atau Nomor Anggota --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->member_code }} - {{ $m->name }} (Telp: {{ $m->phone }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya anggota berstatus aktif yang ditampilkan.</small>
                    </div>

                    <!-- Pilih Buku (Dapat Memilih Lebih dari 1 Buku) -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label small fw-semibold m-0">
                                Buku Yang Dipinjam <span class="text-danger">*</span> (Maksimal 5 Buku)
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addBookBtn">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Buku Lain
                            </button>
                        </div>

                        <div id="booksContainer" class="d-flex flex-column gap-2">
                            <div class="input-group book-row">
                                <span class="input-group-text bg-white"><i class="bi bi-book text-muted"></i></span>
                                <select name="book_ids[]" class="form-select" required>
                                    <option value="">-- Pilih Buku yang Tersedia --</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}">
                                            [{{ $book->book_code }}] {{ $book->title }} (Stok Tersedia: {{ $book->available_stock }} | Rak: {{ $book->shelf_location ?: '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger remove-book-btn" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Pinjam & Jatuh Tempo -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="borrow_date" id="borrowDate" class="form-control" value="{{ old('borrow_date', $today) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="dueDate" class="form-control" value="{{ old('due_date', $suggestedDueDate) }}" required>
                            <small class="text-muted">Standar batas waktu pinjam: 7 hari kalender.</small>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Catatan / Keperluan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="contoh: Referensi skripsi, tugas praktikum...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('borrows.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Peminjaman Buku
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
        const booksContainer = document.getElementById('booksContainer');
        const addBookBtn = document.getElementById('addBookBtn');
        const maxBooks = 5;

        // Opsi buku yang tersedia dalam bentuk HTML template
        const bookOptionsHtml = `
            <option value="">-- Pilih Buku yang Tersedia --</option>
            @foreach($books as $book)
                <option value="{{ $book->id }}">
                    [{{ $book->book_code }}] {{ $book->title }} (Stok Tersedia: {{ $book->available_stock }} | Rak: {{ $book->shelf_location ?: '-' }})
                </option>
            @endforeach
        `;

        function updateRemoveButtons() {
            const rows = booksContainer.querySelectorAll('.book-row');
            rows.forEach((row, index) => {
                const btn = row.querySelector('.remove-book-btn');
                btn.disabled = rows.length <= 1;
            });
        }

        addBookBtn.addEventListener('click', function () {
            const rows = booksContainer.querySelectorAll('.book-row');
            if (rows.length >= maxBooks) {
                alert('Maksimal peminjaman dalam satu transaksi adalah ' + maxBooks + ' buku.');
                return;
            }

            const newRow = document.createElement('div');
            newRow.className = 'input-group book-row';
            newRow.innerHTML = `
                <span class="input-group-text bg-white"><i class="bi bi-book text-muted"></i></span>
                <select name="book_ids[]" class="form-select" required>
                    ${bookOptionsHtml}
                </select>
                <button type="button" class="btn btn-outline-danger remove-book-btn">
                    <i class="bi bi-trash"></i>
                </button>
            `;

            booksContainer.appendChild(newRow);
            updateRemoveButtons();

            // Bind click remove
            newRow.querySelector('.remove-book-btn').addEventListener('click', function () {
                newRow.remove();
                updateRemoveButtons();
            });
        });

        // Event listener delegasi untuk hapus row
        booksContainer.addEventListener('click', function (e) {
            if (e.target.closest('.remove-book-btn')) {
                const row = e.target.closest('.book-row');
                const rows = booksContainer.querySelectorAll('.book-row');
                if (rows.length > 1) {
                    row.remove();
                    updateRemoveButtons();
                }
            }
        });

        // Otomatis atur tanggal jatuh tempo 7 hari saat tanggal pinjam berubah
        const borrowDateInput = document.getElementById('borrowDate');
        const dueDateInput = document.getElementById('dueDate');

        borrowDateInput.addEventListener('change', function () {
            if (this.value) {
                const date = new Date(this.value);
                date.setDate(date.getDate() + 7);
                const yyyy = date.getFullYear();
                const mm = String(date.getMonth() + 1).padStart(2, '0');
                const dd = String(date.getDate()).padStart(2, '0');
                dueDateInput.value = `${yyyy}-${mm}-${dd}`;
            }
        });
    });
</script>
@endpush
