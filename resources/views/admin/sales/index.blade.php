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
            <div class="flex items-center gap-3">
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
    </div>
</div>
@endsection
