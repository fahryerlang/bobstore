@extends('layouts.public')

@section('title', 'Manajemen Diskon')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-blue-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Diskon & Promo</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola kampanye promo, aturan diskon otomatis, dan kupon voucher.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('discounts.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-orange-600 hover:to-[#F87B1B] transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kampanye
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white border border-orange-100 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-orange-100">
                    <thead class="bg-[#FDE7D3]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Diskon</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Aturan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#B45309] uppercase tracking-wider">Kupon</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-[#B45309] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-orange-50">
                        @forelse ($discounts as $discount)
                            <tr class="hover:bg-orange-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $discount->name }}</span>
                                            @if ($discount->code)
                                                <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold bg-blue-100 text-blue-700 rounded-full">Kode: {{ $discount->code }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $discount->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                                {{ $discount->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $discount->applies_automatically ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ $discount->applies_automatically ? 'Otomatis' : 'Perlu Kupon' }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $discount->description ?? '—' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($discount->starts_at || $discount->ends_at)
                                        <div class="flex flex-col">
                                            <span>Mulai: {{ optional($discount->starts_at)->format('d M Y H:i') ?? '—' }}</span>
                                            <span>Selesai: {{ optional($discount->ends_at)->format('d M Y H:i') ?? '—' }}</span>
                                        </div>
                                    @else
                                        <span>Tanpa batas waktu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col gap-1">
                                        <span>{{ $discount->applies_automatically ? 'Diskon otomatis' : 'Diskon berbasis kupon' }}</span>
                                        <span class="text-xs text-gray-500">Stackable: {{ $discount->is_stackable ? 'Ya' : 'Tidak' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">{{ $discount->rules_count }} aturan</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $discount->coupons_count }} kupon</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('discounts.edit', $discount) }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">Kelola</a>
                                        <form action="{{ route('discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Hapus kampanye diskon ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2a4 4 0 014-4h4" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7h8m0 0v8m0-8l-9 9a4 4 0 01-2.828 1.172H5a2 2 0 01-2-2v-3.172A4 4 0 014.172 9L13 0" />
                                        </svg>
                                        <p class="text-sm">Belum ada kampanye diskon. Tambahkan diskon pertama Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($discounts->hasPages())
                <div class="px-6 py-4 bg-[#FFF0E1] border-t border-orange-100">
                    {{ $discounts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
