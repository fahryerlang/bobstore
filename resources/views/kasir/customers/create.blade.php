@extends('layouts.public')

@section('title', 'Daftarkan Pelanggan Baru')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daftarkan Pelanggan Baru</h1>
                <p class="text-sm text-gray-600 mt-1">Masukkan data pelanggan baru untuk menjadi member toko. Data akan muncul otomatis pada pilihan pelanggan saat transaksi kasir.</p>
            </div>

        </div>

        <div class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60">
                <h2 class="text-lg font-semibold text-gray-900">Formulir Member</h2>
            </div>
            <form method="POST" action="{{ route('kasir.customers.store') }}" class="px-6 py-6 space-y-6">
                @csrf
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="081234567890">
                        <p class="mt-2 text-xs text-gray-500">Gunakan angka saja tanpa spasi atau tanda lainnya. Nomor ini harus unik.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peran</label>
                        <div class="rounded-2xl border border-dashed border-orange-200 bg-orange-50/60 px-4 py-3 text-sm text-[#F87B1B] font-semibold">Member (Pembeli)</div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="contoh@email.com">
                        <p class="mt-2 text-xs text-gray-500">Email harus unik dan akan digunakan untuk login.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Minimal 8 karakter">
                        <p class="mt-2 text-xs text-gray-500">Masukkan password untuk member baru (minimal 8 karakter).</p>
                    </div>
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea id="address" name="address" rows="4" required class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Contoh: Jl. Melati No.10, Bandung">{{ old('address') }}</textarea>
                </div>

                <div class="bg-orange-50/70 rounded-2xl px-4 py-4 text-xs text-gray-600">
                    <p class="font-semibold text-gray-800">Catatan:</p>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        <li>Email member harus unik dan akan digunakan untuk login.</li>
                        <li>Password harus minimal 8 karakter untuk keamanan akun.</li>
                        <li>Pelanggan baru langsung muncul di daftar pelanggan pada halaman transaksi kasir.</li>
                    </ul>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('kasir.transactions.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600 hover:border-[#F87B1B] hover:text-[#F87B1B]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Transaksi
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#F87B1B] to-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200/60 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F87B1B]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.105.895-2 2-2h6m-6 0h-6m6 0v6m0-6a2 2 0 10-4 0v6a2 2 0 104 0v-6z" />
                        </svg>
                        Simpan Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
