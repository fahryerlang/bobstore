<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    /**
     * Display the public catalog listing.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categoryId = $request->query('category');
        $subcategoryId = $request->query('subcategory');
        $tagId = $request->query('tag');

        $products = Product::query()
            ->with(['category', 'subcategory', 'tags'])
            ->when($search, function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($subcategoryId, function ($query) use ($subcategoryId) {
                $query->where('subcategory_id', $subcategoryId);
            })
            ->when($tagId, function ($query) use ($tagId) {
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('product_tags.id', $tagId);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Get all categories, subcategories, and tags for filters
        $categories = Category::active()->withCount('products')->get();
        $subcategories = Subcategory::active()
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->withCount('products')
            ->get();
        $tags = ProductTag::all();

        // Get selected filters for display
        $selectedCategory = $categoryId ? Category::find($categoryId) : null;
        $selectedSubcategory = $subcategoryId ? Subcategory::find($subcategoryId) : null;
        $selectedTag = $tagId ? ProductTag::find($tagId) : null;

        return view('catalog.index', [
            'products' => $products,
            'search' => $search,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'tags' => $tags,
            'selectedCategory' => $selectedCategory,
            'selectedSubcategory' => $selectedSubcategory,
            'selectedTag' => $selectedTag,
        ]);
    }

    /**
     * Display a specific product for shoppers.
     */
    public function show(Product $product): View
    {
        return view('catalog.show', [
            'product' => $product,
        ]);
    }
}
