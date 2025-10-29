@extends('layouts.public')

@section('title', 'Kelola Diskon')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-blue-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-10">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('discounts.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#F87B1B] transition">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke daftar
                </a>
                <h1 class="mt-3 text-3xl font-bold text-gray-900">{{ $discount->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">Atur detail kampanye, aturan diskon otomatis, dan kode kupon.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $discount->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                    Status: {{ $discount->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $discount->applies_automatically ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $discount->applies_automatically ? 'Diskon Otomatis' : 'Diskon via Kupon' }}
                </span>
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
            <div class="px-6 py-4 bg-[#FDE7D3] border-b border-orange-100">
                <h2 class="text-xl font-semibold text-gray-900">Detail Kampanye</h2>
                <p class="text-sm text-gray-600">Perbarui informasi dasar diskon.</p>
            </div>
            <form method="POST" action="{{ route('discounts.update', $discount) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-gray-700">Nama Kampanye<span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $discount->name) }}" required class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('name')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="code" class="text-sm font-semibold text-gray-700">Kode Kampanye</label>
                        <input type="text" id="code" name="code" value="{{ old('code', $discount->code) }}" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('code')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-sm font-semibold text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="3" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">{{ old('description', $discount->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="starts_at" class="text-sm font-semibold text-gray-700">Mulai</label>
                        <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', optional($discount->starts_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('starts_at')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="ends_at" class="text-sm font-semibold text-gray-700">Selesai</label>
                        <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at', optional($discount->ends_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                        @error('ends_at')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                        <input type="hidden" name="applies_automatically" value="0">
                        <input type="checkbox" id="applies_automatically" name="applies_automatically" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ old('applies_automatically', $discount->applies_automatically) ? 'checked' : '' }}>
                        <label for="applies_automatically" class="text-sm font-semibold text-gray-700">Diskon otomatis</label>
                    </div>

                    <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                        <input type="hidden" name="is_stackable" value="0">
                        <input type="checkbox" id="is_stackable" name="is_stackable" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ old('is_stackable', $discount->is_stackable) ? 'checked' : '' }}>
                        <label for="is_stackable" class="text-sm font-semibold text-gray-700">Boleh digabung</label>
                    </div>

                    <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 px-4 py-3 rounded-xl">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-semibold text-gray-700">Aktifkan</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-dashed border-gray-200">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-[#F87B1B] rounded-lg hover:opacity-90 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 bg-[#FDE7D3] border-b border-orange-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Aturan Diskon Otomatis</h2>
                    <p class="text-sm text-gray-600">Tetapkan diskon berdasarkan produk, kategori, atau global.</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">{{ $discount->rules->count() }} aturan</span>
            </div>

            <div class="p-6 space-y-6">
                <div class="bg-orange-50 border border-dashed border-orange-200 rounded-xl p-5" x-data="{ target: '{{ old('target_type', 'product') }}' }">
                    <form method="POST" action="{{ route('discounts.rules.store', $discount) }}" class="space-y-4">
                        @csrf
                        <h3 class="text-sm font-semibold text-gray-700">Tambah Aturan Baru</h3>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="target_type" class="text-xs font-semibold text-gray-600">Jenis Target</label>
                                <select id="target_type" name="target_type" x-model="target" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <option value="product">Produk tertentu</option>
                                    <option value="category">Kategori tertentu</option>
                                    <option value="global">Semua produk</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="discount_type" class="text-xs font-semibold text-gray-600">Tipe Diskon</label>
                                <select id="discount_type" name="discount_type" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <option value="percentage">Persentase (%)</option>
                                    <option value="fixed">Potongan nominal</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2" x-show="target === 'product'" x-cloak>
                                <label for="product_id" class="text-xs font-semibold text-gray-600">Produk</label>
                                <select id="product_id" name="product_id" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <option value="">-- Pilih produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">[{{ $product->id }}] {{ $product->nama_barang }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2" x-show="target === 'category'" x-cloak>
                                <label for="category_id" class="text-xs font-semibold text-gray-600">Kategori</label>
                                <select id="category_id" name="category_id" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <option value="">-- Pilih kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="discount_value" class="text-xs font-semibold text-gray-600">Nilai Diskon</label>
                                <input type="number" step="0.01" min="0" id="discount_value" name="discount_value" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Contoh: 10 untuk 10%" required>
                                @error('discount_value')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="min_quantity" class="text-xs font-semibold text-gray-600">Minimum Qty</label>
                                <input type="number" min="1" id="min_quantity" name="min_quantity" value="1" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="priority" class="text-xs font-semibold text-gray-600">Prioritas</label>
                                <input type="number" id="priority" name="priority" value="0" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#F87B1B] rounded-lg hover:opacity-90 transition">Simpan Aturan</button>
                        </div>
                    </form>
                </div>

                <div class="space-y-4">
                    @forelse ($discount->rules as $rule)
                        @php
                            $targetType = $rule->product_id ? 'product' : ($rule->category_id ? 'category' : 'global');
                        @endphp
                        <details class="group border border-gray-200 rounded-xl overflow-hidden">
                            <summary class="flex items-center justify-between gap-4 px-4 py-3 bg-gray-50 cursor-pointer">
                                <div class="flex flex-col text-sm text-gray-700">
                                    <span class="font-semibold">{{ ucfirst($targetType) }} • {{ $rule->discount_type === 'percentage' ? $rule->discount_value . '% ' : 'Rp '.number_format($rule->discount_value,0,',','.') }}</span>
                                    <span class="text-xs text-gray-500">
                                        @if ($rule->product)
                                            Produk: {{ $rule->product->nama_barang }}
                                        @elseif ($rule->category)
                                            Kategori: {{ $rule->category->name }}
                                        @else
                                            Berlaku untuk semua produk
                                        @endif
                                    </span>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-4 py-4 bg-white border-t border-gray-200" x-data="{ target: '{{ $targetType }}' }">
                                <form method="POST" action="{{ route('discount-rules.update', $rule) }}" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="space-y-2">
                                            <label class="text-xs font-semibold text-gray-600">Jenis Target</label>
                                            <select name="target_type" x-model="target" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                                <option value="product">Produk</option>
                                                <option value="category">Kategori</option>
                                                <option value="global">Global</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-semibold text-gray-600">Tipe Diskon</label>
                                            <select name="discount_type" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                                <option value="percentage" {{ $rule->discount_type === 'percentage' ? 'selected' : '' }}>Persentase</option>
                                                <option value="fixed" {{ $rule->discount_type === 'fixed' ? 'selected' : '' }}>Nominal</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="space-y-2" x-show="target === 'product'" x-cloak>
                                            <label class="text-xs font-semibold text-gray-600">Produk</label>
                                            <select name="product_id" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                                <option value="">-- Pilih produk --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $rule->product_id === $product->id ? 'selected' : '' }}>[{{ $product->id }}] {{ $product->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-2" x-show="target === 'category'" x-cloak>
                                            <label class="text-xs font-semibold text-gray-600">Kategori</label>
                                            <select name="category_id" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                                <option value="">-- Pilih kategori --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $rule->category_id === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-semibold text-gray-600">Nilai Diskon</label>
                                            <input type="number" step="0.01" min="0" name="discount_value" value="{{ $rule->discount_value }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-semibold text-gray-600">Minimum Qty</label>
                                            <input type="number" min="1" name="min_quantity" value="{{ $rule->min_quantity }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-semibold text-gray-600">Prioritas</label>
                                            <input type="number" name="priority" value="{{ $rule->priority }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-3 border-t border-dashed border-gray-200">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">Simpan Aturan</button>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('discount-rules.destroy', $rule) }}" onsubmit="return confirm('Hapus aturan ini?');" class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold text-red-600 hover:text-red-500">Hapus Aturan</button>
                                </form>
                            </div>
                        </details>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada aturan. Tambahkan aturan untuk menerapkan diskon otomatis.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white border border-orange-100 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 bg-[#FDE7D3] border-b border-orange-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Kupon Promo</h2>
                    <p class="text-sm text-gray-600">Buat kode voucher yang terhubung dengan kampanye ini.</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $discount->coupons->count() }} kupon</span>
            </div>

            <div class="p-6 space-y-6">
                <div class="bg-blue-50 border border-dashed border-blue-200 rounded-xl p-5">
                    <form method="POST" action="{{ route('discounts.coupons.store', $discount) }}" class="space-y-4">
                        @csrf
                        <h3 class="text-sm font-semibold text-gray-700">Tambah Kupon Baru</h3>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="code">Kode Kupon<span class="text-red-500">*</span></label>
                                <input type="text" id="code" name="code" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" required>
                                @error('code')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="name">Nama Kupon</label>
                                <input type="text" id="name" name="name" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-gray-600" for="description">Deskripsi</label>
                            <textarea id="description" name="description" rows="2" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm"></textarea>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="usage_limit">Batas Pakai Total</label>
                                <input type="number" min="1" id="usage_limit" name="usage_limit" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Tak terbatas">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="per_customer_limit">Batas per Pelanggan</label>
                                <input type="number" min="1" id="per_customer_limit" name="per_customer_limit" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" placeholder="Tak terbatas">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="min_order_value">Minimal Transaksi</label>
                                <input type="number" min="0" step="0.01" id="min_order_value" name="min_order_value" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm" value="0">
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="starts_at_coupon">Mulai</label>
                                <input type="datetime-local" id="starts_at_coupon" name="starts_at" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-600" for="ends_at_coupon">Selesai</label>
                                <input type="datetime-local" id="ends_at_coupon" name="ends_at" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="coupon_is_active" name="is_active" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" checked>
                            <label for="coupon_is_active" class="text-sm font-semibold text-gray-700">Aktifkan kupon</label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#F87B1B] rounded-lg hover:opacity-90 transition">Simpan Kupon</button>
                        </div>
                    </form>
                </div>

                <div class="space-y-4">
                    @forelse ($discount->coupons as $coupon)
                        <div class="border border-gray-200 rounded-xl p-4 bg-white">
                            <form method="POST" action="{{ route('coupons.update', $coupon) }}" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">Kupon: {{ $coupon->code }}</h3>
                                        <p class="text-xs text-gray-500">{{ $coupon->description ?? 'Tanpa deskripsi' }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $coupon->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                        {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Nama</label>
                                        <input type="text" name="name" value="{{ $coupon->name }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-xs">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Batas Pakai Total</label>
                                        <input type="number" min="1" name="usage_limit" value="{{ $coupon->usage_limit }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-xs" placeholder="Tak terbatas">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Batas per Customer</label>
                                        <input type="number" min="1" name="per_customer_limit" value="{{ $coupon->per_customer_limit }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-xs" placeholder="Tak terbatas">
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Minimal Transaksi</label>
                                        <input type="number" step="0.01" min="0" name="min_order_value" value="{{ $coupon->min_order_value }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-xs">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Mulai</label>
                                        <input type="datetime-local" name="starts_at" value="{{ optional($coupon->starts_at)->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-xs">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Selesai</label>
                                        <input type="datetime-local" name="ends_at" value="{{ optional($coupon->ends_at)->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-xs">
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="text-[#F87B1B] focus:ring-[#F87B1B]" {{ $coupon->is_active ? 'checked' : '' }}>
                                    <span class="text-xs font-semibold text-gray-600">Aktifkan kupon</span>
                                </div>

                                <div class="flex justify-end pt-3 border-t border-dashed border-gray-200">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">Simpan Kupon</button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('coupons.destroy', $coupon) }}" onsubmit="return confirm('Hapus kupon ini?');" class="mt-2 text-right">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold text-red-600 hover:text-red-500">Hapus Kupon</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada kupon untuk kampanye ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
