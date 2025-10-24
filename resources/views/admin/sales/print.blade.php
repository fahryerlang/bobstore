<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan · {{ config('app.name', 'BobStore') }}</title>
    <style>
        :root {
            color-scheme: light;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 40px;
            color: #1f2937;
            background: #ffffff;
        }
        h1 {
            font-size: 26px;
            margin-bottom: 4px;
            color: #0f172a;
        }
        h2 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #2563eb;
        }
        .meta {
            font-size: 13px;
            color: #475569;
            margin-bottom: 24px;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .summary-card {
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .summary-card h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: #64748b;
            margin-bottom: 8px;
        }
        .summary-card p {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            font-size: 13px;
        }
        th {
            background: #f1f5f9;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-size: 11px;
            color: #475569;
        }
        tfoot td {
            font-weight: 600;
            background: #f8fafc;
        }
        .filters {
            margin-bottom: 16px;
            font-size: 13px;
        }
        .filters span {
            display: inline-block;
            margin-right: 16px;
        }
        @media print {
            body {
                margin: 20px 32px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <header>
        <h1>Laporan Penjualan</h1>
        <div class="meta">
            <div>{{ config('app.name', 'BobStore') }}</div>
            <div>Dicetak pada: {{ now()->format('d M Y H:i') }}</div>
        </div>
        <div class="filters">
            <strong>Filter aktif:</strong>
            <span>Tanggal: {{ $filters['start_date'] ?? 'Semua' }} s/d {{ $filters['end_date'] ?? 'Semua' }}</span>
            <span>Pelanggan: {{ $selectedCustomer?->name ?? 'Semua' }}</span>
            <span>Produk: {{ $selectedProduct?->nama_barang ?? 'Semua' }}</span>
        </div>
    </header>

    <section class="summary">
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <p>Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <h3>Total Transaksi</h3>
            <p>{{ number_format($summary['total_transactions'] ?? 0) }}</p>
        </div>
        <div class="summary-card">
            <h3>Produk Terjual</h3>
            <p>{{ number_format($summary['total_quantity'] ?? 0) }}</p>
        </div>
    </section>

    <section>
        <h2>Detail Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ optional($sale->sale_date)->format('d M Y H:i') }}</td>
                        <td>{{ optional($sale->customer)->name ?? 'Umum' }}</td>
                        <td>{{ optional($sale->product)->nama_barang ?? '-' }}</td>
                        <td style="text-align: right;">{{ number_format($sale->quantity) }}</td>
                        <td style="text-align: right;">Rp {{ number_format($sale->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 32px; color: #94a3b8;">
                            Belum ada data penjualan untuk kriteria filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($sales->count())
                <tfoot>
                    <tr>
                        <td colspan="4">Total</td>
                        <td style="text-align: right;">{{ number_format($summary['total_quantity'] ?? 0) }}</td>
                        <td></td>
                        <td style="text-align: right;">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </section>
</body>
</html>
