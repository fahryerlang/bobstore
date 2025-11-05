@extends('layouts.public')

@section('title', 'Detail Request Top Up')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('wallet.topup-requests') }}" class="inline-flex items-center text-[#F87B1B] hover:text-orange-600 mb-4 font-medium">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Request Top Up</h1>
        <p class="mt-2 text-gray-600">Request #{{ $topupRequest->id }}</p>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm text-gray-600 mb-2">Status Request</p>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold
                    @if($topupRequest->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($topupRequest->status == 'approved') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    @if($topupRequest->status == 'pending')
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($topupRequest->status == 'approved')
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ $topupRequest->status_label }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600 mb-2">Jumlah Request</p>
                <p class="text-3xl font-bold text-[#F87B1B]">{{ $topupRequest->formatted_amount }}</p>
            </div>
        </div>

        <!-- Timeline -->
        <div class="border-t border-gray-200 pt-6">
            <div class="space-y-4">
                <!-- Created -->
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Request Dibuat</p>
                        <p class="text-sm text-gray-600">{{ $topupRequest->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if($topupRequest->approved_at)
                    <!-- Approved -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Request Disetujui</p>
                            <p class="text-sm text-gray-600">{{ $topupRequest->approved_at->format('d M Y, H:i') }}</p>
                            @if($topupRequest->admin)
                                <p class="text-sm text-gray-600">Oleh: {{ $topupRequest->admin->name }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if($topupRequest->rejected_at)
                    <!-- Rejected -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Request Ditolak</p>
                            <p class="text-sm text-gray-600">{{ $topupRequest->rejected_at->format('d M Y, H:i') }}</p>
                            @if($topupRequest->admin)
                                <p class="text-sm text-gray-600">Oleh: {{ $topupRequest->admin->name }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Detail</h2>
        
        <div class="space-y-4">
            <div class="flex justify-between py-3 border-b border-gray-200">
                <span class="text-gray-600">ID Request</span>
                <span class="font-semibold text-gray-900">#{{ $topupRequest->id }}</span>
            </div>
            
            <div class="flex justify-between py-3 border-b border-gray-200">
                <span class="text-gray-600">Jumlah Top Up</span>
                <span class="font-bold text-[#F87B1B] text-lg">{{ $topupRequest->formatted_amount }}</span>
            </div>
            
            <div class="flex justify-between py-3 border-b border-gray-200">
                <span class="text-gray-600">Status</span>
                <span class="font-semibold text-gray-900">{{ $topupRequest->status_label }}</span>
            </div>
            
            <div class="flex justify-between py-3 border-b border-gray-200">
                <span class="text-gray-600">Tanggal Request</span>
                <span class="font-semibold text-gray-900">{{ $topupRequest->created_at->format('d M Y, H:i') }}</span>
            </div>
            
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

    <!-- Notes -->
    @if($topupRequest->user_notes || $topupRequest->admin_notes)
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Catatan</h2>
            
            @if($topupRequest->user_notes)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Catatan Anda:</h3>
                    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                        <p class="text-gray-700">{{ $topupRequest->user_notes }}</p>
                    </div>
                </div>
            @endif

            @if($topupRequest->admin_notes)
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Catatan Admin:</h3>
                    <div class="border-l-4 rounded-lg p-4
                        @if($topupRequest->status == 'approved') bg-green-50 border-green-400
                        @else bg-red-50 border-red-400
                        @endif">
                        <p class="text-gray-700">{{ $topupRequest->admin_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Transaction Link -->
    @if($topupRequest->walletTransaction)
        <div class="mt-6 bg-green-50 border-l-4 border-green-400 rounded-lg p-5">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-green-900 mb-1">Saldo Telah Ditambahkan</h3>
                    <p class="text-sm text-green-800 mb-2">Request Anda telah disetujui dan saldo telah ditambahkan ke akun Anda.</p>
                    <a href="{{ route('wallet.index') }}" class="text-sm text-green-900 font-medium hover:underline">
                        Lihat saldo Anda →
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
