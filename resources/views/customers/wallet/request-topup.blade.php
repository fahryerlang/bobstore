@extends('layouts.public')

@section('title', 'Request Top Up Saldo')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('wallet.index') }}" class="inline-flex items-center text-[#F87B1B] hover:text-orange-600 mb-4 font-medium">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Request Top Up Saldo</h1>
        <p class="mt-2 text-gray-600">Ajukan permintaan top up saldo secara online</p>
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

    <!-- Pending Request Alert -->
    @if($pendingRequest)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-5 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-yellow-900 mb-2">Anda Memiliki Request Pending</h3>
                    <p class="text-sm text-yellow-800 mb-3">Request top up sebesar <strong>{{ $pendingRequest->formatted_amount }}</strong> masih menunggu persetujuan admin.</p>
                    <a href="{{ route('wallet.topup-requests') }}" class="text-sm text-yellow-900 font-medium hover:underline">
                        Lihat detail request →
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Balance Card -->
    <div class="bg-gradient-to-r from-[#F87B1B] to-orange-600 rounded-2xl shadow-lg p-6 mb-8 text-white">
        <p class="text-white/80 text-sm mb-2">Saldo Saat Ini</p>
        <h2 class="text-4xl font-bold">{{ $wallet->formatted_balance }}</h2>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Form Request Top Up</h2>
        
        <form action="{{ route('wallet.request-topup.store') }}" method="POST" id="topupForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah Top Up <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input 
                            type="text" 
                            id="amount" 
                            name="amount" 
                            value="{{ old('amount') }}"
                            class="block w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-[#F87B1B] text-lg font-semibold @error('amount') border-red-500 @enderror"
                            placeholder="0"
                            required
                            oninput="formatCurrency(this)"
                        >
                    </div>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Minimal Rp 1.000 - Maksimal Rp 100.000.000</p>
                </div>

                <!-- Quick Amount Buttons -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-3">Atau pilih jumlah:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button type="button" onclick="setAmount(10000)" class="quick-amount-btn">
                            Rp 10.000
                        </button>
                        <button type="button" onclick="setAmount(50000)" class="quick-amount-btn">
                            Rp 50.000
                        </button>
                        <button type="button" onclick="setAmount(100000)" class="quick-amount-btn">
                            Rp 100.000
                        </button>
                        <button type="button" onclick="setAmount(500000)" class="quick-amount-btn">
                            Rp 500.000
                        </button>
                    </div>
                </div>

                <!-- User Notes -->
                <div>
                    <label for="user_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea 
                        id="user_notes" 
                        name="user_notes" 
                        rows="4"
                        class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-[#F87B1B] @error('user_notes') border-red-500 @enderror"
                        placeholder="Tambahkan catatan untuk admin jika diperlukan..."
                    >{{ old('user_notes') }}</textarea>
                    @error('user_notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info -->
                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Informasi:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Request Anda akan diproses oleh admin</li>
                                <li>Anda akan menerima notifikasi setelah request disetujui atau ditolak</li>
                                <li>Saldo akan otomatis ditambahkan setelah request disetujui</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-[#F87B1B] hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition-colors duration-200 shadow-lg hover:shadow-xl"
                        @if($pendingRequest) disabled @endif
                    >
                        @if($pendingRequest)
                            Tunggu Request Sebelumnya Diproses
                        @else
                            Kirim Request
                        @endif
                    </button>
                    <a 
                        href="{{ route('wallet.index') }}" 
                        class="px-8 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-4 rounded-xl transition-colors duration-200"
                    >
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.quick-amount-btn {
    @apply bg-white hover:bg-[#F87B1B] hover:text-white text-gray-700 font-semibold py-3 px-4 rounded-lg border-2 border-gray-300 hover:border-[#F87B1B] transition-all duration-200;
}
</style>

<script>
function formatCurrency(input) {
    // Remove non-numeric characters
    let value = input.value.replace(/\D/g, '');
    
    // Format with thousand separator
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    input.value = value;
}

function setAmount(amount) {
    const input = document.getElementById('amount');
    input.value = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Form validation
document.getElementById('topupForm').addEventListener('submit', function(e) {
    const amountInput = document.getElementById('amount');
    const amount = parseInt(amountInput.value.replace(/\D/g, ''));
    
    if (amount < 1000) {
        e.preventDefault();
        alert('Jumlah minimal top up adalah Rp 1.000');
        return false;
    }
    
    if (amount > 100000000) {
        e.preventDefault();
        alert('Jumlah maksimal top up adalah Rp 100.000.000');
        return false;
    }
    
    // Convert formatted value to plain number for submission
    amountInput.value = amount;
});
</script>
@endsection
