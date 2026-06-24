<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $today = Carbon::today();

        $salesToday = Order::whereDate('created_at', $today)->sum('total_amount');
        $transactionsToday = Order::whereDate('created_at', $today)->count();
        $allProducts = Product::all(['stock', 'expired_at']);
        $totalProducts = $allProducts->count();
        
        $lowStockProducts = $allProducts->filter(function ($product) {
            $stock = (int) $product->stock;
            return $stock <= 10 && $stock > 0;
        })->count();

        $outOfStockProducts = $allProducts->filter(function ($product) {
            return (int) $product->stock === 0;
        })->count();

        $almostExpiredProducts = $allProducts->filter(function ($product) {
            if (!$product->expired_at) return false;
            $daysLeft = now()->diffInDays($product->expired_at, false);
            return $daysLeft >= 0 && $daysLeft <= 30;
        })->count();

        $expiredProducts = $allProducts->filter(function ($product) {
            if (!$product->expired_at) return false;
            $daysLeft = now()->diffInDays($product->expired_at, false);
            return $daysLeft < 0;
        })->count();

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
                'out_of_stock_products' => $outOfStockProducts,
                'almost_expired_products' => $almostExpiredProducts,
                'expired_products' => $expiredProducts,
                'weekly_sales' => $chartData
            ]
        ]);
    }
}
