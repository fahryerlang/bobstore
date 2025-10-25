<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Export products to Excel (CSV)
     */
    public function exportProductsExcel()
    {
        $products = Product::orderBy('nama_barang')->get();

        $filename = 'products_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['SKU', 'Nama Barang', 'Harga', 'Stok', 'Tanggal Dibuat']);

            // Data
            foreach ($products as $product) {
                $sku = 'SKU-' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
                fputcsv($file, [
                    $sku,
                    $product->nama_barang,
                    'Rp ' . number_format($product->harga, 0, ',', '.'),
                    $product->stok,
                    $product->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export products to PDF
     */
    public function exportProductsPdf()
    {
        $products = Product::orderBy('nama_barang')->get();
        
        $pdf = Pdf::loadView('exports.products-pdf', compact('products'));
        
        return $pdf->download('products_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Export sales to Excel (CSV)
     */
    public function exportSalesExcel(Request $request)
    {
        $query = Sale::with(['customer', 'cashier', 'product']);

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        $filename = 'sales_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['Invoice', 'Tanggal', 'Pelanggan', 'Kasir', 'Produk', 'Qty', 'Harga Satuan', 'Total']);

            // Data
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->invoice_number,
                    $sale->sale_date->format('d/m/Y H:i'),
                    $sale->customer->name ?? 'Guest',
                    $sale->cashier->name ?? '-',
                    $sale->product->nama_barang ?? '-',
                    $sale->quantity,
                    'Rp ' . number_format($sale->unit_price, 0, ',', '.'),
                    'Rp ' . number_format($sale->total_price, 0, ',', '.'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export sales to PDF
     */
    public function exportSalesPdf(Request $request)
    {
        $query = Sale::with(['customer', 'cashier', 'product']);

        // Filter by date range if provided
        $startDate = null;
        $endDate = null;
        
        if ($request->filled('start_date')) {
            $startDate = $request->start_date;
            $query->whereDate('sale_date', '>=', $startDate);
        }
        if ($request->filled('end_date')) {
            $endDate = $request->end_date;
            $query->whereDate('sale_date', '<=', $endDate);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();
        
        $pdf = Pdf::loadView('exports.sales-pdf', compact('sales', 'startDate', 'endDate'));
        
        return $pdf->download('sales_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Export customers to Excel (CSV)
     */
    public function exportCustomersExcel()
    {
        $customers = User::where('role', 'pembeli')
            ->withCount('sales')
            ->orderBy('name')
            ->get();

        $filename = 'customers_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['ID', 'Nama', 'Email', 'Telepon', 'Total Transaksi', 'Tanggal Daftar']);

            // Data
            foreach ($customers as $customer) {
                $customerId = 'CUST-' . str_pad($customer->id, 6, '0', STR_PAD_LEFT);
                fputcsv($file, [
                    $customerId,
                    $customer->name,
                    $customer->email,
                    $customer->phone ?? '-',
                    $customer->sales_count,
                    $customer->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export customers to PDF
     */
    public function exportCustomersPdf()
    {
        $customers = User::where('role', 'pembeli')
            ->withCount('sales')
            ->orderBy('name')
            ->get();
        
        $pdf = Pdf::loadView('exports.customers-pdf', compact('customers'));
        
        return $pdf->download('customers_' . now()->format('Y-m-d_His') . '.pdf');
    }
}
