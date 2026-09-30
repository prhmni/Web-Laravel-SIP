@extends('layouts.app')

@section('title', 'Pengaturan Sistem Perpustakaan')
@section('page-title', 'Pengaturan Sistem Perpustakaan')
@section('page-subtitle', 'Konfigurasi identitas perpustakaan, aturan sirkulasi, dan tarif denda keterlambatan.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <span class="fw-bold"><i class="bi bi-sliders me-2 text-primary"></i> Parameter Konfigurasi Perpustakaan</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-building me-2 text-primary"></i> Identitas & Kontak Perpustakaan
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Instansi / Perpustakaan <span class="text-danger">*</span></label>
                            <input type="text" name="library_name" class="form-control" value="{{ old('library_name', $settings['library_name']) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nomor Telepon Layanan</label>
                            <input type="text" name="library_phone" class="form-control" value="{{ old('library_phone', $settings['library_phone']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alamat Email Resmi</label>
                            <input type="email" name="library_email" class="form-control" value="{{ old('library_email', $settings['library_email']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alamat Lengkap Kantor</label>
                            <input type="text" name="library_address" class="form-control" value="{{ old('library_address', $settings['library_address']) }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-clock-history me-2 text-warning"></i> Kebijakan & Aturan Sirkulasi
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Lama Pinjam Standar (Hari) <span class="text-danger">*</span></label>
                            <input type="number" name="loan_duration_days" class="form-control" value="{{ old('loan_duration_days', $settings['loan_duration_days']) }}" min="1" max="60" required>
                            <small class="text-muted">Batas waktu sebelum status menjadi terlambat.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tarif Denda per Hari (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="late_fee_per_day" class="form-control" value="{{ old('late_fee_per_day', $settings['late_fee_per_day']) }}" min="0" required>
                            <small class="text-muted">Dihitung per buku per hari keterlambatan.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Batas Maks. Buku per Transaksi <span class="text-danger">*</span></label>
                            <input type="number" name="max_books_per_loan" class="form-control" value="{{ old('max_books_per_loan', $settings['max_books_per_loan']) }}" min="1" max="10" required>
                            <small class="text-muted">Jumlah maksimal buku sekali pinjam.</small>
                        </div>
                    </div>

                    <div class="pt-3 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
