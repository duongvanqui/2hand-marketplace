<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * 1. Hiển thị danh sách sản phẩm ngoài màn hình chính (Index)
     */
    public function index()
    {
        $products = Product::where('status', 'approved')
            ->with(['images', 'category'])
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * 2. Hiển thị trang biểu mẫu đăng tin bán đồ cũ (Create)
     */
    public function create()
    {
        // [CẬP NHẬT]: Chỉ lấy các Danh mục gốc (không có parent_id) và tự động nối kèm Danh mục con (children)
        // Điều này giúp giao diện form đăng tin có thể nhóm <optgroup> chuẩn xác
        $categories = Category::whereNull('parent_id')->with('children')->get();
        
        return view('products.create', compact('categories'));
    }

    /**
     * 3. Xử lý lưu thông tin khi người dùng nhấn nút "ĐĂNG TIN NGAY" (Store)
     */
    public function store(Request $request)
    {
        // Bước A: Kiểm tra tính hợp lệ của dữ liệu form gửi lên
        $request->validate([
            'title' => 'required|max:150',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'condition_pct' => 'required|integer|between:1,100',
            'description' => 'required',
            'specifications' => 'nullable|string', // [CẬP NHẬT]: Cho phép lưu thông số kỹ thuật, có thể để trống
            'location' => 'required',
            'images' => 'required|array|min:1|max:10', // [CẬP NHẬT]: Bắt buộc chọn từ 1 đến 10 ảnh
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        // Bước B: Tiến hành tạo mới bản ghi sản phẩm vào bảng 'products'
        $product = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'specifications' => $request->specifications, // [CẬP NHẬT]: Lưu thông số kỹ thuật vào CSDL
            'original_price' => $request->price,
            'price' => $request->price,
            'condition_pct' => $request->condition_pct,
            'location' => $request->location,
            'status' => 'pending', // Trạng thái chờ duyệt
        ]);

        // Bước C: Vòng lặp bóc tách mảng ảnh và lưu vào bảng liên kết phụ 'product_images'
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                // Lưu tệp tin vào thư mục storage/app/public/products
                $path = $file->store('products', 'public');

                // Gọi quan hệ images() hasMany để tạo bản ghi ảnh
                $product->images()->create([
                    'image_path' => $path,
                    'is_main' => ($index === 0) ? 1 : 0, // Ảnh đầu tiên là ảnh đại diện chính
                ]);
            }
        }

        // Bước D: Điều hướng về trang danh sách (hoặc dashboard) với thông báo xanh
        return redirect()->route('products.index')->with('success', 'Đăng tin thành công! Vui lòng chờ hệ thống phê duyệt.');
    }

    /**
     * 4. Xem chi tiết một sản phẩm đồ cũ (Show)
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['images', 'category'])->firstOrFail();

        // Tăng lượt xem lên +1 mỗi khi có người click vào xem
        $product->increment('view_count'); 

        return view('products.show', compact('product'));
    }

    /**
     * 5. Hiển thị trang Dashboard cá nhân kèm thống kê
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Lấy danh sách sản phẩm của riêng người dùng này (kèm phân trang)
        $products = Product::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->paginate(5);

        // Thống kê số liệu
        $totalApproved = Product::where('user_id', $user->id)->where('status', 'approved')->count();
        $totalViews = Product::where('user_id', $user->id)->sum('view_count');
        $totalRejected = Product::where('user_id', $user->id)->where('status', 'rejected')->count();

        return view('dashboard', compact('products', 'totalApproved', 'totalViews', 'totalRejected'));
    }
}