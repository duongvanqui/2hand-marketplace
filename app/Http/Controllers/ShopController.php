<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function show($id)
    {
        // Lấy thông tin tài khoản cần xem shop
        $user = User::findOrFail($id);

        // Lấy danh sách sản phẩm ĐANG BÁN của tài khoản này
        $products = Product::where('user_id', $id)
                           ->where('status', 'approved')
                           ->latest()
                           ->paginate(12, ['*'], 'products_page');

        // Lấy danh sách đánh giá mà tài khoản này NHẬN ĐƯỢC từ người mua
        $reviews = Review::where('receiver_id', $id)
                         ->with('sender', 'order.product')
                         ->latest()
                         ->paginate(10, ['*'], 'reviews_page');

        // Tính toán điểm số uy tín trung bình
        $averageRating = Review::where('receiver_id', $id)->avg('rating') ?: 0;
        $totalReviews = Review::where('receiver_id', $id)->count();

        // [MỚI]: Lấy thông tin Theo dõi
        $followersCount = $user->followers()->count();
        $isFollowing = Auth::check() ? Auth::user()->followings()->where('following_id', $user->id)->exists() : false;

        $isOwner = Auth::check() && Auth::id() === $user->id;

        return view('shop.show', compact(
            'user', 'products', 'reviews', 'averageRating', 'totalReviews', 'isOwner', 'followersCount', 'isFollowing'
        ));

        // Kiểm tra quyền: Người đang xem có phải là chính chủ của tài khoản này không?
        $isOwner = Auth::check() && Auth::id() === $user->id;

        // Trả về file view đã gộp nằm trong thư mục profile
        return view('shop.show', compact(
            'user', 
            'products', 
            'reviews', 
            'averageRating', 
            'totalReviews', 
            'isOwner'
        ));
    }
}