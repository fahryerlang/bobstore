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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null')->comment('Related sale if points earned from purchase');
            $table->enum('type', ['earn', 'redeem', 'expire', 'adjustment'])->comment('Type of point transaction');
            $table->integer('points')->comment('Positive for earn, negative for redeem/expire');
            $table->integer('balance_after')->comment('Point balance after this transaction');
            $table->text('description')->nullable()->comment('Description or reason for transaction');
            $table->timestamp('expires_at')->nullable()->comment('When these points expire (if applicable)');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
