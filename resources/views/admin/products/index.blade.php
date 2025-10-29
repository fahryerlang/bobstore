@extends('layouts.public')

@section('title', 'Manajemen Produk')

@push('styles')
<style>
    /* Discount Badge Animation */
    @keyframes pulse-subtle {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.85; }
    }
    .discount-badge-pulse {
        animation: pulse-subtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    /* Ensure strikethrough is visible */
    .price-strikethrough {
        text-decoration: line-through;
        text-decoration-thickness: 2px;
        text-decoration-color: #9CA3AF;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manajemen Produk</h1>
                    <p class="mt-1 text-sm text-gray-600">Kelola data barang yang tersedia di toko</p>
                </div>
                <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-[#F87B1B] focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Products Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                    <!-- Product Image -->
                    <div class="relative overflow-hidden bg-gray-100 aspect-square group">
                        @if ($product->gambar)
                            <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_barang }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        @php
                            $discountInfo = $product->getDiscountDisplayInfo();
                        @endphp
                        
                        <!-- Discount Badge (Top Left) - Made More Prominent -->
                        @if ($discountInfo && $discountInfo['has_discount'])
                            <div class="absolute top-0 left-0 z-10">
                                <div class="bg-gradient-to-r from-red-600 to-red-500 text-white px-4 py-2 rounded-br-2xl shadow-2xl discount-badge-pulse" style="box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.5);">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="font-extrabold text-xl">{{ $discountInfo['percentage'] }}%</span>
                                    </div>
                                    <span class="text-xs font-bold block text-center uppercase tracking-wider mt-0.5">DISKON</span>
                                    @if ($discountInfo['min_quantity'] > 1)
                                        <span class="text-[9px] block text-center opacity-90 mt-0.5">Min {{ $discountInfo['min_quantity'] }} pcs</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Stock Badge (Top Right) -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold shadow-lg {{ $product->stok > 10 ? 'bg-green-500 text-white' : ($product->stok > 0 ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white') }}">
                                Stok: {{ $product->stok }}
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $product->nama_barang }}</h3>
                        
                        @php
                            $discountInfo = $product->getDiscountDisplayInfo();
                        @endphp
                        <div class="mb-3">
                            @if ($discountInfo && $discountInfo['has_discount'])
                                <!-- Harga dengan diskon -->
                                <div class="flex items-baseline gap-2 flex-wrap">
                                    <span class="text-2xl font-bold text-[#F87B1B]">Rp {{ number_format($discountInfo['discounted_price'], 0, ',', '.') }}</span>
                                    <span class="text-sm text-gray-500 price-strikethrough" style="text-decoration: line-through; text-decoration-thickness: 2px;">Rp {{ number_format($discountInfo['base_price'], 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-1.5 flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-md shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                        </svg>
                                        HEMAT {{ $discountInfo['percentage'] }}%
                                    </span>
                                    <span class="text-xs text-gray-600 font-semibold bg-gray-100 px-2 py-1 rounded">Rp {{ number_format($discountInfo['savings'], 0, ',', '.') }}</span>
                                    @if ($discountInfo['min_quantity'] > 1)
                                        <span class="text-[10px] text-gray-500 bg-yellow-50 border border-yellow-200 px-2 py-0.5 rounded">Min {{ $discountInfo['min_quantity'] }} pcs</span>
                                    @endif
                                </div>
                            @else
                                <!-- Harga normal -->
                                <span class="text-2xl font-bold text-[#F87B1B]">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            @endif
                            <span class="text-sm text-gray-500 ml-1">/ {{ $product->satuan ?? 'pcs' }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-xl hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-xl hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 transition duration-150">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="h-20 w-20 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-500 text-xl font-semibold mb-2">Belum ada produk</p>
                        <p class="text-gray-400 text-sm mb-6">Mulai tambahkan produk baru untuk toko Anda</p>
                        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white text-sm font-bold rounded-xl hover:from-orange-600 hover:to-[#F87B1B] transition duration-200 shadow-lg">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Produk Pertama
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif

        <!-- Back to Dashboard -->
        <div class="mt-6 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#F87B1B] transition duration-200">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection