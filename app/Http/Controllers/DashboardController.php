<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $today = Carbon::today();

        $salesToday = Order::whereDate('created_at', $today)->sum('total_amount');
        $transactionsToday = Order::whereDate('created_at', $today)->count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();

        // Data grafik penjualan 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $total = Order::whereDate('created_at', $date)->sum('total_amount');
            $chartData[] = [
                'date' => $date->format('d M'),
                'total' => $total
            ];
        }

        return response()->json([
            'data' => [
                'sales_today' => $salesToday,
                'transactions_today' => $transactionsToday,
                'total_products' => $totalProducts,
                'low_stock_products' => $lowStockProducts,
                'weekly_sales' => $chartData
            ]
        ]);
    }
}
