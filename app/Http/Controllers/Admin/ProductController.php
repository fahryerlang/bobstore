<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Pastikan ini di-use
use App\Models\Product;
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
        return view('admin.products.create');
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
        ]);

        $data = $request->all();

        // 2. Handle Upload Gambar
        if ($request->hasFile('gambar')) {
            // Simpan gambar di 'storage/app/public/products'
            // URL publiknya akan menjadi 'storage/products/namafile.jpg'
            $path = $request->file('gambar')->store('public/products');
            $data['gambar'] = $path; // Simpan path-nya ke data
        }

        // 3. Simpan ke Database
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     * Kita menggunakan Route Model Binding (Product $product)
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
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
        ]);

        $data = $request->all();

        // 2. Handle Gambar Baru (Jika ada)
        if ($request->hasFile('gambar')) {

            // 2a. Hapus gambar lama (jika ada)
            if ($product->gambar) {
                Storage::delete($product->gambar);
            }

            // 2b. Simpan gambar baru
            $path = $request->file('gambar')->store('public/products');
            $data['gambar'] = $path;
        }

        // 3. Update data di Database
        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk dari database.
     */
    public function destroy(Product $product)
    {
        // 1. Hapus gambar dari storage (jika ada)
        if ($product->gambar) {
            Storage::delete($product->gambar);
        }

        // 2. Hapus data dari database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}