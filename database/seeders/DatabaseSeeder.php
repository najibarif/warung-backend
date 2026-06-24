<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@warung.com'],
            [
                'name' => 'Admin Warung',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Guest user (for favorites demo)
        User::updateOrCreate(
            ['email' => 'user@warung.com'],
            [
                'name' => 'Pelanggan',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // Categories
        $categories = [
            ['name' => 'Sembako', 'icon' => '🌾'],
            ['name' => 'Minuman', 'icon' => '☕'],
            ['name' => 'Snack', 'icon' => '🍪'],
            ['name' => 'Kebersihan', 'icon' => '🧹'],
            ['name' => 'Kesehatan', 'icon' => '💊'],
            ['name' => 'Alat Tulis', 'icon' => '✏️'],
            ['name' => 'Lainnya', 'icon' => '📦'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = Category::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // Sample products
        $products = [
            ['category_id' => $catModels[0]->id, 'name' => 'Beras 5 Kg', 'price' => 75000, 'stock' => 50, 'is_best_seller' => true, 'barcode' => 'BC001'],
            ['category_id' => $catModels[0]->id, 'name' => 'Minyak Goreng 1 Liter', 'price' => 18000, 'stock' => 30, 'is_promo' => true, 'promo_price' => 15000, 'barcode' => 'BC002'],
            ['category_id' => $catModels[0]->id, 'name' => 'Gula Pasir 1 Kg', 'price' => 17000, 'stock' => 40, 'barcode' => 'BC003'],
            ['category_id' => $catModels[0]->id, 'name' => 'Telur 1 Kg', 'price' => 28000, 'stock' => 5, 'is_best_seller' => true, 'barcode' => 'BC004'],
            ['category_id' => $catModels[0]->id, 'name' => 'Tepung Terigu 1 Kg', 'price' => 13000, 'stock' => 20, 'barcode' => 'BC005'],
            ['category_id' => $catModels[1]->id, 'name' => 'Aqua 600ml', 'price' => 4000, 'stock' => 100, 'is_best_seller' => true, 'barcode' => 'BC006'],
            ['category_id' => $catModels[1]->id, 'name' => 'Indomilk Kotak', 'price' => 8000, 'stock' => 60, 'barcode' => 'BC007'],
            ['category_id' => $catModels[1]->id, 'name' => 'Kopi Sachet Kapal Api', 'price' => 2000, 'stock' => 200, 'is_best_seller' => true, 'barcode' => 'BC008'],
            ['category_id' => $catModels[1]->id, 'name' => 'Teh Sariwangi', 'price' => 1500, 'stock' => 150, 'barcode' => 'BC009'],
            ['category_id' => $catModels[2]->id, 'name' => 'Indomie Goreng', 'price' => 3500, 'stock' => 3, 'is_best_seller' => true, 'barcode' => 'BC010'],
            ['category_id' => $catModels[2]->id, 'name' => 'Chitato 68gr', 'price' => 12000, 'stock' => 25, 'is_promo' => true, 'promo_price' => 10000, 'barcode' => 'BC011'],
            ['category_id' => $catModels[2]->id, 'name' => 'Oreo Vanila', 'price' => 8000, 'stock' => 30, 'barcode' => 'BC012'],
            ['category_id' => $catModels[3]->id, 'name' => 'Sabun Lifebuoy', 'price' => 5000, 'stock' => 40, 'barcode' => 'BC013'],
            ['category_id' => $catModels[3]->id, 'name' => 'Sunlight 800ml', 'price' => 18000, 'stock' => 15, 'barcode' => 'BC014'],
            ['category_id' => $catModels[3]->id, 'name' => 'Rinso 900gr', 'price' => 25000, 'stock' => 4, 'barcode' => 'BC015'],
            ['category_id' => $catModels[4]->id, 'name' => 'Panadol 10 Tab', 'price' => 10000, 'stock' => 20, 'barcode' => 'BC016'],
            ['category_id' => $catModels[4]->id, 'name' => 'Tolak Angin Sachet', 'price' => 5000, 'stock' => 50, 'is_promo' => true, 'promo_price' => 4000, 'barcode' => 'BC017'],
            ['category_id' => $catModels[5]->id, 'name' => 'Pulpen Pilot', 'price' => 4500, 'stock' => 2, 'barcode' => 'BC019'],
            ['category_id' => $catModels[5]->id, 'name' => 'Buku Tulis 40 Lembar', 'price' => 6000, 'stock' => 50, 'barcode' => 'BC020'],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['barcode' => $p['barcode']],
                array_merge([
                    'is_promo' => false,
                    'promo_price' => null,
                    'is_best_seller' => false,
                    'description' => 'Produk berkualitas dari warung kami.',
                ], $p)
            );
        }
    }
}
