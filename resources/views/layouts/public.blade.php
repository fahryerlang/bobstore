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

        <!-- Overlay Backdrop (untuk menutup sidebar saat klik di luar) -->
        <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-30"></div>

        <!-- SIDEBAR (FIXED POSITION) -->
        @php
            $produkIsActive = request()->routeIs('home');
            $dashboardIsActive = request()->routeIs('dashboard');
            $adminProductsIsActive = request()->routeIs('products.*');
            $kasirProductsIsActive = request()->routeIs('kasir.products.*');
            $kasirTransactionsIsActive = request()->routeIs('kasir.transactions.*');
            $kasirCustomersIsActive = request()->routeIs('kasir.customers.*');
            $salesReportIsActive = request()->routeIs('sales.report*');
            $cartIsActive = request()->routeIs('cart.*');
            $customerTransactionsIsActive = request()->routeIs('customer.transactions.*');
            $customerShopIsActive = request()->routeIs('customer.shop');
            $loginIsActive = request()->routeIs('login');
            $registerIsActive = request()->routeIs('register');
        @endphp
        <aside x-cloak x-show="sidebarOpen" x-transition class="fixed left-0 top-0 w-64 h-screen bg-gradient-to-b from-white to-gray-50 border-r border-gray-200 shadow-lg overflow-y-auto z-40">
            <!-- Header Sidebar dengan Logo dan Toggle Button -->
            <div class="bg-white border-b border-gray-200 p-4 flex items-center justify-between sticky top-0 z-10">
                <a href="{{ url('/') }}" @click="sidebarOpen = false" class="text-lg font-bold text-gray-900 hover:text-[#F87B1B] transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#F87B1B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="truncate">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <button @click="sidebarOpen = false" class="p-2 rounded-lg text-gray-600 hover:bg-[#F87B1B] hover:text-white transition focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 flex flex-col h-[calc(100vh-73px)]">
                <!-- Menu Utama -->
                <div class="flex-grow">
                    @guest
                        <!-- Tombol Produk hanya untuk yang belum login -->
                        <div>
                            <a href="{{ route('home') }}" @click="sidebarOpen = false" @class([
                                'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                'text-white bg-[#F87B1B] hover:opacity-90' => $produkIsActive,
                                'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$produkIsActive,
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a2 2 0 00-1.995 1.85L3 9v6a3 3 0 003 3h8a3 3 0 003-3V9a2 2 0 00-1.85-1.995L15 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-7 2v6a1 1 0 001 1h8a1 1 0 001-1V9H5z" clip-rule="evenodd" />
                                </svg>
                                Produk
                            </a>
                        </div>

                        <div class="mt-6 space-y-3">
                            <a href="{{ route('login') }}" @click="sidebarOpen = false" @class([
                                'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-bold rounded-lg transition shadow-md hover:shadow-lg',
                                'text-white bg-[#F87B1B] hover:opacity-90' => $loginIsActive,
                                'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$loginIsActive,
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $registerIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$registerIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                    </svg>
                                    Daftar sebagai Pembeli
                                </a>
                            @endif
                        </div>
                    @endguest

                    @auth
                        <div class="space-y-3">
                            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" @class([
                                'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                'text-white bg-[#F87B1B] hover:opacity-90' => $dashboardIsActive,
                                'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$dashboardIsActive,
                            ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                Dashboard
                            </a>

                            @if (in_array(auth()->user()->role, ['admin', 'kasir']))
                                <a href="{{ route('kasir.transactions.index') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $kasirTransactionsIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$kasirTransactionsIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm0 9a1 1 0 011-1h3a1 1 0 011 1v5H4a1 1 0 01-1-1v-4zm7 0a1 1 0 011-1h5a1 1 0 011 1v7h-7v-7z" clip-rule="evenodd" />
                                    </svg>
                                    Transaksi Penjualan
                                </a>

                                <a href="{{ route('kasir.customers.create') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $kasirCustomersIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$kasirCustomersIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 9a3 3 0 11-6 0 3 3 0 016 0zm8-3a3 3 0 11-6 0 3 3 0 016 0zM6 12a4 4 0 00-4 4 2 2 0 002 2h5a5 5 0 00-.964-2.946A3.978 3.978 0 006 12zm8 0a4 4 0 00-3.036 1.386A4.978 4.978 0 0117 16v2h1a2 2 0 002-2 4 4 0 00-4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Daftarkan Member
                                </a>
                            @endif

                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('products.index') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $adminProductsIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$adminProductsIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                    </svg>
                                    Kelola Produk
                                </a>
                            @elseif (auth()->user()->role === 'kasir')
                                <a href="{{ route('kasir.products.index') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $kasirProductsIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$kasirProductsIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                    </svg>
                                    Kelola Produk (Kasir)
                                </a>
                            @endif

                            @if (in_array(auth()->user()->role, ['admin', 'kasir']))
                                <a href="{{ route('sales.report') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $salesReportIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$salesReportIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm0 7a1 1 0 011-1h4a1 1 0 011 1v7H4a1 1 0 01-1-1v-6zm7 0a1 1 0 011-1h5a1 1 0 011 1v9h-7v-9z" clip-rule="evenodd" />
                                    </svg>
                                    Laporan Penjualan
                                </a>
                            @endif

                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('users.index') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => request()->routeIs('users.*'),
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !request()->routeIs('users.*'),
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                    Kelola Pengguna
                                </a>
                            @endif

                            @if (auth()->user()->role === 'pembeli')
                                <a href="{{ route('customer.shop') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $customerShopIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$customerShopIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-1 1h-3l.4 2H16a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-1.994-1.839L3.34 6H3a1 1 0 01-1-1V3zM7 16a2 2 0 104 0 2 2 0 00-4 0zm9 0a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd" />
                                    </svg>
                                    Belanja Produk
                                </a>

                                <a href="{{ route('cart.index') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $cartIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$cartIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                    Keranjang
                                </a>

                                <a href="{{ route('customer.transactions.index') }}" @click="sidebarOpen = false" @class([
                                    'flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg',
                                    'text-white bg-[#F87B1B] hover:opacity-90' => $customerTransactionsIsActive,
                                    'text-[#F87B1B] border-2 border-[#F87B1B] hover:bg-[#F87B1B] hover:text-white' => !$customerTransactionsIsActive,
                                ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 6a4 4 0 118 0v1h1.5A2.5 2.5 0 0118 9.5v6a2.5 2.5 0 01-2.5 2.5h-9A2.5 2.5 0 014 15.5v-6A2.5 2.5 0 016.5 7H8V6zm2 0a2 2 0 114 0v1H8V6zm0 5a1 1 0 012 0v3a1 1 0 01-2 0v-3z" clip-rule="evenodd" />
                                    </svg>
                                    Riwayat Transaksi
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold text-gray-600 border-2 border-gray-300 hover:border-red-400 hover:text-red-600 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Info User di Bagian Bawah (hanya untuk yang sudah login) -->
                @auth
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="rounded-xl bg-gradient-to-r from-[#F87B1B] to-orange-600 text-white p-4 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#F87B1B]" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs opacity-90 truncate">{{ auth()->user()->email }}</p>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-white/20 backdrop-blur-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </aside>

        <!-- MAIN CONTENT (dengan margin untuk sidebar) -->
        <main :class="sidebarOpen ? 'ml-64' : 'ml-0'" class="transition-all duration-300 min-h-screen">
            <!-- Tombol Toggle Sidebar (hanya muncul saat sidebar tertutup) -->
            <div x-show="!sidebarOpen" x-transition class="fixed left-4 top-4 z-30">
                <button @click="sidebarOpen = true" class="p-3 rounded-lg bg-white text-gray-600 hover:bg-[#F87B1B] hover:text-white transition focus:outline-none shadow-lg border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="bg-gradient-to-br from-white via-gray-50 to-blue-50 min-h-screen">
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
            </div>
        </main>

        <footer :class="sidebarOpen ? 'ml-64' : 'ml-0'" class="border-t border-gray-200 bg-white shadow-inner transition-all duration-300">
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
                    sidebarOpen: false,
                    flash: {
                        visible: false,
                        message: ''
                    },
                    flashTimeout: null,
                    init() {
                        // Tutup sidebar saat navigasi (untuk Livewire atau SPA)
                        window.addEventListener('popstate', () => {
                            this.sidebarOpen = false;
                        });
                    },
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
