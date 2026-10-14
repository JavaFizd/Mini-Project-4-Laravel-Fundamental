@extends('layouts.app')

@section('title', 'Detail Produk — ' . $product->name)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🔍 Rincian Spesifikasi Produk (Eloquent Show)</h1>
        <div class="page-subtitle">Relasi BelongsTo Kategori, BelongsToMany Supplier, & HasMany StockMovements</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('stock.in', ['product_id' => $product->id]) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #059669, #10b981); color: #fff;">
            📥 + Restock Masuk
        </a>
        <a href="{{ route('stock.out', ['product_id' => $product->id]) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #e11d48, #f43f5e); color: #fff;">
            📤 - Catat Keluar
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            ⬅️ Kembali ke Katalog
        </a>
    </div>
</div>

<div class="grid-cols-2">
    <!-- Card Info Produk -->
    <div class="card">
        <div class="card-title">
            <span class="mono" style="color: var(--cyan-neon); font-size: 1.3rem;">[{{ $product->sku }}]</span>
            @if($product->stock <= 0)
                <span class="badge badge-danger">Habis (0)</span>
            @elseif($product->stock <= $product->minimum_stock)
                <span class="badge badge-warning">Kritis ({{ $product->stock }})</span>
            @else
                <span class="badge badge-success">Aman ({{ $product->stock }})</span>
            @endif
        </div>

        <h2 style="color: #fff; margin-bottom: 8px;">{{ $product->name }}</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; line-height: 1.6;">
            {{ $product->description ?: 'Tidak ada rincian deskripsi spesifikasi tambahan.' }}
        </p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; background: var(--bg-input); padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Kategori Induk:</span>
                <div style="font-weight: 600; color: #c084fc;">{{ $product->category->name ?? '-' }}</div>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Harga Jual Satuan:</span>
                <div class="mono" style="font-weight: 700; color: #38bdf8;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Stok Fisik Tersedia:</span>
                <div style="font-size: 1.2rem; font-weight: 700; color: #fff;">{{ $product->stock }} unit</div>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Batas Minimum Stok:</span>
                <div style="font-weight: 600; color: #fbbf24;">{{ $product->minimum_stock }} unit</div>
            </div>
            <div style="grid-column: span 2; border-top: 1px dashed var(--border-color); padding-top: 10px;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Total Valuasi Aset Stok:</span>
                <div class="mono" style="font-size: 1.25rem; font-weight: 700; color: var(--cyan-neon);">
                    Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm" style="color: #fbbf24;">
                ✏️ Edit Data Produk
            </a>
        </div>
    </div>

    <!-- Card Suppliers Terkait (Many-to-Many) -->
    <div class="card">
        <div class="card-title">
            <span>🏢 Pemasok / Vendor Terkait</span>
            <span class="badge badge-cyan">{{ $product->suppliers->count() }} Vendor</span>
        </div>

        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 14px;">
            Relasi <em>Many-to-Many</em> via pivot tabel <code>product_suppliers</code> (Slide 08 & 33).
        </p>

        @if($product->suppliers->isEmpty())
            <p style="color: var(--text-muted); font-size: 0.9rem;">Belum ada supplier yang terhubung ke produk ini.</p>
        @else
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Nama Supplier</th>
                            <th>Kontak</th>
                            <th>Harga Vendor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->suppliers as $s)
                            <tr>
                                <td>
                                    <strong>{{ $s->name }}</strong><br>
                                    <small style="color: var(--text-dim);">{{ $s->contact_person }}</small>
                                </td>
                                <td>
                                    <small>{{ $s->phone ?: '-' }}</small><br>
                                    <small style="color: var(--cyan-neon);">{{ $s->email ?: '-' }}</small>
                                </td>
                                <td class="mono" style="color: #38bdf8;">
                                    {{ $s->pivot->supplier_price ? 'Rp ' . number_format($s->pivot->supplier_price, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Log Mutasi Stok Khusus Produk Ini -->
<div class="card" style="margin-top: 10px;">
    <div class="card-title">
        <span>📜 Buku Besar / Log Mutasi Khusus Produk Ini</span>
        <span class="badge badge-purple">{{ $product->stockMovements->count() }} Catatan</span>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Waktu Catat</th>
                    <th>Tipe Transaksi</th>
                    <th>Kuantitas</th>
                    <th>No. Referensi</th>
                    <th>Operator</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->stockMovements as $m)
                    <tr>
                        <td><small class="mono" style="color: var(--text-muted);">{{ $m->created_at->format('d M Y, H:i') }}</small></td>
                        <td>
                            @if($m->type === 'IN')
                                <span class="badge badge-success">+ MASUK</span>
                            @else
                                <span class="badge badge-danger">- KELUAR</span>
                            @endif
                        </td>
                        <td class="mono" style="font-weight: 700;">
                            {{ $m->type === 'IN' ? '+' : '-' }}{{ $m->quantity }}
                        </td>
                        <td class="mono"><small>{{ $m->reference_no ?: '-' }}</small></td>
                        <td>{{ $m->user->name ?? 'System' }}</td>
                        <td><small>{{ $m->notes ?: '-' }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                            Belum ada catatan mutasi transaksi untuk produk ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
