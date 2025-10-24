@extends('layouts.public')

@section('title', 'Transaksi Kasir')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Transaksi Penjualan</h1>
                <p class="text-sm text-gray-600 mt-1">Proses penjualan, pilih pelanggan, tambahkan produk, dan selesaikan pembayaran.</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-50 text-[#F87B1B]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2h2m3-3l2-2m0 0l2 2m-2-2v12" />
                    </svg>
                    <span>Scan barcode langsung di kolom pencarian</span>
                </div>
            </div>
        </div>

        <form id="transaction-form" method="POST" action="{{ route('kasir.transactions.store') }}" class="space-y-10">
            @csrf
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Pelanggan</h2>
                            <p class="text-sm text-gray-600">Pilih pelanggan terdaftar atau biarkan sebagai pelanggan umum.</p>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Pelanggan / Member</label>
                                <select id="customer_id" name="customer_id" class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm">
                                    <option value="">Umum</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Pelanggan baru? <a href="{{ route('kasir.customers.create') }}" class="font-semibold text-[#F87B1B] hover:underline">Daftarkan member</a>
                                </p>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs uppercase text-gray-500 tracking-[0.25em]">Kasir</p>
                                    <p class="text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-gray-500 tracking-[0.25em]">Tanggal</p>
                                    <p class="text-base font-semibold text-gray-900">{{ now()->isoFormat('DD MMMM YYYY HH:mm') }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Tambah Produk</h2>
                                <p class="text-sm text-gray-600">Cari nama produk atau scan barcode/ID produk.</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-4.215A2 2 0 0016.695 11H7.305a2 2 0 00-1.9 1.785L4 17h5m3-4v4" />
                                </svg>
                                <span>Tekan Enter setelah scan untuk menambahkan otomatis</span>
                            </div>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <div class="relative">
                                <input type="text" id="product-search" placeholder="Cari nama produk atau tempel hasil scan..." autocomplete="off" class="w-full rounded-2xl border-gray-200 focus:border-[#F87B1B] focus:ring-[#F87B1B] text-sm pr-12">
                                <button type="button" id="clear-search" class="absolute inset-y-0 right-0 px-4 text-gray-400 hover:text-[#F87B1B]">
                                    <span class="sr-only">Hapus pencarian</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div id="product-suggestions" class="absolute z-20 mt-2 w-full bg-white border border-orange-100 rounded-2xl shadow-2xl divide-y divide-orange-50 hidden"></div>
                            </div>

                            <div class="overflow-hidden rounded-3xl border border-orange-100">
                                <table class="min-w-full divide-y divide-orange-100">
                                    <thead class="bg-orange-50">
                                        <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            <th scope="col" class="px-4 py-3 text-left">Produk</th>
                                            <th scope="col" class="px-4 py-3 text-center">Harga</th>
                                            <th scope="col" class="px-4 py-3 text-center">Stok</th>
                                            <th scope="col" class="px-4 py-3 text-center">Qty</th>
                                            <th scope="col" class="px-4 py-3 text-right">Subtotal</th>
                                            <th scope="col" class="px-4 py-3 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body" class="divide-y divide-orange-50 bg-white">
                                        <tr id="empty-cart-row">
                                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada produk. Cari atau scan untuk menambahkan.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

                <section class="bg-white shadow-xl rounded-3xl border border-orange-100 overflow-hidden h-fit">
                    <div class="px-6 py-5 border-b border-orange-100 bg-orange-50/60">
                        <h2 class="text-lg font-semibold text-gray-900">Ringkasan Pembayaran</h2>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <dl class="space-y-4 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Subtotal</dt>
                                <dd id="summary-subtotal" class="font-semibold text-gray-900">Rp 0</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-500">Diskon</dt>
                                <dd class="text-right">
                                    <div class="flex items-center gap-2 justify-end">
                                        <span class="text-gray-400">Rp</span>
                                        <input type="number" min="0" step="500" name="discount" id="discount-input" value="0" class="w-32 rounded-xl border-gray-200 text-right focus:border-[#F87B1B] focus:ring-[#F87B1B]">
                                    </div>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between text-base">
                                <dt class="text-gray-700 font-semibold">Total Bayar</dt>
                                <dd id="summary-total" class="text-xl font-bold text-gray-900">Rp 0</dd>
                            </div>
                        </dl>

                        <div class="space-y-4">
                            <div>
                                <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">Rp</span>
                                    <input type="number" min="0" step="500" name="amount_paid" id="amount-paid-input" class="w-full rounded-2xl border-gray-200 pl-10 text-right focus:border-[#F87B1B] focus:ring-[#F87B1B]" placeholder="0" required>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Kembalian</span>
                                <span id="summary-change" class="text-lg font-semibold text-emerald-600">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#F87B1B] to-orange-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-orange-200/60 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F87B1B]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Selesaikan Transaksi
                        </button>
                        <p class="text-xs text-gray-400 text-center">Pastikan jumlah bayar sudah sesuai sebelum menyimpan transaksi.</p>
                    </div>
                </section>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const searchInput = document.getElementById('product-search');
    const clearSearchButton = document.getElementById('clear-search');
    const suggestionBox = document.getElementById('product-suggestions');
    const itemsBody = document.getElementById('items-body');
    const emptyRow = document.getElementById('empty-cart-row');
    const discountInput = document.getElementById('discount-input');
    const amountPaidInput = document.getElementById('amount-paid-input');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const summaryChange = document.getElementById('summary-change');
    const searchEndpoint = @json(route('kasir.transactions.search'));

    let debounceTimer = null;
    let rowCounter = 0;

    const formatCurrency = (value) => {
        return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(Math.max(value, 0));
    };

    const recalcTotals = () => {
        let subtotal = 0;
        itemsBody.querySelectorAll('tr[data-row-id]').forEach((row) => {
            subtotal += parseFloat(row.dataset.lineTotal || '0');
        });

        const discount = parseFloat(discountInput.value || '0');
        const total = Math.max(subtotal - discount, 0);
        const amountPaid = parseFloat(amountPaidInput.value || '0');
        const change = Math.max(amountPaid - total, 0);

        summarySubtotal.textContent = formatCurrency(subtotal);
        summaryTotal.textContent = formatCurrency(total);
        summaryChange.textContent = formatCurrency(change);
    };

    const ensureNotEmpty = () => {
        const hasItems = itemsBody.querySelectorAll('tr[data-row-id]').length > 0;
        emptyRow.classList.toggle('hidden', hasItems);
    };

    const updateRowTotals = (row, quantity, price) => {
        const unitPrice = (typeof price === 'number' && !Number.isNaN(price))
            ? price
            : parseFloat(row.dataset.unitPrice || '0');
        const subtotalCell = row.querySelector('[data-subtotal]');
        const lineTotal = quantity * unitPrice;
        row.dataset.lineTotal = lineTotal.toString();
        subtotalCell.textContent = formatCurrency(lineTotal);
        recalcTotals();
    };

    const addOrIncreaseProduct = (product) => {
        const existingRow = itemsBody.querySelector(`tr[data-product-id="${product.id}"]`);

        if (existingRow) {
            const qtyInput = existingRow.querySelector('input[data-quantity]');
            const currentQty = parseInt(qtyInput.value, 10);
            if (currentQty < product.stok) {
                qtyInput.value = currentQty + 1;
                updateRowTotals(existingRow, currentQty + 1, product.harga);
            }
            return;
        }

        const rowId = `row-${++rowCounter}`;
        const row = document.createElement('tr');
        row.dataset.rowId = rowId;
        row.dataset.productId = product.id;
        row.dataset.lineTotal = '0';
    row.dataset.unitPrice = product.harga;

        row.innerHTML = `
            <td class="px-4 py-4">
                <div class="font-semibold text-gray-900">${product.nama_barang}</div>
                <input type="hidden" name="items[${rowCounter}][product_id]" value="${product.id}">
            </td>
            <td class="px-4 py-4 text-center text-sm text-gray-700">${formatCurrency(product.harga)}</td>
            <td class="px-4 py-4 text-center text-xs text-gray-500">${product.stok}</td>
            <td class="px-4 py-4 text-center">
                <input type="number" min="1" max="${product.stok}" value="1" name="items[${rowCounter}][quantity]" data-quantity class="w-20 rounded-xl border-gray-200 text-center focus:border-[#F87B1B] focus:ring-[#F87B1B]">
            </td>
            <td class="px-4 py-4 text-right font-semibold text-gray-900" data-subtotal>Rp 0</td>
            <td class="px-4 py-4 text-center">
                <button type="button" class="inline-flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-100 h-9 w-9" data-remove>
                    <span class="sr-only">Hapus</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </td>
        `;

        itemsBody.appendChild(row);
        updateRowTotals(row, 1, product.harga);
        ensureNotEmpty();
    };

    const clearSuggestions = () => {
        suggestionBox.innerHTML = '';
        suggestionBox.classList.add('hidden');
    };

    const renderSuggestions = (products) => {
        if (!products.length) {
            clearSuggestions();
            return;
        }

        suggestionBox.innerHTML = products.map((product) => {
            const payload = encodeURIComponent(JSON.stringify(product));
            return `
            <button type="button" class="flex w-full items-center justify-between gap-4 px-4 py-3 text-left text-sm hover:bg-orange-50" data-product="${payload}">
                <span>
                    <span class="block font-semibold text-gray-900">${product.nama_barang}</span>
                    <span class="block text-xs text-gray-500">ID: ${product.id} · Stok: ${product.stok}</span>
                </span>
                <span class="text-sm font-semibold text-gray-900">${formatCurrency(product.harga)}</span>
            </button>
            `;
        }).join('');

        suggestionBox.classList.remove('hidden');
    };

    const fetchProducts = (keyword) => {
        const params = new URLSearchParams();
        if (keyword) {
            params.append('q', keyword);
        }

    fetch(`${searchEndpoint}?${params.toString()}`)
            .then((response) => response.json())
            .then((data) => renderSuggestions(data))
            .catch(() => clearSuggestions());
    };

    searchInput.addEventListener('input', (event) => {
        const keyword = event.target.value.trim();

        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        if (!keyword) {
            clearSuggestions();
            return;
        }

        debounceTimer = setTimeout(() => fetchProducts(keyword), 250);
    });

    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            const firstSuggestion = suggestionBox.querySelector('button[data-product]');
            if (firstSuggestion) {
                const product = JSON.parse(decodeURIComponent(firstSuggestion.dataset.product));
                addOrIncreaseProduct(product);
                searchInput.value = '';
                clearSuggestions();
            }
        }
    });

    clearSearchButton.addEventListener('click', () => {
        searchInput.value = '';
        clearSuggestions();
        searchInput.focus();
    });

    suggestionBox.addEventListener('mousedown', (event) => {
        const target = event.target.closest('button[data-product]');
        if (!target) {
            return;
        }
        event.preventDefault();
    const product = JSON.parse(decodeURIComponent(target.dataset.product));
        addOrIncreaseProduct(product);
        searchInput.value = '';
        clearSuggestions();
        searchInput.focus();
    });

    itemsBody.addEventListener('input', (event) => {
        if (event.target.matches('input[data-quantity]')) {
            const row = event.target.closest('tr[data-row-id]');
            const price = parseFloat(row.dataset.unitPrice || '0');
            let quantity = parseInt(event.target.value, 10) || 1;
            const max = parseInt(event.target.getAttribute('max') || '1', 10);
            if (quantity < 1) {
                quantity = 1;
            }
            if (quantity > max) {
                quantity = max;
                event.target.value = max;
            }
            updateRowTotals(row, quantity, price);
        }
    });

    itemsBody.addEventListener('click', (event) => {
        if (event.target.closest('[data-remove]')) {
            const row = event.target.closest('tr[data-row-id]');
            row.remove();
            ensureNotEmpty();
            recalcTotals();
        }
    });

    discountInput.addEventListener('input', recalcTotals);
    amountPaidInput.addEventListener('input', recalcTotals);

    document.addEventListener('click', (event) => {
        if (!suggestionBox.contains(event.target) && event.target !== searchInput) {
            clearSuggestions();
        }
    });
})();
</script>
@endpush
@endsection
