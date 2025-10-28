<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductTag;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories
        $categories = [
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
                'description' => 'Berbagai macam makanan dan minuman',
                'icon' => '🍔',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Minuman Ringan', 'slug' => 'minuman-ringan', 'description' => 'Minuman segar dan menyegarkan'],
                    ['name' => 'Makanan Ringan', 'slug' => 'makanan-ringan', 'description' => 'Snack dan camilan'],
                    ['name' => 'Makanan Berat', 'slug' => 'makanan-berat', 'description' => 'Makanan utama dan lauk'],
                    ['name' => 'Minuman Panas', 'slug' => 'minuman-panas', 'description' => 'Kopi, teh, dan minuman hangat lainnya'],
                ]
            ],
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Peralatan elektronik dan gadget',
                'icon' => '📱',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Handphone & Aksesoris', 'slug' => 'handphone-aksesoris', 'description' => 'Smartphone dan perlengkapannya'],
                    ['name' => 'Komputer & Laptop', 'slug' => 'komputer-laptop', 'description' => 'Perangkat komputer'],
                    ['name' => 'Audio & Video', 'slug' => 'audio-video', 'description' => 'Headphone, speaker, kamera'],
                ]
            ],
            [
                'name' => 'Fashion & Pakaian',
                'slug' => 'fashion-pakaian',
                'description' => 'Pakaian dan aksesoris fashion',
                'icon' => '👕',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Pakaian Pria', 'slug' => 'pakaian-pria', 'description' => 'Fashion untuk pria'],
                    ['name' => 'Pakaian Wanita', 'slug' => 'pakaian-wanita', 'description' => 'Fashion untuk wanita'],
                    ['name' => 'Pakaian Anak', 'slug' => 'pakaian-anak', 'description' => 'Fashion untuk anak-anak'],
                    ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'description' => 'Tas, sepatu, jam tangan, dll'],
                ]
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'slug' => 'kesehatan-kecantikan',
                'description' => 'Produk kesehatan dan kecantikan',
                'icon' => '💄',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Perawatan Kulit', 'slug' => 'perawatan-kulit', 'description' => 'Skincare dan produk wajah'],
                    ['name' => 'Makeup', 'slug' => 'makeup', 'description' => 'Kosmetik dan makeup'],
                    ['name' => 'Vitamin & Suplemen', 'slug' => 'vitamin-suplemen', 'description' => 'Produk kesehatan'],
                ]
            ],
            [
                'name' => 'Rumah Tangga',
                'slug' => 'rumah-tangga',
                'description' => 'Perlengkapan rumah tangga',
                'icon' => '🏠',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Peralatan Dapur', 'slug' => 'peralatan-dapur', 'description' => 'Alat masak dan makan'],
                    ['name' => 'Peralatan Kebersihan', 'slug' => 'peralatan-kebersihan', 'description' => 'Produk pembersih'],
                    ['name' => 'Dekorasi', 'slug' => 'dekorasi', 'description' => 'Hiasan dan dekorasi rumah'],
                ]
            ],
            [
                'name' => 'Olahraga & Outdoor',
                'slug' => 'olahraga-outdoor',
                'description' => 'Perlengkapan olahraga dan outdoor',
                'icon' => '⚽',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Pakaian Olahraga', 'slug' => 'pakaian-olahraga', 'description' => 'Jersey, sepatu olahraga'],
                    ['name' => 'Alat Fitness', 'slug' => 'alat-fitness', 'description' => 'Peralatan gym dan fitness'],
                    ['name' => 'Outdoor & Camping', 'slug' => 'outdoor-camping', 'description' => 'Perlengkapan outdoor'],
                ]
            ],
            [
                'name' => 'Buku & Alat Tulis',
                'slug' => 'buku-alat-tulis',
                'description' => 'Buku dan perlengkapan tulis',
                'icon' => '📚',
                'is_active' => true,
                'subcategories' => [
                    ['name' => 'Buku', 'slug' => 'buku', 'description' => 'Novel, komik, buku pelajaran'],
                    ['name' => 'Alat Tulis', 'slug' => 'alat-tulis', 'description' => 'Pulpen, pensil, kertas'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'];
            unset($categoryData['subcategories']);
            
            $category = Category::create($categoryData);
            
            foreach ($subcategories as $subcategoryData) {
                $subcategoryData['category_id'] = $category->id;
                $subcategoryData['is_active'] = true;
                Subcategory::create($subcategoryData);
            }
        }

        // Product Tags
        $tags = [
            ['name' => 'Best Seller', 'slug' => 'best-seller', 'color' => '#F87B1B'],
            ['name' => 'New Arrival', 'slug' => 'new-arrival', 'color' => '#10B981'],
            ['name' => 'Promo', 'slug' => 'promo', 'color' => '#EF4444'],
            ['name' => 'Diskon', 'slug' => 'diskon', 'color' => '#F59E0B'],
            ['name' => 'Limited Edition', 'slug' => 'limited-edition', 'color' => '#8B5CF6'],
            ['name' => 'Eksklusif', 'slug' => 'eksklusif', 'color' => '#EC4899'],
            ['name' => 'Organic', 'slug' => 'organic', 'color' => '#059669'],
            ['name' => 'Lokal', 'slug' => 'lokal', 'color' => '#3B82F6'],
            ['name' => 'Import', 'slug' => 'import', 'color' => '#6366F1'],
            ['name' => 'Halal', 'slug' => 'halal', 'color' => '#10B981'],
        ];

        foreach ($tags as $tag) {
            ProductTag::create($tag);
        }
    }
}
