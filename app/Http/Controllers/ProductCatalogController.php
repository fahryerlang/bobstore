<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        $products = Product::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'search' => $search,
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
