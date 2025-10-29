@extends('layouts.public')

@section('title', 'Tambah Diskon')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-blue-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('discounts.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#F87B1B] transition">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl shadow-xl">
            <div class="px-6 py-5 border-b border-orange-100 bg-[#FDE7D3] rounded-t-2xl">
                <h1 class="text-2xl font-bold text-gray-900">Buat Kampanye Diskon</h1>
                <p class="text-sm text-gray-600 mt-1">Isi detail dasar diskon. Aturan dan kupon dapat ditambahkan setelah kampanye dibuat.</p>
            </div>

            <form method="POST" action="{{ route('discounts.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-gray-700">Nama Kampanye<span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('name')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="code" class="text-sm font-semibold text-gray-700">Kode Kampanye</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Opsional, memudahkan pencarian">
                        @error('code')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-sm font-semibold text-gray-700">Deskripsi Singkat</label>
                    <textarea id="description" name="description" rows="3" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Contoh: Diskon akhir tahun untuk kategori elektronik">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="starts_at" class="text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                        <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('starts_at')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="ends_at" class="text-sm font-semibold text-gray-700">Tanggal Selesai</label>
                        <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('ends_at')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                        <input type="hidden" name="applies_automatically" value="0">
                        <input type="checkbox" id="applies_automatically" name="applies_automatically" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ old('applies_automatically', '1') ? 'checked' : '' }}>
                        <label for="applies_automatically" class="text-sm font-semibold text-gray-700">Diskon otomatis</label>
                    </div>

                    <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                        <input type="hidden" name="is_stackable" value="0">
                        <input type="checkbox" id="is_stackable" name="is_stackable" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ old('is_stackable') ? 'checked' : '' }}>
                        <label for="is_stackable" class="text-sm font-semibold text-gray-700">Dapat digabung</label>
                    </div>

                    <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-semibold text-gray-700">Aktifkan sekarang</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-dashed border-gray-200">
                    <a href="{{ route('discounts.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-[#F87B1B] rounded-lg hover:opacity-90 transition">Simpan & lanjutkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
