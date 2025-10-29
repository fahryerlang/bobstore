<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;

echo "=== Checking Database ===\n\n";

// Cek discount table
echo "DISCOUNTS TABLE:\n";
$discounts = DB::table('discounts')->get();
foreach ($discounts as $d) {
    echo json_encode($d, JSON_PRETTY_PRINT) . "\n";
}

echo "\n\nPRODUCTS (first 3):\n";
$products = Product::take(3)->get();
foreach ($products as $p) {
    echo "ID: {$p->id} - {$p->nama_barang} - Harga: Rp {$p->harga}\n";
    
    // Test discount summary
    $summary = $p->discountSummary();
    echo "  Discount applies: " . ($summary['applies'] ? 'YES' : 'NO') . "\n";
    if ($summary['applies']) {
        echo "  Base: {$summary['base_unit_price']}, Discounted: {$summary['unit_price']}\n";
    }
    echo "\n";
}
