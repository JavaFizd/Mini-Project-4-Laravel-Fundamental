<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of products with search, filter, sorting, and pagination (Slide 17, 32, 38).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = strtoupper($request->input('sort_dir', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';

        // Allowlist sorting untuk keamanan (Slide 16)
        $allowedSort = ['name', 'sku', 'price', 'stock', 'created_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'name';
        }

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(6)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'sortBy', 'sortDir'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage (Slide 19).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ], [
            'sku.unique' => 'Kode SKU sudah terdaftar pada produk lain.',
            'price.min' => 'Harga produk tidak boleh negatif.',
            'stock.min' => 'Stok awal tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create($validated);

            // Relasi Many-to-Many Supplier
            if (!empty($validated['suppliers'])) {
                $syncData = [];
                foreach ($validated['suppliers'] as $suppId) {
                    $syncData[$suppId] = ['supplier_price' => $validated['price'] * 0.85];
                }
                $product->suppliers()->sync($syncData);
            }

            // Inisialisasi stok awal jika > 0
            if ($validated['stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id() ?? null,
                    'type' => 'IN',
                    'quantity' => $validated['stock'],
                    'reference_no' => 'INIT-' . date('YmdHis'),
                    'notes' => 'Saldo awal registrasi produk baru.',
                ]);
            }
        });

        return redirect()->route('products.index')
            ->with('success', "Produk [{$validated['sku']}] {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Display the specified product detail.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements' => function ($q) {
            $q->latest();
        }]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $selectedSuppliers = $product->suppliers->pluck('id')->toArray();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'selectedSuppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ]);

        DB::transaction(function () use ($validated, $product) {
            $product->update($validated);

            if (isset($validated['suppliers'])) {
                $syncData = [];
                foreach ($validated['suppliers'] as $suppId) {
                    $syncData[$suppId] = ['supplier_price' => $validated['price'] * 0.85];
                }
                $product->suppliers()->sync($syncData);
            } else {
                $product->suppliers()->detach();
            }
        });

        return redirect()->route('products.index')
            ->with('success', "Produk [{$product->sku}] berhasil diperbarui.");
    }

    /**
     * Remove the specified product from storage (Referential Integrity Check).
     */
    public function destroy(Product $product)
    {
        // Business Rule: Cek apakah produk memiliki catatan histori mutasi (Slide 09 & 33)
        if ($product->stockMovements()->count() > 0) {
            return redirect()->route('products.index')
                ->with('danger', "Gagal Menghapus: Produk [{$product->sku}] memiliki riwayat mutasi stok. Sesuai aturan integritas referensial (RESTRICT), produk dengan riwayat transaksi tidak boleh dihapus.");
        }

        $product->suppliers()->detach();
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produk [{$product->sku}] berhasil dihapus dari inventaris.");
    }
}
