<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User; // <-- IMPORT MODEL USER
use Illuminate\Support\Facades\Hash; // <-- IMPORT HASH FACADE

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun ADMIN
        //    Email: admin@bobstore.com
        //    Pass: password
        User::create([
            'name' => 'Admin BobStore',
            'email' => 'admin@bobstore.com',
            'role' => 'admin',
            'password' => Hash::make('password') // <-- Password di-hash
        ]);

        // 2. Akun KASIR
        //    Email: kasir@bobstore.com
        //    Pass: password
        User::create([
            'name' => 'Kasir BobStore',
            'email' => 'kasir@bobstore.com',
            'role' => 'kasir',
            'password' => Hash::make('password')
        ]);

        // 3. Akun PEMBELI
        //    Email: pembeli@bobstore.com
        //    Pass: password
        User::create([
            'name' => 'Pembeli BobStore',
            'email' => 'pembeli@bobstore.com',
            'role' => 'pembeli',
            'password' => Hash::make('password')
        ]);
    }
}