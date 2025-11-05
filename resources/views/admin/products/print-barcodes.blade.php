<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode Labels</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .print-btn {
            background: #F87B1B;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .print-btn:hover {
            background: #e66a0a;
        }
        
        .labels-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .label {
            background: white;
            border: 2px dashed #ddd;
            padding: 15px;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .label img {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }
        
        .label .product-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .label .product-price {
            font-size: 16px;
            color: #F87B1B;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .label .barcode-number {
            font-family: monospace;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .labels-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 5mm;
                padding: 5mm;
            }
            
            .label {
                border: 1px solid #ddd;
                padding: 3mm;
                width: 100%;
            }
            
            .label .product-name {
                font-size: 10pt;
            }
            
            .label .product-price {
                font-size: 12pt;
            }
            
            .label .barcode-number {
                font-size: 8pt;
            }
        }
        
        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">
            <svg style="display: inline-block; width: 20px; height: 20px; vertical-align: middle; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
            </svg>
            Print {{ count($products) }} Label Barcode
        </button>
    </div>

    <div class="labels-container">
        @foreach($products as $product)
            @if($product->barcode)
                <div class="label">
                    <div class="product-name">{{ $product->nama_barang }}</div>
                    <div class="product-price">{{ $product->formatted_price }}</div>
                    <img src="{{ $barcodeService->generateForProduct($product, 'png') }}" 
                         alt="Barcode {{ $product->barcode }}">
                    <div class="barcode-number">{{ $product->barcode }}</div>
                </div>
            @endif
        @endforeach
    </div>

    @if($products->isEmpty() || $products->filter(fn($p) => !$p->barcode)->count() === count($products))
        <div class="no-print" style="margin-top: 50px;">
            <p style="color: #666;">Tidak ada produk dengan barcode yang valid.</p>
            <a href="{{ route('products.index') }}" style="color: #F87B1B; text-decoration: underline; margin-top: 10px; display: inline-block;">Kembali ke Daftar Produk</a>
        </div>
    @endif

    <script>
        // Auto print on load (optional - uncomment if you want)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // };
    </script>
</body>
</html>
