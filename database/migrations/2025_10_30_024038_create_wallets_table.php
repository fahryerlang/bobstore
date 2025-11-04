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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0); // Saldo user
            $table->decimal('total_topup', 15, 2)->default(0); // Total topup sepanjang masa
            $table->decimal('total_spent', 15, 2)->default(0); // Total pengeluaran sepanjang masa
            $table->boolean('is_active')->default(true); // Status wallet
            $table->timestamp('last_transaction_at')->nullable(); // Transaksi terakhir
            $table->timestamps();
            
            // Index untuk performa
            $table->index('user_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
