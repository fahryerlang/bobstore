<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the authenticated user's cart.
     */
    public function index(): View
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function (Cart $item) {
            $price = optional($item->product)->harga ?? 0;
            return $price * $item->quantity;
        });

        return view('cart.index', [
            'items' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart or increment its quantity.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        $cartItem = Cart::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $data['quantity'];
        $cartItem->save();

        return back()->with('success', $product->nama_barang.' telah ditambahkan ke keranjang.');
    }

    /**
     * Update the quantity for a cart item.
     */
    public function update(Request $request, Cart $cart): RedirectResponse
    {
        $this->authorizeCart($cart);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart->update($data);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        $this->authorizeCart($cart);

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Show checkout page from cart.
     */
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $items = $cartItems->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product->nama_barang,
                'price' => $item->product->harga,
                'quantity' => $item->quantity,
                'max_stock' => $item->product->stok,
                'image' => $item->product->gambar,
            ];
        })->toArray();

        return view('cart.checkout', compact('items'));
    }

    /**
     * Process checkout and complete payment.
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'in:cash,transfer,ewallet,qris'],
            'voucher_code' => ['nullable', 'string'],
        ]);

        // Validate stock availability
        foreach ($data['items'] as $itemData) {
            $product = Product::findOrFail($itemData['product_id']);
            if ($product->stok !== null && $product->stok < $itemData['quantity']) {
                return back()->with('error', 'Stok produk '.$product->nama_barang.' tidak mencukupi.');
            }
        }

        DB::transaction(function () use ($data) {
            $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));

            foreach ($data['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                $unitPrice = $itemData['price'];
                $quantity = $itemData['quantity'];
                $total = $unitPrice * $quantity;

                Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $total,
                    'sale_date' => now(),
                ]);

                if ($product->stok !== null) {
                    $product->decrement('stok', $quantity);
                }
            }

            // Clear cart after successful checkout
            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('customer.transactions.index')->with('success', 'Pembayaran berhasil! Terima kasih telah berbelanja.');
    }

    /**
     * Buy now - Show checkout page for direct purchase without adding to cart.
     */
    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->stok !== null && $product->stok < $data['quantity']) {
            return back()->with('error', 'Stok produk '.$product->nama_barang.' tidak mencukupi.');
        }

        $items = [[
            'id' => $product->id,
            'name' => $product->nama_barang,
            'price' => $product->harga,
            'quantity' => $data['quantity'],
            'max_stock' => $product->stok,
            'image' => $product->gambar,
        ]];

        return view('cart.checkout', compact('items'));
    }

    private function authorizeCart(Cart $cart): void
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
