@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Header -->
        <div class="mb-12">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#F87B1B] via-orange-500 to-amber-600 px-8 py-12 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl">
                                <svg class="w-12 h-12 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-4xl font-bold text-white mb-2">
                                    Selamat Datang Kembali
                                </h1>
                                <p class="text-white/90 text-lg">
                                    {{ Auth::user()->name }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-6">
                            <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="mb-8 bg-white border-l-4 border-red-500 rounded-r-2xl shadow-lg overflow-hidden">
                <div class="p-6 flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Perhatian</h3>
                        <p class="text-gray-600">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Access Cards -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-[#F87B1B] to-orange-600 rounded-full"></div>
                Akses Cepat
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if(Auth::user()->role === 'admin')
                    <!-- Admin Cards -->
                    <a href="{{ route('kasir.products.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#F87B1B] transition-colors">Kelola Produk</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Tambah, edit, dan kelola semua produk di toko Anda</p>
                            <div class="mt-4 flex items-center text-[#F87B1B] font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Buka sekarang</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('home') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Halaman Utama</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Kembali ke beranda dan lihat tampilan toko</p>
                            <div class="mt-4 flex items-center text-blue-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Lihat beranda</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profile.show') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Profil Saya</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Kelola informasi dan keamanan akun Anda</p>
                            <div class="mt-4 flex items-center text-purple-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Edit profil</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                @elseif(Auth::user()->role === 'kasir')
                    <!-- Kasir Cards -->
                    <a href="{{ route('kasir.transactions.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM5 7h14l-1.5 9h-11zM9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#F87B1B] transition-colors">Transaksi Penjualan</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Mulai transaksi, hitung total, dan cetak struk pelanggan.</p>
                            <div class="mt-4 flex items-center text-[#F87B1B] font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Proses transaksi</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('kasir.products.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#F87B1B] transition-colors">Kelola Produk Kasir</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Cek stok, harga, dan informasi produk untuk transaksi</p>
                            <div class="mt-4 flex items-center text-[#F87B1B] font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Buka sekarang</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('kasir.customers.create') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 11a4 4 0 100-8 4 4 0 000 8zm0 0v10m8-9a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">Daftarkan Member</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Input pelanggan baru agar bisa dipilih sebagai member saat transaksi.</p>
                            <div class="mt-4 flex items-center text-emerald-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Tambah member</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profile.show') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Profil Saya</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Kelola informasi akun</p>
                            <div class="mt-4 flex items-center text-purple-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Edit profil</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                @else
                    <!-- Pembeli Cards -->
                    <a href="{{ route('customer.shop') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F87B1B] to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#F87B1B] transition-colors">Belanja Sekarang</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Jelajahi katalog produk pilihan</p>
                            <div class="mt-4 flex items-center text-[#F87B1B] font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Mulai belanja</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('customer.transactions.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a3 3 0 00-3-3H7a3 3 0 00-3 3v12a3 3 0 003 3h6a3 3 0 003-3v-2m3-5h-6m0 0l2 2m-2-2l-2 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">Riwayat Transaksi</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Lihat daftar pesanan yang pernah kamu lakukan dan detail barangnya.</p>
                            <div class="mt-4 flex items-center text-emerald-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Lihat riwayat</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('home') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Halaman Utama</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Kembali ke beranda</p>
                            <div class="mt-4 flex items-center text-blue-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Lihat beranda</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profile.show') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                        <div class="p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Profil Saya</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Kelola informasi akun</p>
                            <div class="mt-4 flex items-center text-purple-600 font-semibold text-sm">
                                <span class="group-hover:mr-2 transition-all">Edit profil</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
        @if (!empty($analyticsData))
            @php
                $summary = $analyticsData['summary'];
                $dailyChart = $analyticsData['dailyChart'];
                $weeklyChart = $analyticsData['weeklyChart'];
                $busyHoursChart = $analyticsData['busyHoursChart'];
                $topProducts = $analyticsData['topProducts'];
                $topCustomers = $analyticsData['topCustomers'];
            @endphp

            <div class="mb-12 space-y-10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">Analitik Penjualan</h2>
                        <p class="text-gray-600 max-w-2xl">Ringkasan performa bisnis membantu Anda mengambil keputusan lebih cepat berdasarkan data terbaru.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm uppercase tracking-[0.25em] text-gray-500 font-semibold">Periode</span>
                        <p class="text-lg font-bold text-gray-900">{{ $summary['period_label'] }}</p>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-[#F87B1B] text-white p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs uppercase tracking-[0.35em] font-semibold">Pendapatan</h3>
                            <svg class="w-8 h-8 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 0V4m0 2c-1.11 0-2.08.402-2.599 1M12 16v2m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M12 18c1.11 0 2.08-.402 2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-bold">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-white/80">Total pendapatan dalam {{ $summary['period_label'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs uppercase tracking-[0.35em] text-[#F87B1B] font-semibold">Pesanan</h3>
                            <svg class="w-8 h-8 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2h-3V3a2 2 0 00-2-2H9a2 2 0 00-2 2v2H4a2 2 0 00-2 2v11a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($summary['orders']) }}</p>
                        <p class="mt-1 text-sm text-slate-600">Pesanan tercatat pada periode ini</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs uppercase tracking-[0.35em] text-[#F87B1B] font-semibold">Produk Terjual</h3>
                            <svg class="w-8 h-8 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2m0 0L7 16a2 2 0 002 2h6a2 2 0 001.994-1.839L18 7H5.4m0 0L4 5m4 13a2 2 0 104 0" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($summary['items']) }}</p>
                        <p class="mt-1 text-sm text-slate-600">Unit produk terjual</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs uppercase tracking-[0.35em] text-[#F87B1B] font-semibold">Rata-Rata Order</h3>
                            <svg class="w-8 h-8 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 11V7a1 1 0 112 0v4h3a1 1 0 010 2h-3v4a1 1 0 11-2 0v-4H8a1 1 0 010-2h3z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-slate-900">Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-slate-600">Nilai rata-rata per pesanan</p>
                    </div>
                </div>

                <div class="grid gap-6 xl:grid-cols-2">
                    <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Tren Pendapatan Harian</h3>
                                <p class="text-sm text-gray-500">7 hari terakhir</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <canvas id="dailyRevenueChart" class="w-full h-64"></canvas>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Pendapatan Mingguan</h3>
                                <p class="text-sm text-gray-500">8 minggu terakhir</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <canvas id="weeklyRevenueChart" class="w-full h-64"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 xl:grid-cols-2">
                    <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Jam Operasional Paling Sibuk</h3>
                                <p class="text-sm text-gray-500">Berdasarkan 30 hari terakhir</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <canvas id="busyHoursChart" class="w-full h-64"></canvas>
                        </div>
                    </div>
                    <div class="grid gap-6">
                        <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Produk Terlaris</h3>
                            </div>
                            <ul class="space-y-4">
                                @forelse ($topProducts as $product)
                                    <li class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $product->product_name }}</p>
                                            <p class="text-sm text-gray-500">{{ number_format($product->total_quantity) }} unit · Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">Belum ada data penjualan produk.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Top Pelanggan</h3>
                            </div>
                            <ul class="space-y-4">
                                @forelse ($topCustomers as $customer)
                                    <li class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $customer->customer_name }}</p>
                                            <p class="text-sm text-gray-500">{{ number_format($customer->orders) }} pesanan · Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500">Belum ada data pelanggan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 px-8 py-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Informasi Akun</h2>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Nama Lengkap
                        </div>
                        <p class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Alamat Email
                        </div>
                        <p class="text-xl font-bold text-gray-900">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Role / Peran
                        </div>
                        <div>
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-md
                                {{ Auth::user()->role === 'admin' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white' : '' }}
                                {{ Auth::user()->role === 'kasir' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : '' }}
                                {{ Auth::user()->role === 'pembeli' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Akun
                        </div>
                        <div>
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            @if (!empty($analyticsData))
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const dailyLabels = @json($analyticsData['dailyChart']['labels']);
                    const dailyRevenue = @json($analyticsData['dailyChart']['revenues']);
                    const dailyQuantity = @json($analyticsData['dailyChart']['quantities']);

                    const weeklyLabels = @json($analyticsData['weeklyChart']['labels']);
                    const weeklyRevenue = @json($analyticsData['weeklyChart']['revenues']);

                    const busyHourLabels = @json($analyticsData['busyHoursChart']['labels']);
                    const busyHourOrders = @json($analyticsData['busyHoursChart']['orders']);

                    const currencyFormatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0,
                    });

                    const dailyCtx = document.getElementById('dailyRevenueChart');
                    if (dailyCtx) {
                        new Chart(dailyCtx, {
                            type: 'line',
                            data: {
                                labels: dailyLabels,
                                datasets: [
                                    {
                                        label: 'Pendapatan',
                                        data: dailyRevenue,
                                        borderColor: '#F87B1B',
                                        backgroundColor: 'rgba(248, 123, 27, 0.15)',
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointRadius: 5,
                                        pointBackgroundColor: '#F87B1B',
                                    },
                                    {
                                        label: 'Produk Terjual',
                                        data: dailyQuantity,
                                        borderColor: '#0EA5E9',
                                        backgroundColor: 'rgba(14, 165, 233, 0.15)',
                                        borderDash: [6, 6],
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4,
                                        pointRadius: 4,
                                        pointBackgroundColor: '#0EA5E9',
                                        yAxisID: 'y1',
                                    },
                                ],
                            },
                            options: {
                                maintainAspectRatio: false,
                                interaction: { intersect: false, mode: 'index' },
                                plugins: {
                                    legend: { display: true },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                if (context.dataset.label === 'Pendapatan') {
                                                    return `${context.dataset.label}: ${currencyFormatter.format(context.parsed.y)}`;
                                                }
                                                return `${context.dataset.label}: ${context.parsed.y}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            callback: value => currencyFormatter.format(value),
                                            color: '#475569',
                                        },
                                        grid: { color: '#E2E8F0' },
                                    },
                                    y1: {
                                        position: 'right',
                                        grid: { drawOnChartArea: false },
                                        ticks: { color: '#0EA5E9' },
                                    },
                                    x: {
                                        ticks: { color: '#475569' },
                                        grid: { display: false },
                                    },
                                },
                            },
                        });
                    }

                    const weeklyCtx = document.getElementById('weeklyRevenueChart');
                    if (weeklyCtx) {
                        new Chart(weeklyCtx, {
                            type: 'bar',
                            data: {
                                labels: weeklyLabels,
                                datasets: [
                                    {
                                        label: 'Pendapatan Mingguan',
                                        data: weeklyRevenue,
                                        backgroundColor: '#F87B1B',
                                        borderRadius: 10,
                                        maxBarThickness: 40,
                                    }
                                ],
                            },
                            options: {
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: context => currencyFormatter.format(context.parsed.y),
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            callback: value => currencyFormatter.format(value),
                                            color: '#475569',
                                        },
                                        grid: { color: '#E2E8F0' },
                                    },
                                    x: {
                                        ticks: { color: '#475569' },
                                        grid: { display: false },
                                    },
                                },
                            },
                        });
                    }

                    const busyCtx = document.getElementById('busyHoursChart');
                    if (busyCtx) {
                        new Chart(busyCtx, {
                            type: 'bar',
                            data: {
                                labels: busyHourLabels,
                                datasets: [
                                    {
                                        label: 'Jumlah Pesanan',
                                        data: busyHourOrders,
                                        backgroundColor: '#F87B1B',
                                        borderRadius: 6,
                                        maxBarThickness: 24,
                                    }
                                ],
                            },
                            options: {
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: context => `${context.parsed.y} pesanan`,
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: { color: '#475569', precision: 0 },
                                        grid: { color: '#E2E8F0' },
                                    },
                                    x: {
                                        ticks: { color: '#475569' },
                                        grid: { display: false },
                                    },
                                },
                            },
                        });
                    }
                </script>
            @endif
            @endsection
