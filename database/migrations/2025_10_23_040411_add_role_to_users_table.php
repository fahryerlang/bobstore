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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom role setelah kolom 'email'
            // Tipe ENUM untuk membatasi nilai hanya ke 3 role ini.
            // Default 'pembeli' agar setiap user baru otomatis jadi pembeli.
            $table->enum('role', ['admin', 'kasir', 'pembeli'])
                  ->default('pembeli')
                  ->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};