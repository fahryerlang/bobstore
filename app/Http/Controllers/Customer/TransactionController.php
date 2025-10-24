<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display the authenticated customer's transaction history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $transactions = SaleTransaction::with(['items.product'])
            ->where('customer_id', $user->id)
            ->latest('created_at')
            ->get();

        $legacySaleGroups = Sale::with('product')
            ->where('user_id', $user->id)
            ->whereNull('sale_transaction_id')
            ->orderByDesc('sale_date')
            ->get()
            ->groupBy('invoice_number');

        $history = new Collection();

        foreach ($transactions as $transaction) {
            $firstItem = $transaction->items->first();
            $history->push([
                'id' => 'txn-'.$transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'date' => $this->resolveDate($transaction->created_at, optional($firstItem)->sale_date),
                'subtotal' => (float) $transaction->subtotal,
                'discount' => (float) $transaction->discount,
                'total' => (float) $transaction->total,
                'amount_paid' => $transaction->amount_paid !== null ? (float) $transaction->amount_paid : null,
                'change_due' => $transaction->change_due !== null ? (float) $transaction->change_due : null,
                'items' => $transaction->items->map(function ($item) {
                    return [
                        'product_name' => $item->product->nama_barang ?? 'Produk dihapus',
                        'quantity' => (int) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'total_price' => (float) $item->total_price,
                    ];
                })->all(),
            ]);
        }

        foreach ($legacySaleGroups as $invoice => $lines) {
            $firstLine = $lines->first();
            $history->push([
                'id' => 'legacy-'.$invoice,
                'invoice_number' => $invoice,
                'date' => $this->resolveDate(optional($firstLine)->sale_date, optional($firstLine)->created_at),
                'subtotal' => (float) $lines->sum('total_price'),
                'discount' => 0.0,
                'total' => (float) $lines->sum('total_price'),
                'amount_paid' => null,
                'change_due' => null,
                'items' => $lines->map(function ($item) {
                    return [
                        'product_name' => $item->product->nama_barang ?? 'Produk dihapus',
                        'quantity' => (int) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'total_price' => (float) $item->total_price,
                    ];
                })->all(),
            ]);
        }

        $sortedHistory = $history->sortByDesc(function ($entry) {
            /** @var Carbon|null $date */
            $date = $entry['date'];
            return $date ? $date->timestamp : 0;
        })->values();

        return view('customers.transactions.index', [
            'transactions' => $sortedHistory,
        ]);
    }

    /**
     * Resolve the date to use for display.
     */
    private function resolveDate(?Carbon $primary, ?Carbon $fallback): ?Carbon
    {
        if ($primary instanceof Carbon) {
            return $primary;
        }

        if ($fallback instanceof Carbon) {
            return $fallback;
        }

        return null;
    }
}
