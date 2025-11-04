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
                @php
                    // Gather all images using helper method
                    $allImages = $product->all_image_urls;
                    
                    $discountInfo = $product->getDiscountDisplayInfo();
                @endphp
                
                @if (count($allImages) > 1)
                    <!-- Image Carousel for Multiple Images -->
                    <div x-data="imageCarousel({{ json_encode($allImages) }})" class="relative">
                        <!-- Main Image Display -->
                        <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 aspect-[4/3] lg:aspect-auto lg:h-full lg:min-h-[520px] overflow-hidden">
                            <template x-for="(image, index) in images" :key="index">
                                <img 
                                    :src="image" 
                                    :alt="`{{ $product->nama_barang }} - Gambar ${index + 1}`"
                                    x-show="currentIndex === index"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    class="absolute inset-0 object-cover w-full h-full"
                                    loading="lazy"
                                >
                            </template>
                            
                            <!-- Discount Badge -->
                            @if ($discountInfo && $discountInfo['has_discount'])
                                <div class="absolute top-4 left-4 z-10">
                                    <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-4 py-3 rounded-xl shadow-2xl transform hover:scale-105 transition">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-bold text-2xl">{{ number_format($discountInfo['percentage'], 0) }}%</span>
                                        </div>
                                        <span class="text-xs font-bold block text-center uppercase tracking-widest">Diskon Spesial</span>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Navigation Arrows -->
                            <button 
                                @click="prev()" 
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition z-10"
                                :class="{ 'opacity-50 cursor-not-allowed': currentIndex === 0 }"
                                :disabled="currentIndex === 0"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button 
                                @click="next()" 
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition z-10"
                                :class="{ 'opacity-50 cursor-not-allowed': currentIndex === images.length - 1 }"
                                :disabled="currentIndex === images.length - 1"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            
                            <!-- Image Counter -->
                            <div class="absolute bottom-4 right-4 bg-black/60 text-white px-3 py-1 rounded-full text-sm font-semibold z-10">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </div>
                        </div>
                        
                        <!-- Thumbnail Strip -->
                        <div class="bg-gray-50 p-3 overflow-x-auto">
                            <div class="flex gap-2 min-w-max">
                                <template x-for="(image, index) in images" :key="index">
                                    <button 
                                        @click="goToImage(index)"
                                        class="relative w-20 h-20 rounded-lg overflow-hidden border-2 transition flex-shrink-0"
                                        :class="currentIndex === index ? 'border-[#F87B1B] ring-2 ring-[#F87B1B] ring-offset-2' : 'border-gray-300 hover:border-gray-400'"
                                    >
                                        <img :src="image" :alt="`Thumbnail ${index + 1}`" class="w-full h-full object-cover">
                                        <div 
                                            x-show="currentIndex === index"
                                            class="absolute inset-0 bg-[#F87B1B]/20"
                                        ></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Single Image Display -->
                    <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 aspect-[4/3] lg:aspect-auto lg:h-full lg:min-h-[520px]">
                        @if (count($allImages) > 0)
                            <img src="{{ $allImages[0] }}" alt="{{ $product->nama_barang }}" class="object-cover w-full h-full" loading="lazy">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Discount Badge on Single Image -->
                        @if ($discountInfo && $discountInfo['has_discount'])
                            <div class="absolute top-4 left-4">
                                <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-4 py-3 rounded-xl shadow-2xl transform hover:scale-105 transition">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-bold text-2xl">{{ number_format($discountInfo['percentage'], 0) }}%</span>
                                    </div>
                                    <span class="text-xs font-bold block text-center uppercase tracking-widest">Diskon Spesial</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->nama_barang }}</h1>
                @php
                    $pricing = $product->getDiscountDisplayInfo();
                @endphp
                @if ($pricing && $pricing['has_discount'])
                    <div class="mt-4 bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-xl border-2 border-orange-200">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-bold text-red-600 uppercase tracking-wide">Harga Spesial dengan Diskon!</span>
                        </div>
                        <div class="flex items-baseline gap-3 flex-wrap">
                            <span class="text-4xl font-extrabold text-[#F87B1B]">Rp {{ number_format($pricing['discounted_price'], 0, ',', '.') }}</span>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 line-through">Rp {{ number_format($pricing['base_price'], 0, ',', '.') }}</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-500 text-white text-xs font-bold shadow-md">
                                    HEMAT {{ number_format($pricing['percentage'], 0) }}%
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-600">
                            💰 Anda menghemat <span class="font-bold text-green-600">Rp {{ number_format($pricing['savings'], 0, ',', '.') }}</span> per item!
                        </div>
                    </div>
                @else
                    <div class="mt-4 text-3xl font-extrabold text-[#F87B1B]">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                @endif

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
                        @if (in_array(auth()->user()->role, ['admin', 'pembeli', 'customer']))
                            <div class="space-y-4">
                                <!-- Form untuk input jumlah yang shared -->
                                <div x-data="{ quantity: 1 }">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="quantity">Jumlah</label>
                                    <input
                                        id="quantity"
                                        x-model="quantity"
                                        type="number"
                                        min="1"
                                        max="{{ $product->stok }}"
                                        class="block w-32 rounded-lg border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm shadow-sm"
                                    >
                                    
                                    <div class="flex flex-wrap gap-3 mt-4">
                                        <!-- Form Tambah ke Keranjang -->
                                        <form method="POST" action="{{ route('cart.store') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" x-bind:value="quantity">
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                                </svg>
                                                Masukkan ke Keranjang
                                            </button>
                                        </form>

                                        <!-- Form Beli Sekarang -->
                                        <form method="POST" action="{{ route('cart.buyNow') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" x-bind:value="quantity">
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white rounded-lg transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                                </svg>
                                                Beli Sekarang
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                Beli Sekarang
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function imageCarousel(images) {
        return {
            images: images,
            currentIndex: 0,
            
            next() {
                if (this.currentIndex < this.images.length - 1) {
                    this.currentIndex++;
                }
            },
            
            prev() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                }
            },
            
            goToImage(index) {
                this.currentIndex = index;
            }
        }
    }
</script>
@endpush
