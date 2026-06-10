<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // CHỐT ĐƠN TỪ GIỎ HÀNG
    public function checkout(Request $request)
    {
        // Yêu cầu nhập đủ thông tin giao hàng
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            foreach ($cartItems as $item) {
                $product = $item->product;

                // Nếu trong lúc mình chốt, có người khác chốt nhanh hơn
                if (!$product || $product->status === 'sold') {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Sản phẩm ' . ($product->title ?? '') . ' vừa bị người khác mua mất. Vui lòng xóa khỏi giỏ!');
                }

                $totalAmount = $product->price;
                $feeAmount = $totalAmount * 0.03; // Phí sàn 3%
                $sellerAmount = $totalAmount - $feeAmount; // Thực nhận 97%

                Order::create([
                    'buyer_id' => Auth::id(),
                    'seller_id' => $product->user_id,
                    'product_id' => $product->id,
                    'total_amount' => $totalAmount,
                    'fee_amount' => $feeAmount,
                    'seller_amount' => $sellerAmount,
                    'payment_method' => $request->payment_method,
                    'status' => ($request->payment_method === 'cod') ? 'pending_shipping' : 'paid_escrow',
                    'receiver_name' => $request->receiver_name, // <-- Đảm bảo có dòng này
                    'phone_number' => $request->phone_number,
                    'shipping_address' => $request->shipping_address,
                ]);

                // Khóa món đồ
                $product->update(['status' => 'sold']);
            }

            // Dọn dẹp giỏ hàng
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Chốt đơn thành công! Tiền đã được giữ an toàn trên hệ thống 2HAND.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // TRANG QUẢN LÝ ĐƠN HÀNG
    public function index()
    {
        $buyingOrders = Order::with('product', 'seller')->where('buyer_id', Auth::id())->latest()->get();
        $sellingOrders = Order::with('product', 'buyer')->where('seller_id', Auth::id())->latest()->get();
        return view('orders.index', compact('buyingOrders', 'sellingOrders'));
    }

    // NGƯỜI BÁN: XÁC NHẬN ĐÃ GỬI
    public function shipOrder($id)
    {
        $order = Order::where('id', $id)->where('seller_id', Auth::id())->firstOrFail();
        
        // Cho phép gửi hàng nếu là COD (pending_shipping) HOẶC Đã chuyển khoản (paid_escrow)
        if ($order->status === 'paid_escrow' || $order->status === 'pending_shipping') {
            $order->update(['status' => 'shipped']);
            return redirect()->back()->with('success', 'Xác nhận gửi hàng thành công!');
        }
        return redirect()->back()->with('error', 'Thao tác không hợp lệ.');
    }

    // NGƯỜI MUA: XÁC NHẬN ĐÃ NHẬN (Giải ngân cho người bán)
    public function confirmReceived($id)
    {
        $order = Order::where('id', $id)->where('buyer_id', Auth::id())->firstOrFail();

        if ($order->status === 'shipped') {
            DB::beginTransaction();
            try {
                // Đổi trạng thái đơn hàng thành hoàn tất
                $order->update(['status' => 'completed']);
                
                // CỘNG TIỀN CHO NGƯỜI BÁN (97% giá trị đơn hàng)
                $seller = \App\Models\User::find($order->seller_id);
                $seller->increment('balance', $order->seller_amount);

                DB::commit();
                return redirect()->back()->with('success', 'Tuyệt vời! Đơn hàng hoàn tất. Hệ thống đã giải ngân ' . number_format($order->seller_amount) . 'đ vào ví người bán.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
            }
        }
        return redirect()->back()->with('error', 'Thao tác không hợp lệ.');
    }

    
}