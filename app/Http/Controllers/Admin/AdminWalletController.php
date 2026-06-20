<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\DB; // BẮT BUỘC PHẢI KHAI BÁO ĐỂ DÙNG TRANSACTION

class AdminWalletController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tính tổng doanh thu phí sàn (3%) từ các đơn đã hoàn tất
        $totalRevenue = Order::where('status', 'completed')->sum('fee_amount');

        // 2. Lấy danh sách các yêu cầu rút tiền đang chờ duyệt (Có bộ lọc tìm kiếm)
        $queryPending = Withdrawal::with('user')->where('status', 'pending');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $queryPending->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhere('bank_info', 'LIKE', "%{$search}%");
        }
        
        $pendingWithdrawals = $queryPending->latest()->get();

        // 3. Lấy lịch sử các yêu cầu rút tiền đã xử lý (Giới hạn 20 dòng cho nhẹ web)
        $processedWithdrawals = Withdrawal::with('user')
                                ->whereIn('status', ['approved', 'rejected'])
                                ->latest()
                                ->take(20)
                                ->get();

        return view('admin.wallet.index', compact('totalRevenue', 'pendingWithdrawals', 'processedWithdrawals'));
    }

    /**
     * Hàm Duyệt rút tiền (Bọc Database Transaction & Lock hàng dữ liệu)
     */
    public function approve($id)
    {
        // Bọc toàn bộ trong Transaction, nếu có bất kỳ lỗi gì xảy ra hệ thống sẽ tự khôi phục dữ liệu gốc
        return DB::transaction(function () use ($id) {
            
            // Sử dụng lockForUpdate() để khóa dòng dữ liệu này lại, không cho phép luồng khác sửa đổi cho đến khi chạy xong
            $withdrawal = Withdrawal::where('id', $id)->lockForUpdate()->findOrFail($id);

            // Chốt chặn an toàn: Tránh lỗi click đúp duyệt trùng
            if ($withdrawal->status !== 'pending') {
                return redirect()->back()->with('error', 'Lệnh rút tiền này đã được xử lý trước đó!');
            }

            // Cập nhật trạng thái lệnh
            $withdrawal->update(['status' => 'approved']);

            // Gửi thông báo qua Web + Email cho người dùng
            if ($withdrawal->user) {
                $withdrawal->user->notify(new SystemNotification([
                    'channels' => ['database', 'mail'],
                    'type'    => 'success',
                    'icon'    => 'fa-money-bill-transfer',
                    'title'   => 'Rút tiền thành công',
                    'message' => 'Lệnh rút <span class="font-bold text-emerald-600">' . number_format($withdrawal->amount) . 'đ</span> của bạn đã được xử lý. Vui lòng kiểm tra tài khoản ngân hàng.',
                    'url'     => route('wallet.index'),
                ]));
            }

            return redirect()->back()->with('success', 'Đã phê duyệt lệnh rút tiền. Hãy nhớ chuyển khoản thực tế cho người dùng nhé!');
        });
    }

    /**
     * Hàm Từ chối rút tiền (Hoàn tiền ròng + Bọc Transaction an toàn tuyệt đối)
     */
    public function reject($id)
    {
        return DB::transaction(function () use ($id) {
            
            // Khóa dòng dữ liệu ngăn chặn Race Condition (thao tác đồng thời)
            $withdrawal = Withdrawal::where('id', $id)->lockForUpdate()->findOrFail($id);

            // Chốt chặn an toàn: Tránh lỗi click đúp cộng tiền 2 lần
            if ($withdrawal->status !== 'pending') {
                return redirect()->back()->with('error', 'Lệnh rút tiền này đã được xử lý trước đó!');
            }

            // 1. Hoàn tiền lại vào số dư ví cho user
            if ($withdrawal->user) {
                $withdrawal->user->increment('balance', $withdrawal->amount);
            }

            // 2. Cập nhật trạng thái lệnh thành Từ chối
            $withdrawal->update(['status' => 'rejected']);

            // 3. Gửi thông báo cảnh báo qua Web + Email
            if ($withdrawal->user) {
                $withdrawal->user->notify(new SystemNotification([
                    'channels' => ['database', 'mail'],
                    'type'    => 'danger',
                    'icon'    => 'fa-triangle-exclamation',
                    'title'   => 'Lệnh rút tiền thất bại',
                    'message' => 'Lệnh rút <span class="font-bold">' . number_format($withdrawal->amount) . 'đ</span> của bạn đã bị từ chối. Tiền đã được hoàn lại vào ví 2HAND.',
                    'url'     => route('wallet.index'),
                ]));
            }

            return redirect()->back()->with('success', 'Đã từ chối lệnh rút tiền và hoàn tiền lại vào ví cho người dùng.');
        });
    }
}