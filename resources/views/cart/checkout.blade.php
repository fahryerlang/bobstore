@extends('layouts.public')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#F87B1B] transition mb-4">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="mt-1 text-sm text-gray-600">Lengkapi informasi pembayaran Anda</p>
        </div>

        <form method="POST" action="{{ route('cart.processCheckout') }}" x-data="checkoutForm()">
            @csrf
            
            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Left Column - Product List & Payment Method -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product List -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#F87B1B] to-orange-600 px-6 py-4">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Produk yang Dibeli
                            </h2>
                        </div>

                        <div class="p-6 space-y-4">
                            @foreach($items as $item)
                                <div class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 w-20 h-20 rounded-lg bg-gray-100 overflow-hidden">
                                        @if($item['image'])
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $item['name'] }}</h3>
                                        @if ($item['total_discount'] > 0)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <span class="text-base font-bold text-[#F87B1B]">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</span>
                                                <span class="text-xs text-gray-400 line-through ml-2">Rp {{ number_format($item['base_price'], 0, ',', '.') }}</span>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-xs font-semibold">-{{ number_format($item['discount_percentage'], 0) }}%</span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($item['unit_price'], 0, ',', '.') }} / pcs</p>
                                        @endif
                                        
                                        <!-- Quantity Adjuster -->
                                        <div class="flex items-center gap-3 mt-3">
                                            <label class="text-sm text-gray-600">Jumlah:</label>
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button type="button" @click="decreaseQuantity({{ $item['id'] }})" class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input type="number" name="items[{{ $item['id'] }}][quantity]" x-model="quantities[{{ $item['id'] }}]" min="1" max="{{ $item['max_stock'] }}" class="w-16 text-center border-0 focus:ring-0 text-sm font-semibold" readonly>
                                                <button type="button" @click="increaseQuantity({{ $item['id'] }}, {{ $item['max_stock'] }})" class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <input type="hidden" name="items[{{ $item['id'] }}][product_id]" value="{{ $item['id'] }}">
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Subtotal</p>
                                        <p class="text-lg font-bold text-[#F87B1B]" x-text="formatPrice(quantities[{{ $item['id'] }}] * finalPrices[{{ $item['id'] }}])"></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Metode Pembayaran
                            </h2>
                        </div>

                        <div class="p-6">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F87B1B] transition" :class="paymentMethod === 'cash' ? 'border-[#F87B1B] bg-orange-50' : ''">
                                    <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="text-[#F87B1B] focus:ring-[#F87B1B]" required>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Tunai</p>
                                        <p class="text-xs text-gray-500">Bayar di kasir</p>
                                    </div>
                                    <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </label>

                                <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F87B1B] transition" :class="paymentMethod === 'transfer' ? 'border-[#F87B1B] bg-orange-50' : ''">
                                    <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod" class="text-[#F87B1B] focus:ring-[#F87B1B]" required>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Transfer Bank</p>
                                        <p class="text-xs text-gray-500">BCA, BNI, Mandiri</p>
                                    </div>
                                    <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </label>

                                <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F87B1B] transition" :class="paymentMethod === 'ewallet' ? 'border-[#F87B1B] bg-orange-50' : ''">
                                    <input type="radio" name="payment_method" value="ewallet" x-model="paymentMethod" class="text-[#F87B1B] focus:ring-[#F87B1B]" required>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">E-Wallet</p>
                                        <p class="text-xs text-gray-500">GoPay, OVO, Dana</p>
                                    </div>
                                    <svg class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </label>

                                <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F87B1B] transition" :class="paymentMethod === 'qris' ? 'border-[#F87B1B] bg-orange-50' : ''">
                                    <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="text-[#F87B1B] focus:ring-[#F87B1B]" required>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">QRIS</p>
                                        <p class="text-xs text-gray-500">Scan & Pay</p>
                                    </div>
                                    <svg class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </label>

                                <label class="relative flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F87B1B] transition" :class="paymentMethod === 'wallet' ? 'border-[#F87B1B] bg-orange-50' : ''">
                                    <input type="radio" name="payment_method" value="wallet" x-model="paymentMethod" class="text-[#F87B1B] focus:ring-[#F87B1B]" required>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Saldo Wallet</p>
                                        <p class="text-xs text-gray-500">
                                            Saldo: Rp {{ number_format(auth()->user()->wallet?->balance ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <svg class="h-8 w-8 text-[#F87B1B]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-4">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                            <h2 class="text-lg font-bold text-white">Ringkasan Pesanan</h2>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Voucher/Promo Code -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Promo / Voucher</label>
                                <div class="flex gap-2">
                                    <input type="text" name="voucher_code" x-model="voucherCode" placeholder="Masukkan kode" class="flex-1 rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <button type="button" @click="applyVoucher()" class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition">
                                        Terapkan
                                    </button>
                                </div>
                                <p class="text-xs text-green-600 mt-2" x-show="voucherApplied" x-text="voucherMessage" x-cloak></p>
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold" x-text="formatPrice(subtotal)"></span>
                                </div>
                                
                                <div class="flex justify-between text-sm" x-show="automaticDiscount > 0" x-cloak>
                                    <span class="text-green-600">Diskon Otomatis</span>
                                    <span class="font-semibold text-green-600">- <span x-text="formatPrice(automaticDiscount)"></span></span>
                                </div>

                                <div class="flex justify-between text-sm" x-show="couponDiscount > 0" x-cloak>
                                    <span class="text-green-600">Diskon Voucher</span>
                                    <span class="font-semibold text-green-600">- <span x-text="formatPrice(couponDiscount)"></span></span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pajak (PPN 11%)</span>
                                    <span class="font-semibold text-gray-400">—</span>
                                </div>

                                <div class="border-t border-gray-200 pt-3 flex justify-between">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-[#F87B1B]" x-text="formatPrice(total)"></span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full mt-6 inline-flex justify-center items-center gap-2 px-6 py-4 bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-[#F87B1B] focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Konfirmasi Pembayaran
                            </button>

                            <p class="text-xs text-center text-gray-500 mt-3">Dengan melanjutkan, Anda menyetujui syarat dan ketentuan yang berlaku</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function checkoutForm() {
    return {
        quantities: {!! json_encode(collect($items)->pluck('quantity', 'id')->toArray()) !!},
        basePrices: {!! json_encode(collect($items)->pluck('base_price', 'id')->toArray()) !!},
        finalPrices: {!! json_encode(collect($items)->pluck('unit_price', 'id')->toArray()) !!},
        paymentMethod: 'cash',
        voucherCode: '',
        voucherApplied: false,
        voucherMessage: '',
        couponDiscount: 0,
        
        get subtotal() {
            let total = 0;
            for (let id in this.quantities) {
                total += this.quantities[id] * this.basePrices[id];
            }
            return total;
        },
        
        get automaticDiscount() {
            let total = 0;
            for (let id in this.quantities) {
                const diff = Math.max(0, this.basePrices[id] - this.finalPrices[id]);
                total += diff * this.quantities[id];
            }
            return total;
        },
        
        get total() {
            return Math.max(0, this.subtotal - this.automaticDiscount - this.couponDiscount);
        },
        
        increaseQuantity(id, maxStock) {
            if (this.quantities[id] < maxStock) {
                this.quantities[id]++;
            }
        },
        
        decreaseQuantity(id) {
            if (this.quantities[id] > 1) {
                this.quantities[id]--;
            }
        },
        
        applyVoucher() {
            if (!this.voucherCode) {
                this.voucherApplied = false;
                this.voucherMessage = 'Masukkan kode voucher terlebih dahulu.';
                return;
            }

            this.voucherApplied = true;
            this.voucherMessage = 'Kode voucher akan diverifikasi saat pembayaran.';
            this.couponDiscount = 0;
        },
        
        formatPrice(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
        }
    }
}
</script>
@endsection
