@extends('layouts.public')

@section('title', 'Struk Transaksi')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Struk Transaksi</h1>
                <p class="text-sm text-gray-600 mt-1">Invoice {{ $transaction->invoice_number }}</p>
            </div>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-2xl border border-[#F87B1B] px-4 py-2 text-sm font-semibold text-[#F87B1B] hover:bg-[#F87B1B] hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F87B1B]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17a4 4 0 01-4-4V7a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4M7 17v3h10v-3M7 17h10" />
                </svg>
                Cetak Struk
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-orange-100 overflow-hidden">
            <div class="px-6 py-6 space-y-6">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Tanggal Transaksi</p>
                        <p class="text-base font-semibold text-gray-900">{{ $transaction->created_at->isoFormat('DD MMMM YYYY HH:mm') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Kasir</p>
                        <p class="text-base font-semibold text-gray-900">{{ $transaction->cashier->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Pelanggan</p>
                        <p class="text-base font-semibold text-gray-900">{{ $transaction->customer->name ?? 'Umum' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Invoice</p>
                        <p class="text-base font-semibold text-gray-900">{{ $transaction->invoice_number }}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-3xl border border-orange-100">
                    <table class="min-w-full divide-y divide-orange-100">
                        <thead class="bg-orange-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <th scope="col" class="px-4 py-3 text-left">Produk</th>
                                <th scope="col" class="px-4 py-3 text-center">Qty</th>
                                <th scope="col" class="px-4 py-3 text-right">Harga</th>
                                <th scope="col" class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-50 bg-white">
                            @foreach ($transaction->items as $item)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900">{{ $item->product->nama_barang ?? 'Produk dihapus' }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $item->product_id }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-orange-50/60 rounded-3xl px-6 py-6 space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Diskon</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-base">
                        <span class="text-gray-700 font-semibold">Total Bayar</span>
                        <span class="text-xl font-bold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Jumlah Diterima</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Kembalian</span>
                        <span class="font-semibold text-emerald-600">Rp {{ number_format($transaction->change_due, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-gray-400">
            <span>Terima kasih telah berbelanja.</span>
            <a href="{{ route('kasir.transactions.index') }}" class="inline-flex items-center gap-1 text-[#F87B1B] font-semibold">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke transaksi
            </a>
        </div>
    </div>
</div>
@endsection
