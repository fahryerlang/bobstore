<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Customer</title>
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
        <h1>LAPORAN DATA CUSTOMER</h1>
        <p>{{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">ID Customer</th>
                <th width="25%">Nama</th>
                <th width="25%">Email</th>
                <th width="15%">Telepon</th>
                <th width="10%">Transaksi</th>
                <th width="10%">Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>CUST-{{ str_pad($customer->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td style="text-align: center;">{{ $customer->sales_count }}</td>
                <td>{{ $customer->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data customer
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">
        Total Customer: {{ $customers->count() }} orang
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem | © {{ now()->year }} BobStore</p>
    </div>
</body>
</html>
