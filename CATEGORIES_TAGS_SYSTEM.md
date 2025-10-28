# Categories & Tags System - Dokumentasi

## Overview
Sistem kategori dan tag untuk klasifikasi produk yang memudahkan pembeli mencari dan menyaring produk.

## Struktur Database

### Tabel yang Dibuat:
1. **categories** - Kategori utama produk
2. **subcategories** - Subkategori dari kategori utama
3. **product_tags** - Tag produk untuk label khusus
4. **product_tag** - Pivot table untuk relasi many-to-many

### Kolom di Tabel Products yang Ditambahkan:
- `category_id` (nullable) - Foreign key ke categories
- `subcategory_id` (nullable) - Foreign key ke subcategories

## Models & Relasi

### Category Model
```php
// Relasi
$category->subcategories()        // HasMany
$category->activeSubcategories()  // HasMany dengan filter is_active
$category->products()             // HasMany

// Scope
Category::active()->get()         // Hanya kategori aktif

// Auto-generate slug dari name
```

### Subcategory Model
```php
// Relasi
$subcategory->category()          // BelongsTo
$subcategory->products()          // HasMany

// Scope
Subcategory::active()->get()      // Hanya subkategori aktif
```

### ProductTag Model
```php
// Relasi
$tag->products()                  // BelongsToMany

// Auto-generate slug dari name
```

### Product Model (Updated)
```php
// Relasi baru
$product->category()              // BelongsTo
$product->subcategory()           // BelongsTo
$product->tags()                  // BelongsToMany

// Menambah/attach tags
$product->tags()->attach([1, 2, 3]);
$product->tags()->sync([1, 2]);
$product->tags()->detach([3]);
```

## Data Seeder

### Kategori yang Di-seed:
1. **Makanan & Minuman** 🍔
   - Minuman Ringan
   - Makanan Ringan
   - Makanan Berat
   - Minuman Panas

2. **Elektronik** 📱
   - Handphone & Aksesoris
   - Komputer & Laptop
   - Audio & Video

3. **Fashion & Pakaian** 👕
   - Pakaian Pria
   - Pakaian Wanita
   - Pakaian Anak
   - Aksesoris

4. **Kesehatan & Kecantikan** 💄
   - Perawatan Kulit
   - Makeup
   - Vitamin & Suplemen

5. **Rumah Tangga** 🏠
   - Peralatan Dapur
   - Peralatan Kebersihan
   - Dekorasi

6. **Olahraga & Outdoor** ⚽
   - Pakaian Olahraga
   - Alat Fitness
   - Outdoor & Camping

7. **Buku & Alat Tulis** 📚
   - Buku
   - Alat Tulis

### Tags yang Di-seed:
- Best Seller (#F87B1B)
- New Arrival (#10B981)
- Promo (#EF4444)
- Diskon (#F59E0B)
- Limited Edition (#8B5CF6)
- Eksklusif (#EC4899)
- Organic (#059669)
- Lokal (#3B82F6)
- Import (#6366F1)
- Halal (#10B981)

## Cara Penggunaan

### Membuat Produk dengan Kategori & Tag
```php
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTag;

// Buat produk
$product = Product::create([
    'nama_barang' => 'Teh Pucuk Harum',
    'harga' => 5000,
    'stok' => 100,
    'satuan' => 'botol',
    'category_id' => 1,      // Makanan & Minuman
    'subcategory_id' => 1,   // Minuman Ringan
]);

// Attach tags
$product->tags()->attach([1, 10]); // Best Seller & Halal
```

### Query dengan Kategori
```php
// Produk berdasarkan kategori
$products = Product::where('category_id', 1)->get();

// Produk dengan kategori (eager loading)
$products = Product::with('category', 'subcategory', 'tags')->get();

// Kategori dengan semua produknya
$category = Category::with('products')->find(1);

// Subcategori dengan produk
$subcategory = Subcategory::with('products')->find(1);
```

### Filter Produk
```php
// Filter by kategori
$products = Product::whereHas('category', function($q) {
    $q->where('slug', 'makanan-minuman');
})->get();

// Filter by tag
$products = Product::whereHas('tags', function($q) {
    $q->where('slug', 'best-seller');
})->get();

// Multiple filters
$products = Product::where('category_id', 1)
    ->where('subcategory_id', 1)
    ->whereHas('tags', function($q) {
        $q->whereIn('slug', ['promo', 'diskon']);
    })
    ->get();
```

## Migrasi

### Menjalankan Migration
```bash
php artisan migrate
```

### Menjalankan Seeder
```bash
php artisan db:seed --class=CategorySeeder
```

### Rollback (jika perlu)
```bash
php artisan migrate:rollback --step=5
```

## Keuntungan Sistem Ini

1. **Navigasi Lebih Mudah** - Pembeli bisa browse berdasarkan kategori
2. **Filter Canggih** - Kombinasi kategori, subkategori, dan tag
3. **SEO Friendly** - Slug otomatis untuk URL yang ramah mesin pencari
4. **Fleksibel** - Tag dapat digunakan untuk promo, label khusus, dll
5. **Scalable** - Mudah menambah kategori/tag baru

## Next Steps (Opsional)

1. Buat halaman browse by category
2. Tambahkan filter di halaman catalog
3. Tampilkan breadcrumb kategori
4. Buat sidebar dengan kategori tree
5. Implementasi search dengan filter kategori & tag
6. Admin panel untuk manage categories & tags

## Files yang Dibuat/Dimodifikasi

### Migrations:
- `2025_10_28_115107_create_categories_table.php`
- `2025_10_28_115233_create_subcategories_table.php`
- `2025_10_28_115340_create_product_tags_table.php`
- `2025_10_28_115504_create_product_tag_table.php`
- `2025_10_28_115547_add_category_fields_to_products_table.php`

### Models:
- `app/Models/Category.php`
- `app/Models/Subcategory.php`
- `app/Models/ProductTag.php`
- `app/Models/Product.php` (updated)

### Seeders:
- `database/seeders/CategorySeeder.php`
