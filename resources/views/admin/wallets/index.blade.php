@extends('layouts.public')

@section('title', 'Kelola Saldo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
         search: '',
         wallets: {{ $wallets->map(fn($w) => [
             'id' => $w->id,
             'user_id' => $w->user_id,
             'user_name' => $w->user->name,
             'user_email' => $w->user->email,
             'formatted_balance' => $w->formatted_balance,
             'formatted_total_topup' => $w->formatted_total_topup,
             'formatted_total_spent' => $w->formatted_total_spent,
             'is_active' => $w->is_active
         ])->toJson() }},
         get filteredWallets() {
             if (this.search.trim() === '') return this.wallets;
             const s = this.search.toLowerCase();
             return this.wallets.filter(w => 
                 w.user_name.toLowerCase().includes(s) || 
                 w.user_email.toLowerCase().includes(s)
             );
         }
     }">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Saldo Pembeli</h1>
                <p class="mt-2 text-gray-600">Manage wallet dan top up saldo untuk pembeli</p>
            </div>
            <div class="flex gap-3">
                @php
                    $pendingTopupCount = \App\Models\WalletTopupRequest::where('status', 'pending')->count();
                @endphp
                <a href="{{ route('admin.wallets.topup-requests') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg relative">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Request Top Up
                    @if($pendingTopupCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingTopupCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.wallets.create') }}" class="inline-flex items-center gap-2 bg-[#F87B1B] text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition shadow-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    Top Up Manual
                </a>
            </div>
        </div>
    </div>

    <!-- Real-time Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" 
                       x-model="search" 
                       placeholder="Cari nama atau email pembeli... (ketik langsung)" 
                       class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] pl-10"
                       autocomplete="off">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="button" 
                    x-show="search.length > 0"
                    @click="search = ''" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Reset
            </button>
        </div>
    </div>

    <!-- Wallets List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($wallets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Top Up</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="wallet in filteredWallets" :key="wallet.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-semibold text-gray-900" x-text="wallet.user_name"></div>
                                        <div class="text-sm text-gray-500" x-text="wallet.user_email"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-lg font-bold text-[#F87B1B]" x-text="wallet.formatted_balance"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600" x-text="wallet.formatted_total_topup"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600" x-text="wallet.formatted_total_spent"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="wallet.is_active" class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                                    <span x-show="!wallet.is_active" class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a :href="`{{ route('admin.wallets.index') }}/${wallet.id}`" class="text-blue-600 hover:text-blue-900">Detail</a>
                                        <a :href="`{{ route('admin.wallets.create') }}?user_id=${wallet.user_id}`" class="text-[#F87B1B] hover:text-orange-600">Top Up</a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <!-- No Results from Filter -->
                        <tr x-show="filteredWallets.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-900">Tidak ditemukan</p>
                                <p class="text-xs text-gray-500">Coba kata kunci lain</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Wallet</h3>
                <p class="text-gray-600">Wallet akan otomatis dibuat saat pembeli melakukan transaksi pertama</p>
            </div>
        @endif
    </div>
</div>
@endsection
