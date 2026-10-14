@extends('layouts.app')

@section('title', 'Pencatatan Barang Masuk')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title" style="color: #34d399;">📥 Pencatatan Barang Masuk (+IN)</h1>
        <div class="page-subtitle">Penambahan stok fisik dengan transaksi database atomik (DB::transaction)</div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        ⬅️ Kembali ke Katalog
    </a>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto; border-top: 4px solid #10b981;">
    <form action="{{ route('stock.in.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label" for="product_id">Pilih Produk Implan / Cyberware *</label>
            <select id="product_id" name="product_id" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ (string)old('product_id', $selectedProductId) === (string)$p->id ? 'selected' : '' }}>
                        [{{ $p->sku }}] {{ $p->name }} (Stok Fisik: {{ $p->stock }} unit)
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="quantity">Kuantitas Masuk (Unit) *</label>
                <input type="number" min="1" id="quantity" name="quantity" class="form-control" 
                       placeholder="Misal: 10" value="{{ old('quantity') }}" required autofocus>
                @error('quantity')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="reference_no">Nomor Referensi / Surat Jalan (PO)</label>
                <input type="text" id="reference_no" name="reference_no" class="form-control mono" 
                       value="{{ old('reference_no', 'PO-' . date('Ymd') . '-' . rand(100, 999)) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="notes">Catatan Transaksi / Sumber Pasokan</label>
            <textarea id="notes" name="notes" class="form-control" 
                      placeholder="Contoh: Pasokan restock reguler dari Arasaka Corp via kurir khusus...">{{ old('notes') }}</textarea>
        </div>

        <div style="background: rgba(16, 185, 129, 0.08); border-left: 3px solid #10b981; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; color: #a7f3d0;">
            ℹ️ <strong>Aturan Bisnis:</strong> Penambahan saldo stok produk dan perekaman entri log <code>stock_movements</code> dibungkus dalam satu blok <code>DB::transaction()</code> atomik.
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #059669, #10b981); color: #fff;">
                ✅ Konfirmasi Barang Masuk
            </button>
        </div>
    </form>
</div>
@endsection
