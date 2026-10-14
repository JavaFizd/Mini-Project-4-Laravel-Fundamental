@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">➕ Tambah Produk Sibernetik (Laravel Form)</h1>
        <div class="page-subtitle">Demonstrasi alur: Form &rarr; @csrf &rarr; Validasi Request &rarr; Eloquent Create &rarr; Redirect</div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        ⬅️ Kembali ke Katalog
    </a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="sku">Kode SKU (Harus Unik) *</label>
                <input type="text" id="sku" name="sku" class="form-control mono" 
                       placeholder="Misal: NV-BIO-XYZ01" value="{{ old('sku') }}" required>
                @error('sku')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="category_id">Kategori Induk (Relationship) *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="name">Nama Produk *</label>
            <input type="text" id="name" name="name" class="form-control" 
                   placeholder="Contoh: Kiroshi Optics Gen 4 Vision" value="{{ old('name') }}" required>
            @error('name')
                <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="price">Harga Satuan (Rp) *</label>
                <input type="number" step="0.01" min="0" id="price" name="price" class="form-control" 
                       placeholder="0.00" value="{{ old('price', '0') }}" required>
                @error('price')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="stock">Stok Awal Fisik *</label>
                <input type="number" min="0" id="stock" name="stock" class="form-control" 
                       value="{{ old('stock', '0') }}" required>
                @error('stock')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="minimum_stock">Batas Minimum Stok *</label>
                <input type="number" min="0" id="minimum_stock" name="minimum_stock" class="form-control" 
                       value="{{ old('minimum_stock', '5') }}" required>
                @error('minimum_stock')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi Spesifikasi Teknis</label>
            <textarea id="description" name="description" class="form-control" 
                      placeholder="Rincian fitur, toleransi cyberware, dll...">{{ old('description') }}</textarea>
        </div>

        <!-- Relasi Many-to-Many Supplier (Slide 08 & 33) -->
        <div class="form-group">
            <label class="form-label">Vendor / Supplier Pemasok (Many-to-Many):</label>
            <div style="background: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; max-height: 180px; overflow-y: auto;">
                @foreach($suppliers as $s)
                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; cursor: pointer; font-size: 0.9rem;">
                        <input type="checkbox" name="suppliers[]" value="{{ $s->id }}" 
                               {{ is_array(old('suppliers')) && in_array($s->id, old('suppliers')) ? 'checked' : '' }}>
                        <span>{{ $s->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                💾 Simpan Produk ke Database (Eloquent)
            </button>
        </div>
    </form>
</div>
@endsection
