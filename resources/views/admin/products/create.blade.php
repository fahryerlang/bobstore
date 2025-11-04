@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Produk Baru</h1>
                    <p class="text-gray-600">Lengkapi formulir di bawah untuk menambahkan produk baru</p>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-300 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-[#F87B1B] to-orange-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Informasi Produk</h2>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-red-800 font-semibold mb-2">Terdapat beberapa kesalahan:</h3>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Nama Barang -->
                    <div>
                        <label for="nama_barang" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_barang" 
                            id="nama_barang" 
                            value="{{ old('nama_barang') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('nama_barang') border-red-500 @enderror"
                            placeholder="Masukkan nama barang"
                            required
                        >
                        @error('nama_barang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label for="barcode" class="block text-sm font-semibold text-gray-700 mb-2">
                            Barcode/Kode Produk
                            <span class="text-gray-500 text-xs font-normal ml-1">(Opsional - untuk scan barcode)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="barcode" 
                                id="barcode" 
                                value="{{ old('barcode') }}" 
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('barcode') border-red-500 @enderror"
                                placeholder="Contoh: 8993176110074 (EAN-13, UPC, CODE-128)"
                                maxlength="50"
                            >
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Masukkan kode barcode produk (13 digit untuk EAN-13, atau format lain). Kosongkan jika tidak ada.
                        </p>
                        @error('barcode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                            <input 
                                type="number" 
                                name="harga" 
                                id="harga" 
                                value="{{ old('harga') }}" 
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('harga') border-red-500 @enderror"
                                placeholder="0"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>
                        @error('harga')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stok dan Satuan (Grid 2 columns) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Stok -->
                        <div>
                            <label for="stok" class="block text-sm font-semibold text-gray-700 mb-2">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="stok" 
                                id="stok" 
                                value="{{ old('stok') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('stok') border-red-500 @enderror"
                                placeholder="0"
                                min="0"
                                required
                            >
                            @error('stok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kategori dan Subkategori -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori
                            </label>
                            <select 
                                name="category_id" 
                                id="category_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('category_id') border-red-500 @enderror"
                                onchange="loadSubcategories(this.value)"
                            >
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subkategori -->
                        <div>
                            <label for="subcategory_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Subkategori
                            </label>
                            <select 
                                name="subcategory_id" 
                                id="subcategory_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('subcategory_id') border-red-500 @enderror"
                            >
                                <option value="">Pilih Subkategori</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->category_id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subcategory_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tag Produk
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($tags as $tag)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input 
                                        type="checkbox" 
                                        name="tags[]" 
                                        value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                    >
                                    <span class="ml-2 text-sm font-medium" style="color: {{ $tag->color }}">
                                        {{ $tag->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gambar Produk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Gambar Produk <span class="text-gray-500 text-xs font-normal">(Pilih metode input)</span>
                        </label>
                        
                        <!-- Toggle Image Input Type -->
                        <div class="flex gap-2 mb-4">
                            <button type="button" onclick="switchImageInput('upload')" id="btnUpload" class="flex-1 px-4 py-2 bg-[#F87B1B] text-white rounded-lg font-semibold transition">
                                Upload File
                            </button>
                            <button type="button" onclick="switchImageInput('url')" id="btnUrl" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                                Link URL
                            </button>
                        </div>

                        <input type="hidden" name="image_type" id="image_type" value="upload">

                        <!-- Upload File Section -->
                        <div id="uploadSection">
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-500 transition duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-[#F87B1B] hover:text-orange-600 focus-within:outline-none">
                                            <span>Upload gambar</span>
                                            <input 
                                                id="gambar" 
                                                name="gambar" 
                                                type="file" 
                                                class="sr-only" 
                                                accept="image/*"
                                                onchange="previewImage(event)"
                                            >
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                            </div>

                            <!-- Multiple Images Upload -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Tambahan (Opsional) <span class="text-xs text-gray-500">- Maks 5 gambar</span>
                                </label>
                                <input 
                                    type="file" 
                                    name="additional_images[]" 
                                    id="additional_images"
                                    multiple 
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-[#F87B1B] hover:file:bg-orange-100"
                                    onchange="previewAdditionalImages(event)"
                                >
                                <p class="mt-1 text-xs text-gray-500">Pilih hingga 5 gambar tambahan</p>
                            </div>
                        </div>

                        <!-- URL Input Section -->
                        <div id="urlSection" class="hidden">
                            <div class="space-y-3">
                                <div>
                                    <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Utama</label>
                                    <input 
                                        type="url" 
                                        name="image_url" 
                                        id="image_url"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        placeholder="https://example.com/image.jpg"
                                        onchange="previewUrlImage()"
                                    >
                                </div>

                                <div id="additionalUrlsContainer">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar Tambahan (Opsional)</label>
                                    <div id="urlInputs">
                                        <input 
                                            type="url" 
                                            name="additional_image_urls[]" 
                                            class="w-full px-4 py-2 mb-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                                            placeholder="https://example.com/image2.jpg"
                                        >
                                    </div>
                                    <button type="button" onclick="addUrlInput()" class="mt-2 text-sm text-[#F87B1B] hover:text-orange-600 font-semibold">
                                        + Tambah URL Gambar
                                    </button>
                                </div>
                            </div>
                        </div>

                        @error('gambar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('image_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Preview Gambar Utama:</p>
                            <img id="preview" src="" alt="Preview" class="max-w-xs rounded-lg shadow-md border border-gray-200">
                        </div>

                        <!-- Additional Images Preview -->
                        <div id="additionalPreview" class="mt-4 hidden">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Preview Gambar Tambahan:</p>
                            <div id="additionalPreviewContainer" class="grid grid-cols-2 md:grid-cols-5 gap-2"></div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Diskon Produk (Opsional)
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">Tambahkan diskon untuk produk ini. Diskon akan otomatis diterapkan saat pelanggan membeli produk.</p>
                    </div>

                    <!-- Enable Discount Toggle -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="enable_discount" 
                            name="enable_discount"
                            value="1"
                            {{ old('enable_discount') ? 'checked' : '' }}
                            class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                            onchange="toggleDiscountFields()"
                        >
                        <label for="enable_discount" class="ml-2 text-sm font-semibold text-gray-700">
                            Aktifkan Diskon untuk Produk Ini
                        </label>
                    </div>

                    <!-- Discount Fields (Hidden by default) -->
                    <div id="discountFields" class="space-y-6 {{ old('enable_discount') ? '' : 'hidden' }}">
                        <!-- Nama Diskon -->
                        <div>
                            <label for="discount_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Diskon
                            </label>
                            <input 
                                type="text" 
                                name="discount_name" 
                                id="discount_name" 
                                value="{{ old('discount_name') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                placeholder="Contoh: Diskon Spesial Produk"
                            >
                        </div>

                        <!-- Tipe dan Nilai Diskon -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tipe Diskon -->
                            <div>
                                <label for="discount_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tipe Diskon
                                </label>
                                <select 
                                    name="discount_type" 
                                    id="discount_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                    onchange="updateDiscountPlaceholder()"
                                >
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                </select>
                            </div>

                            <!-- Nilai Diskon -->
                            <div>
                                <label for="discount_value" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nilai Diskon
                                </label>
                                <div class="relative">
                                    <span id="discount_prefix" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold"></span>
                                    <input 
                                        type="number" 
                                        name="discount_value" 
                                        id="discount_value" 
                                        value="{{ old('discount_value') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                        placeholder="0"
                                        min="0"
                                        step="0.01"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Min Quantity dan Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Minimum Quantity -->
                            <div>
                                <label for="min_quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jumlah Minimum Pembelian
                                </label>
                                <input 
                                    type="number" 
                                    name="min_quantity" 
                                    id="min_quantity" 
                                    value="{{ old('min_quantity', 1) }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                    placeholder="1"
                                    min="1"
                                >
                                <p class="mt-1 text-xs text-gray-500">Diskon berlaku jika pembelian minimal sejumlah ini</p>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Prioritas
                                </label>
                                <input 
                                    type="number" 
                                    name="priority" 
                                    id="priority" 
                                    value="{{ old('priority', 0) }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                    placeholder="0"
                                    min="0"
                                >
                                <p class="mt-1 text-xs text-gray-500">Semakin tinggi angka, semakin tinggi prioritas</p>
                            </div>
                        </div>

                        <!-- Tanggal Mulai dan Selesai -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="starts_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input 
                                    type="datetime-local" 
                                    name="starts_at" 
                                    id="starts_at" 
                                    value="{{ old('starts_at') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                >
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika ingin langsung aktif</p>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="ends_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Selesai
                                </label>
                                <input 
                                    type="datetime-local" 
                                    name="ends_at" 
                                    id="ends_at" 
                                    value="{{ old('ends_at') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                >
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batas waktu</p>
                            </div>
                        </div>

                        <!-- Deskripsi Diskon -->
                        <div>
                            <label for="discount_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi Diskon
                            </label>
                            <textarea 
                                name="discount_description" 
                                id="discount_description" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                                placeholder="Deskripsi opsional tentang diskon ini"
                            >{{ old('discount_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('products.index') }}" 
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-300 shadow-md hover:shadow-lg"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#F87B1B] to-orange-600 hover:from-orange-600 hover:to-[#F87B1B] text-white font-semibold rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:scale-105"
                    >
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Produk
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Switch between upload and URL input modes
    function switchImageInput(mode) {
        const uploadSection = document.getElementById('uploadSection');
        const urlSection = document.getElementById('urlSection');
        const imageTypeInput = document.getElementById('image_type');
        const btnUpload = document.getElementById('btnUpload');
        const btnUrl = document.getElementById('btnUrl');
        
        if (mode === 'upload') {
            uploadSection.classList.remove('hidden');
            urlSection.classList.add('hidden');
            imageTypeInput.value = 'upload';
            btnUpload.classList.add('bg-[#F87B1B]', 'text-white');
            btnUpload.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            btnUrl.classList.remove('bg-[#F87B1B]', 'text-white');
            btnUrl.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        } else {
            uploadSection.classList.add('hidden');
            urlSection.classList.remove('hidden');
            imageTypeInput.value = 'url';
            btnUrl.classList.add('bg-[#F87B1B]', 'text-white');
            btnUrl.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            btnUpload.classList.remove('bg-[#F87B1B]', 'text-white');
            btnUpload.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        }
    }

    // Preview main uploaded image
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Preview additional uploaded images
    function previewAdditionalImages(event) {
        const files = event.target.files;
        const container = document.getElementById('additionalPreviewContainer');
        const previewSection = document.getElementById('additionalPreview');
        
        container.innerHTML = ''; // Clear previous previews
        
        if (files.length > 0) {
            previewSection.classList.remove('hidden');
            
            Array.from(files).forEach((file, index) => {
                if (index < 5) { // Limit to 5 images
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'relative group';
                        imgWrapper.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg shadow-md border border-gray-200">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                <span class="text-white text-xs font-semibold">Gambar ${index + 1}</span>
                            </div>
                        `;
                        container.appendChild(imgWrapper);
                    }
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewSection.classList.add('hidden');
        }
    }

    // Preview main URL image
    function previewUrlImage() {
        const urlInput = document.getElementById('image_url');
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        
        if (urlInput.value) {
            preview.src = urlInput.value;
            previewContainer.classList.remove('hidden');
            
            // Handle image load error
            preview.onerror = function() {
                alert('Gagal memuat gambar dari URL. Pastikan URL valid dan dapat diakses.');
                previewContainer.classList.add('hidden');
            };
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    // Add more URL input fields
    let urlInputCount = 1;
    function addUrlInput() {
        if (urlInputCount >= 5) {
            alert('Maksimal 5 gambar tambahan');
            return;
        }
        
        const container = document.getElementById('urlInputs');
        const newInput = document.createElement('input');
        newInput.type = 'url';
        newInput.name = 'additional_image_urls[]';
        newInput.className = 'w-full px-4 py-2 mb-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500';
        newInput.placeholder = `https://example.com/image${urlInputCount + 1}.jpg`;
        
        container.appendChild(newInput);
        urlInputCount++;
    }

    function loadSubcategories(categoryId) {
        const subcategorySelect = document.getElementById('subcategory_id');
        const options = subcategorySelect.querySelectorAll('option');
        
        // Reset subcategory select
        subcategorySelect.value = '';
        
        // Show/hide options based on selected category
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const optionCategory = option.getAttribute('data-category');
            if (categoryId === '' || optionCategory === categoryId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    function toggleDiscountFields() {
        const checkbox = document.getElementById('enable_discount');
        const fields = document.getElementById('discountFields');
        
        if (checkbox.checked) {
            fields.classList.remove('hidden');
        } else {
            fields.classList.add('hidden');
        }
    }

    function updateDiscountPlaceholder() {
        const type = document.getElementById('discount_type').value;
        const prefix = document.getElementById('discount_prefix');
        const input = document.getElementById('discount_value');
        
        if (type === 'percentage') {
            prefix.textContent = '';
            input.placeholder = '0-100';
            input.max = '100';
            input.classList.remove('pl-12');
            input.classList.add('px-4');
        } else {
            prefix.textContent = 'Rp';
            input.placeholder = '0';
            input.removeAttribute('max');
            input.classList.remove('px-4');
            input.classList.add('pl-12');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        if (categorySelect.value) {
            loadSubcategories(categorySelect.value);
        }
        
        // Initialize discount placeholder
        updateDiscountPlaceholder();
    });
</script>
@endsection