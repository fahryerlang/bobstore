<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Pastikan ini di-use
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file (gambar)

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
        ]);

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

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
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
        ]);

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

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
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
}