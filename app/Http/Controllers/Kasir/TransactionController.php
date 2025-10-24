<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kasir\StoreSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display the cashier transaction workspace.
     */
    public function index(Request $request): View
    {
        $customers = User::query()
            ->where('role', 'pembeli')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('kasir.transactions.index', compact('customers'));
    }

    /**
     * Handle AJAX search for products.
     * Returns products from the same catalog as admin's product management.
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $query = trim((string) $request->input('q'));

        // Get products from the same table as admin's product catalog
        $products = Product::query()
            ->where('stok', '>', 0) // Only show products with stock available
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('nama_barang', 'like', "%{$query}%");

                    if (is_numeric($query)) {
                        $sub->orWhere('id', (int) $query);
                    }
                });
            })
            ->orderByDesc('stok')
            ->orderBy('nama_barang')
            ->limit(20) // Increased limit to show more products
            ->get(['id', 'nama_barang', 'harga', 'stok', 'gambar']);

        return response()->json(
            $products->map(fn ($product) => [
                'id' => $product->id,
                'nama_barang' => $product->nama_barang,
                'harga' => (float) $product->harga,
                'stok' => (int) $product->stok,
                'gambar' => $product->gambar,
            ])
        );
    }

    /**
     * Store a completed cashier transaction.
     */
    public function store(StoreSaleRequest $request): RedirectResponse
    {
        $customerId = $request->input('customer_id');
        $itemsInput = $request->input('items', []);
        $discount = (float) $request->input('discount', 0);
        $amountPaid = (float) $request->input('amount_paid');
        $cashier = $request->user();

        $transaction = DB::transaction(function () use (
            $itemsInput,
            $customerId,
            $discount,
            $amountPaid,
            $cashier
        ) {
            $now = now();
            $invoiceNumber = $this->generateInvoiceNumber();

            $lineItems = [];
            $subtotal = 0;

            foreach ($itemsInput as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                /** @var Product|null $product */
                $product = Product::query()->lockForUpdate()->find($productId);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => ['Produk tidak ditemukan.'],
                    ]);
                }

                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'items' => ['Jumlah setiap produk minimal 1.'],
                    ]);
                }

                if ($quantity > $product->stok) {
                    throw ValidationException::withMessages([
                        'items' => ["Stok {$product->nama_barang} tidak mencukupi."],
                    ]);
                }

                $unitPrice = (float) $product->harga;
                $lineTotal = $unitPrice * $quantity;

                $lineItems[] = [
                    'model' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];

                $subtotal += $lineTotal;
            }

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => ['Diskon melebihi subtotal.'],
                ]);
            }

            $total = max($subtotal - $discount, 0);

            if ($amountPaid < $total) {
                throw ValidationException::withMessages([
                    'amount_paid' => ['Jumlah bayar belum mencukupi total transaksi.'],
                ]);
            }

            $changeDue = $amountPaid - $total;

            $transaction = SaleTransaction::create([
                'invoice_number' => $invoiceNumber,
                'cashier_id' => $cashier->id,
                'customer_id' => $customerId ?: null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'change_due' => $changeDue,
            ]);

            foreach ($lineItems as $line) {
                /** @var Product $productModel */
                $productModel = $line['model'];

                $productModel->decrement('stok', $line['quantity']);

                Sale::create([
                    'sale_transaction_id' => $transaction->id,
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $customerId ?: null,
                    'cashier_id' => $cashier->id,
                    'product_id' => $productModel->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'total_price' => $line['line_total'],
                    'sale_date' => $now,
                ]);
            }

            return $transaction;
        });

        return redirect()
            ->route('kasir.transactions.show', $transaction->invoice_number)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Display a printable receipt for the finished transaction.
     */
    public function show(SaleTransaction $transaction): View
    {
        $transaction->load(['cashier', 'customer', 'items.product']);

        return view('kasir.transactions.show', compact('transaction'));
    }

    /**
     * Generate invoice number string.
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('ymd');

        do {
            $candidate = $prefix . '-' . Str::upper(Str::random(4));
        } while (SaleTransaction::where('invoice_number', $candidate)->exists());

        return $candidate;
    }
}
