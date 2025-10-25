<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #F87B1B;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #F87B1B;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        @if($startDate || $endDate)
            <p>
                Periode: 
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                @elseif($startDate)
                    Dari {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
                @elseif($endDate)
                    Sampai {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                @endif
            </p>
        @else
            <p>Semua Data Penjualan</p>
        @endif
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Invoice</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Pelanggan</th>
                <th width="15%">Kasir</th>
                <th width="18%">Produk</th>
                <th width="8%">Qty</th>
                <th width="12%">Harga</th>
                <th width="12%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                <td>{{ $sale->customer->name ?? 'Guest' }}</td>
                <td>{{ $sale->cashier->name ?? '-' }}</td>
                <td>{{ $sale->product->nama_barang ?? '-' }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>Rp {{ number_format($sale->unit_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data penjualan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Item:</strong>
            <span>{{ $sales->count() }} item</span>
        </div>
        <div class="summary-item">
            <strong>Total Pendapatan:</strong>
            <span>Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Quantity:</strong>
            <span>{{ $sales->sum('quantity') }} unit</span>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem | © {{ now()->year }} BobStore</p>
    </div>
</body>
</html>
