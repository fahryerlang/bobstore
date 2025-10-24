@extends('layouts.public')

@section('title', 'Katalog Produk')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-2xl mx-auto text-center">
            <h1 class="text-3xl font-semibold text-gray-900 sm:text-4xl">Katalog Produk</h1>
            <p class="mt-4 text-base text-gray-600">Jelajahi pilihan produk terbaik kami. Klik pada produk untuk melihat detail lengkap.</p>
        </div>

            <form method="GET" action="{{ route('catalog.index') }}" class="mt-8 max-w-3xl mx-auto">
                <label for="search" class="sr-only">Cari produk</label>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 bg-white border border-gray-200 rounded-xl shadow-sm p-3">
                    <div class="flex items-center gap-2 flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#F87B1B] flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415L12.9 14.32zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                        </svg>
                        <input
                            type="search"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari produk berdasarkan nama..."
                            class="flex-1 border-none bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        @if (!empty($search))
                            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Reset</a>
                        @endif
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition">
                            Cari
                        </button>
                    </div>
                </div>
            </form>

            @if (!empty($search))
                <div class="mt-6 max-w-3xl mx-auto text-sm text-gray-600 text-center">
                    Menampilkan hasil pencarian untuk <span class="font-semibold text-[#F87B1B]">"{{ $search }}"</span>. Ditemukan <span class="font-semibold text-gray-900">{{ $products->total() }}</span> produk.
                </div>
            @endif

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition overflow-hidden flex flex-col">
                    <a href="{{ route('catalog.show', $product) }}" class="relative overflow-hidden bg-gray-100 aspect-[4/3] block">
                        @if ($product->gambar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($product->gambar) }}" alt="{{ $product->nama_barang }}" class="object-cover w-full h-full" loading="lazy">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-sm text-gray-500">Gambar belum tersedia</div>
                        @endif
                    </a>
                    <div class="p-4 flex-1 flex flex-col">
                        <a href="{{ route('catalog.show', $product) }}" class="text-lg font-semibold text-gray-900 hover:text-[#F87B1B] transition">{{ $product->nama_barang }}</a>
                        <div class="mt-3 text-[#F87B1B] font-bold text-base">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        <p class="mt-2 text-sm text-gray-500">Stok: {{ $product->stok }}</p>

                        <div class="mt-auto pt-4">
                            @auth
                                @if (in_array(auth()->user()->role, ['admin', 'pembeli']))
                                    <form method="POST" action="{{ route('cart.store') }}" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition">Tambah ke Keranjang</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-[#F87B1B] hover:underline">Masuk untuk membeli</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                @if (!empty($search))
                    <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white rounded-xl border border-dashed border-gray-200 p-10 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a7 7 0 015.292 11.708l3 3a1 1 0 01-1.414 1.414l-3-3A7 7 0 119 2zm-5 7a5 5 0 1110 0 5 5 0 01-10 0z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-gray-500">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                        <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain atau reset pencarian.</p>
                    </div>
                @else
                    <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white rounded-xl border border-dashed border-gray-200 p-10 text-center">
                        <p class="text-gray-500">Produk belum tersedia. Silakan kembali lagi nanti.</p>
                    </div>
                @endif
            @endforelse
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </section>
@endsection
