<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        /** @var LengthAwarePaginator $products */
        $products = Product::orderByDesc('created_at')->paginate(12);

        return view('kasir.products.index', compact('products'));
    }
}
