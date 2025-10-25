@extends('layouts.public')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="min-h-screen bg-white py-10 px-4">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#F87B1B] font-semibold">Insight Bisnis</p>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900">Laporan Penjualan</h1>
                <p class="mt-2 text-slate-700 max-w-2xl">Pantau performa penjualan toko Anda secara real-time, lengkap dengan filter tanggal, pelanggan, dan produk untuk analisis mendalam.</p>
            </div>
            @php
                $printParams = array_filter($filters ?? []);
            @endphp
            <div>
                <a href="{{ route('sales.report.print', $printParams) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 bg-[#F87B1B] text-white font-semibold rounded-xl shadow-lg hover:bg-orange-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9V3h12v6m-6 9v3m0-3l-3-3m3 3l3-3M6 14h12a2 2 0 002-2V9a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2z" />
                    </svg>
                    Cetak Laporan
                </a>
            </div>
        </div>

        <!-- Filter Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-orange-100">
            <form method="GET" action="{{ route('sales.report') }}" class="p-6">
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#F87B1B] focus:border-[#F87B1B] bg-white shadow-sm" placeholder="dd/mm/yyyy">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#F87B1B] focus:border-[#F87B1B] bg-white shadow-sm">
                    </div>
                    <div>
                        <label for="customer_id" class="block text-sm font-semibold text-slate-700 mb-2">Pelanggan</label>
                        <select id="customer_id" name="customer_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#F87B1B] focus:border-[#F87B1B] bg-white shadow-sm">
                            <option value="">Semua Pelanggan</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(($filters['customer_id'] ?? null) == $customer->id)>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="product_id" class="block text-sm font-semibold text-slate-700 mb-2">Produk</label>
                        <select id="product_id" name="product_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#F87B1B] focus:border-[#F87B1B] bg-white shadow-sm">
                            <option value="">Semua Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(($filters['product_id'] ?? null) == $product->id)>{{ $product->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-6">
                    <a href="{{ route('sales.report') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-[#F87B1B] text-[#F87B1B] font-semibold hover:bg-[#FEEBDC] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#F87B1B] text-white font-semibold shadow-lg hover:bg-orange-600 hover:shadow-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 3a1 1 0 011 1v2a1 1 0 01-1 1H6v9h12V7h-5a1 1 0 01-1-1V4a1 1 0 011-1h6a1 1 0 011 1v14a2 2 0 01-2 2H6a2 2 0 01-2-2V4a1 1 0 011-1h6z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl bg-[#F87B1B] text-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm uppercase tracking-[0.3em] font-semibold opacity-90">Total Revenue</h3>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 0V4m0 2c-1.11 0-2.08.402-2.599 1M12 16v2m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M12 18c1.11 0 2.08-.402 2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="mt-4 text-3xl font-bold">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                <p class="mt-2 text-sm text-white/80">Akumulasi pendapatan berdasarkan filter.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-xl border border-orange-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm uppercase tracking-[0.3em] text-[#F87B1B] font-semibold">Total Transaksi</h3>
                    <svg class="w-8 h-8 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2h-3V3a2 2 0 00-2-2H9a2 2 0 00-2 2v2H4a2 2 0 00-2 2v11a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($summary['total_transactions'] ?? 0) }}</p>
                <p class="mt-2 text-sm text-slate-600">Jumlah transaksi yang berhasil tercatat.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-xl border border-orange-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm uppercase tracking-[0.3em] text-[#F87B1B] font-semibold">Produk Terjual</h3>
                    <svg class="w-8 h-8 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2m0 0L7 16a2 2 0 002 2h6a2 2 0 001.994-1.839L18 7H5.4m0 0L4 5m4 13a2 2 0 104 0" />
                    </svg>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($summary['total_quantity'] ?? 0) }}</p>
                <p class="mt-2 text-sm text-slate-600">Total unit produk yang terjual.</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-orange-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#FDE7D3]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Invoice</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Pelanggan</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Produk</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-[#B45309] uppercase tracking-wider">Qty</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-[#B45309] uppercase tracking-wider">Harga Satuan</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-[#B45309] uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-800">{{ $sale->invoice_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ optional($sale->sale_date)->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ optional($sale->customer)->name ?? 'Umum' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ optional($sale->product)->nama_barang ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-slate-700 font-semibold">{{ number_format($sale->quantity) }}</td>
                                <td class="px-6 py-4 text-right text-slate-700">Rp {{ number_format($sale->unit_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-slate-900 font-semibold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
                                        </svg>
                                        <p class="text-lg font-semibold text-slate-600">Belum ada data penjualan</p>
                                        <p class="text-sm text-slate-500 max-w-md">Silakan lakukan transaksi atau ubah filter tanggal, pelanggan, maupun produk untuk melihat data laporan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($sales instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="px-6 py-4 bg-[#FFF0E1] border-t border-orange-100">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>

        <!-- Export All Data Section -->
        <div class="mt-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900">Ekspor Data</h2>
                <p class="text-slate-600 mt-1">Unduh laporan data penjualan, produk, dan customer dalam format Excel atau PDF</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Export Sales with Filter -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-orange-100">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Data Penjualan</h3>
                                <p class="text-xs text-gray-600 mt-0.5">Ekspor laporan dengan filter</p>
                            </div>
                        </div>
                        
                        <div class="mb-4 space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" id="export-sales-start-date" value="{{ $filters['start_date'] ?? '' }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" id="export-sales-end-date" value="{{ $filters['end_date'] ?? '' }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="#" 
                               onclick="event.preventDefault(); downloadSalesExcel();"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z" />
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                </svg>
                                <span>Excel</span>
                            </a>
                            
                            <a href="#" 
                               onclick="event.preventDefault(); downloadSalesPdf();"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                </svg>
                                <span>PDF</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Export Products -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-orange-100">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Data Produk</h3>
                                <p class="text-xs text-gray-600 mt-0.5">Ekspor seluruh data produk</p>
                            </div>
                        </div>

                        <div class="mb-4 py-8 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="h-12 w-12 mx-auto mb-2 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-sm">Semua produk akan diexport</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('export.products.excel') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z" />
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                </svg>
                                <span>Excel</span>
                            </a>
                            
                            <a href="{{ route('export.products.pdf') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                </svg>
                                <span>PDF</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Export Customers -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-orange-100">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Data Customer</h3>
                                <p class="text-xs text-gray-600 mt-0.5">Ekspor seluruh data customer</p>
                            </div>
                        </div>

                        <div class="mb-4 py-8 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="h-12 w-12 mx-auto mb-2 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-sm">Semua customer akan diexport</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('export.customers.excel') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z" />
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                </svg>
                                <span>Excel</span>
                            </a>
                            
                            <a href="{{ route('export.customers.pdf') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                </svg>
                                <span>PDF</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function downloadSalesExcel() {
        const startDate = document.getElementById('export-sales-start-date').value;
        const endDate = document.getElementById('export-sales-end-date').value;
        const url = '{{ route('export.sales.excel') }}?start_date=' + startDate + '&end_date=' + endDate;
        window.location.href = url;
    }

    function downloadSalesPdf() {
        const startDate = document.getElementById('export-sales-start-date').value;
        const endDate = document.getElementById('export-sales-end-date').value;
        const url = '{{ route('export.sales.pdf') }}?start_date=' + startDate + '&end_date=' + endDate;
        window.location.href = url;
    }
</script>
@endpush
@endsection
