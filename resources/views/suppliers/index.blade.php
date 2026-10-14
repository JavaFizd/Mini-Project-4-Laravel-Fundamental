@extends('layouts.app')

@section('title', 'Kelola Vendor & Supplier')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🏢 Master Data Vendor & Supplier (Many-to-Many)</h1>
        <div class="page-subtitle">Relasi Many-to-Many: Satu Supplier memasok banyak Produk melalui Pivot Table</div>
    </div>
</div>

<div class="grid-cols-2">
    <!-- Form Tambah Supplier -->
    <div class="card">
        <div class="card-title">
            <span>➕ Tambah Supplier Baru</span>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama Vendor / Perusahaan *</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ old('name') }}" placeholder="Misal: Arasaka Cybernetics Corp" required>
                @error('name')
                    <small style="color: #fb7185; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label class="form-label" for="contact_person">Contact Person (PIC)</label>
                    <input type="text" id="contact_person" name="contact_person" class="form-control" 
                           value="{{ old('contact_person') }}" placeholder="Nama PIC">
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-control mono" 
                           value="{{ old('phone') }}" placeholder="+62-...">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ old('email') }}" placeholder="supply@vendor.corp">
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Fasilitas / Depot</label>
                <textarea id="address" name="address" class="form-control" 
                          placeholder="Lokasi fasilitas manufaktur atau drop point...">{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                ➕ Daftarkan Supplier
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Supplier -->
    <div class="card">
        <div class="card-title">
            <span>Daftar Supplier Terverifikasi</span>
            <span class="badge badge-cyan">{{ $suppliers->count() }} Vendor</span>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nama Vendor</th>
                        <th>Kontak PIC</th>
                        <th>Produk Dipasok</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                        <tr>
                            <td>
                                <strong>{{ $s->name }}</strong><br>
                                <small style="color: var(--text-muted);">{{ $s->address ?: '-' }}</small>
                            </td>
                            <td>
                                <strong>{{ $s->contact_person ?: '-' }}</strong><br>
                                <small class="mono" style="color: var(--cyan-neon);">{{ $s->phone ?: '' }}</small><br>
                                <small style="color: #94a3b8;">{{ $s->email ?: '' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-purple">{{ $s->products_count }} Jenis</span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <form action="{{ route('suppliers.destroy', $s) }}" method="POST" style="display: inline-block;"
                                      onsubmit="return confirm('Hapus supplier {{ $s->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada data supplier.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
