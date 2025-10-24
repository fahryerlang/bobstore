<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class SalesReportController extends Controller
{
    /**
     * Display the sales report with filters.
     */
    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);
        $query = $this->buildQuery($filters);

        $summary = $this->buildSummary(clone $query);

        /** @var LengthAwarePaginator $sales */
        $sales = $query
            ->orderByDesc('sale_date')
            ->paginate(15)
            ->withQueryString();

        $customers = User::where('role', 'pembeli')
            ->orderBy('name')
            ->get(['id', 'name']);

        $products = Product::orderBy('nama_barang')
            ->get(['id', 'nama_barang']);

        return view('admin.sales.index', [
            'sales' => $sales,
            'summary' => $summary,
            'customers' => $customers,
            'products' => $products,
            'filters' => $filters,
        ]);
    }

    /**
     * Render printable sales report.
     */
    public function print(Request $request)
    {
        $filters = $this->validateFilters($request);
        $query = $this->buildQuery($filters);

        $sales = $query
            ->orderByDesc('sale_date')
            ->get();

        $summary = $this->buildSummaryFromCollection($sales);

        $selectedCustomer = null;
        $selectedProduct = null;

        if (!empty($filters['customer_id'])) {
            $selectedCustomer = User::select('id', 'name')->find($filters['customer_id']);
        }

        if (!empty($filters['product_id'])) {
            $selectedProduct = Product::select('id', 'nama_barang')->find($filters['product_id']);
        }

        return view('admin.sales.print', [
            'sales' => $sales,
            'summary' => $summary,
            'filters' => $filters,
            'selectedCustomer' => $selectedCustomer,
            'selectedProduct' => $selectedProduct,
        ]);
    }

    /**
     * Validate and sanitize the incoming filters.
     */
    private function validateFilters(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'customer_id' => ['nullable', 'exists:users,id'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        return Arr::only($validated + [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'customer_id' => $request->input('customer_id'),
            'product_id' => $request->input('product_id'),
        ], ['start_date', 'end_date', 'customer_id', 'product_id']);
    }

    /**
     * Build the base sales query using the provided filters.
     */
    private function buildQuery(array $filters)
    {
        $query = Sale::with(['customer', 'product']);

        if (!empty($filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['customer_id'])) {
            $query->where('user_id', $filters['customer_id']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        return $query;
    }

    /**
     * Build summary statistics from the query.
     */
    private function buildSummary($query): array
    {
        return [
            'total_transactions' => (clone $query)->count(),
            'total_quantity' => (clone $query)->sum('quantity'),
            'total_revenue' => (clone $query)->sum('total_price'),
        ];
    }

    /**
     * Build summary statistics from a collection.
     */
    private function buildSummaryFromCollection($sales): array
    {
        return [
            'total_transactions' => $sales->count(),
            'total_quantity' => $sales->sum('quantity'),
            'total_revenue' => $sales->sum('total_price'),
        ];
    }
}
