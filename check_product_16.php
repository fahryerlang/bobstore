<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::find(16);
if ($product) {
    echo "Product ID: 16\n";
    echo "Name: {$product->nama_barang}\n";
    echo "Price: Rp {$product->harga}\n\n";
    
    echo "Testing discountSummary():\n";
    $summary = $product->discountSummary();
    echo "Applies: " . ($summary['applies'] ? 'YES' : 'NO') . "\n";
    echo "Base Price: {$summary['base_unit_price']}\n";
    echo "Unit Price: {$summary['unit_price']}\n";
    echo "Discount %: {$summary['discount_percentage']}\n";
    echo "Total Price: {$summary['total_price']}\n";
    echo "Quantity: {$summary['quantity']}\n";
} else {
    echo "Product ID 16 not found!\n";
}
