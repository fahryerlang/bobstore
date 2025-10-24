@extends('layouts.public')

@section('title', 'Kelola Produk (Kasir)')

@section('content')
<div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daftar Produk Toko</h1>
                <p class="mt-1 text-sm text-gray-600">Lihat stok dan harga produk agar proses transaksi berjalan lancar.</p>
            </div>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-50 border border-orange-200 text-[#F87B1B] font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Update otomatis dari admin
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-orange-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#FDE7D3]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Gambar</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Nama Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Satuan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-orange-50/60 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($product->gambar)
                                        <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_barang }}" class="w-16 h-16 object-cover rounded-lg shadow-md">
                                    @else
                                        <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->nama_barang }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $product->id }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->stok > 10 ? 'bg-green-100 text-green-700' : ($product->stok > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $product->stok }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $product->satuan ?? 'pcs' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
                                        </svg>
                                        <p class="text-lg font-semibold">Belum ada produk</p>
                                        <p class="text-sm text-gray-400">Produk akan muncul otomatis ketika admin menambahkannya.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasPages())
                <div class="px-6 py-4 bg-[#FFF0E1] border-t border-orange-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#F87B1B] transition">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke dashboard
            </a>
        </div>
    </div>
</div>
@endsection
