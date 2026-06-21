<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Models\Product::first();
$product->update(['stock' => '5']); // Simulate request->all()
echo gettype($product->getRawOriginal('stock')) . ' : ' . $product->getRawOriginal('stock') . PHP_EOL;

// Then force update in DB
Illuminate\Support\Facades\DB::collection('products')->update([
    '$set' => ['stock' => 5]
]);
echo "Forced DB update to integer." . PHP_EOL;

$product->refresh();
echo "After force: " . gettype($product->getRawOriginal('stock')) . ' : ' . $product->getRawOriginal('stock') . PHP_EOL;

