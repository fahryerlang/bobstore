@extends('layouts.public')

@section('title', 'Keranjang Belanja')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row lg:items-start lg:gap-10">
            <div class="flex-1">
                <h1 class="text-3xl font-semibold text-gray-900">Keranjang Belanja</h1>
                <p class="mt-2 text-sm text-gray-600">Periksa kembali barang yang ingin Anda beli sebelum melanjutkan checkout.</p>

                @if ($items->isEmpty())
                    <div class="mt-10 bg-white border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                        Keranjang Anda masih kosong. <a href="{{ route('catalog.index') }}" class="text-[#F87B1B] hover:underline font-medium">Belanja sekarang</a>.
                    </div>
                @else
                    <div class="mt-8 space-y-6">
                        @foreach ($items as $item)
                            <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        {{ optional($item->product)->nama_barang ?? 'Produk tidak tersedia' }}
                                    </h2>
                                    @php
                                        $pricing = $item->pricing_summary ?? null;
                                    @endphp
                                    <div class="mt-2 text-sm text-gray-500">
                                        @if ($pricing && $pricing['total_discount'] > 0)
                                            <span class="text-xs inline-flex items-center px-2 py-0.5 bg-red-100 text-red-600 font-semibold rounded-full">Diskon</span>
                                            <span class="text-base font-bold text-[#F87B1B] ml-2">Rp {{ number_format($pricing['unit_price'], 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-400 line-through ml-2">Rp {{ number_format($pricing['base_unit_price'], 0, ',', '.') }}</span>
                                        @else
                                            Harga: Rp {{ number_format(optional($item->product)->harga ?? 0, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantity-{{ $item->id }}" class="text-sm text-gray-600">Jumlah</label>
                                        <input id="quantity-{{ $item->id }}" name="quantity" type="number" min="1" value="{{ $item->quantity }}" class="w-20 rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                        <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-medium text-[#F87B1B] hover:underline">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-600 hover:text-red-500" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="mt-10 lg:mt-0 w-full lg:w-80">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="text-xl font-semibold text-gray-900">Ringkasan</h2>
                    <dl class="mt-4 space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <dt>Subtotal</dt>
                            <dd>Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                        @if ($discountTotal > 0)
                            <div class="flex justify-between text-green-600">
                                <dt>Diskon</dt>
                                <dd>- Rp {{ number_format($discountTotal, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt>Pajak</dt>
                            <dd class="text-gray-400">—</dd>
                        </div>
                        <div class="flex justify-between font-semibold text-gray-900 border-t border-dashed border-gray-200 pt-3">
                            <dt>Total</dt>
                            <dd>Rp {{ number_format($total, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <form method="GET" action="{{ route('cart.checkout') }}" class="mt-6">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 text-sm font-medium text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition" {{ $items->isEmpty() ? 'disabled' : '' }}>Lanjut ke Checkout</button>
                    </form>

                    <p class="mt-3 text-xs text-gray-500">Anda akan diarahkan ke halaman pembayaran untuk menyelesaikan transaksi.</p>
                </div>
            </aside>
        </div>
    </section>
@endsection
