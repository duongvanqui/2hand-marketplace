<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Lịch sử tiền vào (Các đơn đã bán thành công)
        $incomeHistory = Order::with('product')->where('seller_id', $user->id)->where('status', 'completed')->latest()->get();
        // Lịch sử tiền ra (Lệnh rút tiền)
        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->get();

        return view('wallet.index', compact('user', 'incomeHistory', 'withdrawals'));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000',
            'bank_info' => 'required|string'
        ]);

        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'Số dư trong ví không đủ để rút!');
        }

        DB::beginTransaction();
        try {
            // 1. Trừ tiền trong ví
            $user->decrement('balance', $request->amount);

            // 2. Tạo lệnh rút tiền
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'bank_info' => $request->bank_info
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Đã tạo lệnh rút tiền thành công! Admin sẽ duyệt và chuyển khoản cho bạn sớm nhất.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra.');
        }
    }
}