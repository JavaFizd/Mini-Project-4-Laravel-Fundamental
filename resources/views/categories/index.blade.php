@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🏷️ Master Data Kategori (Relationship)</h1>
        <div class="page-subtitle">Relasi One-to-Many: Satu Kategori memiliki Banyak Produk (hasMany)</div>
    </div>
</div>

<div class="grid-cols-2">
    <!-- Form Tambah Kategori -->
    <div class="card">
        <div class="card-title">
            <span>➕ Tambah Kategori Baru</span>
        </div>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama Kategori *</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ old('name') }}" placeholder="Misal: Neural Augmentations" required>
                @error('name')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi Singkat</label>
                <textarea id="description" name="description" class="form-control" 
                          placeholder="Deskripsi jenis produk dalam kategori ini...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                ➕ Tambahkan Kategori
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="card">
        <div class="card-title">
            <span>Daftar Kategori Terdaftar</span>
            <span class="badge badge-purple">{{ $categories->count() }} Kategori</span>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <strong>{{ $cat->name }}</strong><br>
                                <small style="color: var(--text-muted);">{{ $cat->description ?: '-' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-cyan">{{ $cat->products_count }} Produk</span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display: inline-block;"
                                      onsubmit="return confirm('Hapus kategori {{ $cat->name }}? Jika masih ada produk terhubung, penghapusan akan diblokir.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
