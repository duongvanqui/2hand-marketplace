<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Report;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminDashboardController extends Controller
{
    /**
     * Hiển thị trang giao diện Tổng quan hệ thống
     */
    public function index(Request $request)
    {
        $data = $this->getDashboardData($request);
        return view('admin.dashboard.index', $data);
    }

    /**
     * Hàm xử lý xuất báo cáo tổng quan ra file PDF
     */
    public function exportPDF(Request $request)
    {
        $data = $this->getDashboardData($request);
        
        // Khởi tạo và tải view cấu trúc PDF chuyên dụng
        $pdf = Pdf::loadView('admin.exports.dashboard_pdf', $data);
        
        return $pdf->download('bao_cao_tong_quan_he_thong_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Hàm trung gian tập hợp dữ liệu thống kê (Dùng chung cho cả View và PDF)
     */
    private function getDashboardData(Request $request)
    {
        // Nhận tham số ngày lọc từ request, mặc định lấy dữ liệu 7 ngày qua
        $days = $request->input('days', 7);
        
        // 1. Thu thập số liệu cho 6 khối thẻ (Summary Cards) ở trên cùng
        $stats = [
            'totalUsers'        => User::count(),
            'totalProducts'     => Product::count(),
            'pendingProducts'   => Product::where('status', 'pending')->count(),
            'totalOrders'       => Order::count(),
            'totalRevenue'      => Order::where('status', 'completed')->sum('fee_amount'),
            'unresolvedReports' => Report::where('status', 'pending')->count(),
        ];

        // Khởi tạo các mảng chứa cấu trúc dữ liệu cho biểu đồ Chart.js
        $chartLabels     = [];
        $revenueData     = [];
        $ordersData      = [];
        $newUsersData    = [];

        // Khởi tạo các biến tích lũy tổng số lượng hiển thị dưới chân biểu đồ
        $chartTotalRevenue  = 0;
        $chartTotalOrders   = 0;
        $chartTotalProducts = 0;

        if ($days == 365) {
            // Trường hợp lọc 1 năm: Chia biểu đồ thành chuỗi dữ liệu 12 tháng gần nhất
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::today()->startOfMonth()->subMonths($i);
                $chartLabels[] = $date->format('m/Y');

                // Lấy tổng phí sàn nhận được trong tháng
                $rev = Order::where('status', 'completed')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('fee_amount');

                // Lấy tổng số lượng đơn hàng phát sinh trong tháng
                $ord = Order::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                // Lấy tổng số lượng sản phẩm được đăng lên sàn trong tháng
                $prod = Product::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                $revenueData[]  = $rev;
                $ordersData[]   = $ord;
                $newUsersData[] = $prod;

                $chartTotalRevenue  += $rev;
                $chartTotalOrders   += $ord;
                $chartTotalProducts += $prod;
            }
        } else {
            // Trường hợp lọc 7 ngày hoặc 30 ngày: Chia biểu đồ chi tiết theo từng ngày
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chartLabels[] = $date->format('d/m');

                $rev = Order::where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('fee_amount');

                $ord = Order::whereDate('created_at', $date)->count();
                $prod = Product::whereDate('created_at', $date)->count();

                $revenueData[]  = $rev;
                $ordersData[]   = $ord;
                $newUsersData[] = $prod;

                $chartTotalRevenue  += $rev;
                $chartTotalOrders   += $ord;
                $chartTotalProducts += $prod;
            }
        }

        // 2. Lấy dữ liệu thực tế cho 3 bảng danh sách chi tiết ở đáy trang
        $recentPendingProducts = Product::with('user')->where('status', 'pending')->latest()->take(5)->get();
        
        // CHÚ Ý: Sử dụng đúng liên kết 'buyer' thay vì 'user' theo đúng thiết kế Model Order
        $recentOrders = Order::with('buyer')->latest()->take(5)->get();
        
        $recentReports = Report::with(['user', 'product'])->where('status', 'pending')->latest()->take(5)->get();

        // Gộp toàn bộ các mảng dữ liệu cấu trúc lại để gửi đi một lần duy nhất
        return array_merge($stats, [
            'chartLabels'            => $chartLabels,
            'revenueData'            => $revenueData,
            'ordersData'             => $ordersData,
            'newUsersData'           => $newUsersData,
            'chartTotalRevenue'      => $chartTotalRevenue,
            'chartTotalOrders'       => $chartTotalOrders,
            'chartTotalProducts'     => $chartTotalProducts,
            'recentPendingProducts'  => $recentPendingProducts,
            'recentOrders'           => $recentOrders,
            'recentReports'          => $recentReports,
            'days'                   => $days
        ]);
    }
}