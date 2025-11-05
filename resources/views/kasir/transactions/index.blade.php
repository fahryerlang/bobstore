@extends('layouts.public')

@section('title', 'Transaksi Kasir')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Transaksi Penjualan</h1>
                <p class="text-sm text-gray-600 mt-1">Proses penjualan, pilih pelanggan, tambahkan produk, dan selesaikan pembayaran.</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-50 text-[#F87B1B]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span>✨ Scan barcode untuk auto-add produk!</span>
                </div>
            </div>
        </div>

        <form id="transaction-form" method="POST" action="{{ route('kasir.transactions.store') }}" class="space-y-10">
            @csrf
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Pelanggan</h2>
                            <p class="text-sm text-gray-600">Pilih pelanggan terdaftar atau biarkan sebagai pelanggan umum.</p>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Pelanggan / Member</label>
                                <select id="customer_id" name="customer_id" class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <option value="">Pelanggan Umum (Non-Member)</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                data-points="{{ $customer->points }}" 
                                                data-level="{{ $customer->member_level }}">
                                            {{ $customer->name }} @if($customer->points > 0) - {{ $customer->points }} poin @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Pelanggan baru? <a href="{{ route('kasir.customers.create') }}" class="font-semibold text-[#F87B1B] hover:underline">Daftarkan member</a>
                                </p>
                                
                                <!-- Member Points Info (Hidden by default) -->
                                <div id="member-points-info" class="mt-4 p-4 rounded-2xl bg-gradient-to-r from-purple-50 to-orange-50 border border-purple-200 hidden">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span id="member-level-icon" class="text-2xl"></span>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase">Member Level</p>
                                                <p id="member-level-text" class="text-sm font-bold"></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Poin Tersedia</p>
                                            <p id="member-points-display" class="text-2xl font-bold text-[#F87B1B]">0</p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        <p>💰 <span class="font-semibold">1 poin = Rp 100</span> diskon</p>
                                        <p class="mt-1">🎁 Member mendapatkan poin setiap pembelian!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs uppercase text-gray-500 tracking-[0.25em]">Kasir</p>
                                    <p class="text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-gray-500 tracking-[0.25em]">Tanggal</p>
                                    <p class="text-base font-semibold text-gray-900">{{ now()->isoFormat('DD MMMM YYYY HH:mm') }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Product Catalog Grid --}}
                    <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden" 
                             x-data="{ 
                                 catalogSearch: '', 
                                 products: {{ $products->map(fn($p) => [
                                     'id' => $p->id,
                                     'nama_barang' => $p->nama_barang,
                                     'harga' => $p->harga,
                                     'stok' => $p->stok,
                                     'gambar' => $p->gambar,
                                     'image_url' => $p->image_url
                                 ])->toJson() }},
                                 get filteredProducts() {
                                     if (this.catalogSearch.trim() === '') return this.products;
                                     const search = this.catalogSearch.toLowerCase();
                                     return this.products.filter(product => 
                                         product.nama_barang.toLowerCase().includes(search)
                                     );
                                 }
                             }">
                        <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60">
                            <h2 class="text-lg font-semibold text-gray-900">Katalog Produk</h2>
                            <p class="text-sm text-gray-600">Klik produk untuk menambahkan ke keranjang</p>
                        </div>
                        <div class="px-6 py-6 space-y-4">
                            @if($products->count() > 0)
                                <!-- Search Input -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           x-model="catalogSearch"
                                           placeholder="Cari produk di katalog... (misal: Teh Pucuk)" 
                                           class="w-full pl-10 pr-10 py-2.5 rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm"
                                           autocomplete="off">
                                    <button type="button" 
                                            x-show="catalogSearch.length > 0"
                                            @click="catalogSearch = ''"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#F87B1B]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Products Grid -->
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-[600px] overflow-y-auto pr-2">
                                    <template x-for="product in filteredProducts" :key="product.id">
                                        <button type="button" 
                                            class="catalog-product-btn text-left bg-white border-2 border-gray-200 rounded-2xl p-4 hover:border-[#F87B1B] hover:shadow-lg transition-all group"
                                            :data-product-id="product.id"
                                            :data-product-name="product.nama_barang"
                                            :data-product-price="product.harga"
                                            :data-product-stock="product.stok"
                                            :data-product-image="product.gambar"
                                            @click="window.handleCatalogProductClick && window.handleCatalogProductClick(product)">
                                            
                                            <div class="aspect-square rounded-xl overflow-hidden mb-3 bg-gray-100">
                                                <template x-if="product.image_url">
                                                    <img :src="product.image_url" 
                                                         :alt="product.nama_barang" 
                                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><svg class=\'w-12 h-12 text-gray-300\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z\' clip-rule=\'evenodd\'/></svg></div>';">
                                                </template>
                                                <template x-if="!product.image_url">
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                </template>
                                            </div>

                                            <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-[#F87B1B] transition-colors" x-text="product.nama_barang"></h3>

                                            <div class="flex items-center justify-between mt-2">
                                                <p class="text-[#F87B1B] font-bold text-base" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.harga)"></p>
                                                <div class="flex items-center gap-1 text-xs">
                                                    <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                                    </svg>
                                                    <span class="text-gray-600 font-medium" x-text="product.stok"></span>
                                                </div>
                                            </div>

                                            <div class="mt-3 pt-3 border-t border-gray-100">
                                                <div class="flex items-center justify-center gap-2 text-xs font-semibold text-[#F87B1B] group-hover:text-orange-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Tambahkan
                                                </div>
                                            </div>
                                        </button>
                                    </template>
                                </div>

                                <!-- No Results Message -->
                                <div x-show="filteredProducts.length === 0" class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <p class="mt-4 text-sm font-medium text-gray-900">Produk tidak ditemukan</p>
                                    <p class="mt-1 text-xs text-gray-500">Coba kata kunci lain</p>
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">Tidak ada produk tersedia</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Tambah Produk</h2>
                                <p class="text-sm text-gray-600">Cari nama produk atau scan barcode produk.</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs bg-green-50 text-green-700 px-3 py-1.5 rounded-full border border-green-200">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/>
                                    <path fill="white" d="M4 8h2v6H4V8zm4-1h1v7H8V7zm3 2h1v5h-1V9zm3-1h2v6h-2V8z"/>
                                </svg>
                                <span class="font-semibold">Scanner aktif! Scan barcode untuk auto-add ⚡</span>
                            </div>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" id="product-search" placeholder="Cari nama produk atau tempel hasil scan..." autocomplete="off" class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm pr-12">
                                    <button type="button" id="clear-search" class="absolute inset-y-0 right-0 px-4 text-gray-400 hover:text-[#F87B1B]">
                                        <span class="sr-only">Hapus pencarian</span>
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <button type="button" id="scan-barcode-btn" class="flex items-center gap-2 px-5 py-2 bg-[#F87B1B] hover:bg-orange-600 text-white font-medium rounded-2xl transition-colors duration-200 shadow-md hover:shadow-lg">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Scan Barcode</span>
                                </button>
                                <div id="product-suggestions" class="absolute z-20 mt-2 w-full bg-white border border-orange-100 rounded-2xl shadow-2xl divide-y divide-orange-50 hidden"></div>
                            </div>

                            <div class="overflow-hidden rounded-3xl border border-orange-100">
                                <table class="min-w-full divide-y divide-orange-100">
                                    <thead class="bg-orange-50">
                                        <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            <th scope="col" class="px-4 py-3 text-left">Produk</th>
                                            <th scope="col" class="px-4 py-3 text-center">Harga</th>
                                            <th scope="col" class="px-4 py-3 text-center">Stok</th>
                                            <th scope="col" class="px-4 py-3 text-center">Qty</th>
                                            <th scope="col" class="px-4 py-3 text-right">Subtotal</th>
                                            <th scope="col" class="px-4 py-3 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body" class="divide-y divide-orange-50 bg-white">
                                        <tr id="empty-cart-row">
                                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada produk. Cari atau scan untuk menambahkan.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

                <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden h-full sticky top-6">
                    <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60">
                        <h2 class="text-lg font-semibold text-gray-900">Ringkasan Pembayaran</h2>
                        <p class="text-sm text-gray-600 mt-1" id="cart-item-count">0 item dalam keranjang</p>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <!-- Detail Items dalam Keranjang -->
                        <div id="cart-summary-items" class="space-y-2 max-h-[300px] overflow-y-auto pr-2 pb-4 border-b border-gray-100" style="scrollbar-width: thin; scrollbar-color: #F87B1B #f3f4f6;">
                            <div class="text-center py-8 text-sm text-gray-400" id="empty-cart-summary">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <p>Belum ada produk</p>
                            </div>
                        </div>
                        <style>
                            #cart-summary-items::-webkit-scrollbar {
                                width: 6px;
                            }
                            #cart-summary-items::-webkit-scrollbar-track {
                                background: #f3f4f6;
                                border-radius: 10px;
                            }
                            #cart-summary-items::-webkit-scrollbar-thumb {
                                background: #F87B1B;
                                border-radius: 10px;
                            }
                            #cart-summary-items::-webkit-scrollbar-thumb:hover {
                                background: #d66915;
                            }
                        </style>

                        <dl class="space-y-4 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Subtotal</dt>
                                <dd id="summary-subtotal" class="font-semibold text-gray-900">Rp 0</dd>
                            </div>
                            
                            <!-- Points Redemption (for members only) -->
                            <div id="points-redemption-section" class="hidden border-t border-b border-gray-200 py-3 -mx-2 px-2">
                                <div class="flex items-center justify-between mb-2">
                                    <dt class="text-sm font-medium text-purple-600">💳 Tukar Poin</dt>
                                    <dd class="text-xs text-gray-500">
                                        <span id="points-available-label">0 poin tersedia</span>
                                    </dd>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           min="0" 
                                           step="10" 
                                           name="points_to_redeem" 
                                           id="points-to-redeem-input" 
                                           value="0" 
                                           placeholder="0"
                                           class="flex-1 rounded-xl border-gray-200 text-center focus:border-purple-500 focus:ring-purple-500 text-sm">
                                    <span class="text-xs text-gray-500">poin</span>
                                    <span class="text-xs font-semibold text-gray-700">=</span>
                                    <span id="points-value-display" class="text-sm font-bold text-green-600">Rp 0</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Min. 10 poin. 1 poin = Rp 100</p>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Diskon Manual</dt>
                                <dd class="text-right">
                                    <div class="flex items-center gap-2 justify-end">
                                        <span class="text-gray-400">Rp</span>
                                        <input type="number" min="0" step="500" name="discount" id="discount-input" value="0" class="w-32 rounded-xl border-gray-200 text-right focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                                    </div>
                                </dd>
                            </div>
                            
                            <div id="total-discount-display" class="hidden">
                                <div class="flex items-center justify-between text-sm bg-green-50 -mx-2 px-2 py-2 rounded-xl">
                                    <dt class="text-green-700 font-medium">Total Diskon</dt>
                                    <dd id="total-discount-amount" class="font-bold text-green-700">Rp 0</dd>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-base pt-2 border-t border-gray-200">
                                <dt class="text-gray-700 font-semibold">Total Bayar</dt>
                                <dd id="summary-total" class="text-xl font-bold text-gray-900">Rp 0</dd>
                            </div>
                        </dl>

                        <div class="space-y-4">
                            <div>
                                <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">Rp</span>
                                    <input type="number" min="0" step="500" name="amount_paid" id="amount-paid-input" class="w-full rounded-2xl border-gray-200 pl-10 text-right focus:border-[#F87B1B] focus:ring-[#F87B1B]" placeholder="0" required>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Kembalian</span>
                                <span id="summary-change" class="text-lg font-semibold text-emerald-600">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#F87B1B] to-orange-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-orange-200/60 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F87B1B]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Selesaikan Transaksi
                        </button>
                        <p class="text-xs text-gray-400 text-center">Pastikan jumlah bayar sudah sesuai sebelum menyimpan transaksi.</p>
                    </div>
                </section>
            </div>
        </form>
    </div>
