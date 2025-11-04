@extends('layouts.public')

@section('title', 'Detail Wallet - ' . $wallet->user->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.wallets.index') }}" class="inline-flex items-center text-sm text-[#F87B1B] hover:underline mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Wallet
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Wallet</h1>
        <p class="mt-2 text-gray-600">{{ $wallet->user->name }} ({{ $wallet->user->email }})</p>
    </div>

    <!-- Wallet Info Card -->
    <div class="bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-3xl shadow-2xl p-8 mb-8 text-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-white/80 text-sm mb-1">Saldo Saat Ini</p>
                <p class="text-4xl font-bold">{{ $wallet->formatted_balance }}</p>
            </div>
            <div>
                <p class="text-white/80 text-sm mb-1">Total Top Up</p>
                <p class="text-2xl font-bold">{{ $wallet->formatted_total_topup }}</p>
            </div>
            <div>
                <p class="text-white/80 text-sm mb-1">Total Pengeluaran</p>
                <p class="text-2xl font-bold">{{ $wallet->formatted_total_spent }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-white/20 flex items-center justify-between">
            <div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $wallet->is_active ? 'bg-green-500/20' : 'bg-red-500/20' }}">
                    {{ $wallet->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.wallets.create', ['user_id' => $wallet->user_id]) }}" class="inline-flex items-center gap-2 bg-white text-[#F87B1B] px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    Top Up
                </a>
                <form method="POST" action="{{ route('admin.wallets.toggle', $wallet) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-6 py-2 rounded-lg font-semibold hover:bg-white/20 transition">
                        {{ $wallet->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>
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
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $transaction->description }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    @if($transaction->admin)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-semibold">Admin:</span> {{ $transaction->admin->name }}
                                        </p>
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
                <p class="text-gray-600">Wallet ini belum memiliki riwayat transaksi</p>
            </div>
        @endif
    </div>
</div>
@endsection
