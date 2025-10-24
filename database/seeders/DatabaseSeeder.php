<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus/komentari baris default jika ada, seperti:
        // \App\Models\User::factory(10)->create();

        // Panggil seeder yang kita buat
        $this->call([
            UserSeeder::class,
            // Nanti Anda bisa tambahkan ProductSeeder::class di sini
        ]);
    }
}   