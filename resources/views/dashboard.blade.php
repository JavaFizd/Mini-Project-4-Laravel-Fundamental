@extends('layouts.app')

@section('title', 'Pusat Komando Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📊 Pusat Komando Inventaris (Laravel MVC)</h1>
        <div class="page-subtitle">Metrik real-time & perputaran stok implan sibernetik menggunakan Query Builder & Eloquent</div>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('stock.in') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #059669, #10b981); color: #fff;">
            📥 + Barang Masuk
        </a>
        <a href="{{ route('stock.out') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #e11d48, #f43f5e); color: #fff;">
            📤 - Barang Keluar
        </a>
    </div>
</div>

<!-- 5 Widget Statistik Agregat (Slide 29) -->
<div class="grid-cols-4">
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Produk Terdaftar</div>
            <div class="stat-value">{{ number_format($totalProducts) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 4px;">Product::count()</div>
        </div>
        <div class="stat-icon">📦</div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Nilai Inventory</div>
            <div class="stat-value" style="font-size: 1.4rem; color: #38bdf8;">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 4px;">SUM(stock &times; price)</div>
        </div>
        <div class="stat-icon">💰</div>
    </div>

    <div class="stat-card {{ $lowStockCount > 0 ? 'warning' : 'success' }}">
        <div class="stat-info">
            <div class="stat-label">Produk Stok Rendah</div>
            <div class="stat-value" style="color: {{ $lowStockCount > 0 ? '#fbbf24' : '#34d399' }};">
                {{ number_format($lowStockCount) }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 4px;">stock &le; minimum_stock</div>
        </div>
        <div class="stat-icon">⚠️</div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Akumulasi Masuk (IN)</div>
            <div class="stat-value" style="color: #34d399;">+{{ number_format($totalIn) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 4px;">SUM(qty) WHERE type=IN</div>
        </div>
        <div class="stat-icon">📥</div>
    </div>
</div>

<div class="grid-cols-2">
    <!-- Tabel Peringatan Stok Kritis -->
    <div class="card">
        <div class="card-title">
            <span>🚨 Peringatan Stok Kritis (&le; Min)</span>
            <span class="badge badge-warning">{{ $lowStockItems->count() }} Perlu Restock</span>
        </div>
        
        @if($lowStockItems->isEmpty())
            <p style="color: var(--text-muted); font-size: 0.9rem; padding: 12px 0;">Semua stok produk saat ini berada dalam kondisi aman.</p>
        @else
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>SKU & Produk</th>
                            <th>Stok / Min</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->name }}</strong><br>
                                    <small class="mono" style="color: var(--cyan-neon);">{{ $item->sku }}</small>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: #fb7185;">{{ $item->stock }}</span>
                                    <span style="color: var(--text-dim);">/ min {{ $item->minimum_stock }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">Kritis ({{ $item->stock }})</span>
                                </td>
                                <td>
                                    <a href="{{ route('stock.in', ['product_id' => $item->id]) }}" class="btn btn-secondary btn-sm" style="color: #34d399;">
                                        + Restock
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Tabel 5 Transaksi Terakhir -->
    <div class="card">
        <div class="card-title">
            <span>⚡ Histori Pergerakan Stok Terbaru</span>
            <a href="{{ route('stock.history') }}" class="btn btn-secondary btn-sm">Lihat Semua ➡️</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMovements as $m)
                        <tr>
                            <td>
                                @if($m->type === 'IN')
                                    <span class="badge badge-success">+ MASUK</span>
                                @else
                                    <span class="badge badge-danger">- KELUAR</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $m->product->name ?? 'Produk Dihapus' }}</strong><br>
                                <small class="mono" style="color: var(--text-dim);">{{ $m->product->sku ?? '-' }}</small>
                            </td>
                            <td style="font-weight: 700; font-family: monospace;">
                                {{ $m->type === 'IN' ? '+' : '-' }}{{ $m->quantity }}
                            </td>
                            <td><small style="color: var(--text-muted);">{{ $m->created_at->format('d M Y, H:i') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada riwayat pergerakan stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
