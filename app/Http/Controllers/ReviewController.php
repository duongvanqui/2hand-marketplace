<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    // Xử lý lưu đánh giá
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Kiểm tra đơn hàng phải thuộc về người mua và đã hoàn tất
        $order = Order::where('id', $orderId)
                      ->where('buyer_id', Auth::id())
                      ->where('status', 'completed')
                      ->firstOrFail();

        // Kiểm tra xem đã đánh giá chưa (chống spam 2 lần)
        $exists = Review::where('order_id', $orderId)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá đơn hàng này rồi!');
        }

        DB::beginTransaction();
        try {
            // Lưu đánh giá vào DB
            $review = Review::create([
                'order_id'    => $order->id,
                'sender_id'   => Auth::id(),
                'receiver_id' => $order->seller_id,
                'rating'      => $request->rating,
                'comment'     => $request->comment,
            ]);

            // Bắn thông báo cho người bán
            $seller = User::find($order->seller_id);
            if ($seller) {
                $seller->notify(new SystemNotification([
                    'type'    => 'success',
                    'icon'    => 'fa-star',
                    'title'   => 'Bạn có đánh giá mới!',
                    'message' => 'Người mua đã đánh giá <span class="font-bold text-yellow-500">' . $request->rating . ' sao</span> cho sản phẩm "' . ($order->product->title ?? 'Đã xóa') . '".',
                    'url'     => route('reviews.index'), // Link dẫn tới trang "Đánh giá của tôi"
                ]));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $userId = auth()->id();

        // 1. Lấy các đánh giá mà người dùng NHẬN ĐƯỢC (với tư cách người bán)
        $receivedReviews = Review::where('receiver_id', $userId)
            ->with(['sender', 'order.product'])
            ->latest()
            ->paginate(10, ['*'], 'received_page');

        // 2. Lấy các đánh giá mà người dùng ĐÃ GỬI (với tư cách người mua)
        $sentReviews = Review::where('sender_id', $userId)
            ->with(['receiver', 'order.product'])
            ->latest()
            ->paginate(10, ['*'], 'sent_page');

        // 3. Tính điểm trung bình cộng (Trust Score)
        $averageRating = Review::where('receiver_id', $userId)->avg('rating') ?: 0;
        $totalReceived = Review::where('receiver_id', $userId)->count();

        return view('reviews.index', compact('receivedReviews', 'sentReviews', 'averageRating', 'totalReceived'));
    }
}