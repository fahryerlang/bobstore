<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Pastikan ini di-use
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductTag;
use App\Models\Discount;
use App\Models\DiscountRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file (gambar)
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Menampilkan semua produk.
     */
    public function index()
    {
        // Ambil semua produk, urutkan dari yang terbaru, dan paginasi
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        $categories = Category::active()->get();
        $subcategories = Subcategory::active()->get();
        $tags = ProductTag::all();
        
        return view('admin.products.create', compact('categories', 'subcategories', 'tags'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:product_tags,id',
            // Discount fields
            'enable_discount' => 'nullable|boolean',
            'discount_name' => 'nullable|string|max:255',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'priority' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'discount_description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['nama_barang', 'harga', 'stok', 'category_id', 'subcategory_id']);

            // 2. Handle Upload Gambar
            if ($request->hasFile('gambar')) {
                // Simpan gambar di 'storage/app/public/products'
                // URL publiknya akan menjadi 'storage/products/namafile.jpg'
                $path = $request->file('gambar')->store('products', 'public');
                $data['gambar'] = $path; // Simpan path-nya ke data
            }
            
            // 3. Simpan ke Database
            $product = Product::create($data);
            
            // 4. Attach tags jika ada
            if ($request->has('tags')) {
                $product->tags()->attach($request->tags);
            }

            // 5. Handle Discount
            if ($request->input('enable_discount') && $request->filled('discount_value')) {
                $this->createProductDiscount($product, $request);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan produk: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form untuk mengedit produk.
     * Kita menggunakan Route Model Binding (Product $product)
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $subcategories = Subcategory::active()->get();
        $tags = ProductTag::all();
        
        return view('admin.products.edit', compact('product', 'categories', 'subcategories', 'tags'));
    }

    /**
     * Update produk di database.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:product_tags,id',
            // Discount fields
            'enable_discount' => 'nullable|boolean',
            'discount_name' => 'nullable|string|max:255',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'priority' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'discount_description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['nama_barang', 'harga', 'stok', 'category_id', 'subcategory_id']);

            // 2. Handle Gambar Baru (Jika ada)
            if ($request->hasFile('gambar')) {

                // 2a. Hapus gambar lama (jika ada)
                if ($product->gambar) {
                    Storage::disk('public')->delete($product->gambar);
                }

                // 2b. Simpan gambar baru
                $path = $request->file('gambar')->store('products', 'public');
                $data['gambar'] = $path;
            }
            
            // 3. Update data di Database
            $product->update($data);
            
            // 4. Sync tags
            if ($request->has('tags')) {
                $product->tags()->sync($request->tags);
            } else {
                $product->tags()->detach();
            }

            // 5. Handle Discount
            if ($request->input('enable_discount') && $request->filled('discount_value')) {
                $this->updateProductDiscount($product, $request);
            } else {
                // Remove discount if disabled
                $this->removeProductDiscount($product);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus produk dari database.
     */
    public function destroy(Product $product)
    {
        // 1. Hapus gambar dari storage (jika ada)
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        // 2. Hapus data dari database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Create discount for a product
     */
    private function createProductDiscount(Product $product, Request $request): void
    {
        // Create discount campaign
        $discount = Discount::create([
            'name' => $request->input('discount_name') ?: "Diskon {$product->nama_barang}",
            'description' => $request->input('discount_description'),
            'applies_automatically' => true,
            'is_stackable' => false,
            'is_active' => true,
            'starts_at' => $request->input('starts_at') ?: null,
            'ends_at' => $request->input('ends_at') ?: null,
        ]);

        // Get and sanitize values
        $minQuantity = $request->input('min_quantity');
        $priority = $request->input('priority');
        
        // Create discount rule for this product
        DiscountRule::create([
            'discount_id' => $discount->id,
            'product_id' => $product->id,
            'category_id' => null,
            'discount_type' => $request->input('discount_type') ?: 'percentage',
            'discount_value' => $request->input('discount_value'),
            'min_quantity' => ($minQuantity === null || $minQuantity === '') ? 1 : (int)$minQuantity,
            'priority' => ($priority === null || $priority === '') ? 0 : (int)$priority,
        ]);
    }

    /**
     * Update discount for a product
     */
    private function updateProductDiscount(Product $product, Request $request): void
    {
        // Get existing discount rule for this product
        $existingRule = $product->discountRules()->with('discount')->first();

        if ($existingRule && $existingRule->discount) {
            // Update existing discount
            $existingRule->discount->update([
                'name' => $request->input('discount_name') ?: "Diskon {$product->nama_barang}",
                'description' => $request->input('discount_description'),
                'starts_at' => $request->input('starts_at') ?: null,
                'ends_at' => $request->input('ends_at') ?: null,
            ]);

            // Get and sanitize values
            $minQuantity = $request->input('min_quantity');
            $priority = $request->input('priority');

            // Update existing rule
            $existingRule->update([
                'discount_type' => $request->input('discount_type') ?: 'percentage',
                'discount_value' => $request->input('discount_value'),
                'min_quantity' => ($minQuantity === null || $minQuantity === '') ? 1 : (int)$minQuantity,
                'priority' => ($priority === null || $priority === '') ? 0 : (int)$priority,
            ]);
        } else {
            // Create new discount if none exists
            $this->createProductDiscount($product, $request);
        }
    }

    /**
     * Remove discount from a product
     */
    private function removeProductDiscount(Product $product): void
    {
        // Get all discount rules for this product
        $rules = $product->discountRules()->with('discount')->get();

        foreach ($rules as $rule) {
            $discount = $rule->discount;
            
            // Delete the rule
            $rule->delete();
            
            // If the discount has no more rules, delete it too
            if ($discount && $discount->rules()->count() === 0) {
                $discount->delete();
            }
        }
    }
}