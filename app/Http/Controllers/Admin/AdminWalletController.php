<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function index()
    {
        // 1. Tính tổng doanh thu phí sàn (3%) từ các đơn đã hoàn tất
        $totalRevenue = Order::where('status', 'completed')->sum('fee_amount');

        // 2. Lấy danh sách các yêu cầu rút tiền đang chờ duyệt
        $pendingWithdrawals = Withdrawal::with('user')->where('status', 'pending')->latest()->get();

        // 3. Lấy lịch sử các yêu cầu rút tiền đã xử lý
        $processedWithdrawals = Withdrawal::with('user')->where('status', '!=', 'pending')->latest()->take(10)->get();

        return view('admin.wallet.index', compact('totalRevenue', 'pendingWithdrawals', 'processedWithdrawals'));
    }

    // Hàm Duyệt rút tiền
    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Đã phê duyệt lệnh rút tiền. Hãy nhớ chuyển khoản thực tế cho người dùng nhé!');
    }

    // Hàm Từ chối rút tiền (Trả lại tiền vào ví cho khách)
    public function reject($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        
        // Trả lại tiền vào ví cho user
        $withdrawal->user->increment('balance', $withdrawal->amount);
        
        $withdrawal->update(['status' => 'rejected']);

        return redirect()->back()->with('error', ' đã từ chối lệnh rút tiền và hoàn tiền lại vào ví cho người dùng.');
    }
}