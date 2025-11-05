@extends('layouts.public')

@section('title', 'Barcode - ' . $product->nama_barang)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-[#F87B1B] hover:text-orange-600 mb-4 font-medium">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali ke Produk
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Barcode Produk</h1>
        <p class="mt-2 text-gray-600">{{ $product->nama_barang }}</p>
    </div>

    <!-- Barcode Display -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
        <div class="text-center">
            <!-- Product Info -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->nama_barang }}</h2>
                <p class="text-lg text-gray-600">{{ $product->formatted_price }}</p>
            </div>

            <!-- Barcode Image -->
            <div class="flex flex-col items-center justify-center py-8 bg-white border-4 border-dashed border-gray-300 rounded-xl">
                @if($product->barcode)
                    <img src="{{ $barcodeService->generateForProduct($product, 'png') }}" 
                         alt="Barcode {{ $product->barcode }}" 
                         class="max-w-full h-auto"
                         style="max-height: 150px;">
                    <p class="mt-4 text-2xl font-mono font-bold text-gray-900 tracking-wider">{{ $product->barcode }}</p>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="mt-4 text-gray-600">Barcode belum tersedia untuk produk ini</p>
                        <p class="text-sm text-gray-500 mt-2">Barcode akan di-generate otomatis saat produk disimpan</p>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="mt-6 grid grid-cols-2 gap-4 text-left">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">ID Produk</p>
                    <p class="text-lg font-semibold text-gray-900">#{{ $product->id }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Stok</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $product->stok }} unit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <button onclick="window.print()" class="flex-1 bg-[#F87B1B] hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition-colors shadow-lg hover:shadow-xl">
            <svg class="inline-block w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
            </svg>
            Print Barcode
        </button>
        <a href="{{ route('products.edit', $product) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-4 rounded-xl text-center transition-colors">
            Edit Produk
        </a>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white, .bg-white * {
        visibility: visible;
    }
    .bg-white {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        box-shadow: none !important;
    }
    button, a, nav, header, footer {
        display: none !important;
    }
}
</style>
@endsection
