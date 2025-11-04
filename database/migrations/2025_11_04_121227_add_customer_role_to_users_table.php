<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom role dari ENUM untuk menambahkan 'customer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir', 'customer', 'pembeli') NOT NULL DEFAULT 'pembeli'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM lama (tanpa 'customer')
        // PERHATIAN: Ini akan gagal jika ada user dengan role 'customer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir', 'pembeli') NOT NULL DEFAULT 'pembeli'");
    }
};
