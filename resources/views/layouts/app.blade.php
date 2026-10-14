<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NeuroVault IMS') — Laravel Fundamental P5</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🧬</text></svg>">
</head>
<body>
<header class="navbar">
    <div class="nav-container">
        <a href="{{ route('dashboard') }}" class="nav-brand">
            🧬 NEURO<span class="accent">VAULT</span> <small style="font-size: 0.6em; color: var(--text-muted); font-weight: normal; border: 1px solid var(--border-accent); padding: 2px 6px; border-radius: 4px;">Laravel MVC v10</small>
        </a>

        <nav class="nav-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                📦 Produk (Resource)
            </a>
            <a href="{{ route('stock.in') }}" class="{{ request()->routeIs('stock.in*') ? 'active' : '' }}" style="color: #34d399;">
                📥 + Barang Masuk
            </a>
            <a href="{{ route('stock.out') }}" class="{{ request()->routeIs('stock.out*') ? 'active' : '' }}" style="color: #fb7185;">
                📤 - Barang Keluar
            </a>
            <a href="{{ route('stock.history') }}" class="{{ request()->routeIs('stock.history') ? 'active' : '' }}">
                📜 Histori Stok
            </a>
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                📈 Laporan Agregat
            </a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                🏷️ Kategori
            </a>
            <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                🏢 Supplier
            </a>
        </nav>

        <div class="nav-user">
            <div class="user-badge">
                <span style="font-weight: 600; color: #fff;">Dr. Victor Vector</span>
                <span class="role-tag role-admin">[LARAVEL APP]</span>
            </div>
        </div>
    </div>
</header>

<main class="container">
    {{-- Flash Notifications via Blade Component (Slide 23) --}}
    @if(session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('danger'))
        <x-alert type="danger">
            {{ session('danger') }}
        </x-alert>
    @endif

    @if($errors->any())
        <x-alert type="danger">
            <strong>Terdapat kesalahan validasi input:</strong>
            <ul style="margin-top: 6px; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    @yield('content')
</main>

<footer class="footer">
    <div style="max-width: 1300px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <strong>NeuroVault IMS</strong> &copy; {{ date('Y') }} — Cyberware Logistics on Laravel Framework.
        </div>
        <div>
            Pertemuan 5: Laravel Fundamental & MVC Migration.
        </div>
    </div>
</footer>
</body>
</html>
