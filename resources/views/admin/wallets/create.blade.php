@extends('layouts.public')

@section('title', 'Top Up Saldo')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Top Up Saldo</h1>
        <p class="mt-2 text-gray-600">Isi saldo untuk pembeli</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-8">
        <form method="POST" action="{{ route('admin.wallets.store') }}">
            @csrf

            <!-- Search User -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pembeli</label>
                <select name="user_id" id="user_id" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]" required>
                    <option value="">-- Pilih Pembeli --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $selectedUser?->id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                            @if($user->wallet)
                                - Saldo: Rp {{ number_format($user->wallet->balance, 0, ',', '.') }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Top Up (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" min="1000" step="1000" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]" placeholder="10000" required>
                <p class="mt-1 text-xs text-gray-500">Minimal Rp 1.000</p>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]" placeholder="Catatan atau keterangan top up">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-[#F87B1B] text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Top Up Sekarang
                </button>
                <a href="{{ route('admin.wallets.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
