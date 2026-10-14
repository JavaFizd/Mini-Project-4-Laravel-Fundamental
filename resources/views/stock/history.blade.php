@extends('layouts.app')

@section('title', 'Histori Mutasi Stok')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📜 Histori Mutasi Persediaan (Audit Trail)</h1>
        <div class="page-subtitle">Jejak rekaman lengkap seluruh aktivitas barang masuk dan keluar (Eloquent Relationship)</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('stock.in') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #059669, #10b981); color: #fff;">
            📥 Catat Masuk
        </a>
        <a href="{{ route('stock.out') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #e11d48, #f43f5e); color: #fff;">
            📤 Catat Keluar
        </a>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <form method="GET" action="{{ route('stock.history') }}" class="filter-form">
        <div style="min-width: 160px;">
            <select name="type" class="form-control">
                <option value="">Semua Tipe Mutasi</option>
                <option value="IN" {{ $type === 'IN' ? 'selected' : '' }}>+ Barang Masuk (IN)</option>
                <option value="OUT" {{ $type === 'OUT' ? 'selected' : '' }}>- Barang Keluar (OUT)</option>
            </select>
        </div>

        <div style="min-width: 240px; flex: 1;">
            <select name="product_id" class="form-control">
                <option value="">Semua Produk Cyberware</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ (string)$productId === (string)$p->id ? 'selected' : '' }}>
                        [{{ $p->sku }}] {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-secondary">
            Filter Log
        </button>

        @if($type || $productId)
            <a href="{{ route('stock.history') }}" class="btn btn-secondary btn-sm">
                ✕ Reset
            </a>
        @endif
    </form>

    <div style="font-size: 0.85rem; color: var(--text-muted);">
        Total: <strong>{{ $movements->total() }}</strong> mutasi tercatat
    </div>
</div>

<!-- Tabel Histori -->
<div class="table-responsive">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Waktu Transaksi</th>
                <th>Tipe</th>
                <th>Produk & Kategori</th>
                <th>Kuantitas</th>
                <th>No. Referensi</th>
                <th>Petugas Pelaksana</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $m)
                <tr>
                    <td>
                        <small class="mono" style="color: var(--text-muted);">{{ $m->created_at->format('d M Y, H:i') }}</small>
                    </td>
                    <td>
                        @if($m->type === 'IN')
                            <span class="badge badge-success">+ MASUK</span>
                        @else
                            <span class="badge badge-danger">- KELUAR</span>
                        @endif
                    </td>
                    <td>
                        @if($m->product)
                            <a href="{{ route('products.show', $m->product) }}" style="color: #fff; text-decoration: none; font-weight: 600;">
                                {{ $m->product->name }}
                            </a><br>
                            <span class="mono" style="color: var(--cyan-neon); font-size: 0.78rem;">[{{ $m->product->sku }}]</span>
                            <span class="badge badge-purple" style="font-size: 0.7rem;">{{ $m->product->category->name ?? '-' }}</span>
                        @else
                            <span style="color: var(--text-dim);">Produk Dihapus</span>
                        @endif
                    </td>
                    <td class="mono" style="font-size: 1.1rem; font-weight: 700; color: {{ $m->type === 'IN' ? '#34d399' : '#fb7185' }};">
                        {{ $m->type === 'IN' ? '+' : '-' }}{{ $m->quantity }}
                    </td>
                    <td class="mono">
                        <small>{{ $m->reference_no ?: '-' }}</small>
                    </td>
                    <td>
                        <strong>{{ $m->user->name ?? 'System' }}</strong>
                    </td>
                    <td>
                        <small style="color: var(--text-muted);">{{ $m->notes ?: '-' }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 36px;">
                        Tidak ada histori transaksi mutasi stok yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 24px;">
    {{ $movements->links() }}
</div>
@endsection
