@extends('layouts.public')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full">
        <!-- Split Layout Container -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 min-h-[700px]">
                <!-- Left Side - Description -->
                <div class="bg-gradient-to-br from-[#F87B1B] to-orange-600 p-12 flex flex-col justify-center text-white relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
                    
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

                        <h2 class="text-4xl font-bold mb-6">Selamat Datang Kembali!</h2>
                        <p class="text-orange-100 text-lg mb-8 leading-relaxed">
                            Masuk ke akun Anda untuk mengakses katalog produk lengkap, mengelola keranjang belanja, dan menikmati pengalaman berbelanja yang mudah.
                        </p>

                        <!-- Features List -->
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Belanja Mudah</h3>
                                    <p class="text-orange-100 text-sm">Akses cepat ke semua produk favorit Anda</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Aman & Terpercaya</h3>
                                    <p class="text-orange-100 text-sm">Data Anda dilindungi dengan enkripsi</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="bg-white bg-opacity-20 p-2 rounded-lg mt-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Keranjang Tersimpan</h3>
                                    <p class="text-orange-100 text-sm">Lanjutkan belanja kapan saja</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="p-12 flex flex-col justify-center">
                    <div class="max-w-md mx-auto w-full">
                        <div class="mb-6">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">Masuk</h3>
                            <p class="text-gray-600">Pilih role dan masuk dengan akun Anda</p>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Masuk Sebagai
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" onclick="selectRole('admin')" id="role-admin" 
                                    class="role-btn px-3 py-2.5 text-xs font-semibold border-2 border-gray-300 text-gray-700 rounded-lg transition duration-200 flex flex-col items-center gap-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Admin</span>
                                </button>
                                <button type="button" onclick="selectRole('kasir')" id="role-kasir" 
                                    class="role-btn px-3 py-2.5 text-xs font-semibold border-2 border-gray-300 text-gray-700 rounded-lg transition duration-200 flex flex-col items-center gap-1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>Kasir</span>
                                </button>
                                <button type="button" onclick="selectRole('pembeli')" id="role-pembeli" 
                                    class="role-btn px-3 py-2.5 text-xs font-semibold border-2 border-[#F87B1B] bg-[#F87B1B] text-white rounded-lg transition duration-200 flex flex-col items-center gap-1 active">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Pembeli</span>
                                </button>
                            </div>
                            <input type="hidden" name="role_hint" id="role_hint" value="pembeli">
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ session('status') }}
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
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
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F87B1B] focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                                    placeholder="••••••••">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember & Forgot -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="remember" class="w-4 h-4 text-[#F87B1B] border-gray-300 rounded focus:ring-[#F87B1B] transition duration-200">
                                    <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900">Ingat saya</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#F87B1B] hover:text-orange-600 transition duration-200">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white font-bold py-3.5 px-4 rounded-xl hover:from-orange-600 hover:to-[#F87B1B] focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Masuk Sekarang
                            </button>
                        </form>

                        <!-- Register Link -->
                        <div class="mt-6 text-center">
                            <p class="text-gray-600 mb-3">Belum punya akun?</p>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center text-[#F87B1B] font-semibold hover:text-orange-600 transition duration-200 group">
                                    Daftar Sekarang
                                    <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Back to Home -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-[#F87B1B] transition duration-200 inline-flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Default state - tidak aktif */
.role-btn:not(.active):hover {
    border-color: #F87B1B !important;
    color: #F87B1B !important;
}

/* Active state hover */
.role-btn.active:hover {
    opacity: 0.9;
}
</style>

<script>
function selectRole(role) {
    // Remove active class from all buttons
    document.querySelectorAll('.role-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-[#F87B1B]', 'text-white', 'border-[#F87B1B]');
        btn.classList.add('border-gray-300', 'text-gray-700');
    });
    
    // Add active class to selected button
    const selectedBtn = document.getElementById('role-' + role);
    selectedBtn.classList.remove('border-gray-300', 'text-gray-700');
    selectedBtn.classList.add('active', 'bg-[#F87B1B]', 'text-white', 'border-[#F87B1B]');
    
    // Update hidden input
    document.getElementById('role_hint').value = role;
    
    // Update placeholder text based on role
    const emailInput = document.getElementById('email');
    if (role === 'admin') {
        emailInput.placeholder = 'admin@bobstore.com';
    } else if (role === 'kasir') {
        emailInput.placeholder = 'kasir@bobstore.com';
    } else {
        emailInput.placeholder = 'nama@email.com';
    }
}
</script>
@endsection