<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::find(16);
if ($product) {
    echo "Product ID: 16 - {$product->nama_barang}\n";
    echo "Price: Rp {$product->harga}\n\n";
    
    $discountInfo = $product->availableDiscountInfo();
    if ($discountInfo) {
        echo "HAS DISCOUNT!\n";
        echo "Type: {$discountInfo['discount_type']}\n";
        echo "Value: {$discountInfo['discount_value']}\n";
        echo "Percentage: {$discountInfo['discount_percentage']}%\n";
        echo "Min Quantity: {$discountInfo['min_quantity']}\n";
        echo "Base Price: Rp {$discountInfo['base_price']}\n";
        echo "Sample Discounted Price: Rp {$discountInfo['sample_discounted_price']}\n";
    } else {
        echo "NO DISCOUNT AVAILABLE\n";
    }
}
