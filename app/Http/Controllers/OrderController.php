<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();
        return response()->json(['data' => $orders]);
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return response()->json(['data' => $order]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                $totalAmount = 0;
                $orderItems = [];

                foreach ($validated['items'] as $item) {
                    $product = Product::where('id', $item['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($product->stock < $item['quantity']) {
                        return response()->json([
                            'message' => "Stok produk {$product->name} tidak mencukupi."
                        ], 400);
                    }

                    $price = $product->effective_price;
                    $subtotal = $price * $item['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                    ];

                    $product->decrement('stock', $item['quantity']);
                }

                $monthLetter = chr(64 + (int)date('m'));
                $uniqueCode = date('d') . date('H') . '-' . $monthLetter . date('i') . date('y') . '-' . uniqid();

                $amountPaid = $validated['amount_paid'] ?? $totalAmount;
                $changeAmount = max(0, $amountPaid - $totalAmount);

                $order = Order::create([
                    'order_number' => $uniqueCode,
                    'user_id' => $request->user()->id ?? null,
                    'total_amount' => $totalAmount,
                    'amount_paid' => $amountPaid,
                    'change_amount' => $changeAmount,
                    'payment_method' => 'tunai',
                ]);

                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                return response()->json([
                    'message' => 'Transaksi berhasil!',
                    'data' => $order->load('items.product')
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'validated' => $validated,
            ]);

            return response()->json([
                'message' => 'Gagal memproses transaksi.'
            ], 500);
        }
    }
}
