<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the storefront landing page with highlighted products.
     */
    public function index(): View
    {
        $products = Product::latest()->take(12)->get();

        return view('home', [
            'products' => $products,
        ]);
    }
}
