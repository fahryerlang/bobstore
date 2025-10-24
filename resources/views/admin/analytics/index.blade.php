@extends('layouts.public')

@section('title', 'Dashboard Analitik')

@section('content')
<div class="min-h-screen bg-white py-10 px-4">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-[#F87B1B] font-semibold">Insight Cepat</p>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900">Dashboard Analitik</h1>
                <p class="mt-2 text-slate-600 max-w-3xl">Monitor performa bisnis harian dan mingguan secara visual. Temukan produk terlaris, pelanggan terbaik, dan jam operasional paling sibuk untuk keputusan yang tepat.</p>
            </div>
            <div class="flex flex-col md:items-end gap-2 text-right">
                <span class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Periode Analisis</span>
                <span class="text-lg font-bold text-slate-900">{{ $summary['period_label'] }}</span>
            </div>
        </div>

        <!-- Summary Cards -->
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

        <!-- Charts -->
        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Tren Pendapatan Harian</h2>
                        <p class="text-sm text-slate-500">7 hari terakhir</p>
                    </div>
                </div>
                <div class="mt-6">
                    <canvas id="dailyRevenueChart" class="w-full h-64"></canvas>
                </div>
            </div>
            <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Pendapatan Mingguan</h2>
                        <p class="text-sm text-slate-500">8 minggu terakhir</p>
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
                        <h2 class="text-lg font-semibold text-slate-900">Jam Operasional Paling Sibuk</h2>
                        <p class="text-sm text-slate-500">Berdasarkan 30 hari terakhir</p>
                    </div>
                </div>
                <div class="mt-6">
                    <canvas id="busyHoursChart" class="w-full h-64"></canvas>
                </div>
            </div>
            <div class="grid gap-6">
                <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Produk Terlaris</h2>
                    <ul class="space-y-4">
                        @forelse($topProducts as $product)
                            <li class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $product->product_name }}</p>
                                    <p class="text-sm text-slate-500">{{ number_format($product->total_quantity) }} unit · Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-slate-500">Belum ada data penjualan produk.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rounded-2xl bg-white border border-orange-100 p-6 shadow-lg">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Top Pelanggan</h2>
                    <ul class="space-y-4">
                        @forelse($topCustomers as $customer)
                            <li class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $customer->customer_name }}</p>
                                    <p class="text-sm text-slate-500">{{ number_format($customer->orders) }} pesanan · Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-slate-500">Belum ada data pelanggan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyLabels = @json($dailyChart['labels']);
    const dailyRevenue = @json($dailyChart['revenues']);
    const dailyQuantity = @json($dailyChart['quantities']);

    const weeklyLabels = @json($weeklyChart['labels']);
    const weeklyRevenue = @json($weeklyChart['revenues']);

    const busyHourLabels = @json($busyHoursChart['labels']);
    const busyHourOrders = @json($busyHoursChart['orders']);

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
@endsection
