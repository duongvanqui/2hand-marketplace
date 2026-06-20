<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách đơn hàng kèm thông tin Sản phẩm, Người bán, Người mua
        $query = Order::with(['product', 'seller', 'buyer'])->latest();

        // Chức năng tìm kiếm theo Mã đơn hàng hoặc Tên sản phẩm
        if ($search = $request->search) {
            $query->where('id', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
        }

        // Lọc theo trạng thái
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Thống kê nhanh để hiển thị lên thẻ (Card)
        $stats = [
            'total_orders'  => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('fee_amount'), // Tổng phí sàn thu được
            'pending'       => Order::whereIn('status', ['pending_shipping', 'paid_escrow'])->count(),
            'completed'     => Order::where('status', 'completed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = Order::with(['product', 'seller', 'buyer'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}