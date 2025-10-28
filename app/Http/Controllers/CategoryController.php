<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan semua kategori
     */
    public function index()
    {
        $categories = Category::active()
            ->withCount('products')
            ->with('activeSubcategories')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Tampilkan produk berdasarkan kategori
     */
    public function show(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->with(['category', 'subcategory', 'tags'])
            ->paginate(20);

        $subcategories = $category->activeSubcategories;
        $tags = ProductTag::all();

        return view('categories.show', compact('category', 'products', 'subcategories', 'tags'));
    }

    /**
     * Tampilkan produk berdasarkan subkategori
     */
    public function subcategory(Subcategory $subcategory)
    {
        $products = Product::where('subcategory_id', $subcategory->id)
            ->with(['category', 'subcategory', 'tags'])
            ->paginate(20);

        $category = $subcategory->category;
        $tags = ProductTag::all();

        return view('categories.subcategory', compact('subcategory', 'category', 'products', 'tags'));
    }

    /**
     * Tampilkan produk berdasarkan tag
     */
    public function tag(ProductTag $tag)
    {
        $products = $tag->products()
            ->with(['category', 'subcategory', 'tags'])
            ->paginate(20);

        $categories = Category::active()->get();

        return view('categories.tag', compact('tag', 'products', 'categories'));
    }
}
