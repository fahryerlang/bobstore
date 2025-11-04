<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update first product with test barcode
$product = App\Models\Product::first();
if ($product) {
    $product->barcode = '8993176110074';
    $product->save();
    echo "Successfully updated product: {$product->nama_barang}\n";
    echo "Barcode: {$product->barcode}\n";
    echo "Product ID: {$product->id}\n";
} else {
    echo "No products found in database\n";
}
