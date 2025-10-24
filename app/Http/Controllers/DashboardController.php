<?php

namespace App\Http\Controllers;

use App\Services\SalesAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request, SalesAnalyticsService $analyticsService)
    {
        $user = Auth::user();

        $analyticsData = null;
        if ($user && in_array($user->role, ['admin', 'kasir'])) {
            $analyticsData = [
                'summary' => $analyticsService->getSummary(),
                'dailyChart' => $analyticsService->getDailyMetrics(),
                'weeklyChart' => $analyticsService->getWeeklyMetrics(),
                'busyHoursChart' => $analyticsService->getBusyHoursMetrics(),
                'topProducts' => $analyticsService->getTopProducts(),
                'topCustomers' => $analyticsService->getTopCustomers(),
            ];
        }

        return view('dashboard', [
            'analyticsData' => $analyticsData,
        ]);
    }
}
