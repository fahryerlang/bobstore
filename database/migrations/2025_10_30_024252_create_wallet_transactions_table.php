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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User yang melakukan transaksi
            $table->enum('type', ['topup', 'payment', 'refund', 'adjustment']); // Tipe transaksi
            $table->decimal('amount', 15, 2); // Jumlah transaksi
            $table->decimal('balance_before', 15, 2); // Saldo sebelum transaksi
            $table->decimal('balance_after', 15, 2); // Saldo setelah transaksi
            $table->string('reference_type')->nullable(); // Model yang terkait (Sale, etc)
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dari model terkait
            $table->text('description')->nullable(); // Deskripsi transaksi
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang approve topup
            $table->string('status')->default('completed'); // completed, pending, failed
            $table->json('meta')->nullable(); // Data tambahan (payment method, notes, etc)
            $table->timestamps();
            
            // Indexes
            $table->index('wallet_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
