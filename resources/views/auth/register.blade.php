@extends('layouts.public')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full">
        <!-- Split Layout Container -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 min-h-[700px]">
                <!-- Left Side - Register Form -->
                <div class="p-12 flex flex-col justify-center order-2 md:order-1">
                    <div class="max-w-md mx-auto w-full">
                        <div class="mb-8">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun</h3>
                            <p class="text-gray-600">Bergabunglah dengan kami sekarang!</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap
                                </label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror"
                                    placeholder="Nama lengkap Anda">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                                    placeholder="nama@email.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password
                                </label>
                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                                    placeholder="Minimal 8 karakter">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Konfirmasi Password
                                </label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-transparent transition duration-200"
                                    placeholder="Ulangi password">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white font-bold py-3.5 px-4 rounded-xl hover:from-orange-600 hover:to-[#F87B1B] focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mt-6">
                                Daftar Sekarang
                            </button>
                        </form>

                        <!-- Login Link -->
                        <div class="mt-8 text-center">
                            <p class="text-gray-600 mb-3">Sudah punya akun?</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center text-[#F87B1B] font-semibold hover:text-orange-600 transition duration-200 group">
                                Masuk Sekarang
                                <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>

                        <!-- Back to Home -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-[#F87B1B] transition duration-200 inline-flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Description -->
                <div class="bg-gradient-to-br from-[#F87B1B] to-orange-600 p-12 flex flex-col justify-center text-white relative overflow-hidden order-1 md:order-2">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mt-32"></div>
                    <div class="absolute bottom-0 right-0 w-48 h-48 bg-white opacity-5 rounded-full -mr-24 -mb-24"></div>
                    
                    <div class="relative z-10">
                        <!-- Logo -->
                        <div class="flex items-center gap-3 mb-8">
                            <div class="bg-white p-3 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#F87B1B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold">{{ config('app.name', 'BobStore') }}</h1>
                        </div>

                        <h2 class="text-4xl font-bold mb-6">Mulai Pengalaman Belanja Anda!</h2>
                        <p class="text-orange-100 text-lg mb-8 leading-relaxed">
                            Daftarkan akun Anda sekarang dan nikmati kemudahan berbelanja online dengan berbagai keuntungan eksklusif untuk member kami.
                        </p>

                        <!-- Benefits List -->
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Gratis Pendaftaran</h3>
                                    <p class="text-orange-100 text-sm">Tanpa biaya apapun untuk membuat akun</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Akses Penuh Katalog</h3>
                                    <p class="text-orange-100 text-sm">Jelajahi semua produk yang tersedia</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Proses Cepat & Mudah</h3>
                                    <p class="text-orange-100 text-sm">Hanya perlu beberapa menit untuk mendaftar</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Data Terlindungi</h3>
                                    <p class="text-orange-100 text-sm">Keamanan informasi Anda prioritas kami</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 mt-10 pt-8 border-t border-white border-opacity-20">
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1">1000+</div>
                                <div class="text-orange-100 text-sm">Produk</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1">500+</div>
                                <div class="text-orange-100 text-sm">Member</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold mb-1">24/7</div>
                                <div class="text-orange-100 text-sm">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection