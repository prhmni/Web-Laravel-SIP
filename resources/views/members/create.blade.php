@extends('layouts.app')

@section('title', 'Registrasi Anggota Baru')
@section('page-title', 'Registrasi Anggota Baru')
@section('page-subtitle', 'Pendaftaran pemustaka baru untuk akses layanan peminjaman buku.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i> Formulir Anggota Baru</span>
                <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Nomor Anggota & Tanggal Bergabung -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nomor Anggota <span class="text-danger">*</span></label>
                            <input type="text" name="member_code" class="form-control" value="{{ old('member_code', $suggestedCode) }}" required>
                            <small class="text-muted">Nomor identitas unik anggota perpustakaan.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Bergabung <span class="text-danger">*</span></label>
                            <input type="date" name="joined_date" class="form-control" value="{{ old('joined_date', date('Y-m-d')) }}" required>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap pemustaka" required>
                        </div>

                        <!-- Jenis Kelamin & Status -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status Keanggotaan <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <!-- Kontak: Telepon & Email -->
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="contoh: 081234567890" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alamat Email (Opsional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com">
                        </div>

                        <!-- Alamat -->
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat tempat tinggal...">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('members.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Data Anggota
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
