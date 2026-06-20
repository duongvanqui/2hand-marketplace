<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User; // Bổ sung import Model User
use App\Notifications\SystemNotification; // Bổ sung import Notification
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Hàm hiển thị danh sách tin đã lưu
    public function index()
    {
        // Lấy danh sách sản phẩm mà user hiện tại đang yêu thích
        $favorites = Auth::user()->favorites()->latest('favorites.created_at')->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    // Hàm xử lý việc thả tim/bỏ thả tim (dùng cho Ajax)
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để lưu tin.'], 401);
        }

        $productId = $request->input('product_id');
        
        // [ĐÃ SỬA]: Truy vấn lấy sản phẩm ra trước để dùng cho việc gửi thông báo
        $product = Product::findOrFail($productId);
        
        // Hàm toggle() của Laravel cực hay: Nếu chưa có thì Thêm, nếu có rồi thì Xóa
        $toggled = Auth::user()->favorites()->toggle($productId);
        
        // Kiểm tra xem vừa được thêm vào (attached) hay bị xóa đi (detached)
        $isFavorited = count($toggled['attached']) > 0;

        // Bắn thông báo nếu là Thả tim (không bắn khi bỏ tim) VÀ không phải tự thả tim bài của mình
        if ($isFavorited && $product->user_id !== Auth::id()) {
            $owner = User::find($product->user_id);
            if ($owner) {
                $owner->notify(new SystemNotification([
                    'type'    => 'favorite', // Gán loại 'favorite' để set màu tím bên Dashboard
                    'icon'    => 'fa-heart',
                    'title'   => 'Sản phẩm được yêu thích',
                    'message' => 'Sản phẩm <span class="font-bold text-gray-900">"' . $product->title . '"</span> đã có người thêm vào yêu thích.',
                    'url'     => route('products.show', $product->slug),
                ]));
            }
        }

        return response()->json([
            'success'     => true,
            'isFavorited' => $isFavorited,
            'message'     => $isFavorited ? 'Đã thêm vào mục Yêu thích' : 'Đã bỏ Yêu thích'
        ]);
    }
}