<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Katalog Produk') · {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>[x-cloak]{display:none !important;}</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-white text-gray-900 font-sans antialiased" x-data="layoutState()" x-on:notify.window="showFlash($event.detail)">
        <!-- Flash Notification -->
        <div x-cloak x-show="flash.visible" x-transition.opacity.duration.200ms class="fixed top-4 right-4 z-[60] max-w-sm w-full">
            <div class="rounded-xl border border-orange-200 bg-white shadow-lg ring-1 ring-orange-100/60 p-4 flex items-start gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100 text-[#F87B1B]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900" x-text="flash.message"></p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition" @click="flash.visible = false">
                    <span class="sr-only">Tutup</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- NAVBAR (Fixed Top) -->
        <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-bold text-gray-900 hover:text-[#F87B1B] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#F87B1B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="hidden sm:block">{{ config('app.name', 'Laravel') }}</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-[#F87B1B]' : 'text-gray-700 hover:text-[#F87B1B]' }} transition">
                            Beranda
                        </a>
                        <a href="{{ route('catalog.index') }}" class="text-sm font-semibold {{ request()->routeIs('catalog.*') ? 'text-[#F87B1B]' : 'text-gray-700 hover:text-[#F87B1B]' }} transition">
                            Katalog
                        </a>
                        
                        @auth
                            @if (auth()->user()->role === 'pembeli')
                                <a href="{{ route('cart.index') }}" class="text-sm font-semibold {{ request()->routeIs('cart.*') ? 'text-[#F87B1B]' : 'text-gray-700 hover:text-[#F87B1B]' }} transition flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                    Keranjang
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Right Side -->
                    <div class="hidden md:flex items-center gap-3">
                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-[#F87B1B] hover:text-orange-600 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition shadow-md hover:shadow-lg">
                                    Daftar
                                </a>
                            @endif
                        @endguest

                        @auth
                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                    <div class="w-8 h-8 bg-gradient-to-r from-[#F87B1B] to-orange-600 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="hidden lg:block text-left">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-500 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-cloak x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2">
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        Profil
                                    </a>
                                    <div class="border-t border-gray-200 my-2"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div x-cloak x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200 py-4 space-y-2">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'text-[#F87B1B] bg-orange-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
                        Beranda
                    </a>
                    <a href="{{ route('catalog.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('catalog.*') ? 'text-[#F87B1B] bg-orange-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
                        Katalog
                    </a>
                    
                    @auth
                        @if (auth()->user()->role === 'pembeli')
                            <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-sm font-semibold {{ request()->routeIs('cart.*') ? 'text-[#F87B1B] bg-orange-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
                                Keranjang
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            Profil
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-lg transition">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm font-semibold text-white bg-[#F87B1B] hover:opacity-90 rounded-lg transition text-center">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="min-h-screen">
            @if (session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 text-green-800 px-4 py-3 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-400 text-red-800 px-4 py-3 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-gray-200 bg-white shadow-inner">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 text-center flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#F87B1B]" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                © {{ now()->year }} {{ config('app.name', 'Laravel') }}. Semua hak dilindungi.
            </div>
        </footer>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('layoutState', () => ({
                    flash: {
                        visible: false,
                        message: ''
                    },
                    flashTimeout: null,
                    showFlash(message) {
                        this.flash.message = message;
                        this.flash.visible = true;
                        if (this.flashTimeout) {
                            clearTimeout(this.flashTimeout);
                        }
                        this.flashTimeout = setTimeout(() => {
                            this.flash.visible = false;
                            this.flashTimeout = null;
                        }, 3500);
                    }
                }));
            });
        </script>
        @stack('scripts')
    </body>
</html>
