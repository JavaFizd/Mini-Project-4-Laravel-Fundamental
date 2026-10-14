@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">✏️ Edit Produk [{{ $product->sku }}]</h1>
        <div class="page-subtitle">Demonstrasi alur: Form &rarr; @method('PUT') &rarr; Validasi &rarr; Eloquent Update</div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        ⬅️ Kembali ke Katalog
    </a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="sku">Kode SKU (Harus Unik) *</label>
                <input type="text" id="sku" name="sku" class="form-control mono" 
                       value="{{ old('sku', $product->sku) }}" required>
                @error('sku')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="category_id">Kategori Induk *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                   value="{{ old('name', $product->name) }}" required>
            @error('name')
                <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="price">Harga Satuan (Rp) *</label>
                <input type="number" step="0.01" min="0" id="price" name="price" class="form-control" 
                       value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Stok Fisik Saat Ini</label>
                <input type="text" class="form-control" value="{{ $product->stock }} unit (Gunakan menu Transaksi)" disabled>
            </div>

            <div class="form-group">
                <label class="form-label" for="minimum_stock">Batas Minimum Stok *</label>
                <input type="number" min="0" id="minimum_stock" name="minimum_stock" class="form-control" 
                       value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                @error('minimum_stock')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Vendor / Supplier Pemasok (Many-to-Many):</label>
            <div style="background: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; max-height: 180px; overflow-y: auto;">
                @foreach($suppliers as $s)
                    <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; cursor: pointer; font-size: 0.9rem;">
                        <input type="checkbox" name="suppliers[]" value="{{ $s->id }}" 
                               {{ in_array($s->id, old('suppliers', $selectedSuppliers)) ? 'checked' : '' }}>
                        <span>{{ $s->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                💾 Simpan Perubahan Produk
            </button>
        </div>
    </form>
</div>
@endsection
