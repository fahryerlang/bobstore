@extends('layouts.public')

@section('title', $product->nama_barang)

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#F87B1B] hover:underline transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke katalog
        </a>

        <div class="mt-6 grid gap-10 lg:grid-cols-2 lg:items-start">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden lg:h-full">
                <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 aspect-[4/3] lg:aspect-auto lg:h-full lg:min-h-[520px]">
                    @if ($product->gambar)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->gambar) }}" alt="{{ $product->nama_barang }}" class="object-cover w-full h-full" loading="lazy">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->nama_barang }}</h1>
                <div class="mt-4 text-3xl font-extrabold text-[#F87B1B]">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
                        </svg>
                        <span class="font-medium text-gray-700">Stok tersedia:</span>
                        <span class="font-bold text-gray-900">{{ $product->stok }}</span>
                    </div>

                    <div class="text-sm text-gray-600 leading-relaxed bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="font-medium text-gray-700 mb-1">Deskripsi Produk:</p>
                        <p>Produk berkualitas tinggi. Silakan hubungi kami untuk informasi lebih lanjut mengenai produk ini.</p>
                    </div>
                </div>

                <div class="mt-8">
                    @auth
                        @if (in_array(auth()->user()->role, ['admin', 'pembeli']))
                            <form method="POST" action="{{ route('cart.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="quantity">Jumlah</label>
                                    <input
                                        id="quantity"
                                        name="quantity"
                                        type="number"
                                        min="1"
                                        max="{{ $product->stok }}"
                                        value="1"
                                        class="block w-32 rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm shadow-sm"
                                    >
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                        Masukkan ke Keranjang
                                    </button>
                                    <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        Beli Sekarang
                                    </a>
                                </div>
                            </form>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                Peran Anda tidak memiliki akses untuk membeli produk.
                            </div>
                        @endif
                    @else
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Login Diperlukan</p>
                                    <p class="text-sm text-gray-700">
                                        Silakan <a href="{{ route('login') }}" class="font-bold text-[#F87B1B] hover:underline">masuk</a> terlebih dahulu untuk menambahkan produk ini ke keranjang atau melakukan pembelian.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg" @click="$dispatch('notify', 'Silakan masuk terlebih dahulu sebelum melanjutkan pembelian.')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Masukkan ke Keranjang
                            </button>
                            <button type="button" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white rounded-lg transition" @click="$dispatch('notify', 'Silakan masuk terlebih dahulu sebelum melanjutkan pembelian.')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                Beli Sekarang
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
