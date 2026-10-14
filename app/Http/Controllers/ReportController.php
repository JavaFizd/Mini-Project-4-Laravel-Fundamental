<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan Agregasi SQL (Slide 14 & Slide 28).
     */
    public function index()
    {
        // 1. Laporan Arus Barang per Produk Menggunakan Agregasi CASE WHEN (Slide 28)
        $productReports = Product::query()
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('stock_movements', 'stock_movements.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.stock',
                'products.price',
                'categories.name as category_name',
                DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "IN" THEN stock_movements.quantity ELSE 0 END), 0) as total_in'),
                DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "OUT" THEN stock_movements.quantity ELSE 0 END), 0) as total_out'),
                DB::raw('products.stock * products.price as total_value')
            )
            ->groupBy('products.id', 'products.sku', 'products.name', 'products.stock', 'products.price', 'categories.name')
            ->orderBy('products.name')
            ->get();

        // 2. Laporan Ringkasan per Kategori Menggunakan COUNT, SUM, AVG (Slide 14)
        $categoryReports = Category::query()
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(products.id) as total_products'),
                DB::raw('COALESCE(SUM(products.stock), 0) as total_stock'),
                DB::raw('COALESCE(AVG(products.price), 0) as average_price'),
                DB::raw('COALESCE(SUM(products.stock * products.price), 0) as category_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('category_value')
            ->get();

        $grandTotalStock = $productReports->sum('stock');
        $grandTotalValue = $productReports->sum('total_value');
        $grandTotalIn = $productReports->sum('total_in');
        $grandTotalOut = $productReports->sum('total_out');

        return view('reports.index', compact(
            'productReports',
            'categoryReports',
            'grandTotalStock',
            'grandTotalValue',
            'grandTotalIn',
            'grandTotalOut'
        ));
    }
}