</div>

<!-- Barcode Scanner Modal -->
<div id="barcode-scanner-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100">
                            <svg class="h-6 w-6 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Scan Barcode Produk</h3>
                            <p class="text-sm text-gray-500">Arahkan barcode ke kamera</p>
                        </div>
                    </div>
                    <button type="button" id="close-scanner-btn" class="rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-2 transition-colors">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4">
                    <!-- Scanner Container -->
                    <div id="qr-reader" class="w-full rounded-2xl overflow-hidden border-2 border-orange-200 bg-black"></div>
                    
                    <!-- Status Messages -->
                    <div id="scanner-status" class="mt-4 p-4 rounded-2xl bg-blue-50 border border-blue-200">
                        <div class="flex items-center gap-2 text-blue-800">
                            <svg class="h-5 w-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium">Siap untuk scan. Posisikan barcode di depan kamera.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- html5-qrcode Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    const searchInput = document.getElementById('product-search');
    const clearSearchButton = document.getElementById('clear-search');
    const suggestionBox = document.getElementById('product-suggestions');
    const itemsBody = document.getElementById('items-body');
    const emptyRow = document.getElementById('empty-cart-row');
    const discountInput = document.getElementById('discount-input');
    const amountPaidInput = document.getElementById('amount-paid-input');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const summaryChange = document.getElementById('summary-change');
    const searchEndpoint = @json(route('kasir.transactions.search'));
    const scanBarcodeEndpoint = @json(route('kasir.transactions.scan-barcode'));

    let debounceTimer = null;
    let rowCounter = 0;
    let barcodeBuffer = '';
    let barcodeTimeout = null;

    const formatCurrency = (value) => {
        return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(Math.max(value, 0));
    };

    const updateCartSummary = () => {
        const cartSummaryItems = document.getElementById('cart-summary-items');
        const emptyCartSummary = document.getElementById('empty-cart-summary');
        const cartItemCount = document.getElementById('cart-item-count');
        const rows = itemsBody.querySelectorAll('tr[data-row-id]');
        
        if (rows.length === 0) {
            emptyCartSummary.style.display = 'block';
            cartItemCount.textContent = '0 item dalam keranjang';
            return;
        }
        
        emptyCartSummary.style.display = 'none';
        
        // Hitung total item
        let totalQty = 0;
        rows.forEach(row => {
            const qtyInput = row.querySelector('input[data-quantity]');
            totalQty += parseInt(qtyInput.value || 0);
        });
        
        cartItemCount.textContent = `${totalQty} item dalam keranjang`;
        
        // Render item summary
        let summaryHTML = '';
        rows.forEach(row => {
            const productId = row.dataset.productId;
            const productName = row.querySelector('td:first-child .font-semibold')?.textContent || 'Produk';
            const qtyInput = row.querySelector('input[data-quantity]');
            const qty = parseInt(qtyInput.value || 0);
            const unitPrice = parseFloat(row.dataset.unitPrice || 0);
            const subtotal = parseFloat(row.dataset.lineTotal || 0);
            
            // Get image if exists
            const img = row.querySelector('img');
            const imgSrc = img ? img.src : '';
            
            summaryHTML += `
                <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        ${imgSrc ? 
                            `<img src="${imgSrc}" alt="${productName}" class="w-10 h-10 rounded-lg object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                             <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center" style="display: none;">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                             </div>` :
                            `<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                             </div>`
                        }
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">${productName}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-gray-500">${qty}x</span>
                            <span class="text-xs text-gray-500">${formatCurrency(unitPrice)}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-900">${formatCurrency(subtotal)}</div>
                    </div>
                </div>
            `;
        });
        
        // Remove empty message and add items
        const existingItems = cartSummaryItems.querySelectorAll('.flex.items-start');
        existingItems.forEach(item => item.remove());
        
        emptyCartSummary.insertAdjacentHTML('afterend', summaryHTML);
    };

    const recalcTotals = () => {
        let subtotal = 0;
        itemsBody.querySelectorAll('tr[data-row-id]').forEach((row) => {
            subtotal += parseFloat(row.dataset.lineTotal || '0');
        });

        // Calculate discounts
        const manualDiscount = parseFloat(discountInput.value || '0');
        const pointsToRedeem = parseInt(pointsToRedeemInput?.value || '0');
        const pointsDiscount = pointsToRedeem * 100; // 1 point = Rp 100
        
        // Validate points don't exceed subtotal
        let actualPointsDiscount = pointsDiscount;
        if (manualDiscount + pointsDiscount > subtotal) {
            actualPointsDiscount = Math.max(0, subtotal - manualDiscount);
            const maxPoints = Math.floor(actualPointsDiscount / 100);
            if (pointsToRedeemInput && pointsToRedeem > maxPoints) {
                pointsToRedeemInput.value = maxPoints;
                actualPointsDiscount = maxPoints * 100;
                pointsValueDisplay.textContent = formatCurrency(actualPointsDiscount);
            }
        }
        
        const totalDiscount = manualDiscount + actualPointsDiscount;
        const total = Math.max(subtotal - totalDiscount, 0);
        const amountPaid = parseFloat(amountPaidInput.value || '0');
        const change = Math.max(amountPaid - total, 0);

        summarySubtotal.textContent = formatCurrency(subtotal);
        summaryTotal.textContent = formatCurrency(total);
        summaryChange.textContent = formatCurrency(change);
        
        // Show/hide total discount display
        if (totalDiscount > 0 && totalDiscountDisplay) {
            totalDiscountDisplay.classList.remove('hidden');
            totalDiscountAmount.textContent = formatCurrency(totalDiscount);
        } else if (totalDiscountDisplay) {
            totalDiscountDisplay.classList.add('hidden');
        }
        
        // Update cart summary di panel kanan
        updateCartSummary();
    };

    const ensureNotEmpty = () => {
        const hasItems = itemsBody.querySelectorAll('tr[data-row-id]').length > 0;
        emptyRow.classList.toggle('hidden', hasItems);
        
        // Update cart summary
        updateCartSummary();
    };

    const updateRowTotals = (row, quantity, price) => {
        const unitPrice = (typeof price === 'number' && !Number.isNaN(price))
            ? price
            : parseFloat(row.dataset.unitPrice || '0');
        const subtotalCell = row.querySelector('[data-subtotal]');
        const lineTotal = quantity * unitPrice;
        row.dataset.lineTotal = lineTotal.toString();
        subtotalCell.textContent = formatCurrency(lineTotal);
        recalcTotals();
    };

    const addOrIncreaseProduct = (product) => {
        const existingRow = itemsBody.querySelector(`tr[data-product-id="${product.id}"]`);

        if (existingRow) {
            const qtyInput = existingRow.querySelector('input[data-quantity]');
            const currentQty = parseInt(qtyInput.value, 10);
            if (currentQty < product.stok) {
                qtyInput.value = currentQty + 1;
                updateRowTotals(existingRow, currentQty + 1, product.harga);
            }
            return;
        }

        const rowId = `row-${++rowCounter}`;
        const row = document.createElement('tr');
        row.dataset.rowId = rowId;
        row.dataset.productId = product.id;
        row.dataset.lineTotal = '0';
    row.dataset.unitPrice = product.harga;

        // Generate image URL
        const imageUrl = product.image_url || (product.gambar ? `/storage/${product.gambar}` : '');
        const imageHtml = imageUrl ? 
            `<img src="${imageUrl}" alt="${product.nama_barang}" class="w-12 h-12 rounded-lg object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
             <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center" style="display: none;">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
             </div>` :
            `<div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
             </div>`;
        
        row.innerHTML = `
            <td class="px-4 py-4">
                <div class="flex items-center gap-3">
                    ${imageHtml}
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 truncate">${product.nama_barang}</div>
                        <div class="text-xs text-gray-500 mt-0.5">ID: ${product.id}</div>
                    </div>
                </div>
                <input type="hidden" name="items[${rowCounter}][product_id]" value="${product.id}">
            </td>
            <td class="px-4 py-4 text-center text-sm text-gray-700">${formatCurrency(product.harga)}</td>
            <td class="px-4 py-4 text-center text-xs text-gray-500">${product.stok}</td>
            <td class="px-4 py-4 text-center">
                <input type="number" min="1" max="${product.stok}" value="1" name="items[${rowCounter}][quantity]" data-quantity class="w-20 rounded-xl border-gray-200 text-center focus:border-[#F87B1B] focus:ring-[#F87B1B]">
            </td>
            <td class="px-4 py-4 text-right font-semibold text-gray-900" data-subtotal>Rp 0</td>
            <td class="px-4 py-4 text-center">
                <button type="button" class="inline-flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-100 h-9 w-9" data-remove>
                    <span class="sr-only">Hapus</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </td>
        `;

        itemsBody.appendChild(row);
        updateRowTotals(row, 1, product.harga);
        ensureNotEmpty();
    };

    const clearSuggestions = () => {
        suggestionBox.innerHTML = '';
        suggestionBox.classList.add('hidden');
    };

    const renderSuggestions = (products) => {
        if (!products.length) {
            clearSuggestions();
            return;
        }

        suggestionBox.innerHTML = products.map((product) => {
            const payload = encodeURIComponent(JSON.stringify(product));
            const imagePath = product.image_url || '/images/no-image.png';
            const sku = 'SKU-' + String(product.id).padStart(6, '0');
            return `
            <button type="button" class="flex w-full items-center gap-4 px-4 py-3 text-left text-sm hover:bg-orange-50 transition-colors" data-product="${payload}">
                <img src="${imagePath}" alt="${product.nama_barang}" class="w-12 h-12 object-cover rounded-lg border border-gray-200" onerror="this.src='/images/no-image.png'">
                <div class="flex-1 min-w-0">
                    <span class="block font-semibold text-gray-900 truncate">${product.nama_barang}</span>
                    <span class="block text-xs text-gray-500">${sku} · Stok: ${product.stok}</span>
                </div>
                <span class="flex-shrink-0 font-semibold text-[#F87B1B]">${formatCurrency(product.harga)}</span>
            </button>
            `;
        }).join('');

        suggestionBox.classList.remove('hidden');
    };

    const fetchProducts = (keyword) => {
        const params = new URLSearchParams();
        if (keyword) {
            params.append('q', keyword);
        }

    fetch(`${searchEndpoint}?${params.toString()}`)
            .then((response) => response.json())
            .then((data) => renderSuggestions(data))
            .catch(() => clearSuggestions());
    };

    // ===BARCODE SCANNER FUNCTIONALITY===
    const scanBarcode = async (barcode) => {
        try {
            const response = await fetch(scanBarcodeEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ barcode: barcode.trim() })
            });

            const data = await response.json();

            if (data.success && data.product) {
                addOrIncreaseProduct(data.product);
                
                // Show success notification
                showNotification('✅ Produk ditambahkan: ' + data.product.nama_barang, 'success');
                
                // Clear search input
                searchInput.value = '';
                searchInput.focus();
            } else {
                showNotification('❌ ' + (data.message || 'Produk tidak ditemukan'), 'error');
                searchInput.value = '';
                searchInput.focus();
            }
        } catch (error) {
            console.error('Barcode scan error:', error);
            showNotification('❌ Error scanning barcode', 'error');
        }
    };

    // Simple notification function
    const showNotification = (message, type = 'info') => {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-semibold transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    };

    // Detect barcode scanner input (hardware scanner types fast)
    let lastKeyTime = Date.now();
    document.addEventListener('keypress', (event) => {
        // Ignore if user is typing in another input (except search input)
        if (document.activeElement && 
            document.activeElement !== searchInput && 
            (document.activeElement.tagName === 'INPUT' || 
             document.activeElement.tagName === 'TEXTAREA' ||
             document.activeElement.tagName === 'SELECT')) {
            return;
        }

        const currentTime = Date.now();
        const timeDiff = currentTime - lastKeyTime;
        
        // If keys are pressed rapidly (< 50ms between keys), it's likely a scanner
        if (timeDiff > 100) {
            barcodeBuffer = '';
        }
        
        if (event.key === 'Enter') {
            if (barcodeBuffer.length > 0) {
                event.preventDefault();
                scanBarcode(barcodeBuffer);
                barcodeBuffer = '';
            }
        } else if (event.key.length === 1) {
            barcodeBuffer += event.key;
        }
        
        lastKeyTime = currentTime;
        
        // Auto clear buffer after 200ms of inactivity
        if (barcodeTimeout) {
            clearTimeout(barcodeTimeout);
        }
        barcodeTimeout = setTimeout(() => {
            if (barcodeBuffer.length > 0) {
                // If buffer has content, focus search input
                searchInput.value = barcodeBuffer;
                searchInput.focus();
            }
            barcodeBuffer = '';
        }, 200);
    });

    // Manual barcode scan button
    document.getElementById('scan-barcode-btn')?.addEventListener('click', () => {
        const barcode = prompt('Masukkan barcode produk:');
        if (barcode && barcode.trim()) {
            scanBarcode(barcode.trim());
        }
    });

    searchInput.addEventListener('input', (event) => {
        const keyword = event.target.value.trim();

        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        if (!keyword) {
            clearSuggestions();
            return;
        }

        debounceTimer = setTimeout(() => fetchProducts(keyword), 250);
    });

    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            
            // First try as barcode if looks like one (numeric/alphanumeric without spaces)
            const value = searchInput.value.trim();
            if (value && /^[A-Za-z0-9\-_]+$/.test(value)) {
                scanBarcode(value);
                return;
            }
            
            // Otherwise use first suggestion
            const firstSuggestion = suggestionBox.querySelector('button[data-product]');
            if (firstSuggestion) {
                const product = JSON.parse(decodeURIComponent(firstSuggestion.dataset.product));
                addOrIncreaseProduct(product);
                searchInput.value = '';
                clearSuggestions();
            }
        }
    });

    clearSearchButton.addEventListener('click', () => {
        searchInput.value = '';
        clearSuggestions();
        searchInput.focus();
    });

    suggestionBox.addEventListener('mousedown', (event) => {
        const target = event.target.closest('button[data-product]');
        if (!target) {
            return;
        }
        event.preventDefault();
    const product = JSON.parse(decodeURIComponent(target.dataset.product));
        addOrIncreaseProduct(product);
        searchInput.value = '';
        clearSuggestions();
        searchInput.focus();
    });

    itemsBody.addEventListener('input', (event) => {
        if (event.target.matches('input[data-quantity]')) {
            const row = event.target.closest('tr[data-row-id]');
            const price = parseFloat(row.dataset.unitPrice || '0');
            let quantity = parseInt(event.target.value, 10) || 1;
            const max = parseInt(event.target.getAttribute('max') || '1', 10);
            if (quantity < 1) {
                quantity = 1;
            }
            if (quantity > max) {
                quantity = max;
                event.target.value = max;
            }
            updateRowTotals(row, quantity, price);
        }
    });

    itemsBody.addEventListener('click', (event) => {
        if (event.target.closest('[data-remove]')) {
            const row = event.target.closest('tr[data-row-id]');
            row.remove();
            ensureNotEmpty();
            recalcTotals();
        }
    });

    discountInput.addEventListener('input', recalcTotals);
    amountPaidInput.addEventListener('input', recalcTotals);

    // === LOYALTY POINTS FUNCTIONALITY ===
    const customerSelect = document.getElementById('customer_id');
    const pointsRedemptionSection = document.getElementById('points-redemption-section');
    const pointsToRedeemInput = document.getElementById('points-to-redeem-input');
    const pointsAvailableLabel = document.getElementById('points-available-label');
    const pointsValueDisplay = document.getElementById('points-value-display');
    const memberPointsInfo = document.getElementById('member-points-info');
    const totalDiscountDisplay = document.getElementById('total-discount-display');
    const totalDiscountAmount = document.getElementById('total-discount-amount');
    
    let currentMemberData = {
        points: 0,
        level: null,
        isMember: false
    };

    // Handle customer selection change
    customerSelect.addEventListener('change', async function() {
        const selectedOption = this.options[this.selectedIndex];
        
        // Reset points input
        pointsToRedeemInput.value = 0;
        
        // Check if "Pelanggan Umum" is selected
        if (this.value === '') {
            // Hide member-specific UI
            pointsRedemptionSection.classList.add('hidden');
            memberPointsInfo.classList.add('hidden');
            currentMemberData = { points: 0, level: null, isMember: false };
            recalcTotals();
            return;
        }
        
        // Get member data from data attributes
        const points = parseInt(selectedOption.dataset.points || '0');
        const level = selectedOption.dataset.level;
        
        currentMemberData = {
            points: points,
            level: level,
            isMember: true
        };
        
        // Update UI
        if (currentMemberData.isMember && currentMemberData.points >= 0) {
            // Show points redemption section
            pointsRedemptionSection.classList.remove('hidden');
            
            // Update available points label
            pointsAvailableLabel.textContent = `${currentMemberData.points} poin tersedia`;
            
            // Set max attribute on input
            pointsToRedeemInput.max = currentMemberData.points;
            
            // Show member info card
            memberPointsInfo.classList.remove('hidden');
            
            // Update member info card content
            const levelInfo = getMemberLevelInfo(level);
            document.getElementById('member-level-icon').textContent = levelInfo.icon;
            document.getElementById('member-level-text').textContent = levelInfo.name;
            document.getElementById('member-points-display').textContent = currentMemberData.points.toLocaleString('id-ID');
        } else {
            pointsRedemptionSection.classList.add('hidden');
            memberPointsInfo.classList.add('hidden');
        }
        
        recalcTotals();
    });

    // Handle points input change
    pointsToRedeemInput.addEventListener('input', function() {
        let pointsToRedeem = parseInt(this.value || '0');
        
        // Validate minimum
        if (pointsToRedeem > 0 && pointsToRedeem < 10) {
            pointsToRedeem = 10;
            this.value = 10;
        }
        
        // Validate maximum (available points)
        if (pointsToRedeem > currentMemberData.points) {
            pointsToRedeem = currentMemberData.points;
            this.value = currentMemberData.points;
        }
        
        // Calculate and display point value
        const pointValue = pointsToRedeem * 100; // 1 point = Rp 100
        pointsValueDisplay.textContent = formatCurrency(pointValue);
        
        recalcTotals();
    });

    // Helper function to get member level info
    function getMemberLevelInfo(level) {
        const levelData = {
            bronze: { name: 'Member Bronze', icon: '🥉', color: 'bg-amber-600' },
            silver: { name: 'Member Silver', icon: '🥈', color: 'bg-gray-400' },
            gold: { name: 'Member Gold', icon: '🥇', color: 'bg-yellow-500' },
            platinum: { name: 'Member Platinum', icon: '💎', color: 'bg-purple-600' }
        };
        return levelData[level] || { name: 'Member', icon: '👤', color: 'bg-gray-400' };
    }

    document.addEventListener('click', (event) => {
        if (!suggestionBox.contains(event.target) && event.target !== searchInput) {
            clearSuggestions();
        }
    });

    // Handle catalog product clicks - Global function untuk Alpine.js
    window.handleCatalogProductClick = function(product) {
        console.log('✅ Katalog product clicked:', product);
        
        // Validasi stok
        if (!product.stok || product.stok <= 0) {
            alert('Produk "' + product.nama_barang + '" stok habis!');
            return;
        }
        
        // Tambahkan produk ke keranjang
        addOrIncreaseProduct(product);
        
        // Visual feedback - cari button yang diklik
        const button = event.target.closest('.catalog-product-btn');
        if (button) {
            button.classList.add('ring-2', 'ring-[#F87B1B]', 'ring-offset-2', 'scale-95');
            setTimeout(() => {
                button.classList.remove('ring-2', 'ring-[#F87B1B]', 'ring-offset-2', 'scale-95');
            }, 300);
        }
        
        // Scroll ke bagian keranjang di mobile
        if (window.innerWidth < 1024) {
            const paymentSection = document.querySelector('.lg\\:col-span-1');
            if (paymentSection) {
                paymentSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    };
    
    // Event delegation sebagai fallback (jika Alpine.js tidak berfungsi)
    document.addEventListener('click', (event) => {
        const button = event.target.closest('.catalog-product-btn');
        
        if (button && !button.hasAttribute('@click')) {
            event.preventDefault();
            event.stopPropagation();
            
            const product = {
                id: button.dataset.productId,
                nama_barang: button.dataset.productName,
                harga: parseFloat(button.dataset.productPrice),
                stok: parseInt(button.dataset.productStock),
                gambar: button.dataset.productImage
            };
            
            console.log('📦 Fallback: Katalog product clicked via delegation:', product);
            window.handleCatalogProductClick(product);
        }
    });

    // ============================================================
    // BARCODE SCANNER
    // ============================================================
    const scanButton = document.getElementById('scan-barcode-btn');
    const scannerModal = document.getElementById('barcode-scanner-modal');
    const closeScannerBtn = document.getElementById('close-scanner-btn');
    const scannerStatus = document.getElementById('scanner-status');
    let html5QrCode = null;
    let isScanning = false;

    // Update scanner status message
    const updateScannerStatus = (message, type = 'info') => {
        const colors = {
            info: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-800' },
            success: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800' },
            error: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800' },
            warning: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800' }
        };
        const color = colors[type];
        scannerStatus.className = `mt-4 p-4 rounded-2xl ${color.bg} border ${color.border}`;
        scannerStatus.innerHTML = `
            <div class="flex items-center gap-2 ${color.text}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
    };

    // Search product by barcode
    const searchProductByBarcode = async (barcode) => {
        try {
            updateScannerStatus('🔍 Mencari produk dengan barcode: ' + barcode + '...', 'info');
            
            const response = await fetch(`${searchEndpoint}?query=${encodeURIComponent(barcode)}`);
            const data = await response.json();

            if (data.length > 0) {
                const product = data[0]; // Ambil produk pertama
                
                // Check stock
                if (product.stok <= 0) {
                    updateScannerStatus('❌ Produk "' + product.nama_barang + '" stok habis!', 'error');
                    return;
                }

                // Add to cart
                addOrIncreaseProduct(product);
                updateScannerStatus('✅ Berhasil! "' + product.nama_barang + '" ditambahkan ke keranjang.', 'success');
                
                // Auto close after 1.5 seconds
                setTimeout(() => {
                    stopScanner();
                }, 1500);
            } else {
                updateScannerStatus('❌ Barcode tidak ditemukan: ' + barcode, 'error');
            }
        } catch (error) {
            console.error('Error searching product:', error);
            updateScannerStatus('❌ Terjadi kesalahan saat mencari produk.', 'error');
        }
    };

    // Start scanner
    const startScanner = () => {
        if (isScanning) return;
        
        scannerModal.classList.remove('hidden');
        isScanning = true;
        updateScannerStatus('📷 Memulai kamera...', 'info');

        html5QrCode = new Html5Qrcode("qr-reader");
        
        html5QrCode.start(
            { facingMode: "environment" }, // Prefer back camera, fallback to any camera
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                // Barcode detected!
                console.log('Barcode detected:', decodedText);
                searchProductByBarcode(decodedText);
            },
            (errorMessage) => {
                // Scanning... (this fires continuously, ignore)
            }
        ).then(() => {
            updateScannerStatus('✅ Kamera siap! Arahkan barcode ke kamera.', 'success');
        }).catch((err) => {
            console.error('Failed to start scanner:', err);
            updateScannerStatus('❌ Gagal mengakses kamera. Pastikan izin kamera diaktifkan.', 'error');
            isScanning = false;
        });
    };

    // Stop scanner
    const stopScanner = () => {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
                isScanning = false;
                scannerModal.classList.add('hidden');
            }).catch((err) => {
                console.error('Error stopping scanner:', err);
            });
        } else {
            scannerModal.classList.add('hidden');
            isScanning = false;
        }
    };

    // Event listeners
    scanButton.addEventListener('click', startScanner);
    closeScannerBtn.addEventListener('click', stopScanner);

    // Close on overlay click
    scannerModal.addEventListener('click', (e) => {
        if (e.target === scannerModal) {
            stopScanner();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isScanning) {
            stopScanner();
        }
    });
})();
</script>
@endpush
@endsection
