@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#F87B1B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Katalog Produk
                    </h1>
                    <p class="mt-3 text-base text-gray-600">Produk terbaik yang ditambahkan oleh admin dapat dilihat detailnya oleh seluruh pengguna.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg">
                    Lihat Semua Produk
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($products as $product)
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100 hover:shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden flex flex-col">
                            <a href="{{ route('catalog.show', $product) }}" class="relative overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 aspect-[4/3] block group">
                                @if ($product->gambar)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($product->gambar) }}" alt="{{ $product->nama_barang }}" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-[#F87B1B] text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Baru
                                </div>
                            </a>
                            <div class="p-5 flex-1 flex flex-col">
                                <a href="{{ route('catalog.show', $product) }}" class="text-lg font-bold text-gray-900 hover:text-[#F87B1B] transition">{{ $product->nama_barang }}</a>
                                <div class="mt-3 text-2xl font-extrabold text-[#F87B1B]">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                <p class="mt-2 text-sm text-gray-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
                                    </svg>
                                    Stok: <span class="font-semibold text-gray-700">{{ $product->stok }}</span>
                                </p>

                                <div class="mt-auto pt-4">
                                    @auth
                                        @if (in_array(auth()->user()->role, ['admin', 'pembeli']))
                                            <form method="POST" action="{{ route('cart.store') }}" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                                    </svg>
                                                    Tambah ke Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex text-xs text-gray-400 italic">Peran Anda tidak memiliki akses keranjang.</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#F87B1B] hover:underline transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                            </svg>
                                            Masuk untuk membeli
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border-2 border-dashed border-gray-300 rounded-2xl p-12 text-center text-gray-400 sm:col-span-2 xl:col-span-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-lg font-semibold">Produk belum tersedia.</p>
                            <p class="text-sm mt-2">Silakan kembali lagi nanti untuk melihat produk terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
    </section>
@endsection
