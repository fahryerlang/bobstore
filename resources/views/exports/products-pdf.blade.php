<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk</title>
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
        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA PRODUK</h1>
        <p>{{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">SKU</th>
                <th width="35%">Nama Barang</th>
                <th width="20%">Harga</th>
                <th width="10%">Stok</th>
                <th width="20%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>SKU-{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $product->nama_barang }}</td>
                <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                <td>{{ $product->stok }}</td>
                <td>{{ $product->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data produk
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">
        Total Produk: {{ $products->count() }} item
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem | © {{ now()->year }} BobStore</p>
    </div>
</body>
</html>
