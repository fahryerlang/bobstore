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
     * Process checkout, persist sales, and clear the cart.
     */
    public function checkout(): RedirectResponse
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        foreach ($cartItems as $item) {
            if (!$item->product) {
                return redirect()->route('cart.index')->with('error', 'Salah satu produk tidak tersedia lagi.');
            }

            if ($item->product->stok !== null && $item->product->stok < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk '.$item->product->nama_barang.' tidak mencukupi.');
            }
        }

        DB::transaction(function () use ($cartItems) {
            $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));

            foreach ($cartItems as $item) {
                $product = $item->product;

                $unitPrice = $product->harga ?? 0;
                $quantity = $item->quantity;
                $total = $unitPrice * $quantity;

                Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $item->user_id,
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

            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('cart.index')->with('success', 'Checkout berhasil. Terima kasih telah berbelanja!');
    }

    private function authorizeCart(Cart $cart): void
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
