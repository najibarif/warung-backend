<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItems = [];

            // Hitung total dan validasi stok
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Stok produk {$product->name} tidak mencukupi."
                    ], 400);
                }

                $price = $product->is_promo && $product->promo_price ? $product->promo_price : $product->price;
                $subtotal = $price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];

                // Kurangi stok
                $product->decrement('stock', $item['quantity']);
            }

            // Buat Order
            $order = Order::create([
                'user_id' => $request->user()->id ?? null,
                'total_amount' => $totalAmount,
                'payment_method' => 'tunai',
            ]);

            // Buat Order Items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil!',
                'data' => $order->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
