<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để báo cáo.'], 401);
        }

        if ($product->user_id === Auth::id()) {
            return response()->json(['error' => 'Bạn không thể báo cáo sản phẩm của chính mình.'], 403);
        }

        // Tránh spam báo cáo 1 sản phẩm nhiều lần
        $exists = Report::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
        if ($exists) {
            return response()->json(['error' => 'Bạn đã gửi báo cáo cho sản phẩm này rồi. Chúng tôi đang xử lý.'], 422);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        return response()->json(['success' => 'Cảm ơn bạn. Báo cáo đã được ghi nhận và sẽ được ban quản trị xem xét.']);
    }
}