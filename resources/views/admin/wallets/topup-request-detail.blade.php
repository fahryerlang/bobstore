@extends('layouts.public')

@section('title', 'Detail Request Top Up')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.wallets.topup-requests') }}" class="inline-flex items-center text-[#F87B1B] hover:text-orange-600 mb-4 font-medium">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Request Top Up</h1>
        <p class="mt-2 text-gray-600">Request #{{ $topupRequest->id }}</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Info -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Informasi Request</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        @if($topupRequest->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($topupRequest->status == 'approved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $topupRequest->status_label }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-gray-600">ID Request</span>
                        <span class="font-semibold text-gray-900">#{{ $topupRequest->id }}</span>
                    </div>
                    
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-gray-600">Jumlah Top Up</span>
                        <span class="font-bold text-[#F87B1B] text-2xl">{{ $topupRequest->formatted_amount }}</span>
                    </div>
                    
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-gray-600">Tanggal Request</span>
                        <span class="font-semibold text-gray-900">{{ $topupRequest->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    @if($topupRequest->user_notes)
                        <div class="py-3">
                            <h3 class="font-semibold text-gray-900 mb-2">Catatan Pembeli:</h3>
                            <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                                <p class="text-gray-700">{{ $topupRequest->user_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($topupRequest->admin_notes)
                        <div class="py-3">
                            <h3 class="font-semibold text-gray-900 mb-2">Catatan Admin:</h3>
                            <div class="border-l-4 rounded-lg p-4
                                @if($topupRequest->status == 'approved') bg-green-50 border-green-400
                                @else bg-red-50 border-red-400
                                @endif">
                                <p class="text-gray-700">{{ $topupRequest->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($topupRequest->admin)
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Diproses Oleh</span>
                            <span class="font-semibold text-gray-900">{{ $topupRequest->admin->name }}</span>
                        </div>
                    @endif

                    @if($topupRequest->approved_at)
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Tanggal Disetujui</span>
                            <span class="font-semibold text-gray-900">{{ $topupRequest->approved_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif

                    @if($topupRequest->rejected_at)
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Tanggal Ditolak</span>
                            <span class="font-semibold text-gray-900">{{ $topupRequest->rejected_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Forms (Only if pending) -->
            @if($topupRequest->canBeProcessed())
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Proses Request</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Approve Form -->
                        <form action="{{ route('admin.wallets.topup-request-approve', $topupRequest->id) }}" method="POST" class="border-2 border-green-300 rounded-xl p-6">
                            @csrf
                            <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Setujui Request
                            </h3>
                            <div class="mb-4">
                                <label for="approve_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catatan (Opsional)
                                </label>
                                <textarea 
                                    id="approve_notes" 
                                    name="admin_notes" 
                                    rows="3"
                                    class="block w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Catatan untuk pembeli..."
                                ></textarea>
                            </div>
                            <button 
                                type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition-colors"
                                onclick="return confirm('Yakin ingin menyetujui request ini? Saldo akan ditambahkan ke akun pembeli.')"
                            >
                                Setujui & Tambah Saldo
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form action="{{ route('admin.wallets.topup-request-reject', $topupRequest->id) }}" method="POST" class="border-2 border-red-300 rounded-xl p-6">
                            @csrf
                            <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Tolak Request
                            </h3>
                            <div class="mb-4">
                                <label for="reject_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="reject_notes" 
                                    name="admin_notes" 
                                    rows="3"
                                    class="block w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Jelaskan alasan penolakan..."
                                    required
                                ></textarea>
                            </div>
                            <button 
                                type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors"
                                onclick="return confirm('Yakin ingin menolak request ini?')"
                            >
                                Tolak Request
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pembeli</h3>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 h-16 w-16 rounded-full bg-[#F87B1B] flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($topupRequest->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">{{ $topupRequest->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $topupRequest->user->email }}</div>
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Role</span>
                        <span class="text-sm font-semibold text-gray-900 capitalize">{{ $topupRequest->user->role }}</span>
                    </div>
                    @if($topupRequest->user->phone)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Telepon</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $topupRequest->user->phone }}</span>
                        </div>
                    @endif
                </div>

                <a href="{{ route('admin.wallets.show', $topupRequest->user->wallet->id ?? '#') }}" 
                   class="mt-4 block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                    Lihat Wallet
                </a>
            </div>

            <!-- Current Wallet Info -->
            @if($topupRequest->user->wallet)
                <div class="bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-2xl shadow-xl p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Saldo Saat Ini</h3>
                    <p class="text-3xl font-bold mb-4">{{ $topupRequest->user->wallet->formatted_balance }}</p>
                    
                    @if($topupRequest->status == 'pending')
                        <div class="border-t border-white/20 pt-4 mt-4">
                            <p class="text-sm text-white/80 mb-2">Setelah Disetujui:</p>
                            <p class="text-2xl font-bold">
                                Rp {{ number_format($topupRequest->user->wallet->balance + $topupRequest->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
