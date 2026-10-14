<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard with 5 aggregate metrics and quick warnings (Slide 14 & 29).
     */
    public function index()
    {
        $totalProducts = Product::count();
        $inventoryValue = Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0;
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalIn = StockMovement::where('type', 'IN')->sum('quantity');
        $totalOut = StockMovement::where('type', 'OUT')->sum('quantity');

        $lowStockItems = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'inventoryValue',
            'lowStockCount',
            'totalIn',
            'totalOut',
            'lowStockItems',
            'recentMovements'
        ));
    }
}
