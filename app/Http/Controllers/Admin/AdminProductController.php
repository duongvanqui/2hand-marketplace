<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;
use App\Models\User;

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
    // APPROVE — Duyệt tin (Đã fix lỗi duyệt trùng)
    // ----------------------------------------------------------------
    public function approve($id)
    {
        $product = Product::findOrFail($id);

        // Chốt chặn an toàn: Nếu tin đã duyệt rồi thì không xử lý nữa
        if ($product->status === 'approved') {
            return back()->with('error', 'Sản phẩm này đã được duyệt trước đó!');
        }

        $product->update([
            'status'           => 'approved',
            'rejection_reason' => null,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        $owner = User::find($product->user_id);
        if ($owner) {
            $owner->notify(new SystemNotification([
                'type'    => 'success',
                'icon'    => 'fa-check',
                'title'   => 'Tin đăng được duyệt',
                'message' => 'Sản phẩm <span class="font-bold text-gray-900">"' . $product->title . '"</span> của bạn đã được phê duyệt và hiển thị công khai.',
                'url'     => route('products.show', $product->slug),
            ]));
        }

        return back()->with('success', "Đã duyệt tin \"{$product->title}\".");
    }

    // ----------------------------------------------------------------
    // REJECT — Từ chối tin kèm lý do (Đã fix lỗi từ chối trùng)
    // ----------------------------------------------------------------
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Vui lòng nhập lý do từ chối.',
        ]);

        $product = Product::findOrFail($id);

        // Chốt chặn an toàn: Nếu tin đã bị từ chối rồi thì không xử lý lại
        if ($product->status === 'rejected') {
            return back()->with('error', 'Sản phẩm này đã bị từ chối trước đó!');
        }

        $product->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        $owner = User::find($product->user_id);
        if ($owner) {
            $owner->notify(new SystemNotification([
                'type'    => 'danger',
                'icon'    => 'fa-xmark',
                'title'   => 'Tin đăng bị từ chối',
                'message' => 'Sản phẩm <span class="font-bold text-gray-900">"' . $product->title . '"</span> bị từ chối. Lý do: <span class="text-red-600 font-medium">' . $request->rejection_reason . '</span>',
                'url'     => route('dashboard'),
            ]));
        }

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

        return back()->with('success', 'Đã cập nhật trạng thái sản phẩm!');
    }

    // ----------------------------------------------------------------
    // PUSH TIN — Đẩy tin lên đầu (Đã fix lỗi chặn trạng thái)
    // ----------------------------------------------------------------
    public function pushTin(Request $request, $id)
    {
        $request->validate([
            'days' => 'nullable|integer|min:1|max:30'
        ]);

        $product = Product::findOrFail($id);

        // Chốt chặn: Chỉ đẩy tin khi sản phẩm đang được hiển thị công khai (đã duyệt)
        if ($product->status !== 'approved') {
            return back()->with('error', 'Chỉ có thể đẩy những tin đã được duyệt và đang hoạt động!');
        }

        $days = (int) $request->input('days', 3);
        $product->update(['pushed_until' => now()->addDays($days)]);

        // Gửi thông báo cho người dùng
        $owner = User::find($product->user_id);
        if ($owner) {
            $owner->notify(new SystemNotification([
                'type'    => 'info',
                'icon'    => 'fa-arrow-up-right-dots',
                'title'   => 'Tin đăng được đẩy lên TOP',
                'message' => 'Sản phẩm <span class="font-bold">"' . $product->title . '"</span> của bạn đã được Admin đẩy lên trang đầu trong ' . $days . ' ngày.',
                'url'     => route('products.show', $product->slug),
            ]));
        }

        return back()->with('success', "Đã đẩy tin \"{$product->title}\" lên đầu trong {$days} ngày.");
    }

    // ----------------------------------------------------------------
    // DESTROY — Xóa vĩnh viễn tin
    // ----------------------------------------------------------------
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $title   = $product->title;
        $product->delete();

        return back()->with('success', "Đã xóa vĩnh viễn tin \"{$title}\".");
    }

    // ----------------------------------------------------------------
    // BULK ACTION — Xử lý hàng loạt (Đã fix vòng lặp gửi thông báo)
    // ----------------------------------------------------------------
    public function bulkAction(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|string',
            'action'      => 'required|in:approve,reject,delete'
        ]);

        $ids = explode(',', $request->product_ids);

        // Lấy danh sách sản phẩm để chạy logic thông báo từng người
        $products = Product::whereIn('id', $ids)->get();
        $count = $products->count();

        if ($count === 0) {
            return back()->with('error', 'Không tìm thấy sản phẩm nào để xử lý.');
        }

        switch ($request->action) {
            case 'approve':
                foreach ($products as $product) {
                    if ($product->status !== 'approved') {
                        $product->update([
                            'status'           => 'approved',
                            'rejection_reason' => null,
                            'reviewed_by'      => Auth::id(),
                            'reviewed_at'      => now(),
                        ]);

                        $owner = User::find($product->user_id);
                        if ($owner) {
                            $owner->notify(new SystemNotification([
                                'type'    => 'success',
                                'icon'    => 'fa-check',
                                'title'   => 'Tin đăng được duyệt',
                                'message' => 'Sản phẩm <span class="font-bold">"' . $product->title . '"</span> đã được phê duyệt.',
                                'url'     => route('products.show', $product->slug),
                            ]));
                        }
                    }
                }
                $message = "Đã duyệt hiển thị thành công {$count} sản phẩm!";
                break;

            case 'reject':
                foreach ($products as $product) {
                    if ($product->status !== 'rejected') {
                        $product->update([
                            'status'           => 'rejected',
                            'rejection_reason' => 'Từ chối do vi phạm (Xử lý hàng loạt bởi Admin)',
                            'reviewed_by'      => Auth::id(),
                            'reviewed_at'      => now(),
                        ]);

                        $owner = User::find($product->user_id);
                        if ($owner) {
                            $owner->notify(new SystemNotification([
                                'type'    => 'danger',
                                'icon'    => 'fa-xmark',
                                'title'   => 'Tin đăng bị từ chối',
                                'message' => 'Sản phẩm <span class="font-bold">"' . $product->title . '"</span> bị từ chối do vi phạm quy định sàn.',
                                'url'     => route('dashboard'),
                            ]));
                        }
                    }
                }
                $message = "Đã từ chối/ẩn {$count} sản phẩm được chọn!";
                break;

            case 'delete':
                Product::whereIn('id', $ids)->delete();
                $message = "Đã xóa vĩnh viễn {$count} sản phẩm!";
                break;

            default:
                $message = "Đã xử lý thành công!";
        }

        return redirect()->back()->with('success', $message);
    }
}
