<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductTag;
use Illuminate\Database\Seeder;

class SampleProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Makanan & Minuman
            [
                'nama_barang' => 'Coca Cola 330ml',
                'harga' => 6000,
                'stok' => 100,
                'category_id' => 1,
                'subcategory_id' => 1,
                'tags' => [1, 10], // Best Seller, Halal
            ],
            [
                'nama_barang' => 'Indomie Goreng',
                'harga' => 3500,
                'stok' => 200,
                'category_id' => 1,
                'subcategory_id' => 2,
                'tags' => [1, 8], // Best Seller, Lokal
            ],
            [
                'nama_barang' => 'Nasi Goreng Spesial',
                'harga' => 25000,
                'stok' => 50,
                'category_id' => 1,
                'subcategory_id' => 3,
                'tags' => [2, 10], // New Arrival, Halal
            ],
            
            // Elektronik
            [
                'nama_barang' => 'Headphone Bluetooth XYZ',
                'harga' => 350000,
                'stok' => 25,
                'category_id' => 2,
                'subcategory_id' => 7,
                'tags' => [2, 5], // New Arrival, Limited Edition
            ],
            [
                'nama_barang' => 'Mouse Gaming RGB',
                'harga' => 150000,
                'stok' => 40,
                'category_id' => 2,
                'subcategory_id' => 6,
                'tags' => [3, 4], // Promo, Diskon
            ],
            
            // Fashion & Pakaian
            [
                'nama_barang' => 'Kaos Polos Premium',
                'harga' => 85000,
                'stok' => 75,
                'category_id' => 3,
                'subcategory_id' => 8,
                'tags' => [1, 8], // Best Seller, Lokal
            ],
            [
                'nama_barang' => 'Sepatu Sneakers Canvas',
                'harga' => 250000,
                'stok' => 30,
                'category_id' => 3,
                'subcategory_id' => 11,
                'tags' => [2, 4], // New Arrival, Diskon
            ],
            
            // Kesehatan & Kecantikan
            [
                'nama_barang' => 'Facial Wash Cleanser',
                'harga' => 45000,
                'stok' => 60,
                'category_id' => 4,
                'subcategory_id' => 12,
                'tags' => [1, 7], // Best Seller, Organic
            ],
            [
                'nama_barang' => 'Lipstick Matte Long Lasting',
                'harga' => 75000,
                'stok' => 45,
                'category_id' => 4,
                'subcategory_id' => 13,
                'tags' => [6, 9], // Eksklusif, Import
            ],
            
            // Rumah Tangga
            [
                'nama_barang' => 'Panci Set Stainless Steel',
                'harga' => 450000,
                'stok' => 15,
                'category_id' => 5,
                'subcategory_id' => 15,
                'tags' => [5, 9], // Limited Edition, Import
            ],
        ];

        foreach ($products as $productData) {
            $tags = $productData['tags'];
            unset($productData['tags']);
            
            $product = Product::create($productData);
            $product->tags()->attach($tags);
        }

        $this->command->info('Successfully created ' . count($products) . ' sample products!');
    }
}
