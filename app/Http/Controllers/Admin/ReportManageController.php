<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Product;
use App\Notifications\ProductLockedNotification;
use Illuminate\Http\Request;

class ReportManageController extends Controller
{
    // Hiển thị danh sách báo cáo
    public function index(Request $request)
    {
        // Khởi tạo query kèm theo các table liên kết để tối ưu hiệu suất (Eager Loading)
        $query = Report::with(['user', 'product.user']);

        // 1. Xử lý Tìm kiếm (Theo lý do báo cáo, chi tiết, hoặc tên người báo cáo)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reason', 'LIKE', "%{$search}%")
                  ->orWhere('details', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 2. Xử lý Bộ lọc Trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Xuất dữ liệu ra view
        $reports = $query->latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    // Xử lý báo cáo (Khóa, Bỏ qua, Mở khóa, Khôi phục)
    public function handle(Request $request, Report $report)
    {
        $action = $request->action; // Lấy hành động từ form HTML gửi lên

        // LUỒNG 1: KHÓA SẢN PHẨM
        if ($action === 'lock') {
            $product = $report->product;
            
            if ($product) {
                // ĐÃ FIX: Dùng chữ 'locked' thay vì số '0'
                $product->update(['status' => 'locked']); 
                
                // Gửi thông báo cho người bán
                $product->user->notify(new \App\Notifications\ProductLockedNotification($product, $report->reason));
            }

            // Đóng tất cả các báo cáo liên quan đến sản phẩm này thành "Đã xử lý"
            Report::where('product_id', $report->product_id)->update(['status' => 'resolved']);

            return back()->with('success', 'Đã khóa sản phẩm thành công và gửi thông báo cho người bán!');
            
        } 
        // LUỒNG 2: BỎ QUA BÁO CÁO (Tin rác, báo cáo sai)
        elseif ($action === 'dismiss') {
            $report->update(['status' => 'dismissed']);
            return back()->with('success', 'Đã bỏ qua báo cáo này do không phát hiện vi phạm.');
            
        } 
        // LUỒNG 3: MỞ KHÓA SẢN PHẨM (Khôi phục nếu lỡ khóa nhầm)
        elseif ($action === 'unlock') {
            $product = $report->product;
            
            if ($product) {
                // ĐÃ FIX: Khôi phục trạng thái sản phẩm về chữ 'approved' (Đang bán) thay vì số '1'
                $product->update(['status' => 'approved']); 
            }

            // Đưa báo cáo về lại trạng thái Chờ xử lý để theo dõi thêm
            $report->update(['status' => 'pending']);

            return back()->with('success', 'Đã mở khóa, khôi phục sản phẩm thành công!');
            
        } 
        // LUỒNG 4: KHÔI PHỤC BÁO CÁO (Nếu lỡ bấm bỏ qua nhầm)
        elseif ($action === 'revert') {
            $report->update(['status' => 'pending']);
            return back()->with('success', 'Đã khôi phục báo cáo về trạng thái Chờ xử lý!');
        }

        // Bắt lỗi nếu hành động không nằm trong 4 luồng trên
        return back()->with('error', 'Hành động không hợp lệ hệ thống.');
    }
}