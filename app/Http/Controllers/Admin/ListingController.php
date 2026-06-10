<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ListingController — Quản lý tin đăng phía Admin
 * Thay thế AdminProductController với đầy đủ tính năng:
 * - Lọc nâng cao (trạng thái, danh mục, người bán, thời gian)
 * - Duyệt / Từ chối kèm lý do
 * - Duyệt hàng loạt
 * - Đẩy tin
 * - Audit log tự động
 * - Xóa tin
 */
class ListingController extends Controller
{
    // Số lần resubmit tối đa
    const MAX_RESUBMIT = 3;

    // ----------------------------------------------------------------
    // INDEX — Danh sách tin với bộ lọc nâng cao
    // ----------------------------------------------------------------
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category'])
            ->orderByRaw("CASE
                WHEN status = 'pending' THEN 0
                ELSE 1
            END")                          // Pending lên đầu
            ->orderBy('created_at', 'desc');

        // Tìm theo tên hoặc người bán
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

        // Lọc theo người bán
        if ($userId = $request->user_id) {
            $query->where('user_id', $userId);
        }

        // Lọc theo khoảng thời gian
        if ($from = $request->date_from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->date_to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $sellers    = User::where('role', 'user')->orderBy('name')->get();

        // Thống kê nhanh
        $stats = [
            'pending'  => Product::where('status', 'pending')->count(),
            'approved' => Product::where('status', 'approved')->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
            'total'    => Product::count(),
        ];

        return view('admin.listings.index', compact(
            'products', 'categories', 'sellers', 'stats'
        ));
    }

    // ----------------------------------------------------------------
    // SHOW — Xem trước tin (Preview)
    // ----------------------------------------------------------------
    public function show($id)
    {
        $product = Product::with(['user', 'category', 'images', 'reviewer'])->findOrFail($id);
        return view('admin.listings.show', compact('product'));
    }

    // ----------------------------------------------------------------
    // APPROVE — Duyệt tin
    // ----------------------------------------------------------------
    public function approve(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'status'           => 'approved',
            'rejection_reason' => null,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        // Ghi audit log
        $this->writeAuditLog($product, 'approved');

        // Thông báo cho người đăng
        $this->notifyUser($product, 'approved');

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã duyệt tin thành công.']);
        }

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

        $this->writeAuditLog($product, 'rejected', $request->rejection_reason);
        $this->notifyUser($product, 'rejected', $request->rejection_reason);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã từ chối tin.']);
        }

        return back()->with('success', "Đã từ chối tin \"{$product->title}\".");
    }

    // ----------------------------------------------------------------
    // BULK ACTION — Duyệt / Từ chối hàng loạt
    // ----------------------------------------------------------------
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'     => 'required|in:approve,reject',
            'ids'        => 'required|array|min:1',
            'ids.*'      => 'integer|exists:products,id',
            'reason'     => 'required_if:action,reject|nullable|string|max:500',
        ]);

        $products = Product::whereIn('id', $request->ids)->get();
        $count    = $products->count();

        foreach ($products as $product) {
            if ($request->action === 'approve') {
                $product->update([
                    'status'           => 'approved',
                    'rejection_reason' => null,
                    'reviewed_by'      => Auth::id(),
                    'reviewed_at'      => now(),
                ]);
                $this->writeAuditLog($product, 'approved');
                $this->notifyUser($product, 'approved');
            } else {
                $product->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $request->reason,
                    'reviewed_by'      => Auth::id(),
                    'reviewed_at'      => now(),
                ]);
                $this->writeAuditLog($product, 'rejected', $request->reason);
                $this->notifyUser($product, 'rejected', $request->reason);
            }
        }

        $action = $request->action === 'approve' ? 'duyệt' : 'từ chối';
        return back()->with('success', "Đã {$action} {$count} tin thành công.");
    }

    // ----------------------------------------------------------------
    // PUSH — Đẩy tin lên đầu
    // ----------------------------------------------------------------
    public function push($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['pushed_at' => now()]);

        return back()->with('success', "Đã đẩy tin \"{$product->title}\" lên đầu.");
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
    // AUDIT LOG — Ghi nhật ký duyệt bài
    // ----------------------------------------------------------------
    private function writeAuditLog(Product $product, string $action, ?string $reason = null): void
    {
        // Ghi vào storage/logs/audit.log
        $log = sprintf(
            "[%s] Admin #%d (%s) đã %s tin #%d \"%s\"%s\n",
            now()->format('Y-m-d H:i:s'),
            Auth::id(),
            Auth::user()->name,
            $action === 'approved' ? 'DUYỆT' : 'TỪ CHỐI',
            $product->id,
            $product->title,
            $reason ? " | Lý do: {$reason}" : ''
        );

        file_put_contents(storage_path('logs/audit.log'), $log, FILE_APPEND);
    }

    // ----------------------------------------------------------------
    // NOTIFY — Thông báo cho người đăng tin
    // ----------------------------------------------------------------
    private function notifyUser(Product $product, string $status, ?string $reason = null): void
    {
        // Tạo thông báo trong DB (bảng notifications của Laravel)
        $message = match($status) {
            'approved' => "Tin đăng \"{$product->title}\" của bạn đã được duyệt và hiển thị.",
            'rejected' => "Tin đăng \"{$product->title}\" bị từ chối. Lý do: {$reason}",
            default    => "Trạng thái tin đăng \"{$product->title}\" đã thay đổi.",
        };

        // Lưu vào session flash cho đơn giản,
        // hoặc dùng Laravel Notification nếu đã setup
        // $product->user->notify(new ProductStatusChanged($product, $status, $reason));

        // Tạm thời lưu vào bảng notifications thủ công
        \DB::table('notifications')->insert([
            'id'              => \Str::uuid(),
            'type'            => 'App\Notifications\ProductStatusChanged',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id'   => $product->user_id,
            'data'            => json_encode([
                'product_id'   => $product->id,
                'product_title'=> $product->title,
                'status'       => $status,
                'reason'       => $reason,
                'message'      => $message,
            ]),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}