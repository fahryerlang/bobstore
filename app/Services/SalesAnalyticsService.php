<?php

namespace App\Services;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesAnalyticsService
{
    public function getSummary(?Carbon $now = null): array
    {
        $now ??= Carbon::now();
        $start30 = $now->copy()->subDays(29)->startOfDay();

        $aggregate = Sale::selectRaw('SUM(total_price) as revenue, SUM(quantity) as items, COUNT(*) as orders')
            ->where('sale_date', '>=', $start30)
            ->first();

        $revenue = (float) ($aggregate->revenue ?? 0);
        $orders = (int) ($aggregate->orders ?? 0);
        $items = (int) ($aggregate->items ?? 0);
        $avgOrderValue = $orders > 0 ? $revenue / $orders : 0;

        return [
            'period_label' => '30 hari terakhir',
            'revenue' => $revenue,
            'orders' => $orders,
            'items' => $items,
            'avg_order_value' => $avgOrderValue,
        ];
    }

    public function getDailyMetrics(?Carbon $now = null): array
    {
        $now ??= Carbon::now();
        $start = $now->copy()->subDays(6)->startOfDay();
        $end = $now->copy()->endOfDay();

        $rows = Sale::selectRaw('DATE(sale_date) as day, SUM(total_price) as revenue, SUM(quantity) as quantity')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $revenues = [];
        $quantities = [];

        $cursor = $start->copy();
        while ($cursor <= $end) {
            $dayKey = $cursor->format('Y-m-d');
            $labels[] = $cursor->isoFormat('DD MMM');
            $revenues[] = (float) ($rows[$dayKey]->revenue ?? 0);
            $quantities[] = (int) ($rows[$dayKey]->quantity ?? 0);
            $cursor->addDay();
        }

        return compact('labels', 'revenues', 'quantities');
    }

    public function getWeeklyMetrics(?Carbon $now = null): array
    {
        $now ??= Carbon::now();
        $start = $now->copy()->startOfWeek()->subWeeks(7);
        $end = $now->copy()->endOfWeek();

        $rows = Sale::selectRaw('YEARWEEK(sale_date, 3) as year_week, SUM(total_price) as revenue')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('year_week')
            ->orderBy('year_week')
            ->get()
            ->keyBy('year_week');

        $labels = [];
        $revenues = [];

        $cursor = $start->copy();
        while ($cursor <= $end) {
            $weekKey = $cursor->format('oW');
            $weekStart = $cursor->copy();
            $weekEnd = $cursor->copy()->endOfWeek();

            $labels[] = $weekStart->isoFormat('DD MMM').'–'.$weekEnd->isoFormat('DD MMM');
            $revenues[] = (float) ($rows[$weekKey]->revenue ?? 0);

            $cursor->addWeek();
        }

        return compact('labels', 'revenues');
    }

    public function getBusyHoursMetrics(?Carbon $now = null): array
    {
        $now ??= Carbon::now();
        $start = $now->copy()->subDays(29)->startOfDay();
        $end = $now->copy()->endOfDay();

        $rows = Sale::selectRaw('HOUR(sale_date) as hour, COUNT(*) as orders')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $labels = [];
        $orders = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            $orders[] = (int) ($rows[$hour]->orders ?? 0);
        }

        return compact('labels', 'orders');
    }

    public function getTopProducts()
    {
        return Sale::select([
                'sales.product_id',
                DB::raw('SUM(sales.quantity) as total_quantity'),
                DB::raw('SUM(sales.total_price) as total_revenue'),
                'products.nama_barang as product_name',
            ])
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->groupBy('sales.product_id', 'products.nama_barang')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();
    }

    public function getTopCustomers()
    {
        return Sale::select([
                'sales.user_id',
                DB::raw('SUM(sales.total_price) as total_spent'),
                DB::raw('SUM(sales.quantity) as total_items'),
                DB::raw('COUNT(*) as orders'),
                'users.name as customer_name',
            ])
            ->leftJoin('users', 'sales.user_id', '=', 'users.id')
            ->groupBy('sales.user_id', 'users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->customer_name = $row->customer_name ?? 'Umum';
                return $row;
            });
    }
}
