<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Hiển thị giỏ hàng từ Database
    public function index()
    {
        // Lấy các sản phẩm trong giỏ của User đang đăng nhập
        $cartItems = Cart::with('product')
                         ->where('user_id', Auth::id())
                         ->get();
        
        $total = 0;
        foreach($cartItems as $item) {
            // Chỉ tính tiền nếu sản phẩm còn tồn tại và chưa bị bán
            if($item->product && $item->product->status !== 'sold') {
                $total += $item->product->price;
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    // 2. Thêm vào giỏ hàng (ĐÃ BỔ SUNG CHẶN TỰ MUA & AJAX)
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // BỔ SUNG 1: Chặn người dùng tự mua sản phẩm của chính mình
        if ($product->user_id === Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Bạn không thể tự mua đồ do chính mình đăng bán!']);
            }
            return redirect()->back()->with('error', 'Bạn không thể tự mua đồ do chính mình đăng bán!');
        }

        // Kiểm tra xem sản phẩm đã bị bán chưa?
        if($product->status === 'sold') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rất tiếc, sản phẩm này đã được người khác mua!']);
            }
            return redirect()->back()->with('error', 'Rất tiếc, sản phẩm này đã được người khác mua!');
        }

        // Kiểm tra xem người dùng đã có món này trong giỏ chưa
        $exists = Cart::where('user_id', Auth::id())
                      ->where('product_id', $id)
                      ->exists();

        if($exists) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm này đã có sẵn trong giỏ hàng của bạn!']);
            }
            return redirect()->back()->with('error', 'Sản phẩm này đã có trong giỏ hàng của bạn!');
        }

        // Thêm vào Database
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $id
        ]);

        // Đếm lại tổng số lượng giỏ hàng để cập nhật lên Navbar
        $cartCount = Cart::where('user_id', Auth::id())->count();

        // BỔ SUNG 2: Nếu là request từ Ajax (Javascript), trả về JSON để không phải load lại trang
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                'cartCount' => $cartCount
            ]);
        }

        // Nếu là request bình thường (không dùng Ajax), tải lại trang như cũ
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // 3. Xóa khỏi giỏ hàng
    public function remove($id)
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}