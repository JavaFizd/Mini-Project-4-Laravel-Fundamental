@extends('layouts.app')

@section('title', 'Pencatatan Barang Keluar')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title" style="color: #fb7185;">📤 Pencatatan Barang Keluar (-OUT)</h1>
        <div class="page-subtitle">Validasi integritas stok dengan lockForUpdate() dan pencegahan nilai stok negatif</div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        ⬅️ Kembali ke Katalog
    </a>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto; border-top: 4px solid #f43f5e;">
    <form action="{{ route('stock.out.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label" for="product_id">Pilih Produk yang Dikeluarkan *</label>
            <select id="product_id" name="product_id" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ (string)old('product_id', $selectedProductId) === (string)$p->id ? 'selected' : '' }}>
                        [{{ $p->sku }}] {{ $p->name }} (Tersedia: {{ $p->stock }} unit)
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="quantity">Kuantitas Keluar (Unit) *</label>
                <input type="number" min="1" id="quantity" name="quantity" class="form-control" 
                       placeholder="Misal: 2" value="{{ old('quantity') }}" required autofocus>
                @error('quantity')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="reference_no">Nomor Referensi / Sales Order (SO)</label>
                <input type="text" id="reference_no" name="reference_no" class="form-control mono" 
                       value="{{ old('reference_no', 'SO-' . date('Ymd') . '-' . rand(100, 999)) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="notes">Tujuan Pengeluaran / Keterangan Klien</label>
            <textarea id="notes" name="notes" class="form-control" 
                      placeholder="Contoh: Operasi pemasangan implan cyberware klien solo...">{{ old('notes') }}</textarea>
        </div>

        <div style="background: rgba(244, 63, 94, 0.08); border-left: 3px solid #f43f5e; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; color: #fecdd3;">
            ⚠️ <strong>Aturan Bisnis (Slide 26 & 33):</strong> Query mengunci baris data dengan <code>Product::lockForUpdate()</code>. Jika kuantitas keluar melebihi stok yang ada, transaksi otomatis di-rollback seketika sehingga stok tidak akan pernah minus.
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #e11d48, #f43f5e); color: #fff;">
                🚀 Konfirmasi Barang Keluar
            </button>
        </div>
    </form>
</div>
@endsection
