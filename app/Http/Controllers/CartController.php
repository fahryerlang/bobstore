<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\DiscountRule;
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

        $summary = [
            'subtotal' => 0.0,
            'discount' => 0.0,
            'total' => 0.0,
        ];

        $cartItems->each(function (Cart $item) use (&$summary) {
            $product = $item->product;

            if (! $product) {
                return;
            }

            $pricing = $product->discountSummary($item->quantity);

            $item->pricing_summary = $pricing;

            $summary['subtotal'] += $pricing['base_total_price'];
            $summary['discount'] += $pricing['total_discount'];
            $summary['total'] += $pricing['total_price'];
        });

        return view('cart.index', [
            'items' => $cartItems,
            'subtotal' => round($summary['subtotal'], 2),
            'discountTotal' => round($summary['discount'], 2),
            'total' => round($summary['total'], 2),
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

        $items = [];
        $summary = [
            'subtotal' => 0.0,
            'discount' => 0.0,
            'total' => 0.0,
        ];

        foreach ($cartItems as $item) {
            $product = $item->product;

            if (! $product) {
                continue;
            }

            $pricing = $product->discountSummary($item->quantity);

            $items[] = [
                'id' => $product->id,
                'name' => $product->nama_barang,
                'base_price' => $pricing['base_unit_price'],
                'unit_price' => $pricing['unit_price'],
                'base_total_price' => $pricing['base_total_price'],
                'total_price' => $pricing['total_price'],
                'quantity' => $item->quantity,
                'max_stock' => $product->stok,
                'image' => $product->gambar,
                'discount_percentage' => $pricing['discount_percentage'],
                'total_discount' => $pricing['total_discount'],
            ];

            $summary['subtotal'] += $pricing['base_total_price'];
            $summary['discount'] += $pricing['total_discount'];
            $summary['total'] += $pricing['total_price'];
        }

        return view('cart.checkout', [
            'items' => $items,
            'summary' => [
                'subtotal' => round($summary['subtotal'], 2),
                'discount' => round($summary['discount'], 2),
                'total' => round($summary['total'], 2),
            ],
        ]);
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
            'payment_method' => ['required', 'string', 'in:cash,transfer,ewallet,qris'],
            'voucher_code' => ['nullable', 'string'],
        ]);

        $itemsPayload = collect($data['items'])->map(function (array $item) {
            return [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
            ];
        })->values();

        if ($itemsPayload->isEmpty()) {
            return back()->with('error', 'Tidak ada produk yang diproses.');
        }

        $itemDetails = [];
        $orderSubtotal = 0.0;
        $orderDiscount = 0.0;
        $orderTotal = 0.0;

        foreach ($itemsPayload as $itemData) {
            $product = Product::findOrFail($itemData['product_id']);
            $quantity = $itemData['quantity'];

            if ($product->stok !== null && $product->stok < $quantity) {
                return back()->with('error', 'Stok produk '.$product->nama_barang.' tidak mencukupi.');
            }

            $pricing = $product->discountSummary($quantity);

            $itemDetails[] = [
                'product' => $product,
                'quantity' => $quantity,
                'base_unit_price' => $pricing['base_unit_price'],
                'unit_price' => $pricing['unit_price'],
                'base_total_price' => $pricing['base_total_price'],
                'total_price' => $pricing['total_price'],
                'automatic_discount' => $pricing['total_discount'],
                'total_discount' => $pricing['total_discount'],
            ];

            $orderSubtotal += $pricing['base_total_price'];
            $orderDiscount += $pricing['total_discount'];
            $orderTotal += $pricing['total_price'];
        }

        $couponCode = strtoupper(trim((string) ($data['voucher_code'] ?? '')));
        $coupon = null;
        $couponDiscountTotal = 0.0;

        if ($couponCode !== '') {
            $coupon = Coupon::whereRaw('UPPER(code) = ?', [$couponCode])->first();

            if (! $coupon) {
                return back()->with('error', 'Kode voucher tidak ditemukan.');
            }

            if ($coupon->discount && $coupon->discount->applies_automatically) {
                return back()->with('error', 'Kode voucher ini tidak diperlukan karena diskon tersebut sudah diterapkan otomatis.');
            }

            if (! $coupon->canBeUsedBy(Auth::user(), $orderTotal)) {
                return back()->with('error', 'Kode voucher tidak dapat digunakan untuk transaksi ini.');
            }

            foreach ($itemDetails as &$detail) {
                $rule = DiscountRule::resolveForProduct(
                    $detail['product'],
                    $detail['quantity'],
                    [
                        'only_automatic' => false,
                        'discount_id' => $coupon->discount_id,
                    ]
                );

                if (! $rule) {
                    continue;
                }

                $pricing = $rule->buildPricing($detail['unit_price'], $detail['quantity']);

                if ($pricing['total_discount'] <= 0) {
                    continue;
                }

                $detail['unit_price'] = $pricing['unit_price'];
                $detail['total_price'] = $pricing['total_price'];
                $detail['total_discount'] += $pricing['total_discount'];
                $detail['coupon_discount'] = $pricing['total_discount'];

                $couponDiscountTotal += $pricing['total_discount'];
            }
            unset($detail);

            if ($couponDiscountTotal <= 0) {
                return back()->with('error', 'Kode voucher tidak berlaku untuk produk di keranjang Anda.');
            }

            $orderDiscount += $couponDiscountTotal;
            $orderTotal = max(0, $orderTotal - $couponDiscountTotal);
        }

        $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));

        $sales = [];

        DB::transaction(function () use ($itemDetails, $invoiceNumber, &$sales, $coupon, $couponDiscountTotal) {
            foreach ($itemDetails as $detail) {
                /** @var Product $product */
                $product = $detail['product'];
                $quantity = $detail['quantity'];

                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $detail['unit_price'],
                    'total_price' => $detail['total_price'],
                    'sale_date' => now(),
                ]);

                $sales[] = $sale;

                if ($product->stok !== null) {
                    $product->decrement('stok', $quantity);
                }
            }

            if ($coupon && $couponDiscountTotal > 0) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'customer_id' => Auth::id(),
                    'sale_id' => $sales[0]->id ?? null,
                    'used_at' => now(),
                ]);
            }

            Cart::where('user_id', Auth::id())->delete();
        });

        $message = 'Pembayaran berhasil! Terima kasih telah berbelanja.';

        if ($orderDiscount > 0) {
            $message .= ' Anda menghemat Rp '.number_format($orderDiscount, 0, ',', '.').'.';
        }

        return redirect()->route('customer.transactions.index')->with('success', $message);
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

        $pricing = $product->discountSummary($data['quantity']);

        $items = [[
            'id' => $product->id,
            'name' => $product->nama_barang,
            'base_price' => $pricing['base_unit_price'],
            'unit_price' => $pricing['unit_price'],
            'base_total_price' => $pricing['base_total_price'],
            'total_price' => $pricing['total_price'],
            'quantity' => $data['quantity'],
            'max_stock' => $product->stok,
            'image' => $product->gambar,
            'discount_percentage' => $pricing['discount_percentage'],
            'total_discount' => $pricing['total_discount'],
        ]];

        $summary = [
            'subtotal' => round($pricing['base_total_price'], 2),
            'discount' => round($pricing['total_discount'], 2),
            'total' => round($pricing['total_price'], 2),
        ];

        return view('cart.checkout', compact('items', 'summary'));
    }

    private function authorizeCart(Cart $cart): void
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
