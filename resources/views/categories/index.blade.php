@extends('layouts.app')

@section('title', 'Kategori Buku')
@section('page-title', 'Kategori Buku')
@section('page-subtitle', 'Kelola klasifikasi dan pengelompokan koleksi perpustakaan.')

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <!-- Search Box -->
                <form action="{{ route('categories.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0" style="max-width: 320px;">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama kategori..." value="{{ $search }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        @if($search)
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-danger" title="Reset"><i class="bi bi-x"></i></a>
                        @endif
                    </div>
                </form>

                <!-- Tombol Tambah Kategori (Modal Trigger) -->
                <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAddCategory">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th style="width: 140px;" class="text-center">Jumlah Buku</th>
                            <th style="width: 130px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $cat)
                            <tr>
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $cat->name }}</div>
                                    <small class="text-muted">Slug: <code>{{ $cat->slug }}</code></small>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $cat->description ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-subtle-primary px-3 py-1 fs-6">
                                        {{ $cat->books_count }} buku
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="btn btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditCategory{{ $cat->id }}" 
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <!-- Delete Trigger -->
                                        <button type="button" class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalDeleteCategory{{ $cat->id }}" 
                                                title="Hapus"
                                                {{ $cat->books_count > 0 ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Edit Kategori -->
                                    <div class="modal fade text-start" id="modalEditCategory{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('categories.update', $cat) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold">Edit Kategori Buku</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Nama Kategori</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Deskripsi (Opsional)</label>
                                                            <textarea name="description" class="form-control" rows="3">{{ $cat->description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade text-start" id="modalDeleteCategory{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4 text-center">
                                                    <i class="bi bi-exclamation-circle text-danger display-4 mb-3 d-block"></i>
                                                    <h6 class="fw-bold mb-2">Hapus Kategori?</h6>
                                                    <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus kategori <strong>{{ $cat->name }}</strong>?</p>
                                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-flex justify-content-center gap-2">
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
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-tags display-6 text-secondary d-block mb-2"></i>
                                    Tidak ada data kategori yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori Baru -->
<div class="modal fade" id="modalAddCategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Kategori Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="contoh: Sastra & Novel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Keterangan singkat mengenai kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
