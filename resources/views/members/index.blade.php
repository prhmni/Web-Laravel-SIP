@extends('layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Manajemen Data Anggota')
@section('page-subtitle', 'Kelola data pemustaka, riwayat keanggotaan, dan cetak kartu anggota.')

@section('content')
<div class="card">
    <div class="card-header p-3">
        <form action="{{ route('members.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Filter Status -->
            <div class="col-md-3 col-sm-6">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Search Field -->
            <div class="col-md-5 col-sm-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, nomor anggota, email, atau telepon..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if($search || $status)
                        <a href="{{ route('members.index') }}" class="btn btn-outline-danger" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>

            <!-- Tombol Tambah Anggota -->
            <div class="col-md-4 text-md-end text-start mt-2 mt-md-0">
                <a href="{{ route('members.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Registrasi Anggota Baru
                </a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Lengkap</th>
                    <th>Gender</th>
                    <th>Kontak</th>
                    <th>Tgl Bergabung</th>
                    <th class="text-center" style="width: 100px;">Status</th>
                    <th class="text-center" style="width: 120px;">Pinjaman Aktif</th>
                    <th style="width: 150px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $index => $member)
                    <tr>
                        <td>{{ $members->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <code>{{ $member->member_code }}</code>
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('members.show', $member) }}" class="fw-bold text-dark text-decoration-none">
                                {{ $member->name }}
                            </a>
                            @if($member->address)
                                <div class="text-muted small text-truncate" style="max-width: 250px;">
                                    <i class="bi bi-geo-alt me-1"></i> {{ $member->address }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $member->gender }}</td>
                        <td>
                            <div><i class="bi bi-telephone text-muted me-1"></i> {{ $member->phone }}</div>
                            @if($member->email)
                                <small class="text-muted"><i class="bi bi-envelope me-1"></i> {{ $member->email }}</small>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($member->joined_date)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if($member->status === 'aktif')
                                <span class="badge badge-subtle-success px-2 py-1">Aktif</span>
                            @else
                                <span class="badge badge-subtle-secondary px-2 py-1">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($member->active_borrows_count > 0)
                                <span class="badge badge-subtle-primary px-2 py-1">
                                    {{ $member->active_borrows_count }} transaksi
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('members.card', $member) }}" class="btn btn-outline-info" title="Cetak Kartu Anggota" target="_blank">
                                    <i class="bi bi-person-vcard"></i>
                                </a>
                                <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMemberModal{{ $member->id }}" title="Hapus" {{ $member->active_borrows_count > 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Modal Konfirmasi Hapus Anggota -->
                            <div class="modal fade text-start" id="deleteMemberModal{{ $member->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body p-4 text-center">
                                            <i class="bi bi-exclamation-triangle text-danger display-4 mb-3 d-block"></i>
                                            <h6 class="fw-bold mb-2">Hapus Anggota?</h6>
                                            <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus data anggota <strong>{{ $member->name }}</strong>?</p>
                                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-flex justify-content-center gap-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-light px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-danger px-3">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-6 text-secondary d-block mb-2"></i>
                            Tidak ada data anggota yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
        <div class="card-footer bg-white border-top-0 d-flex justify-content-end p-3">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection
