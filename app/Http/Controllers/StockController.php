<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    /**
     * Tampilkan formulir barang masuk (IN / Restock).
     */
    public function inCreate(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->query('product_id');

        return view('stock.in', compact('products', 'selectedProductId'));
    }

    /**
     * Proses transaksi barang masuk secara atomik (Slide 25 & 27).
     */
    public function inStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_no' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ], [
            'quantity.min' => 'Kuantitas barang masuk harus berupa bilangan bulat lebih dari 0.',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $product->increment('stock', $validated['quantity']);

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id() ?? null,
                'type' => 'IN',
                'quantity' => $validated['quantity'],
                'reference_no' => $validated['reference_no'] ?? ('PO-' . date('Ymd') . '-' . rand(100, 999)),
                'notes' => $validated['notes'] ?? 'Penerimaan pasokan barang masuk (Restock).',
            ]);
        });

        return redirect()->route('stock.history')
            ->with('success', "Transaksi barang masuk berhasil dicatat. Stok produk telah bertambah {$validated['quantity']} unit.");
    }

    /**
     * Tampilkan formulir barang keluar (OUT / Dispense).
     */
    public function outCreate(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->query('product_id');

        return view('stock.out', compact('products', 'selectedProductId'));
    }

    /**
     * Proses transaksi barang keluar dengan SELECT ... FOR UPDATE dan pencegahan stok negatif (Slide 26 & 33).
     */
    public function outStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_no' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ], [
            'quantity.min' => 'Kuantitas barang keluar harus minimal 1 unit.',
        ]);

        DB::transaction(function () use ($validated) {
            // Lock baris data dengan FOR UPDATE (Slide 26)
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

            // Business rule: kuantitas keluar dilarang melebihi stok yang tersedia (Slide 33)
            if ($validated['quantity'] > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => "Kuantitas keluar ({$validated['quantity']} unit) MELEBIHI stok fisik yang tersedia ({$product->stock} unit). Transaksi dibatalkan untuk mencegah stok negatif.",
                ]);
            }

            $product->decrement('stock', $validated['quantity']);

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id() ?? null,
                'type' => 'OUT',
                'quantity' => $validated['quantity'],
                'reference_no' => $validated['reference_no'] ?? ('SO-' . date('Ymd') . '-' . rand(100, 999)),
                'notes' => $validated['notes'] ?? 'Pengeluaran/pemasangan implan kepada klien.',
            ]);
        });

        return redirect()->route('stock.history')
            ->with('success', "Transaksi barang keluar berhasil dicatat. Sisa stok fisik produk telah disesuaikan.");
    }

    /**
     * Tampilkan buku besar log pergerakan mutasi stok (Audit Trail).
     */
    public function history(Request $request)
    {
        $type = $request->input('type');
        $productId = $request->input('product_id');

        $movements = StockMovement::with(['product.category', 'user'])
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('stock.history', compact('movements', 'products', 'type', 'productId'));
    }
}
