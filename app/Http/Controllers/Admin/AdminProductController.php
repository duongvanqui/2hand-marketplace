<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    // ----------------------------------------------------------------
    // INDEX — Danh sách sản phẩm với tìm kiếm + lọc + phân trang
    // ----------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category'])
            ->orderByRaw('CASE WHEN pushed_until > NOW() THEN 0 ELSE 1 END')
            ->orderBy('pushed_until', 'desc')
            ->orderBy('created_at', 'desc');

        // Tìm theo tên sản phẩm hoặc người bán
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        // Lọc theo trạng thái
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        // Lọc theo danh mục
        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Thống kê nhanh
        $stats = [
            'total'    => Product::count(),
            'pending'  => Product::where('status', 'pending')->count(),
            'approved' => Product::where('status', 'approved')->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    // ----------------------------------------------------------------
    // SHOW — Xem chi tiết / preview tin
    // ----------------------------------------------------------------
    public function show($id)
    {
        $product = Product::with(['user', 'category', 'images', 'reviewer'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    // ----------------------------------------------------------------
    // APPROVE — Duyệt tin
    // ----------------------------------------------------------------
    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'status'           => 'approved',
            'rejection_reason' => null,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        $this->notify($product, 'approved');

        return back()->with('success', "Đã duyệt tin \"{$product->title}\".");
    }

    // ----------------------------------------------------------------
    // REJECT — Từ chối tin kèm lý do
    // ----------------------------------------------------------------
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Vui lòng nhập lý do từ chối.',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        $this->notify($product, 'rejected', $request->rejection_reason);

        return back()->with('success', "Đã từ chối tin \"{$product->title}\".");
    }

    // ----------------------------------------------------------------
    // TOGGLE STATUS — Ẩn / Hiện tin
    // ----------------------------------------------------------------
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = ($product->status === 'hidden') ? 'approved' : 'hidden';
        $product->save();

        return back()->with('success', 'Đã cập nhật trạng thái!');
    }

    // ----------------------------------------------------------------
    // PUSH TIN — Đẩy tin lên đầu
    // ----------------------------------------------------------------
    public function pushTin(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $days    = $request->input('days', 3);
        $product->update(['pushed_until' => now()->addDays($days)]);

        return back()->with('success', "Đã đẩy tin \"{$product->title}\" lên đầu trong {$days} ngày.");
    }

    // ----------------------------------------------------------------
    // DESTROY — Xóa tin
    // ----------------------------------------------------------------
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $title   = $product->title;
        $product->delete();

        return back()->with('success', "Đã xóa tin \"{$title}\".");
    }

    // ----------------------------------------------------------------
    // PRIVATE: Ghi thông báo vào bảng notifications
    // ----------------------------------------------------------------
    private function notify(Product $product, string $status, ?string $reason = null): void
    {
        $message = match($status) {
            'approved' => "Tin đăng \"{$product->title}\" đã được duyệt và hiển thị.",
            'rejected' => "Tin đăng \"{$product->title}\" bị từ chối. Lý do: {$reason}",
            default    => "Trạng thái tin đăng \"{$product->title}\" đã thay đổi.",
        };

        \DB::table('notifications')->insert([
            'id'              => \Str::uuid(),
            'type'            => 'App\Notifications\ProductStatusChanged',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $product->user_id,
            'data'            => json_encode([
                'product_id'    => $product->id,
                'product_title' => $product->title,
                'product_slug'  => $product->slug,
                'status'        => $status,
                'reason'        => $reason,
                'message'       => $message,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}