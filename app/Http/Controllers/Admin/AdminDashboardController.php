<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
// use App\Models\Report; // Mở comment nếu bạn đã tạo Model Report
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. CÁC CHỈ SỐ TỔNG QUAN (Summary Cards)
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $pendingProducts = Product::where('status', 'pending')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('fee_amount');
        
        // Mặc định là 0 nếu bạn chưa làm bảng Report, hãy thay đổi logic nếu đã có bảng
        $unresolvedReports = 0; // Report::where('status', 'pending')->count();

        // 2. DỮ LIỆU BIỂU ĐỒ (6 tháng gần nhất)
        $chartLabels = [];
        $revenueData = [];
        $ordersData = [];
        $newUsersData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonths($i);
            $monthName = $month->format('m/Y');
            $chartLabels[] = $monthName;

            // Truy vấn dữ liệu theo từng tháng (Bạn có thể tối ưu bằng group by SQL sau)
            $revenueData[] = Order::where('status', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('fee_amount');

            $ordersData[] = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $newUsersData[] = User::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return view('admin.dashboard.index', compact(
            'totalUsers', 'totalProducts', 'pendingProducts', 
            'totalOrders', 'totalRevenue', 'unresolvedReports',
            'chartLabels', 'revenueData', 'ordersData', 'newUsersData'
        ));
    }
}