<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DISCOUNT RULES TABLE ===\n\n";
$rules = DB::table('discount_rules')->get();
foreach ($rules as $rule) {
    echo json_encode($rule, JSON_PRETTY_PRINT) . "\n\n";
}
