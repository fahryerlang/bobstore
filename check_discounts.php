<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Discount;
use App\Models\Product;

echo "=== Checking Discounts ===\n\n";

// Cek jumlah discount aktif
$activeDiscounts = Discount::where('is_active', 1)->count();
echo "Active Discounts: {$activeDiscounts}\n\n";

// Cek semua discount
$discounts = Discount::all();
echo "Total Discounts: {$discounts->count()}\n\n";

foreach ($discounts as $discount) {
    $product = Product::find($discount->product_id);
    echo "Discount ID: {$discount->id}\n";
    echo "Product ID: {$discount->product_id}\n";
    echo "Product Name: " . ($product ? $product->nama_barang : 'N/A') . "\n";
    echo "Type: {$discount->type}\n";
    echo "Value: {$discount->value}\n";
    echo "Active: " . ($discount->is_active ? 'Yes' : 'No') . "\n";
    echo "Starts: {$discount->starts_at}\n";
    echo "Ends: {$discount->ends_at}\n";
    echo "---\n\n";
}

// Test discountSummary pada produk pertama
$product = Product::first();
if ($product) {
    echo "Testing Product: {$product->nama_barang}\n";
    $summary = $product->discountSummary();
    echo "Discount Applies: " . ($summary['applies'] ? 'YES' : 'NO') . "\n";
    if ($summary['applies']) {
        echo "Base Price: {$summary['base_unit_price']}\n";
        echo "Discounted Price: {$summary['unit_price']}\n";
        echo "Discount %: {$summary['discount_percentage']}%\n";
    }
}
