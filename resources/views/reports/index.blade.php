@extends('layouts.app')

@section('title', 'Laporan Agregat Inventaris')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📈 Laporan Inventaris Berbasis Fungsi Agregat (Laravel Eloquent)</h1>
        <div class="page-subtitle">Penerapan GROUP BY, SUM(CASE WHEN...), COUNT, dan AVG via Query Builder (Slide 14 & 28)</div>
    </div>
    <button class="btn btn-secondary" onclick="window.print()">
        🖨️ Cetak Dokumen Laporan
    </button>
</div>

<!-- Kartu Ringkasan Metrik Laporan -->
<div class="grid-cols-4">
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Unit Fisik Tersedia</div>
            <div class="stat-value">{{ number_format($grandTotalStock) }} unit</div>
        </div>
        <div class="stat-icon">📦</div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Nilai Kapitalisasi Aset</div>
            <div class="stat-value" style="font-size: 1.4rem; color: #38bdf8;">Rp {{ number_format($grandTotalValue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-icon">💎</div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Total Volume Masuk (IN)</div>
            <div class="stat-value" style="color: #34d399;">+{{ number_format($grandTotalIn) }} unit</div>
        </div>
        <div class="stat-icon">📥</div>
    </div>

    <div class="stat-card danger">
        <div class="stat-info">
            <div class="stat-label">Total Volume Keluar (OUT)</div>
            <div class="stat-value" style="color: #fb7185;">-{{ number_format($grandTotalOut) }} unit</div>
        </div>
        <div class="stat-icon">📤</div>
    </div>
</div>

<!-- Laporan 1: Ringkasan per Kategori (Slide 14) -->
<div class="card" style="margin-bottom: 28px;">
    <div class="card-title">
        <span>🏷️ 1. Laporan Agregasi per Kategori Produk (Slide 14)</span>
        <span class="mono" style="font-size: 0.8rem; color: var(--text-dim);">COUNT(p.id), SUM(p.stock), AVG(p.price)</span>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Jumlah Jenis Produk</th>
                    <th>Total Stok Fisik</th>
                    <th>Rata-rata Harga Satuan</th>
                    <th>Subtotal Valuasi Aset</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryReports as $cr)
                    <tr>
                        <td><strong>{{ $cr->name }}</strong></td>
                        <td class="mono">{{ $cr->total_products }} SKU</td>
                        <td class="mono" style="font-weight: 700;">{{ number_format($cr->total_stock) }} unit</td>
                        <td class="mono" style="color: #94a3b8;">Rp {{ number_format($cr->average_price, 0, ',', '.') }}</td>
                        <td class="mono" style="color: var(--cyan-neon); font-weight: 700;">Rp {{ number_format($cr->category_value, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Laporan 2: Laporan Arus Barang per Produk (Slide 28) -->
<div class="card">
    <div class="card-title">
        <span>🔄 2. Laporan Perputaran Arus Mutasi Produk (Slide 28)</span>
        <span class="mono" style="font-size: 0.8rem; color: var(--text-dim);">SUM(CASE WHEN sm.type='IN'...)</span>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>SKU & Nama Produk</th>
                    <th>Kategori</th>
                    <th>Total Masuk (+IN)</th>
                    <th>Total Keluar (-OUT)</th>
                    <th>Sisa Stok Fisik</th>
                    <th>Harga Satuan</th>
                    <th>Total Nilai Aset</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productReports as $pr)
                    <tr>
                        <td>
                            <strong style="color: #fff;">{{ $pr->name }}</strong><br>
                            <small class="mono" style="color: var(--cyan-neon);">{{ $pr->sku }}</small>
                        </td>
                        <td><span class="badge badge-purple">{{ $pr->category_name }}</span></td>
                        <td class="mono" style="color: #34d399; font-weight: 700;">+{{ number_format($pr->total_in) }}</td>
                        <td class="mono" style="color: #fb7185; font-weight: 700;">-{{ number_format($pr->total_out) }}</td>
                        <td class="mono" style="font-size: 1rem; font-weight: 700; color: #fff;">
                            {{ number_format($pr->stock) }}
                        </td>
                        <td class="mono" style="color: #94a3b8;">Rp {{ number_format($pr->price, 0, ',', '.') }}</td>
                        <td class="mono" style="color: #38bdf8; font-weight: 700;">Rp {{ number_format($pr->total_value, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #0d121e; font-weight: 700;">
                    <td colspan="2" style="text-align: right; text-transform: uppercase;">Total Akumulasi:</td>
                    <td class="mono" style="color: #34d399;">+{{ number_format($grandTotalIn) }}</td>
                    <td class="mono" style="color: #fb7185;">-{{ number_format($grandTotalOut) }}</td>
                    <td class="mono" style="color: #fff;">{{ number_format($grandTotalStock) }} unit</td>
                    <td>-</td>
                    <td class="mono" style="color: var(--cyan-neon); font-size: 1.05rem;">Rp {{ number_format($grandTotalValue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
