<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 12, 2);
            $table->unsignedInteger('min_quantity')->default(1);
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->index(['discount_id', 'product_id']);
            $table->index(['discount_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_rules');
    }
};
