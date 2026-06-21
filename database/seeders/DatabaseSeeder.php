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
        User::create([
            'name' => 'Admin Warung',
            'email' => 'admin@warung.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Guest user (for favorites demo)
        User::create([
            'name' => 'Pelanggan',
            'email' => 'user@warung.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Categories
        $categories = [
            ['name' => 'Sembako', 'icon' => '🌾'],
            ['name' => 'Minuman', 'icon' => '☕'],
            ['name' => 'Snack', 'icon' => '🍪'],
            ['name' => 'Kebersihan', 'icon' => '🧹'],
            ['name' => 'Kesehatan', 'icon' => '💊'],
            ['name' => 'Rokok', 'icon' => '🚬'],
            ['name' => 'Alat Tulis', 'icon' => '✏️'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Sample products
        $products = [
            ['category_id' => 1, 'name' => 'Beras 5 Kg', 'price' => 75000, 'stock' => 50, 'is_best_seller' => true, 'barcode' => 'BC001'],
            ['category_id' => 1, 'name' => 'Minyak Goreng 1 Liter', 'price' => 18000, 'stock' => 30, 'is_promo' => true, 'promo_price' => 15000, 'barcode' => 'BC002'],
            ['category_id' => 1, 'name' => 'Gula Pasir 1 Kg', 'price' => 17000, 'stock' => 40, 'barcode' => 'BC003'],
            ['category_id' => 1, 'name' => 'Telur 1 Kg', 'price' => 28000, 'stock' => 5, 'is_best_seller' => true, 'barcode' => 'BC004'],
            ['category_id' => 1, 'name' => 'Tepung Terigu 1 Kg', 'price' => 13000, 'stock' => 20, 'barcode' => 'BC005'],
            ['category_id' => 2, 'name' => 'Aqua 600ml', 'price' => 4000, 'stock' => 100, 'is_best_seller' => true, 'barcode' => 'BC006'],
            ['category_id' => 2, 'name' => 'Indomilk Kotak', 'price' => 8000, 'stock' => 60, 'barcode' => 'BC007'],
            ['category_id' => 2, 'name' => 'Kopi Sachet Kapal Api', 'price' => 2000, 'stock' => 200, 'is_best_seller' => true, 'barcode' => 'BC008'],
            ['category_id' => 2, 'name' => 'Teh Sariwangi', 'price' => 1500, 'stock' => 150, 'barcode' => 'BC009'],
            ['category_id' => 3, 'name' => 'Indomie Goreng', 'price' => 3500, 'stock' => 3, 'is_best_seller' => true, 'barcode' => 'BC010'],
            ['category_id' => 3, 'name' => 'Chitato 68gr', 'price' => 12000, 'stock' => 25, 'is_promo' => true, 'promo_price' => 10000, 'barcode' => 'BC011'],
            ['category_id' => 3, 'name' => 'Oreo Vanila', 'price' => 8000, 'stock' => 30, 'barcode' => 'BC012'],
            ['category_id' => 4, 'name' => 'Sabun Lifebuoy', 'price' => 5000, 'stock' => 40, 'barcode' => 'BC013'],
            ['category_id' => 4, 'name' => 'Sunlight 800ml', 'price' => 18000, 'stock' => 15, 'barcode' => 'BC014'],
            ['category_id' => 4, 'name' => 'Rinso 900gr', 'price' => 25000, 'stock' => 4, 'barcode' => 'BC015'],
            ['category_id' => 5, 'name' => 'Panadol 10 Tab', 'price' => 10000, 'stock' => 20, 'barcode' => 'BC016'],
            ['category_id' => 5, 'name' => 'Tolak Angin Sachet', 'price' => 5000, 'stock' => 50, 'is_promo' => true, 'promo_price' => 4000, 'barcode' => 'BC017'],
            ['category_id' => 6, 'name' => 'Rokok Sampoerna 16', 'price' => 25000, 'stock' => 30, 'is_best_seller' => true, 'barcode' => 'BC018'],
            ['category_id' => 7, 'name' => 'Pulpen Pilot', 'price' => 4500, 'stock' => 2, 'barcode' => 'BC019'],
            ['category_id' => 7, 'name' => 'Buku Tulis 40 Lembar', 'price' => 6000, 'stock' => 50, 'barcode' => 'BC020'],
        ];

        foreach ($products as $p) {
            Product::create(array_merge([
                'is_promo' => false,
                'promo_price' => null,
                'is_best_seller' => false,
                'description' => 'Produk berkualitas dari warung kami.',
            ], $p));
        }
    }
}
