@extends('layouts.public')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
                <p class="text-sm text-gray-600 mt-1">Lihat kembali transaksi yang pernah kamu lakukan beserta detail produknya.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-[#F87B1B] px-4 py-2 text-sm font-semibold text-[#F87B1B] hover:bg-[#F87B1B] hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Belanja Lagi
            </a>
        </div>

        @if ($transactions->isEmpty())
            <div class="bg-white border border-dashed border-gray-200 rounded-3xl p-10 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-orange-50 text-[#F87B1B]">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-4.215A2 2 0 0016.695 11H7.305a2 2 0 00-1.9 1.785L4 17h5m3-4v4" />
                    </svg>
                </div>
                <h2 class="mt-6 text-xl font-semibold text-gray-900">Belum ada transaksi</h2>
                <p class="mt-3 text-sm text-gray-500 max-w-md mx-auto">Setelah kamu melakukan pembelian, daftar transaksi akan muncul di sini. Mulai belanja untuk mendapatkan poin dan promo menarik.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($transactions as $transaction)
                    <details class="group bg-white border border-orange-100 rounded-3xl shadow-lg overflow-hidden">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5">
                            <div>
                                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-3 py-1 font-semibold text-[#F87B1B] text-xs">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h3.28a1 1 0 01.948.684l.894 2.684A1 1 0 0011.28 9H19a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                        </svg>
                                        {{ $transaction['invoice_number'] ?? 'Tanpa Invoice' }}
                                    </span>
                                    <span>{{ optional($transaction['date'])->isoFormat('DD MMMM YYYY HH:mm') }}</span>
                                </div>
                                <h2 class="mt-2 text-lg font-semibold text-gray-900">Total: Rp {{ number_format($transaction['total'], 0, ',', '.') }}</h2>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-[#F87B1B] font-semibold">
                                <span>Lihat Detail</span>
                                <svg class="h-5 w-5 transform transition duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <div class="border-t border-orange-100 bg-orange-50/50 px-6 py-5 space-y-6">
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Subtotal</p>
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($transaction['subtotal'], 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Diskon</p>
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($transaction['discount'], 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Total Belanja</p>
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</p>
                                </div>
                                @if (!is_null($transaction['amount_paid']))
                                    <div>
                                        <p class="text-gray-500">Dibayar</p>
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($transaction['amount_paid'], 0, ',', '.') }}</p>
                                    </div>
                                @endif
                                @if (!is_null($transaction['change_due']))
                                    <div>
                                        <p class="text-gray-500">Kembalian</p>
                                        <p class="font-semibold text-emerald-600">Rp {{ number_format($transaction['change_due'], 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="overflow-hidden rounded-2xl border border-orange-100">
                                <table class="min-w-full divide-y divide-orange-100">
                                    <thead class="bg-white text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left">Produk</th>
                                            <th scope="col" class="px-4 py-3 text-center">Qty</th>
                                            <th scope="col" class="px-4 py-3 text-right">Harga</th>
                                            <th scope="col" class="px-4 py-3 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-orange-50 bg-white text-sm">
                                        @foreach ($transaction['items'] as $item)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <p class="font-semibold text-gray-900">{{ $item['product_name'] }}</p>
                                                </td>
                                                <td class="px-4 py-3 text-center">{{ $item['quantity'] }}</td>
                                                <td class="px-4 py-3 text-right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
