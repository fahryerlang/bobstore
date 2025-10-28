@extends('layouts.public')

@section('title', 'Katalog Produk')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-2xl mx-auto text-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-900 sm:text-4xl">Katalog Produk</h1>
            <p class="mt-4 text-base text-gray-600">Jelajahi pilihan produk terbaik kami. Klik pada produk untuk melihat detail lengkap.</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('catalog.index') }}" class="max-w-3xl mx-auto mb-8">
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

        <!-- Active Filters -->
        @if ($selectedCategory || $selectedSubcategory || $selectedTag || !empty($search))
            <div class="mb-6 flex flex-wrap items-center gap-2">
                <span class="text-sm font-semibold text-gray-700">Filter Aktif:</span>
                
                @if (!empty($search))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#F87B1B] text-white text-xs font-medium rounded-full">
                        Pencarian: "{{ $search }}"
                        <a href="{{ route('catalog.index', array_filter(['category' => request('category'), 'subcategory' => request('subcategory'), 'tag' => request('tag')])) }}" class="ml-1 hover:bg-white/20 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </a>
                    </span>
                @endif

                @if ($selectedCategory)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                        @switch($selectedCategory->slug)
                            @case('makanan-minuman')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 21.99V21h15.03v.99c0 .55-.45 1-1.01 1H2.01c-.56 0-1.01-.45-1.01-1zm15.03-7c0-8-15.03-8-15.03 0h15.03zM1.02 17h15v2h-15z"/></svg>
                                @break
                            @case('elektronik')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/></svg>
                                @break
                            @case('fashion-pakaian')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 4l-4-4-4 4H2v6l6-2v12h8V8l6 2V4h-6zm0 2.5l4 1.5v1.5l-4-1.5V6.5z"/></svg>
                                @break
                            @case('kesehatan-kecantikan')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 10.57 2 9.14 3.43 7.71 2 5.57 4.14 4.14 2.71 2.71 4.14l1.43 1.43L2 7.71l1.43 1.43L2 10.57 3.43 12 7 8.43 15.57 17 12 20.57 13.43 22l1.43-1.43L16.29 22l2.14-2.14 1.43 1.43 1.43-1.43-1.43-1.43L22 16.29z"/></svg>
                                @break
                            @case('rumah-tangga')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                                @break
                            @case('olahraga-outdoor')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-5-8c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm4 4c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm2-6c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm2 2c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm1 4c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z"/></svg>
                                @break
                            @default
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
                        @endswitch
                        {{ $selectedCategory->name }}
                        <a href="{{ route('catalog.index', array_filter(['search' => $search, 'subcategory' => request('subcategory'), 'tag' => request('tag')])) }}" class="ml-1 hover:bg-blue-200 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </a>
                    </span>
                @endif

                @if ($selectedSubcategory)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                        {{ $selectedSubcategory->name }}
                        <a href="{{ route('catalog.index', array_filter(['search' => $search, 'category' => request('category'), 'tag' => request('tag')])) }}" class="ml-1 hover:bg-green-200 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </a>
                    </span>
                @endif

                @if ($selectedTag)
                    <span class="inline-flex items-center gap-1 px-3 py-1 text-white text-xs font-medium rounded-full" style="background-color: {{ $selectedTag->color }}">
                        {{ $selectedTag->name }}
                        <a href="{{ route('catalog.index', array_filter(['search' => $search, 'category' => request('category'), 'subcategory' => request('subcategory')])) }}" class="ml-1 hover:bg-white/20 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </a>
                    </span>
                @endif

                <a href="{{ route('catalog.index') }}" class="text-xs text-gray-500 hover:text-[#F87B1B] font-medium">Reset Semua</a>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filter -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-4">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#F87B1B]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </h2>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Kategori</h3>
                        <div class="space-y-2">
                            @foreach ($categories as $category)
                                <a href="{{ route('catalog.index', array_filter(['category' => $category->id, 'search' => $search])) }}" 
                                   class="flex items-center justify-between p-2 rounded-lg {{ request('category') == $category->id ? 'bg-[#F87B1B] text-white' : 'hover:bg-gray-50 text-gray-700' }} transition">
                                    <span class="flex items-center gap-2 text-sm">
                                        @if ($category->slug === 'makanan-minuman')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 21.99V21h15.03v.99c0 .55-.45 1-1.01 1H2.01c-.56 0-1.01-.45-1.01-1zm15.03-7c0-8-15.03-8-15.03 0h15.03zM1.02 17h15v2h-15z"/>
                                            </svg>
                                        @elseif ($category->slug === 'elektronik')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/>
                                            </svg>
                                        @elseif ($category->slug === 'fashion-pakaian')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 4l-4-4-4 4H2v6l6-2v12h8V8l6 2V4h-6zm0 2.5l4 1.5v1.5l-4-1.5V6.5z"/>
                                            </svg>
                                        @elseif ($category->slug === 'kesehatan-kecantikan')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 10.57 2 9.14 3.43 7.71 2 5.57 4.14 4.14 2.71 2.71 4.14l1.43 1.43L2 7.71l1.43 1.43L2 10.57 3.43 12 7 8.43 15.57 17 12 20.57 13.43 22l1.43-1.43L16.29 22l2.14-2.14 1.43 1.43 1.43-1.43-1.43-1.43L22 16.29z"/>
                                            </svg>
                                        @elseif ($category->slug === 'rumah-tangga')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                            </svg>
                                        @elseif ($category->slug === 'olahraga-outdoor')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-5-8c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm4 4c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm2-6c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm2 2c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm1 4c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
                                            </svg>
                                        @endif
                                        <span class="font-medium">{{ $category->name }}</span>
                                    </span>
                                    @if ($category->products_count > 0)
                                        <span class="text-xs {{ request('category') == $category->id ? 'bg-white/20' : 'bg-gray-100 text-gray-600' }} px-2 py-0.5 rounded-full">
                                            {{ $category->products_count }}
                                        </span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Subcategories (jika kategori dipilih) -->
                    @if (request('category') && $subcategories->count() > 0)
                        <div class="mb-6 border-t border-gray-200 pt-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Subkategori</h3>
                            <div class="space-y-2">
                                @foreach ($subcategories as $subcategory)
                                    <a href="{{ route('catalog.index', array_filter(['category' => request('category'), 'subcategory' => $subcategory->id, 'search' => $search])) }}" 
                                       class="flex items-center justify-between p-2 rounded-lg {{ request('subcategory') == $subcategory->id ? 'bg-green-500 text-white' : 'hover:bg-gray-50 text-gray-700' }} transition">
                                        <span class="text-sm font-medium">{{ $subcategory->name }}</span>
                                        @if ($subcategory->products_count > 0)
                                            <span class="text-xs {{ request('subcategory') == $subcategory->id ? 'bg-white/20' : 'bg-gray-100 text-gray-600' }} px-2 py-0.5 rounded-full">
                                                {{ $subcategory->products_count }}
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Tag Produk</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <a href="{{ route('catalog.index', array_filter(['tag' => $tag->id, 'category' => request('category'), 'subcategory' => request('subcategory'), 'search' => $search])) }}" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white transition hover:opacity-80 {{ request('tag') == $tag->id ? 'ring-2 ring-offset-2 ring-gray-400' : '' }}"
                                   style="background-color: {{ $tag->color }}">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                @if (!empty($search))
                    <div class="mb-6 text-sm text-gray-600">
                        Menampilkan hasil pencarian untuk <span class="font-semibold text-[#F87B1B]">"{{ $search }}"</span>. Ditemukan <span class="font-semibold text-gray-900">{{ $products->total() }}</span> produk.
                    </div>
                @else
                    <div class="mb-6 text-sm text-gray-600">
                        Menampilkan <span class="font-semibold text-gray-900">{{ $products->total() }}</span> produk.
                    </div>
                @endif

                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition overflow-hidden flex flex-col">
                    <a href="{{ route('catalog.show', $product) }}" class="relative overflow-hidden bg-gray-100 aspect-[4/3] block group">
                        @if ($product->gambar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($product->gambar) }}" alt="{{ $product->nama_barang }}" class="object-cover w-full h-full" loading="lazy">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-sm text-gray-500">Gambar belum tersedia</div>
                        @endif
                        
                        <!-- Tags on image -->
                        @if ($product->tags && $product->tags->count() > 0)
                            <div class="absolute top-2 right-2 flex flex-col gap-1">
                                @foreach ($product->tags->take(2) as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold text-white shadow-lg" style="background-color: {{ $tag->color }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                    <div class="p-4 flex-1 flex flex-col">
                        <!-- Category Badge -->
                        @if ($product->category)
                            <div class="mb-2">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $product->category->icon }} {{ $product->category->name }}
                                </span>
                            </div>
                        @endif

                        <a href="{{ route('catalog.show', $product) }}" class="text-lg font-semibold text-gray-900 hover:text-[#F87B1B] transition line-clamp-2">{{ $product->nama_barang }}</a>
                        
                        @if ($product->subcategory)
                            <p class="mt-1 text-xs text-gray-500">{{ $product->subcategory->name }}</p>
                        @endif
                        
                        <div class="mt-3 text-[#F87B1B] font-bold text-xl">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        <p class="mt-2 text-sm text-gray-500">Stok: <span class="font-semibold">{{ $product->stok }}</span></p>

                        <div class="mt-auto pt-4">
                            @auth
                                @if (in_array(auth()->user()->role, ['admin', 'pembeli']))
                                    <form method="POST" action="{{ route('cart.store') }}" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            </svg>
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-sm font-medium text-[#F87B1B] hover:underline">
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
                <div class="sm:col-span-2 xl:col-span-3 bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                    @if (!empty($search) || request('category') || request('subcategory') || request('tag'))
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a7 7 0 015.292 11.708l3 3a1 1 0 01-1.414 1.414l-3-3A7 7 0 119 2zm-5 7a5 5 0 1110 0 5 5 0 01-10 0z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak ada produk ditemukan</h3>
                        <p class="text-gray-500 mb-4">Tidak ada produk yang cocok dengan filter yang Anda pilih.</p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Reset Semua Filter
                        </a>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada produk</h3>
                        <p class="text-gray-500">Produk belum tersedia. Silakan kembali lagi nanti.</p>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
</section>
@endsection