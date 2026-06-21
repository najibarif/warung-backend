<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::all();
foreach($products as $p) {
    $p->stock = (int) $p->stock;
    $p->price = (float) $p->price;
    $p->save();
}
echo "Updated all products to have integer stock.";
