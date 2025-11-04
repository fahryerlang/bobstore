@extends('layouts.public')

@section('title', 'Saldo Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <svg class="w-10 h-10 text-[#F87B1B]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
            </svg>
            Saldo Saya
        </h1>
        <p class="mt-2 text-gray-600">Kelola saldo dan riwayat transaksi Anda</p>
    </div>

    <!-- Wallet Card -->
    <div class="bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-3xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full"></div>
            <div class="absolute -left-10 -bottom-10 w-60 h-60 bg-white rounded-full"></div>
        </div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-white/80 text-sm font-medium mb-1">Saldo Tersedia</p>
                    <h2 class="text-5xl font-bold">{{ $wallet->formatted_balance }}</h2>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-white/20">
                <div>
                    <p class="text-white/70 text-xs mb-1">Total Top Up</p>
                    <p class="text-xl font-bold">{{ $wallet->formatted_total_topup }}</p>
                </div>
                <div>
                    <p class="text-white/70 text-xs mb-1">Total Pengeluaran</p>
                    <p class="text-xl font-bold">{{ $wallet->formatted_total_spent }}</p>
                </div>
            </div>

            <!-- Status Badge -->
            @if($wallet->is_active)
                <div class="mt-4 inline-flex items-center gap-2 bg-green-500/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Wallet Aktif
                </div>
            @else
                <div class="mt-4 inline-flex items-center gap-2 bg-red-500/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Wallet Nonaktif
                </div>
            @endif
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-5 mb-8">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-semibold text-blue-900 mb-1">Cara Top Up Saldo</h3>
                <p class="text-sm text-blue-800">Untuk mengisi saldo, silakan hubungi admin atau datang langsung ke toko. Admin akan melakukan top up saldo untuk Anda.</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('wallet.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                <select name="type" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                    <option value="">Semua Tipe</option>
                    <option value="topup" {{ request('type') == 'topup' ? 'selected' : '' }}>Top Up</option>
                    <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Pembayaran</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#F87B1B] text-white px-6 py-2.5 rounded-lg font-semibold hover:opacity-90 transition">
                    Filter
                </button>
                <a href="{{ route('wallet.index') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">Riwayat Transaksi</h2>
        </div>

        @if($transactions->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                                    @if($transaction->type == 'topup') bg-green-100
                                    @elseif($transaction->type == 'payment') bg-red-100
                                    @elseif($transaction->type == 'refund') bg-blue-100
                                    @else bg-yellow-100 @endif">
                                    @if($transaction->type == 'topup')
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($transaction->type == 'payment')
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" transform="rotate(180 10 10)"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>

                                <!-- Transaction Details -->
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-semibold text-gray-900">{{ $transaction->type_label }}</h3>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            @if($transaction->type == 'topup') bg-green-100 text-green-700
                                            @elseif($transaction->type == 'payment') bg-red-100 text-red-700
                                            @elseif($transaction->type == 'refund') bg-blue-100 text-blue-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $transaction->description }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    @if($transaction->admin)
                                        <p class="text-xs text-gray-500 mt-1">Oleh: {{ $transaction->admin->name }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="text-right">
                                <p class="text-lg font-bold
                                    @if(in_array($transaction->type, ['topup', 'refund'])) text-green-600
                                    @else text-red-600 @endif">
                                    {{ $transaction->formatted_amount }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Saldo: Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                <p class="text-gray-600">Riwayat transaksi Anda akan muncul di sini</p>
            </div>
        @endif
    </div>
</div>
@endsection
