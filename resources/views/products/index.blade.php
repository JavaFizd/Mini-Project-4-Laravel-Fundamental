@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📦 Katalog Inventaris Produk (Resource Controller)</h1>
        <div class="page-subtitle">Eloquent Eager Loading, Search, Filter Kategori, Allowlist Sorting, & Pagination</div>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        ➕ Tambah Produk Baru
    </a>
</div>

<!-- Form Search, Filter, dan Sorting (Slide 16 & 38) -->
<div class="filter-bar">
    <form method="GET" action="{{ route('products.index') }}" class="filter-form">
        <!-- Input Search -->
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="search" class="form-control" 
                   placeholder="🔍 Cari nama atau SKU..." value="{{ $search }}">
        </div>

        <!-- Filter Kategori -->
        <div style="min-width: 180px;">
            <select name="category_id" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Sorting Allowlist -->
        <div style="min-width: 150px;">
            <select name="sort_by" class="form-control">
                <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Urut: Nama</option>
                <option value="sku" {{ $sortBy === 'sku' ? 'selected' : '' }}>Urut: SKU</option>
                <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Urut: Harga</option>
                <option value="stock" {{ $sortBy === 'stock' ? 'selected' : '' }}>Urut: Stok</option>
                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Urut: Tanggal</option>
            </select>
        </div>

        <!-- Sorting Direction -->
        <div style="min-width: 110px;">
            <select name="sort_dir" class="form-control">
                <option value="ASC" {{ $sortDir === 'ASC' ? 'selected' : '' }}>A &rarr; Z (ASC)</option>
                <option value="DESC" {{ $sortDir === 'DESC' ? 'selected' : '' }}>Z &rarr; A (DESC)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-secondary">
            Terapkan
        </button>

        @if($search || $categoryId || $sortBy !== 'name' || $sortDir !== 'ASC')
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm" title="Reset filter">
                ✕ Reset
            </a>
        @endif
    </form>

    <div style="font-size: 0.85rem; color: var(--text-muted);">
        Menampilkan <strong>{{ $products->count() }}</strong> dari <strong>{{ $products->total() }}</strong> unit
    </div>
</div>

<!-- Tabel Listing Produk dengan Forelse (Slide 39) -->
<div class="table-responsive">
    <table class="custom-table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori (Relationship)</th>
                <th>Harga Satuan</th>
                <th>Stok / Min</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
                <tr>
                    <td class="mono" style="color: var(--cyan-neon); font-weight: 600;">
                        {{ $p->sku }}
                    </td>
                    <td>
                        <strong>{{ $p->name }}</strong>
                    </td>
                    <td>
                        <span class="badge badge-purple">{{ $p->category->name ?? 'Tanpa Kategori' }}</span>
                    </td>
                    <td class="mono" style="color: #38bdf8;">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </td>
                    <td>
                        <strong style="color: #fff;">{{ $p->stock }}</strong>
                        <small style="color: var(--text-dim);">/ min {{ $p->minimum_stock }}</small>
                    </td>
                    <td>
                        @if($p->stock <= 0)
                            <span class="badge badge-danger">Habis (0)</span>
                        @elseif($p->stock <= $p->minimum_stock)
                            <span class="badge badge-warning">Kritis ({{ $p->stock }})</span>
                        @else
                            <span class="badge badge-success">Aman ({{ $p->stock }})</span>
                        @endif
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('products.show', $p) }}" class="btn btn-secondary btn-sm">
                            👁️ Detail
                        </a>
                        <a href="{{ route('products.edit', $p) }}" class="btn btn-secondary btn-sm" style="color: #fbbf24;">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('products.destroy', $p) }}" method="POST" style="display: inline-block;"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus [{{ $p->sku }}]? Jika memiliki histori mutasi, penghapusan akan diblokir.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                🗑️ Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-muted);">
                        🔍 Tidak ditemukan data produk yang cocok dengan kriteria pencarian atau filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links dengan withQueryString (Slide 24 & 32) -->
<div style="margin-top: 24px;">
    {{ $products->links() }}
</div>
@endsection
