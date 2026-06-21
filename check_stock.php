<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = App\Models\Product::all(['name', 'stock']);
foreach($products as $p) {
    echo $p->name . ' => ' . gettype($p->getRawOriginal('stock')) . ' : ' . $p->getRawOriginal('stock') . PHP_EOL;
}
