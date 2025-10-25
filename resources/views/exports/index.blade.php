@extends('layouts.public')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Ekspor Data</h1>
                <p class="text-gray-600 mt-2">Unduh laporan data produk, penjualan, dan customer dalam format Excel atau PDF</p>
            </div>
            
            <!-- Export Products -->
            <div class="bg-white overflow-hidden shadow-xl rounded-3xl border border-orange-100">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Data Produk</h3>
                            <p class="text-sm text-gray-600 mt-1">Ekspor seluruh data produk ke Excel atau PDF</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('export.products.excel') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z" />
                                <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                            </svg>
                            <span>Unduh Excel (CSV)</span>
                        </a>
                        
                        <a href="{{ route('export.products.pdf') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                            </svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Export Sales -->
            <div class="bg-white overflow-hidden shadow-xl rounded-3xl border border-orange-100">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Data Penjualan</h3>
                            <p class="text-sm text-gray-600 mt-1">Ekspor laporan penjualan dengan filter tanggal</p>
                        </div>
                    </div>
                    
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" id="sales-start-date" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                            <input type="date" id="sales-end-date" class="w-full rounded-xl border-gray-300 focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="#" 
                           onclick="event.preventDefault(); downloadSalesExcel();"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z" />
                                <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                            </svg>
                            <span>Unduh Excel (CSV)</span>
                        </a>
                        
                        <a href="#" 
                           onclick="event.preventDefault(); downloadSalesPdf();"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                            </svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>

                    <script>
                        function downloadSalesExcel() {
                            const startDate = document.getElementById('sales-start-date').value;
                            const endDate = document.getElementById('sales-end-date').value;
                            console.log('Excel - Start:', startDate, 'End:', endDate);
                            const url = '{{ route('export.sales.excel') }}?start_date=' + startDate + '&end_date=' + endDate;
                            console.log('URL:', url);
                            window.location.href = url;
                        }

                        function downloadSalesPdf() {
                            const startDate = document.getElementById('sales-start-date').value;
                            const endDate = document.getElementById('sales-end-date').value;
                            console.log('PDF - Start:', startDate, 'End:', endDate);
                            const url = '{{ route('export.sales.pdf') }}?start_date=' + startDate + '&end_date=' + endDate;
                            console.log('URL:', url);
                            window.location.href = url;
                        }
                    </script>
                </div>
            </div>

            <!-- Export Customers -->
            <div class="bg-white overflow-hidden shadow-xl rounded-3xl border border-orange-100">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Data Customer</h3>
                            <p class="text-sm text-gray-600 mt-1">Ekspor seluruh data customer/pembeli ke Excel atau PDF</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('export.customers.excel') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z" />
                                <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                            </svg>
                            <span>Unduh Excel (CSV)</span>
                        </a>
                        
                        <a href="{{ route('export.customers.pdf') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                            </svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
